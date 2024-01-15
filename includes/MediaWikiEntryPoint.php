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

namespace MediaWiki;

use Exception;
use IBufferingStatsdDataFactory;
use IContextSource;
use JobQueueGroup;
use Liuggio\StatsdClient\Sender\SocketSender;
use LogicException;
use MediaWiki\Config\Config;
use MediaWiki\Config\ConfigException;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\TransactionRoundDefiningUpdate;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebResponse;
use MediaWiki\Specials\SpecialRunJobs;
use MediaWiki\WikiMap\WikiMap;
use MWExceptionHandler;
use Profiler;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SamplingStatsdClient;
use Throwable;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\ScopedCallback;

/**
 * @defgroup entrypoint Entry points
 *
 * Web entry points reside in top-level MediaWiki directory (i.e. installation path).
 * These entry points handle web requests to interact with the wiki. Other PHP files
 * in the repository are not accessed directly from the web, but instead included by
 * an entry point.
 */

/**
 * Base class for entry point handlers.
 *
 * @note: This is not stable to extend by extensions, because MediaWiki does not
 * allow extensions to define new entry points.
 *
 * @ingroup entrypoint
 * @since 1.42, factored out of the previously existing MediaWiki class.
 */
abstract class MediaWikiEntryPoint {
	use ProtectedHookAccessorTrait;

	private IContextSource $context;
	private Config $config;

	/** @var int Class DEFER_* constant; how non-blocking post-response tasks should run */
	private int $postSendStrategy;

	/** Call fastcgi_finish_request() to make post-send updates async */
	private const DEFER_FASTCGI_FINISH_REQUEST = 1;

	/** Set Content-Length and call ob_end_flush()/flush() to make post-send updates async */
	private const DEFER_SET_LENGTH_AND_FLUSH = 2;

	/** Do not try to make post-send updates async (e.g. for CLI mode) */
	private const DEFER_CLI_MODE = 3;

	private bool $preparedForOutput = false;

	/**
	 * @param IContextSource $context
	 */
	public function __construct( IContextSource $context ) {
		$this->context = $context;
		$this->config = $this->context->getConfig();

		if ( MW_ENTRY_POINT === 'cli' ) {
			$this->postSendStrategy = self::DEFER_CLI_MODE;
		} elseif ( function_exists( 'fastcgi_finish_request' ) ) {
			$this->postSendStrategy = self::DEFER_FASTCGI_FINISH_REQUEST;
		} else {
			$this->postSendStrategy = self::DEFER_SET_LENGTH_AND_FLUSH;
		}
	}

	/**
	 * Perform any setup needed before execute() is called.
	 * Final wrapper function for setup().
	 * Will be called by doRun().
	 */
	final protected function setup() {
		// Much of the functionality in WebStart.php and Setup.php should be moved here eventually.
		// As of MW 1.41, a lot of it still wants to run in file scope.

		// TODO: move define( 'MW_ENTRY_POINT' here )
		// TODO: move ProfilingContext::singleton()->init( ... ) here.

		$this->doSetup();
	}

	/**
	 * Perform any setup needed before execute() is called.
	 * Called by doRun() via doSetup().
	 */
	protected function doSetup() {
		// no-op
	}

	/**
	 * Prepare for sending the output. Should be called by entry points before
	 * sending the response.
	 * Final wrapper function for doPrepareForOutput().
	 * Will be called automatically at the end of doRun(), but will do nothing if it was
	 * already called from execute().
	 */
	final protected function prepareForOutput() {
		if ( $this->preparedForOutput ) {
			// only do this once.
			return;
		}

		$this->preparedForOutput = true;

		$this->doPrepareForOutput();
	}

	/**
	 * Prepare for sending the output. Should be called by entry points before
	 * sending the response.
	 * Will be called automatically by run() via prepareForOutput().
	 * Subclasses may override this method, but should not call it directly.
	 *
	 * @note arc-lamp profiling relies on the name of this method,
	 *        it's hard coded in the arclamp-generate-svgs script!
	 */
	protected function doPrepareForOutput() {
		// Commit any changes in the current transaction round so that:
		// a) the transaction is not rolled back after success output was already sent
		// b) error output is not jumbled together with success output in the response
		// TODO: split this up and pull out stuff like spreading cookie blocks
		$this->commitMainTransaction();
	}

