<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
namespace Wikimedia\Rdbms;

use BagOStuff;
use EmptyBagOStuff;
use Exception;
use Generator;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use NullStatsdDataFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use WANObjectCache;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\ScopedCallback;

/**
 * @see ILBFactory
 * @ingroup Database
 */
abstract class LBFactory implements ILBFactory {
	/** @var ChronologyProtector */
	private $chronProt;
	/** @var CriticalSectionProvider|null */
	private $csProvider;
	/**
	 * @var callable|null An optional callback that returns a ScopedCallback instance,
	 * meant to profile the actual query execution in {@see Database::doQuery}
	 */
	private $profiler;
	/** @var TransactionProfiler */
	private $trxProfiler;
	/** @var StatsdDataFactoryInterface */
	private $statsd;
	/** @var LoggerInterface */
	private $logger;
	/** @var callable Error logger */
	private $errorLogger;
	/** @var callable Deprecation logger */
	private $deprecationLogger;

	/** @var BagOStuff */
	protected $cpStash;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var WANObjectCache */
	protected $wanCache;
	/** @var DatabaseDomain Local domain */
	protected $localDomain;

	/** @var array Web request information about the client */
	private $requestInfo;
	/** @var bool Whether this PHP instance is for a CLI script */
	private $cliMode;
	/** @var string Agent name for query profiling */
	private $agent;
	/** @var string Secret string for HMAC hashing */
	private $secret;

	/** @var array[] $aliases Map of (table => (dbname, schema, prefix) map) */
	private $tableAliases = [];
	/** @var string[] Map of (index alias => index) */
	private $indexAliases = [];
	/** @var DatabaseDomain[]|string[] Map of (domain alias => DB domain) */
	protected $domainAliases = [];
	/** @var callable[] */
	private $replicationWaitCallbacks = [];

	/** @var int|null Ticket used to delegate transaction ownership */
	private $ticket;
	/** @var string|false String if a requested DBO_TRX transaction round is active */
	private $trxRoundId = false;
	/** @var string One of the ROUND_* class constants */
	private $trxRoundStage = self::ROUND_CURSORY;
	/** @var int Default replication wait timeout */
	private $replicationWaitTimeout;

	/** @var string|false Reason all LBs are read-only or false if not */
	protected $readOnlyReason = false;

	/** @var string|null */
	private $defaultGroup = null;

	private const ROUND_CURSORY = 'cursory';
	private const ROUND_BEGINNING = 'within-begin';
	private const ROUND_COMMITTING = 'within-commit';
	private const ROUND_ROLLING_BACK = 'within-rollback';
	private const ROUND_COMMIT_CALLBACKS = 'within-commit-callbacks';
	private const ROUND_ROLLBACK_CALLBACKS = 'within-rollback-callbacks';
	private const ROUND_ROLLBACK_SESSIONS = 'within-rollback-session';

	/**
	 * @var callable
	 */
	private $configCallback = null;

	public function __construct( array $conf ) {
		$this->configure( $conf );

		if ( isset( $conf['configCallback'] ) ) {
			$this->configCallback = $conf['configCallback'];
		}

		$this->requestInfo = [
			'IPAddress' => $_SERVER['REMOTE_ADDR'] ?? '',
			'UserAgent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
			// Headers application can inject via LBFactory::setRequestInfo()
			'ChronologyProtection' => null,
			'ChronologyClientId' => null, // prior $cpClientId value from LBFactory::shutdown()
			'ChronologyPositionIndex' => null // prior $cpIndex value from LBFactory::shutdown()
		];
	}

