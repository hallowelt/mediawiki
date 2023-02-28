<?php
/**
 * Obligatory redundant license notice. Exception to the GPL's "keep intact all
 * the notices" clause with respect to this notice is hereby granted.
 *
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
 * @ingroup Maintenance
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to rename titles affected by changes to Unicode (or
 * otherwise to Language::ucfirst).
 *
 * @ingroup Maintenance
 */
class UppercaseTitlesForUnicodeTransition extends Maintenance {

	private const MOVE = 0;
	private const INPLACE_MOVE = 1;
	private const UPPERCASE = 2;

	/** @var bool */
	private $run = false;

	/** @var array */
	private $charmap = [];

	/** @var User */
	private $user;

	/** @var string */
	private $reason = 'Uppercasing title for Unicode upgrade';

	/** @var string[] */
	private $tags = [];

	/** @var array */
	private $seenUsers = [];

	/** @var array|null */
	private $namespaces = null;

	/** @var string|null */
	private $prefix = null, $suffix = null;

	/** @var int|null */
	private $prefixNs = null;

	/** @var string[]|null */
	private $tables = null;

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Rename titles when changing behavior of Language::ucfirst().\n"
			. "\n"
			. "This script skips User and User_talk pages for registered users, as renaming of users "
			. "is too complex to try to implement here. Use something like Extension:Renameuser to "
			. "clean those up; this script can provide a list of user names affected."
		);
		$this->addOption(
			'charmap', 'Character map generated by maintenance/language/generateUcfirstOverrides.php',
			true, true
		);
		$this->addOption(
			'user', 'System user to use to do the renames. Default is "Maintenance script".', false, true
		);
		$this->addOption(
			'steal',
			'If the username specified by --user exists, specify this to force conversion to a system user.'
		);
		$this->addOption(
			'run', 'If not specified, the script will not actually perform any moves (i.e. it will dry-run).'
		);
		$this->addOption(
			'prefix', 'When the new title already exists, add this prefix.', false, true
		);
		$this->addOption(
			'suffix', 'When the new title already exists, add this suffix.', false, true
		);
		$this->addOption( 'reason', 'Reason to use when moving pages.', false, true );
		$this->addOption( 'tag', 'Change tag to apply when moving pages.', false, true );
		$this->addOption( 'tables', 'Comma-separated list of database tables to process.', false, true );
		$this->addOption(
			'userlist', 'Filename to which to output usernames needing rename. ' .
			'This file can then be used directly by renameInvalidUsernames.php maintenance script',
			false,
			true
		);
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$this->run = $this->getOption( 'run', false );

		if ( $this->run ) {
			$username = $this->getOption( 'user', User::MAINTENANCE_SCRIPT_USER );
			$steal = $this->getOption( 'steal', false );
			$this->user = User::newSystemUser( $username, [ 'steal' => $steal ] );
			if ( !$this->user ) {
				$user = User::newFromName( $username );
				if ( !$steal && $user && $user->isRegistered() ) {
					$this->fatalError( "User $username already exists.\n"
						. "Use --steal if you really want to steal it from the human who currently owns it."
					);
				}
				$this->fatalError( "Could not obtain system user $username." );
			}
		}

		$tables = $this->getOption( 'tables' );
		if ( $tables !== null ) {
			$this->tables = explode( ',', $tables );
		}

		$prefix = $this->getOption( 'prefix' );
		if ( $prefix !== null ) {
			$title = Title::newFromText( $prefix . 'X' );
			if ( !$title || substr( $title->getDBkey(), -1 ) !== 'X' ) {
				$this->fatalError( 'Invalid --prefix.' );
			}
			if ( $title->getNamespace() <= NS_MAIN || $title->isExternal() ) {
				$this->fatalError( 'Invalid --prefix. It must not be in namespace 0 and must not be external' );
			}
			$this->prefixNs = $title->getNamespace();
			$this->prefix = substr( $title->getText(), 0, -1 );
		}
		$this->suffix = $this->getOption( 'suffix' );

		$this->reason = $this->getOption( 'reason' ) ?: $this->reason;
		$this->tags = (array)$this->getOption( 'tag', null );

		$charmapFile = $this->getOption( 'charmap' );
		if ( !file_exists( $charmapFile ) ) {
			$this->fatalError( "Charmap file $charmapFile does not exist." );
		}
		if ( !is_file( $charmapFile ) || !is_readable( $charmapFile ) ) {
			$this->fatalError( "Charmap file $charmapFile is not readable." );
		}
		$this->charmap = require $charmapFile;
		if ( !is_array( $this->charmap ) ) {
			$this->fatalError( "Charmap file $charmapFile did not return a PHP array." );
		}
		$this->charmap = array_filter(
			$this->charmap,
			function ( $v, $k ) {
				if ( mb_strlen( $k ) !== 1 ) {
					$this->error( "Ignoring mapping from multi-character key '$k' to '$v'" );
					return false;
				}
				return $k !== $v;
			},
			ARRAY_FILTER_USE_BOTH
		);
		if ( !$this->charmap ) {
			$this->fatalError( "Charmap file $charmapFile did not contain any usable character mappings." );
		}

		$db = $this->getDB( $this->run ? DB_PRIMARY : DB_REPLICA );

		// Process inplace moves first, before actual moves, so mungeTitle() doesn't get confused
		$this->processTable(
			$db, self::INPLACE_MOVE, 'archive', 'ar_namespace', 'ar_title', [ 'ar_timestamp', 'ar_id' ]
		);
		$this->processTable(
			$db, self::INPLACE_MOVE, 'filearchive', NS_FILE, 'fa_name', [ 'fa_timestamp', 'fa_id' ]
		);
		$this->processTable(
			$db, self::INPLACE_MOVE, 'logging', 'log_namespace', 'log_title', [ 'log_id' ]
		);
		$this->processTable(
			$db, self::INPLACE_MOVE, 'protected_titles', 'pt_namespace', 'pt_title', []
		);
		$this->processTable( $db, self::MOVE, 'page', 'page_namespace', 'page_title', [ 'page_id' ] );
		$this->processTable( $db, self::MOVE, 'image', NS_FILE, 'img_name', [] );
		$this->processTable(
			$db, self::UPPERCASE, 'redirect', 'rd_namespace', 'rd_title', [ 'rd_from' ]
		);
		$this->processUsers( $db );
	}

	/**
	 * Get batched LIKE conditions from the charmap
	 * @param ISQLPlatform $db Database handle
	 * @param string $field Field name
	 * @param int $batchSize Size of the batches
	 * @return array
	 */
	private function getLikeBatches( ISQLPlatform $db, $field, $batchSize = 100 ) {
		$ret = [];
		$likes = [];
		foreach ( $this->charmap as $from => $to ) {
			$likes[] = $field . $db->buildLike( $from, $db->anyString() );
			if ( count( $likes ) >= $batchSize ) {
				$ret[] = $db->makeList( $likes, $db::LIST_OR );
				$likes = [];
			}
		}
		if ( $likes ) {
			$ret[] = $db->makeList( $likes, $db::LIST_OR );
		}
		return $ret;
	}

	/**
	 * Get the list of namespaces to operate on
	 *
	 * We only care about namespaces where we can move pages and titles are
	 * capitalized.
	 *
	 * @return int[]
	 */
	private function getNamespaces() {
		if ( $this->namespaces === null ) {
			$nsinfo = MediaWikiServices::getInstance()->getNamespaceInfo();
			$this->namespaces = array_filter(
				array_keys( $nsinfo->getCanonicalNamespaces() ),
				static function ( $ns ) use ( $nsinfo ) {
					return $nsinfo->isMovable( $ns ) && $nsinfo->isCapitalized( $ns );
				}
			);
			usort( $this->namespaces, static function ( $ns1, $ns2 ) use ( $nsinfo ) {
				if ( $ns1 === $ns2 ) {
					return 0;
				}

				$s1 = $nsinfo->getSubject( $ns1 );
				$s2 = $nsinfo->getSubject( $ns2 );

				// Order by subject namespace number first
				if ( $s1 !== $s2 ) {
					return $s1 < $s2 ? -1 : 1;
				}

				// Second, put subject namespaces before non-subject namespaces
				if ( $s1 === $ns1 ) {
					return -1;
				}
				if ( $s2 === $ns2 ) {
					return 1;
				}

				// Don't care about the relative order if there are somehow
				// multiple non-subject namespaces for a namespace.
				return 0;
			} );
		}

		return $this->namespaces;
	}

	/**
	 * Check if a ns+title is a registered user's page
	 * @param IReadableDatabase $db Database handle
	 * @param int $ns
	 * @param string $title
	 * @return bool
	 */
	private function isUserPage( IReadableDatabase $db, $ns, $title ) {
		if ( $ns !== NS_USER && $ns !== NS_USER_TALK ) {
			return false;
		}

		[ $base ] = explode( '/', $title, 2 );
		if ( !isset( $this->seenUsers[$base] ) ) {
			// Can't use User directly because it might uppercase the name
			$this->seenUsers[$base] = (bool)$db->selectField(
				'user',
				'user_id',
				[ 'user_name' => strtr( $base, '_', ' ' ) ],
				__METHOD__
			);
		}
		return $this->seenUsers[$base];
	}

	/**
	 * Munge a target title, if necessary
	 * @param IReadableDatabase $db Database handle
	 * @param Title $oldTitle
	 * @param Title &$newTitle
	 * @return bool If $newTitle is (now) ok
	 */
	private function mungeTitle( IReadableDatabase $db, Title $oldTitle, Title &$newTitle ) {
		$nt = $newTitle->getPrefixedText();

		$munge = false;
		if ( $this->isUserPage( $db, $newTitle->getNamespace(), $newTitle->getText() ) ) {
			$munge = 'Target title\'s user exists';
		} else {
			$mpFactory = MediaWikiServices::getInstance()->getMovePageFactory();
			$status = $mpFactory->newMovePage( $oldTitle, $newTitle )->isValidMove();
			if ( !$status->isOK() && (
				$status->hasMessage( 'articleexists' ) || $status->hasMessage( 'redirectexists' ) ) ) {
				$munge = 'Target title exists';
			}
		}
		if ( !$munge ) {
			return true;
		}

		if ( $this->prefix !== null ) {
			$newTitle = Title::makeTitle(
				$this->prefixNs,
				$this->prefix . $oldTitle->getPrefixedText() . ( $this->suffix ?? '' )
			);
		} elseif ( $this->suffix !== null ) {
			$dbkey = $newTitle->getText();
			$i = $newTitle->getNamespace() === NS_FILE ? strrpos( $dbkey, '.' ) : false;
			if ( $i !== false ) {
				$newTitle = Title::makeTitle(
					$newTitle->getNamespace(),
					substr( $dbkey, 0, $i ) . $this->suffix . substr( $dbkey, $i )
				);
			} else {
				$newTitle = Title::makeTitle( $newTitle->getNamespace(), $dbkey . $this->suffix );
			}
		} else {
			$this->error(
				"Cannot move {$oldTitle->getPrefixedText()} → $nt: "
				. "$munge and no --prefix or --suffix was given"
			);
			return false;
		}

		if ( !$newTitle->canExist() ) {
			$this->error(
				"Cannot move {$oldTitle->getPrefixedText()} → $nt: "
				. "$munge and munged title '{$newTitle->getPrefixedText()}' is not valid"
			);
			return false;
		}
		if ( $newTitle->exists() ) {
			$this->error(
				"Cannot move {$oldTitle->getPrefixedText()} → $nt: "
				. "$munge and munged title '{$newTitle->getPrefixedText()}' also exists"
			);
			return false;
		}

		return true;
	}

	/**
	 * Use MovePage to move a title
	 * @param IDatabase $db Database handle
	 * @param int $ns
	 * @param string $title
	 * @return bool|null True on success, false on error, null if skipped
	 */
	private function doMove( IDatabase $db, $ns, $title ) {
		$char = mb_substr( $title, 0, 1 );
		if ( !array_key_exists( $char, $this->charmap ) ) {
			$this->error(
				"Query returned NS$ns $title, which does not begin with a character in the charmap."
			);
			return false;
		}

		if ( $this->isUserPage( $db, $ns, $title ) ) {
			$this->output( "... Skipping user page NS$ns $title\n" );
			return null;
		}

		$oldTitle = Title::makeTitle( $ns, $title );
		$newTitle = Title::makeTitle( $ns, $this->charmap[$char] . mb_substr( $title, 1 ) );
		$deletionReason = $this->shouldDelete( $db, $oldTitle, $newTitle );
		if ( !$this->mungeTitle( $db, $oldTitle, $newTitle ) ) {
			return false;
		}

		$services = MediaWikiServices::getInstance();
		$mpFactory = $services->getMovePageFactory();
		$movePage = $mpFactory->newMovePage( $oldTitle, $newTitle );
		$status = $movePage->isValidMove();
		if ( !$status->isOK() ) {
			$this->error(
				"Invalid move {$oldTitle->getPrefixedText()} → {$newTitle->getPrefixedText()}: "
				. $status->getMessage( false, false, 'en' )->useDatabase( false )->plain()
			);
			return false;
		}

		if ( !$this->run ) {
			$this->output(
				"Would rename {$oldTitle->getPrefixedText()} → {$newTitle->getPrefixedText()}\n"
			);
			if ( $deletionReason ) {
				$this->output(
					"Would then delete {$newTitle->getPrefixedText()}: $deletionReason\n"
				);
			}
			return true;
		}

		$status = $movePage->move( $this->user, $this->reason, false, $this->tags );
		if ( !$status->isOK() ) {
			$this->error(
				"Move {$oldTitle->getPrefixedText()} → {$newTitle->getPrefixedText()} failed: "
				. $status->getMessage( false, false, 'en' )->useDatabase( false )->plain()
			);
		}
		$this->output( "Renamed {$oldTitle->getPrefixedText()} → {$newTitle->getPrefixedText()}\n" );

		// The move created a log entry under the old invalid title. Fix it.
		$db->update(
			'logging',
			[
				'log_title' => $this->charmap[$char] . mb_substr( $title, 1 ),
			],
			[
				'log_namespace' => $oldTitle->getNamespace(),
				'log_title' => $oldTitle->getDBkey(),
				'log_page' => $newTitle->getArticleID(),
			],
			__METHOD__
		);

		if ( $deletionReason !== null ) {
			$page = $services->getWikiPageFactory()->newFromTitle( $newTitle );
			$error = '';
			$status = $page->doDeleteArticleReal(
				$deletionReason,
				$this->user,
				false, // don't suppress
				null, // unused
				$error,
				null, // unused
				[], // tags
				'delete',
				true // immediate
			);
			if ( !$status->isOK() ) {
				$this->error(
					"Deletion of {$newTitle->getPrefixedText()} failed: "
					. $status->getMessage( false, false, 'en' )->useDatabase( false )->plain()
				);
				return false;
			}
			$this->output( "Deleted {$newTitle->getPrefixedText()}\n" );
		}

		return true;
	}

	/**
	 * Determine whether the old title should be deleted
	 *
	 * If it's already a redirect to the new title, or the old and new titles
	 * are redirects to the same place, there's no point in keeping it.
	 *
	 * Note the caller will still rename it before deleting it, so the archive
	 * and logging rows wind up in a sensible place.
	 *
	 * @param IReadableDatabase $db
	 * @param Title $oldTitle
	 * @param Title $newTitle
	 * @return string|null Deletion reason, or null if it shouldn't be deleted
	 */
	private function shouldDelete( IReadableDatabase $db, Title $oldTitle, Title $newTitle ) {
		$oldRow = $db->selectRow(
			[ 'page', 'redirect' ],
			[ 'ns' => 'rd_namespace', 'title' => 'rd_title' ],
			[ 'page_namespace' => $oldTitle->getNamespace(), 'page_title' => $oldTitle->getDBkey() ],
			__METHOD__,
			[],
			[ 'redirect' => [ 'JOIN', 'rd_from = page_id' ] ]
		);
		if ( !$oldRow ) {
			// Not a redirect
			return null;
		}

		if ( (int)$oldRow->ns === $newTitle->getNamespace() &&
			$oldRow->title === $newTitle->getDBkey()
		) {
			return $this->reason . ", and found that [[{$oldTitle->getPrefixedText()}]] is "
				. "already a redirect to [[{$newTitle->getPrefixedText()}]]";
		} else {
			$newRow = $db->selectRow(
				[ 'page', 'redirect' ],
				[ 'ns' => 'rd_namespace', 'title' => 'rd_title' ],
				[ 'page_namespace' => $newTitle->getNamespace(), 'page_title' => $newTitle->getDBkey() ],
				__METHOD__,
				[],
				[ 'redirect' => [ 'JOIN', 'rd_from = page_id' ] ]
			);
			if ( $newRow && $oldRow->ns === $newRow->ns && $oldRow->title === $newRow->title ) {
				$nt = Title::makeTitle( $newRow->ns, $newRow->title );
				return $this->reason . ", and found that [[{$oldTitle->getPrefixedText()}]] and "
					. "[[{$newTitle->getPrefixedText()}]] both redirect to [[{$nt->getPrefixedText()}]].";
			}
		}

		return null;
	}

	/**
	 * Directly update a database row
	 * @param IDatabase $db Database handle
	 * @param int $op Operation to perform
	 *  - self::INPLACE_MOVE: Directly update the database table to move the page
	 *  - self::UPPERCASE: Rewrite the table to point to the new uppercase title
	 * @param string $table
	 * @param string|int $nsField
	 * @param string $titleField
	 * @param stdClass $row
	 * @return bool|null True on success, false on error, null if skipped
	 */
	private function doUpdate( IDatabase $db, $op, $table, $nsField, $titleField, $row ) {
		$ns = is_int( $nsField ) ? $nsField : (int)$row->$nsField;
		$title = $row->$titleField;

		$char = mb_substr( $title, 0, 1 );
		if ( !array_key_exists( $char, $this->charmap ) ) {
			$r = json_encode( $row, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			$this->error(
				"Query returned $r, but title does not begin with a character in the charmap."
			);
			return false;
		}

		$oldTitle = Title::makeTitle( $ns, $title );
		$newTitle = Title::makeTitle( $ns, $this->charmap[$char] . mb_substr( $title, 1 ) );
		if ( $op !== self::UPPERCASE && !$this->mungeTitle( $db, $oldTitle, $newTitle ) ) {
			return false;
		}

		if ( $this->run ) {
			$db->update(
				$table,
				array_merge(
					is_int( $nsField ) ? [] : [ $nsField => $newTitle->getNamespace() ],
					[ $titleField => $newTitle->getDBkey() ]
				),
				(array)$row,
				__METHOD__
			);
			$r = json_encode( $row, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			$this->output( "Set $r to {$newTitle->getPrefixedText()}\n" );
		} else {
			$r = json_encode( $row, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			$this->output( "Would set $r to {$newTitle->getPrefixedText()}\n" );
		}

		return true;
	}

	/**
	 * Rename entries in other tables
	 * @param IDatabase $db Database handle
	 * @param int $op Operation to perform
	 *  - self::MOVE: Use MovePage to move the page
	 *  - self::INPLACE_MOVE: Directly update the database table to move the page
	 *  - self::UPPERCASE: Rewrite the table to point to the new uppercase title
	 * @param string $table
	 * @param string|int $nsField
	 * @param string $titleField
	 * @param string[] $pkFields Additional fields to match a unique index
	 *  starting with $nsField and $titleField.
	 */
	private function processTable( IDatabase $db, $op, $table, $nsField, $titleField, $pkFields ) {
		if ( $this->tables !== null && !in_array( $table, $this->tables, true ) ) {
			$this->output( "Skipping table `$table`, not in --tables.\n" );
			return;
		}

		$batchSize = $this->getBatchSize();
		$namespaces = $this->getNamespaces();
		$likes = $this->getLikeBatches( $db, $titleField );
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		if ( is_int( $nsField ) ) {
			$namespaces = array_intersect( $namespaces, [ $nsField ] );
		}

		if ( !$namespaces ) {
			$this->output( "Skipping table `$table`, no valid namespaces.\n" );
			return;
		}

		$this->output( "Processing table `$table`...\n" );

		$selectFields = array_merge(
			is_int( $nsField ) ? [] : [ $nsField ],
			[ $titleField ],
			$pkFields
		);
		$contFields = array_merge( [ $titleField ], $pkFields );

		$lastReplicationWait = 0.0;
		$count = 0;
		$errors = 0;
		foreach ( $namespaces as $ns ) {
			foreach ( $likes as $like ) {
				$cont = [];
				do {
					$res = $db->select(
						$table,
						$selectFields,
						[ "$nsField = $ns", $like, $db->buildComparison( '>', $cont ) ],
						__METHOD__,
						[ 'ORDER BY' => array_merge( [ $titleField ], $pkFields ), 'LIMIT' => $batchSize ]
					);
					$cont = [];
					foreach ( $res as $row ) {
						$cont = [];
						foreach ( $contFields as $field ) {
							$cont[ $field ] = $row->$field;
						}

						if ( $op === self::MOVE ) {
							$ns = is_int( $nsField ) ? $nsField : (int)$row->$nsField;
							$ret = $this->doMove( $db, $ns, $row->$titleField );
						} else {
							$ret = $this->doUpdate( $db, $op, $table, $nsField, $titleField, $row );
						}
						if ( $ret === true ) {
							$count++;
						} elseif ( $ret === false ) {
							$errors++;
						}
					}

					if ( $this->run ) {
						// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
						$r = $cont ? json_encode( $row, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : '<end>';
						$this->output( "... $table: $count renames, $errors errors at $r\n" );
						$lbFactory->waitForReplication(
							[ 'timeout' => 30, 'ifWritesSince' => $lastReplicationWait ]
						);
						$lastReplicationWait = microtime( true );
					}
				} while ( $cont );
			}
		}

		$this->output( "Done processing table `$table`.\n" );
	}

	/**
	 * List users needing renaming
	 * @param IReadableDatabase $db Database handle
	 */
	private function processUsers( IReadableDatabase $db ) {
		$userlistFile = $this->getOption( 'userlist' );
		if ( $userlistFile === null ) {
			$this->output( "Not generating user list, --userlist was not specified.\n" );
			return;
		}

		$fh = fopen( $userlistFile, 'ab' );
		if ( !$fh ) {
			$this->error( "Could not open user list file $userlistFile" );
			return;
		}

		$this->output( "Generating user list...\n" );
		$count = 0;
		$batchSize = $this->getBatchSize();
		foreach ( $this->getLikeBatches( $db, 'user_name' ) as $like ) {
			$cont = [];
			while ( true ) {
				$rows = $db->select(
					'user',
					[ 'user_id', 'user_name' ],
					array_merge( [ $like ], $cont ),
					__METHOD__,
					[ 'ORDER BY' => 'user_name', 'LIMIT' => $batchSize ]
				);

				if ( !$rows->numRows() ) {
					break;
				}

				foreach ( $rows as $row ) {
					$char = mb_substr( $row->user_name, 0, 1 );
					if ( !array_key_exists( $char, $this->charmap ) ) {
						$this->error(
							"Query returned $row->user_name, but user name does not " .
							"begin with a character in the charmap."
						);
						continue;
					}
					$newName = $this->charmap[$char] . mb_substr( $row->user_name, 1 );
					fprintf( $fh, "%s\t%s\t%s\n", WikiMap::getCurrentWikiId(), $row->user_id, $newName );
					$count++;
					$cont = [ 'user_name > ' . $db->addQuotes( $row->user_name ) ];
				}
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
				$this->output( "... at $row->user_name, $count names so far\n" );
			}
		}

		if ( !fclose( $fh ) ) {
			$this->error( "fclose on $userlistFile failed" );
		}
		$this->output( "User list output to $userlistFile, $count users need renaming.\n" );
	}
}

$maintClass = UppercaseTitlesForUnicodeTransition::class;
require_once RUN_MAINTENANCE_IF_MAIN;