	/**
	 * Main app life cycle: Calls doSetup(), execute(),
	 * prepareForOutput(), and postOutputShutdown().
	 */
	final public function run() {
		$this->setup();

		try {
			$this->execute();

			// Prepare for flushing the output. Will do nothing if it was already called by execute().
			$this->prepareForOutput();
		} catch ( Throwable $e ) {
			$this->handleTopLevelError( $e );
		}

		$this->postOutputShutdown();
	}

	/**
	 * Report a top level error.
	 * Subclasses in core may override this to handle errors according
	 * to the expected output format.
	 * This method is not safe to override for extensions.
	 *
	 * @param Throwable $e
	 */
	protected function handleTopLevelError( Throwable $e ) {
		// Type errors and such: at least handle it now and clean up the LBFactory state
		MWExceptionHandler::handleException( $e, MWExceptionHandler::CAUGHT_BY_ENTRYPOINT );
	}

	/**
	 * Subclasses implement the entry point's functionality by overriding this method.
	 * This method is not safe to override for extensions.
	 */
	abstract protected function execute();

	/**
	 * If enabled, after everything specific to this request is done, occasionally run jobs
	 */
	protected function schedulePostSendJobs() {
		$jobRunRate = $this->config->get( MainConfigNames::JobRunRate );
		if (
			// Post-send job running disabled
			$jobRunRate <= 0 ||
			// Jobs cannot run due to site read-only mode
			MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ||
			// HTTP response body and Content-Length headers likely to not match,
			// causing post-send updates to block the client when using mod_php
			$this->context->getRequest()->getMethod() === 'HEAD' ||
			$this->context->getRequest()->getHeader( 'If-Modified-Since' ) ||
			$this->context->getRequest()->getHeader( 'If-None-Match' )
		) {
			return;
		}

		if ( $jobRunRate < 1 ) {
			$max = mt_getrandmax();
			if ( mt_rand( 0, $max ) > $max * $jobRunRate ) {
				return; // the higher the job run rate, the less likely we return here
			}
			$n = 1;
		} else {
			$n = intval( $jobRunRate );
		}

		// Note that DeferredUpdates will catch and log any errors (T88312)
		DeferredUpdates::addUpdate( new TransactionRoundDefiningUpdate( function () use ( $n ) {
			$logger = LoggerFactory::getInstance( 'runJobs' );
			if ( $this->config->get( MainConfigNames::RunJobsAsync ) ) {
				// Send an HTTP request to the job RPC entry point if possible
				$invokedWithSuccess = $this->triggerAsyncJobs( $n, $logger );
				if ( !$invokedWithSuccess ) {
					// Fall back to blocking on running the job(s)
					$logger->warning( "Jobs switched to blocking; Special:RunJobs disabled" );
					$this->triggerSyncJobs( $n );
				}
			} else {
				$this->triggerSyncJobs( $n );
			}
		}, __METHOD__ ) );
	}