	/**
	 * @param array $conf
	 * @return void
	 */
	protected function configure( array $conf ): void {
		$this->localDomain = isset( $conf['localDomain'] )
			? DatabaseDomain::newFromId( $conf['localDomain'] )
			: DatabaseDomain::newUnspecified();

		if ( isset( $conf['readOnlyReason'] ) && is_string( $conf['readOnlyReason'] ) ) {
			$this->readOnlyReason = $conf['readOnlyReason'];
		}

		$this->cpStash = $conf['cpStash'] ?? new EmptyBagOStuff();
		$this->srvCache = $conf['srvCache'] ?? new EmptyBagOStuff();
		$this->wanCache = $conf['wanCache'] ?? WANObjectCache::newEmpty();

		$this->logger = $conf['logger'] ?? new NullLogger();
		$this->errorLogger = $conf['errorLogger'] ?? static function ( Throwable $e ) {
				trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
		};
		$this->deprecationLogger = $conf['deprecationLogger'] ?? static function ( $msg ) {
				trigger_error( $msg, E_USER_DEPRECATED );
		};

		$this->profiler = $conf['profiler'] ?? null;
		$this->trxProfiler = $conf['trxProfiler'] ?? new TransactionProfiler();
		$this->statsd = $conf['statsdDataFactory'] ?? new NullStatsdDataFactory();

		$this->csProvider = $conf['criticalSectionProvider'] ?? null;

		$this->cliMode = $conf['cliMode'] ?? ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
		$this->agent = $conf['agent'] ?? '';
		$this->defaultGroup = $conf['defaultGroup'] ?? null;
		$this->secret = $conf['secret'] ?? '';
		$this->replicationWaitTimeout = $this->cliMode ? 60 : 1;

		static $nextTicket;
		$this->ticket = $nextTicket = ( is_int( $nextTicket ) ? $nextTicket++ : mt_rand() );
	}