	/**
	 * This function commits all DB and session changes as needed *before* the
	 * client can receive a response (in case DB commit fails) and thus also before
	 * the response can trigger a subsequent related request by the client
	 */
	protected function commitMainTransaction() {
		$context = $this->context;

		$config = $context->getConfig();
		$request = $context->getRequest();
		$output = $context->getOutput();
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();

		// Try to make sure that all RDBMs, session, and other storage updates complete
		ignore_user_abort( true );

		// Commit all RDBMs changes from the main transaction round
		$lbFactory->commitPrimaryChanges(
			__METHOD__,
			// Abort if any transaction was too big
			$config->get( MainConfigNames::MaxUserDBWriteDuration )
		);
		wfDebug( __METHOD__ . ': primary transaction round committed' );

		// Run updates that need to block the client or affect output (this is the last chance)
		DeferredUpdates::doUpdates(
			$config->get( MainConfigNames::ForceDeferredUpdatesPreSend )
				? DeferredUpdates::ALL
				: DeferredUpdates::PRESEND
		);

		wfDebug( __METHOD__ . ': pre-send deferred updates completed' );

		// Persist the session to avoid race conditions on subsequent requests by the client
		$request->getSession()->save(); // T214471
		wfDebug( __METHOD__ . ': session changes committed' );

		// Subsequent requests by the client should see the DB replication positions, as written
		// to ChronologyProtector during the shutdown() call below.
		// Setting the cpPosIndex cookie is normally enough. However, this will not work for
		// cross-wiki redirects within the same wiki farm, so set the ?cpPoxIndex in that case.
		$isCrossWikiRedirect = (
			$output->getRedirect() &&
			$lbFactory->hasOrMadeRecentPrimaryChanges( INF ) &&
			self::getUrlDomainDistance( $output->getRedirect() ) === 'remote'
		);

		// Persist replication positions for DBs modified by this request (at this point).
		// These help provide "session consistency" for the client on their next requests.
		$cpIndex = null;
		$cpClientId = null;
		$lbFactory->shutdown(
			$lbFactory::SHUTDOWN_NORMAL,
			null,
			$cpIndex,
			$cpClientId
		);
		$now = time();

		$allowHeaders = !( $output->isDisabled() || headers_sent() );

		if ( $cpIndex > 0 ) {
			if ( $allowHeaders ) {
				$expires = $now + ChronologyProtector::POSITION_COOKIE_TTL;
				$options = [ 'prefix' => '' ];
				$value = ChronologyProtector::makeCookieValueFromCPIndex( $cpIndex, $now, $cpClientId );
				$request->response()->setCookie( 'cpPosIndex', $value, $expires, $options );
			}

			if ( $isCrossWikiRedirect ) {
				if ( $output->getRedirect() ) {
					$url = $output->getRedirect();
					if ( $lbFactory->hasStreamingReplicaServers() ) {
						$url = strpos( $url, '?' ) === false
							? "$url?cpPosIndex=$cpIndex" : "$url&cpPosIndex=$cpIndex";
					}
					$output->redirect( $url );
				} else {
					MWExceptionHandler::logException(
						new LogicException( "No redirect; cannot append cpPosIndex parameter." ),
						MWExceptionHandler::CAUGHT_BY_ENTRYPOINT
					);
				}
			}
		}

		if ( $allowHeaders ) {
			// Set a cookie to tell all CDN edge nodes to "stick" the user to the DC that
			// handles this POST request (e.g. the "primary" data center). Also have the user
			// briefly bypass CDN so ChronologyProtector works for cacheable URLs.
			if ( $request->wasPosted() && $lbFactory->hasOrMadeRecentPrimaryChanges() ) {
				$expires = $now + max(
						ChronologyProtector::POSITION_COOKIE_TTL,
						$config->get( MainConfigNames::DataCenterUpdateStickTTL )
					);
				$options = [ 'prefix' => '' ];
				$request->response()->setCookie( 'UseDC', 'master', $expires, $options );
			}

			// Avoid letting a few seconds of replica DB lag cause a month of stale data.
			// This logic is also intimately related to the value of $wgCdnReboundPurgeDelay.
			if ( $lbFactory->laggedReplicaUsed() ) {
				$maxAge = $config->get( MainConfigNames::CdnMaxageLagged );
				$output->lowerCdnMaxage( $maxAge );
				$request->response()->header( "X-Database-Lagged: true" );
				wfDebugLog( 'replication',
					"Lagged DB used; CDN cache TTL limited to $maxAge seconds" );
			}

			// Avoid long-term cache pollution due to message cache rebuild timeouts (T133069)
			if ( $services->getMessageCache()->isDisabled() ) {
				$maxAge = $config->get( MainConfigNames::CdnMaxageSubstitute );
				$output->lowerCdnMaxage( $maxAge );
				$request->response()->header( "X-Response-Substitute: true" );
			}

			if ( !$output->couldBePublicCached() || $output->haveCacheVaryCookies() ) {
				// Autoblocks: If this user is autoblocked (and the cookie block feature is enabled
				// for autoblocks), then set a cookie to track this block.
				// This has to be done on all logged-in page loads (not just upon saving edits),
				// because an autoblocked editor might not edit again from the same IP address.
				//
				// IP blocks: For anons, if their IP is blocked (and cookie block feature is enabled
				// for IP blocks), we also want to set the cookie whenever it is safe to do.
				// Basically from any url that are definitely not publicly cacheable (like viewing
				// EditPage), or when the HTTP response is personalised for other reasons (e.g. viewing
				// articles within the same browsing session after making an edit).
				$user = $context->getUser();
				$services->getBlockManager()
					->trackBlockWithCookie( $user, $request->response() );
			}
		}
	}

	/**
	 * @param string $url
	 * @return string Either "local", "remote" if in the farm, "external" otherwise
	 */
	private static function getUrlDomainDistance( $url ) {
		$clusterWiki = WikiMap::getWikiFromUrl( $url );
		if ( WikiMap::isCurrentWikiId( $clusterWiki ) ) {
			return 'local'; // the current wiki
		}
		if ( $clusterWiki !== false ) {
			return 'remote'; // another wiki in this cluster/farm
		}

		return 'external';
	}

	/**
	 * Forces the response to be sent to the client and then
	 * does work that can be done *after* the
	 * user gets the HTTP response, so they don't block on it.
	 */
	final protected function postOutputShutdown() {
		$this->doPostOutputShutdown();
	}

	/**
	 * Forces the response to be sent to the client and then
	 * does work that can be done *after* the
	 * user gets the HTTP response, so they don't block on it.
	 *
	 * @since 1.26 (formerly on the MediaWiki class)
	 *
	 * @note arc-lamp profiling relies on the name of this method,
	 *        it's hard coded in the arclamp-generate-svgs script!
	 */
	protected function doPostOutputShutdown() {
		// Record backend request timing
		$timing = $this->context->getTiming();
		$timing->mark( 'requestShutdown' );

		// Defer everything else if possible...
		if ( $this->postSendStrategy === self::DEFER_FASTCGI_FINISH_REQUEST ) {
			// Flush the output to the client, continue processing, and avoid further output
			fastcgi_finish_request();
		} elseif ( $this->postSendStrategy === self::DEFER_SET_LENGTH_AND_FLUSH ) {
			// Flush the output to the client, continue processing, and avoid further output
			if ( ob_get_level() ) {
				// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
				@ob_end_flush();
			}
			// Flush the web server output buffer to the client/proxy if possible
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			@flush();
		}

		// Since the headers and output where already flushed, disable WebResponse setters
		// during post-send processing to warnings and unexpected behavior (T191537)
		WebResponse::disableForPostSend();
		// Run post-send updates while preventing further output...
		ob_start( static function () {
			return ''; // do not output uncaught exceptions
		} );
		try {
			$this->restInPeace();
		} catch ( Throwable $e ) {
			MWExceptionHandler::rollbackPrimaryChangesAndLog(
				$e,
				MWExceptionHandler::CAUGHT_BY_ENTRYPOINT
			);
		}
		$length = ob_get_length();
		if ( $length > 0 ) {
			trigger_error( __METHOD__ . ": suppressed $length byte(s)", E_USER_NOTICE );
		}
		ob_end_clean();
	}