	public function destroy() {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->disable( __METHOD__ );
		}
	}

	/**
	 * Reload config using the callback passed defined $config['configCallback'].
	 *
	 * If the config returned by the callback is different from the existing config,
	 * this calls reconfigure() on all load balancers, which causes them to invalidate
	 * any existing connections and re-connect using the new configuration.
	 *
	 * Long-running processes should call this from time to time
	 * (but not too often, because it is somewhat expensive),
	 * preferably after each batch.
	 * Maintenance scripts can do that by calling $this->waitForReplication(),
	 * which calls this method.
	 */
	public function autoReconfigure(): void {
		if ( !$this->configCallback ) {
			return;
		}

		$conf = ( $this->configCallback )();
		if ( $conf ) {
			$this->reconfigure( $conf );
		}
	}

	/**
	 * Reconfigure using the given config array.
	 * Any fields omitted from $conf will be taken from the current config.
	 *
	 * If the config changed, this calls reconfigure() on all load balancers,
	 * which causes them to close all existing connections.
	 *
	 * @note This invalidates the current transaction ticket.
	 *
	 * @warning This must only be called in top level code such as the execute()
	 * method of a maintenance script. Any database connection in use when this
	 * method is called will become defunct.
	 *
	 * @since 1.39
	 *
	 * @param array $conf A configuration array, using the same structure as
	 *        the one passed to the constructor (see also $wgLBFactoryConf).
	 */
	public function reconfigure( array $conf ): void {
		if ( !$conf ) {
			return;
		}

		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->reconfigure( $conf );
		}
	}

	public function getLocalDomainID() {
		return $this->localDomain->getId();
	}

	public function shutdown(
		$flags = self::SHUTDOWN_NORMAL,
		callable $workCallback = null,
		&$cpIndex = null,
		&$cpClientId = null
	) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$chronProt = $this->getChronologyProtector();
		if ( ( $flags & self::SHUTDOWN_NO_CHRONPROT ) != self::SHUTDOWN_NO_CHRONPROT ) {
			$this->shutdownChronologyProtector( $chronProt, $cpIndex );
			$this->logger->debug( __METHOD__ . ': finished ChronologyProtector shutdown' );
		}
		$cpClientId = $chronProt->getClientId();

		$this->commitPrimaryChanges( __METHOD__ );

		$this->logger->debug( 'LBFactory shutdown completed' );
	}

	public function getAllLBs() {
		foreach ( $this->getLBsForOwner() as $lb ) {
			yield $lb;
		}
	}

	/**
	 * Get all tracked load balancers with the internal "for owner" interface.
	 * Most subclasses override this, we just provide an implementation here
	 * for the benefit of Wikibase's FakeLBFactory.
	 *
	 * @return Generator|ILoadBalancerForOwner[]
	 */
	abstract protected function getLBsForOwner();

	public function flushReplicaSnapshots( $fname = __METHOD__ ) {
		if ( $this->trxRoundId !== false && $this->trxRoundId !== $fname ) {
			$this->logger->warning(
				"$fname: transaction round '{$this->trxRoundId}' still running",
				[ 'exception' => new RuntimeException() ]
			);
		}
		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->flushReplicaSnapshots( $fname );
		}
	}

	final public function beginPrimaryChanges( $fname = __METHOD__ ) {
		$this->assertTransactionRoundStage( self::ROUND_CURSORY );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$this->trxRoundStage = self::ROUND_BEGINNING;
		if ( $this->trxRoundId !== false ) {
			throw new DBTransactionError(
				null,
				"$fname: transaction round '{$this->trxRoundId}' already started"
			);
		}
		$this->trxRoundId = $fname;
		// Set DBO_TRX flags on all appropriate DBs
		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->beginPrimaryChanges( $fname );
		}
		$this->trxRoundStage = self::ROUND_CURSORY;
	}

	final public function commitPrimaryChanges( $fname = __METHOD__, int $maxWriteDuration = 0 ) {
		$this->assertTransactionRoundStage( self::ROUND_CURSORY );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$this->trxRoundStage = self::ROUND_COMMITTING;
		if ( $this->trxRoundId !== false && $this->trxRoundId !== $fname ) {
			throw new DBTransactionError(
				null,
				"$fname: transaction round '{$this->trxRoundId}' still running"
			);
		}
		// Run pre-commit callbacks and suppress post-commit callbacks, aborting on failure
		do {
			$count = 0; // number of callbacks executed this iteration
			foreach ( $this->getLBsForOwner() as $lb ) {
				$count += $lb->finalizePrimaryChanges( $fname );
			}
		} while ( $count > 0 );
		$this->trxRoundId = false;
		// Perform pre-commit checks, aborting on failure
		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->approvePrimaryChanges( $maxWriteDuration, $fname );
		}
		// Log the DBs and methods involved in multi-DB transactions
		$this->logIfMultiDbTransaction();
		// Actually perform the commit on all primary DB connections and revert DBO_TRX
		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->commitPrimaryChanges( $fname );
		}
		// Run all post-commit callbacks in a separate step
		$e = $this->executePostTransactionCallbacks();
		// Throw any last post-commit callback error
		if ( $e instanceof Exception ) {
			throw $e;
		}
	}

	final public function rollbackPrimaryChanges( $fname = __METHOD__ ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$this->trxRoundStage = self::ROUND_ROLLING_BACK;
		$this->trxRoundId = false;
		// Actually perform the rollback on all primary DB connections and revert DBO_TRX
		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->rollbackPrimaryChanges( $fname );
		}
		// Run all post-commit callbacks in a separate step
		$this->executePostTransactionCallbacks();
	}

	final public function flushPrimarySessions( $fname = __METHOD__ ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		// Release named locks and table locks on all primary DB connections
		$this->trxRoundStage = self::ROUND_ROLLBACK_SESSIONS;
		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->flushPrimarySessions( $fname );
		}
		$this->trxRoundStage = self::ROUND_CURSORY;
	}

	/**
	 * @return Exception|null
	 */
	private function executePostTransactionCallbacks() {
		$this->trxRoundStage = self::ROUND_COMMIT_CALLBACKS;
		$fname = __METHOD__;
		// Run all post-commit callbacks until new ones stop getting added
		$e = null; // first callback exception
		do {
			foreach ( $this->getLBsForOwner() as $lb ) {
				$ex = $lb->runPrimaryTransactionIdleCallbacks( $fname );
				$e = $e ?: $ex;
			}
		} while ( $this->hasPrimaryChanges() );
		// Run all listener callbacks once
		foreach ( $this->getLBsForOwner() as $lb ) {
			$ex = $lb->runPrimaryTransactionListenerCallbacks( $fname );
			$e = $e ?: $ex;
		}
		$this->trxRoundStage = self::ROUND_CURSORY;

		return $e;
	}

	public function hasTransactionRound() {
		return ( $this->trxRoundId !== false );
	}

	public function isReadyForRoundOperations() {
		return ( $this->trxRoundStage === self::ROUND_CURSORY );
	}

	/**
	 * Log query info if multi DB transactions are going to be committed now
	 */
	private function logIfMultiDbTransaction() {
		$callersByDB = [];
		foreach ( $this->getLBsForOwner() as $lb ) {
			$primaryName = $lb->getServerName( $lb->getWriterIndex() );
			$callers = $lb->pendingPrimaryChangeCallers();
			if ( $callers ) {
				$callersByDB[$primaryName] = $callers;
			}
		}

		if ( count( $callersByDB ) >= 2 ) {
			$dbs = implode( ', ', array_keys( $callersByDB ) );
			$msg = "Multi-DB transaction [{$dbs}]:\n";
			foreach ( $callersByDB as $db => $callers ) {
				$msg .= "$db: " . implode( '; ', $callers ) . "\n";
			}
			$this->logger->info( $msg );
		}
	}

	public function hasPrimaryChanges() {
		foreach ( $this->getLBsForOwner() as $lb ) {
			if ( $lb->hasPrimaryChanges() ) {
				return true;
			}
		}
		return false;
	}

	public function laggedReplicaUsed() {
		foreach ( $this->getLBsForOwner() as $lb ) {
			if ( $lb->laggedReplicaUsed() ) {
				return true;
			}
		}
		return false;
	}

	public function hasOrMadeRecentPrimaryChanges( $age = null ) {
		foreach ( $this->getLBsForOwner() as $lb ) {
			if ( $lb->hasOrMadeRecentPrimaryChanges( $age ) ) {
				return true;
			}
		}
		return false;
	}

	public function waitForReplication( array $opts = [] ) {
		$opts += [
			'timeout' => $this->replicationWaitTimeout,
			'ifWritesSince' => null
		];

		$lbs = [];
		foreach ( $this->getLBsForOwner() as $lb ) {
			$lbs[] = $lb;
		}
		if ( !$lbs ) {
			return true; // nothing actually used
		}

		// Get all the primary DB positions of applicable DBs right now.
		// This can be faster since waiting on one cluster reduces the
		// time needed to wait on the next clusters.
		$primaryPositions = array_fill( 0, count( $lbs ), false );
		foreach ( $lbs as $i => $lb ) {
			if (
				// No writes to wait on getting replicated
				!$lb->hasPrimaryConnection() ||
				// No replication; avoid getPrimaryPos() permissions errors (T29975)
				!$lb->hasStreamingReplicaServers() ||
				// No writes since the last replication wait
				(
					$opts['ifWritesSince'] &&
					$lb->lastPrimaryChangeTimestamp() < $opts['ifWritesSince']
				)
			) {
				continue; // no need to wait
			}

			$primaryPositions[$i] = $lb->getPrimaryPos();
		}

		// Run any listener callbacks *after* getting the DB positions. The more
		// time spent in the callbacks, the less time is spent in waitForAll().
		foreach ( $this->replicationWaitCallbacks as $callback ) {
			$callback();
		}

		$failed = [];
		foreach ( $lbs as $i => $lb ) {
			if ( $primaryPositions[$i] ) {
				// The RDBMS may not support getPrimaryPos()
				if ( !$lb->waitForAll( $primaryPositions[$i], $opts['timeout'] ) ) {
					$failed[] = $lb->getServerName( $lb->getWriterIndex() );
				}
			}
		}

		return !$failed;
	}

	public function setWaitForReplicationListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->replicationWaitCallbacks[$name] = $callback;
		} else {
			unset( $this->replicationWaitCallbacks[$name] );
		}
	}

	public function getEmptyTransactionTicket( $fname ) {
		if ( $this->hasPrimaryChanges() ) {
			$this->logger->error(
				__METHOD__ . ": $fname does not have outer scope",
				[ 'exception' => new RuntimeException() ]
			);

			return null;
		}

		return $this->ticket;
	}

	public function getPrimaryDatabase( $domain = false ): IDatabase {
		return $this->getMainLB( $domain )->getConnection( DB_PRIMARY, [], $domain );
	}

	public function getReplicaDatabase( $domain = false, $group = null ): IReadableDatabase {
		if ( $group === null ) {
			$groups = [];
		} else {
			$groups = [ $group ];
		}
		return $this->getMainLB( $domain )->getConnection( DB_REPLICA, $groups, $domain );
	}

	final public function commitAndWaitForReplication( $fname, $ticket, array $opts = [] ) {
		if ( $ticket !== $this->ticket ) {
			$this->logger->error(
				__METHOD__ . ": $fname does not have outer scope ($ticket vs {$this->ticket})",
				[ 'exception' => new RuntimeException() ]
			);

			return false;
		}

		// The transaction owner and any caller with the empty transaction ticket can commit
		// so that getEmptyTransactionTicket() callers don't risk seeing DBTransactionError.
		if ( $this->trxRoundId !== false && $fname !== $this->trxRoundId ) {
			$this->logger->info( "$fname: committing on behalf of {$this->trxRoundId}" );
			$fnameEffective = $this->trxRoundId;
		} else {
			$fnameEffective = $fname;
		}

		$this->commitPrimaryChanges( $fnameEffective );
		$waitSucceeded = $this->waitForReplication( $opts );
		// If a nested caller committed on behalf of $fname, start another empty $fname
		// transaction, leaving the caller with the same empty transaction state as before.
		if ( $fnameEffective !== $fname ) {
			$this->beginPrimaryChanges( $fnameEffective );
		}

		return $waitSucceeded;
	}

	public function getChronologyProtectorTouched( $domain = false ) {
		return $this->getChronologyProtector()->getTouched( $this->getMainLB( $domain ) );
	}

	public function disableChronologyProtection() {
		$this->getChronologyProtector()->setEnabled( false );
	}

	/**
	 * @return ChronologyProtector
	 */
	protected function getChronologyProtector() {
		if ( $this->chronProt ) {
			return $this->chronProt;
		}

		$this->chronProt = new ChronologyProtector(
			$this->cpStash,
			[
				'ip' => $this->requestInfo['IPAddress'],
				'agent' => $this->requestInfo['UserAgent'],
				'clientId' => $this->requestInfo['ChronologyClientId'] ?: null
			],
			$this->requestInfo['ChronologyPositionIndex'],
			$this->secret
		);
		$this->chronProt->setLogger( $this->logger );

		if ( $this->cliMode ) {
			$this->chronProt->setEnabled( false );
		} elseif ( $this->requestInfo['ChronologyProtection'] === 'false' ) {
			// Request opted out of using position wait logic. This is useful for requests
			// done by the job queue or background ETL that do not have a meaningful session.
			$this->chronProt->setWaitEnabled( false );
		} elseif ( $this->cpStash instanceof EmptyBagOStuff ) {
			// No where to store any DB positions and wait for them to appear
			$this->chronProt->setEnabled( false );
			$this->logger->debug( 'Cannot use ChronologyProtector with EmptyBagOStuff' );
		}

		$this->logger->debug(
			__METHOD__ . ': request info ' .
			json_encode( $this->requestInfo, JSON_PRETTY_PRINT )
		);

		return $this->chronProt;
	}

	/**
	 * Get and record all of the staged DB positions into persistent memory storage
	 *
	 * @param ChronologyProtector $cp
	 * @param int|null &$cpIndex DB position key write counter; incremented on update [returned]
	 */
	protected function shutdownChronologyProtector(
		ChronologyProtector $cp, &$cpIndex = null
	) {
		// Remark all of the relevant DB primary positions
		foreach ( $this->getLBsForOwner() as $lb ) {
			$cp->stageSessionPrimaryPos( $lb );
		}
		// Write the positions to the persistent stash
		$cp->persistSessionReplicationPositions( $cpIndex );
	}

	/**
	 * Get parameters to ILoadBalancer::__construct()
	 *
	 * @return array
	 */
	final protected function baseLoadBalancerParams() {
		if ( $this->trxRoundStage === self::ROUND_COMMIT_CALLBACKS ) {
			$initStage = ILoadBalancerForOwner::STAGE_POSTCOMMIT_CALLBACKS;
		} elseif ( $this->trxRoundStage === self::ROUND_ROLLBACK_CALLBACKS ) {
			$initStage = ILoadBalancerForOwner::STAGE_POSTROLLBACK_CALLBACKS;
		} else {
			$initStage = null;
		}

		return [
			'localDomain' => $this->localDomain,
			'readOnlyReason' => $this->readOnlyReason,
			'srvCache' => $this->srvCache,
			'wanCache' => $this->wanCache,
			'profiler' => $this->profiler,
			'trxProfiler' => $this->trxProfiler,
			'logger' => $this->logger,
			'errorLogger' => $this->errorLogger,
			'deprecationLogger' => $this->deprecationLogger,
			'statsdDataFactory' => $this->statsd,
			'cliMode' => $this->cliMode,
			'agent' => $this->agent,
			'defaultGroup' => $this->defaultGroup,
			'chronologyCallback' => function ( ILoadBalancer $lb ) {
				// Defer ChronologyProtector construction in case setRequestInfo() ends up
				// being called later (but before the first connection attempt) (T192611)
				return $this->getChronologyProtector()->yieldSessionPrimaryPos( $lb );
			},
			'roundStage' => $initStage,
			'criticalSectionProvider' => $this->csProvider
		];
	}

	/**
	 * @param ILoadBalancerForOwner $lb
	 */
	protected function initLoadBalancer( ILoadBalancerForOwner $lb ) {
		if ( $this->trxRoundId !== false ) {
			$lb->beginPrimaryChanges( $this->trxRoundId ); // set DBO_TRX
		}

		$lb->setTableAliases( $this->tableAliases );
		$lb->setIndexAliases( $this->indexAliases );
		$lb->setDomainAliases( $this->domainAliases );
	}

	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	public function setIndexAliases( array $aliases ) {
		$this->indexAliases = $aliases;
	}

	public function setDomainAliases( array $aliases ) {
		$this->domainAliases = $aliases;
	}

	public function getTransactionProfiler(): TransactionProfiler {
		return $this->trxProfiler;
	}

	public function setLocalDomainPrefix( $prefix ) {
		$this->localDomain = new DatabaseDomain(
			$this->localDomain->getDatabase(),
			$this->localDomain->getSchema(),
			$prefix
		);

		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->setLocalDomainPrefix( $prefix );
		}
	}

	public function redefineLocalDomain( $domain ) {
		$this->closeAll( __METHOD__ );

		$this->localDomain = DatabaseDomain::newFromId( $domain );

		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->redefineLocalDomain( $this->localDomain );
		}
	}

	public function closeAll( $fname = __METHOD__ ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		foreach ( $this->getLBsForOwner() as $lb ) {
			$lb->closeAll( $fname );
		}
	}

	public function setAgentName( $agent ) {
		$this->agent = $agent;
	}

	public function appendShutdownCPIndexAsQuery( $url, $index ) {
		foreach ( $this->getLBsForOwner() as $lb ) {
			if ( $lb->hasStreamingReplicaServers() ) {
				return strpos( $url, '?' ) === false
					? "$url?cpPosIndex=$index" : "$url&cpPosIndex=$index";
			}
		}
		return $url; // no primary/replica clusters touched
	}

	public function setRequestInfo( array $info ) {
		if ( $this->chronProt ) {
			throw new LogicException( 'ChronologyProtector already initialized' );
		}

		$this->requestInfo = $info + $this->requestInfo;
	}

	public function setDefaultReplicationWaitTimeout( $seconds ) {
		$old = $this->replicationWaitTimeout;
		$this->replicationWaitTimeout = max( 1, (int)$seconds );

		return $old;
	}

	/**
	 * @param string $stage
	 */
	private function assertTransactionRoundStage( $stage ) {
		if ( $this->trxRoundStage !== $stage ) {
			throw new DBTransactionError(
				null,
				"Transaction round stage must be '$stage' (not '{$this->trxRoundStage}')"
			);
		}
	}

	/**
	 * @param float|null &$time Mock UNIX timestamp for testing
	 * @codeCoverageIgnore
	 */
	public function setMockTime( &$time ) {
		$this->getChronologyProtector()->setMockTime( $time );
	}
}