	/**
	 * Check if an HTTP->HTTPS redirect should be done. It may still be aborted
	 * by a hook, so this is not the final word.
	 *
	 * @return bool
	 */
	protected function shouldDoHttpRedirect() {
		$request = $this->context->getRequest();

		// Don't redirect if we're already on HTTPS
		if ( $request->getProtocol() !== 'http' ) {
			return false;
		}

		$force = $this->config->get( MainConfigNames::ForceHTTPS );

		// Don't redirect if $wgServer is explicitly HTTP. We test for this here
		// by checking whether UrlUtils::expand() is able to force HTTPS.
		if (
			!preg_match(
				'#^https://#',
				(string)MediaWikiServices::getInstance()->getUrlUtils()->expand(
					$request->getRequestURL(),
					PROTO_HTTPS
				)
			)
		) {
			if ( $force ) {
				throw new RuntimeException( '$wgForceHTTPS is true but the server is not HTTPS' );
			}
			return false;
		}

		// Configured $wgForceHTTPS overrides the remaining conditions
		if ( $force ) {
			return true;
		}

		// Check if HTTPS is required by the session or user preferences
		return $request->getSession()->shouldForceHTTPS() ||
			// Check the cookie manually, for paranoia
			$request->getCookie( 'forceHTTPS', '' ) ||
			$this->context->getUser()->requiresHTTPS();
	}

	/**
	 * Print a response body to the current buffer (if there is one) or the server (otherwise)
	 *
	 * This method should be called after commitMainTransaction() and before postOutputShutdown()
	 *
	 * Any accompanying Content-Type header is assumed to have already been set
	 *
	 * @param string $content Response content, usually from OutputPage::output()
	 */
	protected function outputResponsePayload( $content ) {
		// Append any visible profiling data in a manner appropriate for the Content-Type
		ob_start();
		try {
			Profiler::instance()->logDataPageOutputOnly();
		} finally {
			$content .= ob_get_clean();
		}

		// By default, usually one output buffer is active now, either the internal PHP buffer
		// started by "output_buffering" in php.ini or the buffer started by MW_SETUP_CALLBACK.
		// The MW_SETUP_CALLBACK buffer has an unlimited chunk size, while the internal PHP
		// buffer only has an unlimited chunk size if output_buffering="On". If the buffer was
		// filled up to the chunk size with printed data, then HTTP headers will have already
		// been sent. Also, if the entry point had to stream content to the client, then HTTP
		// headers will have already been sent as well, regardless of chunk size.

		// Disable mod_deflate compression since it interferes with the output buffer set
		// by MW_SETUP_CALLBACK and can also cause the client to wait on deferred updates
		if ( function_exists( 'apache_setenv' ) ) {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			@apache_setenv( 'no-gzip', '1' );
		}

		if (
			// "Content-Length" is used to prevent clients from waiting on deferred updates
			$this->postSendStrategy === self::DEFER_SET_LENGTH_AND_FLUSH &&
			// The HTTP response code clearly allows for a meaningful body
			in_array( http_response_code(), [ 200, 404 ], true ) &&
			// The queue of (post-send) deferred updates is non-empty
			DeferredUpdates::pendingUpdatesCount() &&
			// Any buffered output is not spread out across multiple output buffers
			ob_get_level() <= 1 &&
			// It is not too late to set additional HTTP headers
			!headers_sent()
		) {
			$response = $this->context->getRequest()->response();

			$obStatus = ob_get_status();
			if ( !isset( $obStatus['name'] ) ) {
				// No output buffer is active
				$response->header( 'Content-Length: ' . strlen( $content ) );
			} elseif ( $obStatus['name'] === 'default output handler' ) {
				// Internal PHP "output_buffering" output buffer (note that the internal PHP
				// "zlib.output_compression" output buffer is named "zlib output compression")
				$response->header( 'Content-Length: ' . ( ob_get_length() + strlen( $content ) ) );
			}

			// The MW_SETUP_CALLBACK output buffer ("MediaWiki\OutputHandler::handle") sets
			// "Content-Length" where applicable. Other output buffer types might not set this
			// header, and since they might mangle or compress the payload, it is not possible
			// to determine the final payload size here.

			// Tell the client to immediately end the connection as soon as the response payload
			// has been read (informed by any "Content-Length" header). This prevents the client
			// from waiting on deferred updates.
			// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Connection
			if ( ( $_SERVER['SERVER_PROTOCOL'] ?? '' ) === 'HTTP/1.1' ) {
				$response->header( 'Connection: close' );
			}
		}

		// Print the content *after* adjusting HTTP headers and disabling mod_deflate since
		// calling "print" will send the output to the client if there is no output buffer or
		// if the output buffer chunk size is reached
		print $content;
	}

	/**
	 * Ends this task peacefully.
	 * Called after the response has been sent to the client.
	 * Subclasses in core may override this to add end-of-request code,
	 * but should always call the parent method.
	 * This method is not safe to override by extensions.
	 */
	protected function restInPeace() {
		// Either all DB and deferred updates should happen or none.
		// The latter should not be cancelled due to client disconnect.
		ignore_user_abort( true );

		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		// Assure deferred updates are not in the main transaction
		$lbFactory->commitPrimaryChanges( __METHOD__ );

		// Loosen DB query expectations since the HTTP client is unblocked
		$profiler = Profiler::instance();
		$trxProfiler = $profiler->getTransactionProfiler();
		$trxProfiler->redefineExpectations(
			$this->context->getRequest()->hasSafeMethod()
				? $this->config->get( MainConfigNames::TrxProfilerLimits )['PostSend-GET']
				: $this->config->get( MainConfigNames::TrxProfilerLimits )['PostSend-POST'],
			__METHOD__
		);

		// Do any deferred jobs; preferring to run them now if a client will not wait on them
		DeferredUpdates::doUpdates();

		// Handle external profiler outputs.
		// Any embedded profiler outputs were already processed in outputResponsePayload().
		$profiler->logData();

		// Send metrics gathered by StatsFactory
		$services->getStatsFactory()->flush();

		self::emitBufferedStatsdData(
			$services->getStatsdDataFactory(),
			$this->config
		);

		// Commit and close up!
		$lbFactory->commitPrimaryChanges( __METHOD__ );
		$lbFactory->shutdown( $lbFactory::SHUTDOWN_NO_CHRONPROT );

		wfDebug( "Request ended normally" );
	}

	/**
	 * Send out any buffered statsd data according to sampling rules
	 *
	 * For web requests, this is called once by MediaWiki::restInPeace(),
	 * which is post-send (after the response is sent to the client).
	 *
	 * For maintenance scripts, especially long-running CLI scripts, it is called
	 * more often, to avoid OOM, since we buffer stats (T181385), based on the
	 * following heuristics:
	 *
	 * - Long-running scripts that involve database writes often use transactions
	 *   to commit chunks of work. We flush from IDatabase::setTransactionListener,
	 *   as wired up by MWLBFactory::applyGlobalState.
	 *
	 * - Long-running scripts that involve database writes but don't need any
	 *   transactions will still periodically wait for replication to be
	 *   graceful to the databases. We flush from ILBFactory::setWaitForReplicationListener
	 *   as wired up by MWLBFactory::applyGlobalState.
	 *
	 * - Any other long-running scripts will probably report progress to stdout
	 *   in some way. We also flush from Maintenance::output().
	 *
	 * @param IBufferingStatsdDataFactory $stats
	 * @param Config $config
	 * @throws ConfigException
	 * @since 1.31 (formerly one the MediaWiki class)
	 */
	public static function emitBufferedStatsdData(
		IBufferingStatsdDataFactory $stats, Config $config
	) {
		if ( $config->get( MainConfigNames::StatsdServer ) && $stats->hasData() ) {
			try {
				$stats->updateCount( 'stats.statsdclient.buffered', $stats->getDataCount() );
				$statsdServer = explode( ':', $config->get( MainConfigNames::StatsdServer ), 2 );
				$statsdHost = $statsdServer[0];
				$statsdPort = $statsdServer[1] ?? 8125;
				$statsdSender = new SocketSender( $statsdHost, $statsdPort );
				$statsdClient = new SamplingStatsdClient( $statsdSender, true, false );
				$statsdClient->setSamplingRates( $config->get( MainConfigNames::StatsdSamplingRates ) );
				$statsdClient->send( $stats->getData() );
			} catch ( Exception $e ) {
				MWExceptionHandler::logException( $e, MWExceptionHandler::CAUGHT_BY_ENTRYPOINT );
			}
		}
		// empty buffer for the next round
		$stats->clearData();
	}

	/**
	 * @param int $n Number of jobs to try to run
	 */
	protected function triggerSyncJobs( $n ) {
		$scope = Profiler::instance()->getTransactionProfiler()->silenceForScope();
		$runner = MediaWikiServices::getInstance()->getJobRunner();
		$runner->run( [ 'maxJobs' => $n ] );
		ScopedCallback::consume( $scope );
	}

	/**
	 * @param int $n Number of jobs to try to run
	 * @param LoggerInterface $runJobsLogger
	 * @return bool Success
	 */
	protected function triggerAsyncJobs( $n, LoggerInterface $runJobsLogger ) {
		$services = MediaWikiServices::getInstance();
		// Do not send request if there are probably no jobs
		$group = $services->getJobQueueGroupFactory()->makeJobQueueGroup();
		if ( !$group->queuesHaveJobs( JobQueueGroup::TYPE_DEFAULT ) ) {
			return true;
		}

		$query = [ 'title' => 'Special:RunJobs',
			'tasks' => 'jobs', 'maxjobs' => $n, 'sigexpiry' => time() + 5 ];
		$query['signature'] = SpecialRunJobs::getQuerySignature(
			$query, $this->config->get( MainConfigNames::SecretKey ) );

		$errno = $errstr = null;
		$info = $services->getUrlUtils()->parse( $this->config->get( MainConfigNames::CanonicalServer ) ) ?? [];
		$https = ( $info['scheme'] ?? null ) === 'https';
		$host = $info['host'] ?? null;
		$port = $info['port'] ?? ( $https ? 443 : 80 );

		AtEase::suppressWarnings();
		$sock = $host ? fsockopen(
			$https ? 'tls://' . $host : $host,
			$port,
			$errno,
			$errstr,
			// If it takes more than 100ms to connect to ourselves there is a problem...
			0.100
		) : false;
		AtEase::restoreWarnings();

		$invokedWithSuccess = true;
		if ( $sock ) {
			$special = $services->getSpecialPageFactory()->getPage( 'RunJobs' );
			$url = $special->getPageTitle()->getCanonicalURL( $query );
			$req = (
				"POST $url HTTP/1.1\r\n" .
				"Host: $host\r\n" .
				"Connection: Close\r\n" .
				"Content-Length: 0\r\n\r\n"
			);

			$runJobsLogger->info( "Running $n job(s) via '$url'" );
			// Send a cron API request to be performed in the background.
			// Give up if this takes too long to send (which should be rare).
			stream_set_timeout( $sock, 2 );
			$bytes = fwrite( $sock, $req );
			if ( $bytes !== strlen( $req ) ) {
				$invokedWithSuccess = false;
				$runJobsLogger->error( "Failed to start cron API (socket write error)" );
			} else {
				// Do not wait for the response (the script should handle client aborts).
				// Make sure that we don't close before that script reaches ignore_user_abort().
				$start = microtime( true );
				$status = fgets( $sock );
				$sec = microtime( true ) - $start;
				if ( !preg_match( '#^HTTP/\d\.\d 202 #', $status ) ) {
					$invokedWithSuccess = false;
					$runJobsLogger->error( "Failed to start cron API: received '$status' ($sec)" );
				}
			}
			fclose( $sock );
		} else {
			$invokedWithSuccess = false;
			$runJobsLogger->error( "Failed to start cron API (socket error $errno): $errstr" );
		}

		return $invokedWithSuccess;
	}

	/**
	 * Returns the main service container.
	 *
	 * This is intended as a stepping stone for migration.
	 * Ideally, individual service objects should be injected
	 * via the constructor.
	 *
	 * @return MediaWikiServices
	 */
	protected function getServiceContainer(): MediaWikiServices {
		return MediaWikiServices::getInstance();
	}

	protected function getContext(): IContextSource {
		return $this->context;
	}

	protected function getRequest(): WebRequest {
		return $this->context->getRequest();
	}

	protected function getResponse(): WebResponse {
		return $this->getRequest()->response();
	}

	protected function getConfig( string $key ) {
		return $this->config->get( $key );
	}
}
