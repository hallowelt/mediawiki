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
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobStore;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script for finding and marking bad content blobs.
 *
 * @ingroup Maintenance
 */
class FindBadBlobs extends Maintenance {

	private RevisionStore $revisionStore;
	private BlobStore $blobStore;

	public function __construct() {
		parent::__construct();

		$this->setBatchSize( 1000 );
		$this->addDescription( 'Find and mark bad content blobs. Marked blobs will be read as empty. '
			. 'Use --scan-from to find revisions with bad blobs, use --mark to mark them.' );
		$this->addOption( 'scan-from', 'Start scanning revisions at the given date. '
			. 'Format: Anything supported by MediaWiki, e.g. YYYYMMDDHHMMSS or YYYY-MM-DDTHH:MM:SS',
			false, true );
		$this->addOption( 'scan-to', 'End of scan date range. '
			. 'Format: Anything supported by MediaWiki, e.g. YYYYMMDDHHMMSS or YYYY-MM-DDTHH:MM:SS',
			false, true );
		$this->addOption( 'revisions', 'A list of revision IDs to process, separated by comma or '
			. 'colon or whitespace. Revisions belonging to deleted pages will work. '
			. 'If set to "-" IDs are read from stdin, one per line.', false, true );
		$this->addOption( 'limit', 'Maximum number of revisions for --scan-from to scan. '
			. 'Default: 1000', false, true );
		$this->addOption( 'mark', 'Mark the blob as "known bad", to avoid errors when '
			. 'attempting to read it. The value given is the reason for marking the blob as bad, '
			. 'typically a ticket ID. Requires --revisions to also be set.', false, true );
	}

	/**
	 * @return string
	 */
	private function getStartTimestamp() {
		$tsOpt = $this->getOption( 'scan-from' );
		if ( strlen( $tsOpt ) < 14 ) {
			$this->fatalError( 'Bad timestamp: ' . $tsOpt
				. ', please provide time and date down to the second.' );
		}

		$ts = wfTimestamp( TS_MW, $tsOpt );
		if ( !$ts ) {
			$this->fatalError( 'Bad timestamp: ' . $tsOpt );
		}

		return $ts;
	}

	private function getEndTimestamp(): string {
		$tsOpt = $this->getOption( 'scan-to' );
		if ( strlen( $tsOpt ) < 14 ) {
			$this->fatalError( 'Bad timestamp: ' . $tsOpt
				. ', please provide time and date down to the second.' );
		}

		$ts = wfTimestamp( TS_MW, $tsOpt );
		if ( !$ts ) {
			$this->fatalError( 'Bad timestamp: ' . $tsOpt );
		}

		return $ts;
	}

	/**
	 * @return int[]
	 */
	private function getRevisionIds() {
		$opt = $this->getOption( 'revisions' );

		if ( $opt === '-' ) {
			$opt = stream_get_contents( STDIN );

			if ( !$opt ) {
				return [];
			}
		}

		return $this->parseIntList( $opt );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$services = $this->getServiceContainer();
		$this->revisionStore = $services->getRevisionStore();
		$this->blobStore = $services->getBlobStore();

		if ( $this->hasOption( 'revisions' ) ) {
			if ( $this->hasOption( 'scan-from' ) || $this->hasOption( 'scan-to' ) ) {
				$this->fatalError( 'Cannot use --revisions together with --scan-from or --scan-to' );
			}

			$ids = $this->getRevisionIds();

			$count = $this->scanRevisionsById( $ids );
		} elseif ( $this->hasOption( 'scan-from' ) ) {
			if ( $this->hasOption( 'mark' ) ) {
				$this->fatalError( 'Cannot use --mark with --scan-from, '
					. 'use --revisions to specify revisions to mark.' );
			}

			if ( $this->hasOption( 'scan-to' ) && $this->hasOption( 'limit' ) ) {
				$this->fatalError( 'Cannot use --limit with --scan-to' );
			}

			$count = $this->scanRevisionsByTimestamp();
			$this->output( "The range of archive rows scanned is based on the range of revision IDs "
				. "scanned in the revision table.\n" );
		} else {
			if ( $this->hasOption( 'mark' ) ) {
				$this->fatalError( 'The --mark must be used together with --revisions' );
			} else {
				$this->fatalError( 'Must specify one of --revisions or --scan-from' );
			}
		}

		if ( $this->hasOption( 'mark' ) ) {
			$this->output( "Marked $count bad revisions.\n" );
		} else {
			$this->output( "Found $count bad revisions.\n" );

			if ( $count > 0 ) {
				$this->output( "On a unix/linux environment, you can use grep and cut to list of IDs\n" );
				$this->output( "that can then be used with the --revisions option. E.g.\n" );
				$this->output( "  grep '! Found bad blob' | cut -s -f 3\n" );
			}
		}
	}

	/**
	 * @return int
	 */
	private function scanRevisionsByTimestamp() {
		$fromTimestamp = $this->getStartTimestamp();
		if ( $this->getOption( 'scan-to' ) ) {
			$toTimestamp = $this->getEndTimestamp();
		} else {
			$toTimestamp = null;
		}

		$total = $this->getOption( 'limit', 1000 );
		$count = 0;
		$lastRevId = 0;
		$firstRevId = 0;
		$lastTimestamp = $fromTimestamp;
		$revisionRowsScanned = 0;
		$archiveRowsScanned = 0;

		$this->output( "Scanning revisions table, "
			. "$total rows starting at rev_timestamp $fromTimestamp\n" );

		while ( $toTimestamp === null ? $revisionRowsScanned < $total : true ) {
			$batchSize = min( $total - $revisionRowsScanned, $this->getBatchSize() );
			$revisions = $this->loadRevisionsByTimestamp( $lastRevId, $lastTimestamp, $batchSize, $toTimestamp );
			if ( !$revisions ) {
				break;
			}

			foreach ( $revisions as $rev ) {
				// we are sorting by timestamp, so we may encounter revision IDs out of sequence
				$firstRevId = $firstRevId ? min( $firstRevId, $rev->getId() ) : $rev->getId();
				$lastRevId = max( $lastRevId, $rev->getId() );

				$count += $this->checkRevision( $rev );
			}

			$lastTimestamp = $rev->getTimestamp();
			$batchSize = count( $revisions );
			$revisionRowsScanned += $batchSize;
			$this->output(
				"\t- Scanned a batch of $batchSize revisions, "
				. "up to revision $lastRevId ($lastTimestamp)\n"
			);

			$this->waitForReplication();
		}

		// NOTE: the archive table isn't indexed by timestamp, so the best we can do is use the
		// revision ID just before the first revision ID we found above as the starting point
		// of the scan, and scan up to on revision after the last revision ID we found above.
		// If $firstRevId is 0, the loop body above didn't execute,
		// so we should skip the one below as well.
		$fromArchived = $this->getNextRevision( $firstRevId, '<', 'DESC' );
		$maxArchived = $this->getNextRevision( $lastRevId, '>', 'ASC' );
		$maxArchived = $maxArchived ?: PHP_INT_MAX;

		$this->output( "Scanning archive table by ar_rev_id, $fromArchived to $maxArchived\n" );
		while ( $firstRevId > 0 && $fromArchived < $maxArchived ) {
			$batchSize = min( $total - $archiveRowsScanned, $this->getBatchSize() );
			$revisions = $this->loadArchiveByRevisionId( $fromArchived, $maxArchived, $batchSize );
			if ( !$revisions ) {
				break;
			}
			/** @var RevisionRecord $rev */
			foreach ( $revisions as $rev ) {
				$count += $this->checkRevision( $rev );
			}
			$fromArchived = $rev->getId();
			$batchSize = count( $revisions );
			$archiveRowsScanned += $batchSize;
			$this->output(
				"\t- Scanned a batch of $batchSize archived revisions, "
				. "up to revision $fromArchived ($lastTimestamp)\n"
			);

			$this->waitForReplication();
		}

		return $count;
	}

	/**
	 * @param int $afterId
	 * @param string $fromTimestamp
	 * @param int $batchSize
	 * @param ?string $toTimestamp
	 *
	 * @return RevisionStoreRecord[]
	 */
	private function loadRevisionsByTimestamp( int $afterId, string $fromTimestamp, $batchSize, $toTimestamp ) {
		$db = $this->getReplicaDB();
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $db )
			->joinComment()
			->where( $db->buildComparison( '>', [
				'rev_timestamp' => $fromTimestamp,
				'rev_id' => $afterId,
			] ) )
			->useIndex( [ 'revision' => 'rev_timestamp' ] )
			->orderBy( [ 'rev_timestamp', 'rev_id' ] )
			->limit( $batchSize );

		if ( $toTimestamp ) {
			$queryBuilder->where( $db->expr( 'rev_timestamp', '<', $toTimestamp ) );
		}

		$rows = $queryBuilder->caller( __METHOD__ )->fetchResultSet();
		$result = $this->revisionStore->newRevisionsFromBatch( $rows, [ 'slots' => true ] );
		$this->handleStatus( $result );

		$records = array_filter( $result->value );

		'@phan-var RevisionStoreRecord[] $records';
		return $records;
	}

	/**
	 * @param int $afterId
	 * @param int $uptoId
	 * @param int $batchSize
	 *
	 * @return RevisionArchiveRecord[]
	 */
	private function loadArchiveByRevisionId( int $afterId, int $uptoId, $batchSize ) {
		$db = $this->getReplicaDB();
		$rows = $this->revisionStore->newArchiveSelectQueryBuilder( $db )
			->joinComment()
			->where( [ $db->expr( 'ar_rev_id', '>', $afterId ), $db->expr( 'ar_rev_id', '<=', $uptoId ) ] )
			->orderBy( 'ar_rev_id' )
			->limit( $batchSize )
			->caller( __METHOD__ )->fetchResultSet();
		$result = $this->revisionStore->newRevisionsFromBatch(
			$rows,
			[ 'archive' => true, 'slots' => true ]
		);
		$this->handleStatus( $result );

		$records = array_filter( $result->value );

		'@phan-var RevisionArchiveRecord[] $records';
		return $records;
	}

	/**
	 * Returns the revision ID next to $revId, according to $comp and $dir
	 *
	 * @param int $revId
	 * @param string $comp the comparator, either '<' or '>', to go with $dir
	 * @param string $dir the sort direction to go with $comp, either 'ARC' or 'DESC'
	 *
	 * @return int
	 */
	private function getNextRevision( int $revId, string $comp, string $dir ) {
		$db = $this->getReplicaDB();
		$next = $db->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( "rev_id $comp $revId" )
			->orderBy( [ "rev_id" ], $dir )
			->caller( __METHOD__ )
			->fetchField();
		return (int)$next;
	}

	/**
	 * @param array $ids
	 *
	 * @return int
	 */
	private function scanRevisionsById( array $ids ) {
		$count = 0;
		$total = count( $ids );

		$this->output( "Scanning $total ids\n" );

		foreach ( array_chunk( $ids, $this->getBatchSize() ) as $batch ) {
			$revisions = $this->loadRevisionsById( $batch );

			if ( !$revisions ) {
				continue;
			}

			/** @var RevisionRecord $rev */
			foreach ( $revisions as $rev ) {
				$count += $this->checkRevision( $rev );
			}

			$batchSize = count( $revisions );
			$this->output( "\t- Scanned a batch of $batchSize revisions\n" );
		}

		return $count;
	}

	/**
	 * @param int[] $ids
	 *
	 * @return RevisionRecord[]
	 */
	private function loadRevisionsById( array $ids ) {
		$db = $this->getReplicaDB();
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $db );

		$rows = $queryBuilder
			->joinComment()
			->where( [ 'rev_id' => $ids ] )
			->caller( __METHOD__ )->fetchResultSet();

		$result = $this->revisionStore->newRevisionsFromBatch( $rows, [ 'slots' => true ] );

		$this->handleStatus( $result );

		$revisions = array_filter( $result->value );
		'@phan-var RevisionArchiveRecord[] $revisions';

		// if not all revisions were found, check the archive table.
		if ( count( $revisions ) < count( $ids ) ) {
			$rows = $this->revisionStore->newArchiveSelectQueryBuilder( $db )
				->joinComment()
				->where( [ 'ar_rev_id' => array_diff( $ids, array_keys( $revisions ) ) ] )
				->caller( __METHOD__ )->fetchResultSet();

			$archiveResult = $this->revisionStore->newRevisionsFromBatch(
				$rows,
				[ 'slots' => true, 'archive' => true ]
			);

			$this->handleStatus( $archiveResult );

			// don't use array_merge, since it will re-index
			$revisions += array_filter( $archiveResult->value );
		}

		return $revisions;
	}

	/**
	 * @param RevisionRecord $rev
	 *
	 * @return int
	 */
	private function checkRevision( RevisionRecord $rev ) {
		$count = 0;
		foreach ( $rev->getSlots()->getSlots() as $slot ) {
			$count += $this->checkSlot( $rev, $slot );
		}

		if ( $count === 0 && $this->hasOption( 'mark' ) ) {
			$this->output( "\t# No bad blob found on revision {$rev->getId()}, skipped!\n" );
		}

		return $count;
	}

	/**
	 * @param RevisionRecord $rev
	 * @param SlotRecord $slot
	 *
	 * @return int
	 */
	private function checkSlot( RevisionRecord $rev, SlotRecord $slot ) {
		$address = $slot->getAddress();

		try {
			$blob = $this->blobStore->getBlob( $address );
			if ( mb_check_encoding( $blob ) ) {
				// nothing to do
				return 0;
			} else {
				$type = 'invalid-utf-8';
				$error = 'Invalid UTF-8';
			}
		} catch ( Exception $ex ) {
			$error = $ex->getMessage();
			$type = get_class( $ex );
		}

		// NOTE: output the revision ID again at the end in a separate column for easy processing
		// via the "cut" shell command.
		$this->output( "\t! Found bad blob on revision {$rev->getId()} "
			. "from {$rev->getTimestamp()} ({$slot->getRole()} slot): "
			. "content_id={$slot->getContentId()}, address=<{$slot->getAddress()}>, "
			. "error='$error', type='$type'. ID:\t{$rev->getId()}\n" );

		if ( $this->hasOption( 'mark' ) ) {
			$newAddress = $this->markBlob( $slot, $error );
			$this->output( "\tChanged address to <$newAddress>\n" );
		}

		return 1;
	}

	/**
	 * @param SlotRecord $slot
	 * @param string|null $error
	 *
	 * @return false|string
	 */
	private function markBlob( SlotRecord $slot, ?string $error = null ) {
		$args = [];

		if ( $this->hasOption( 'mark' ) ) {
			$args['reason'] = $this->getOption( 'mark' );
		}

		if ( $error ) {
			$args['error'] = $error;
		}

		$address = $slot->getAddress() ?: 'empty';
		$badAddress = 'bad:' . urlencode( $address );

		if ( $args ) {
			$badAddress .= '?' . wfArrayToCgi( $args );
		}

		$badAddress = substr( $badAddress, 0, 255 );

		$dbw = $this->getPrimaryDB();
		$dbw->newUpdateQueryBuilder()
			->update( 'content' )
			->set( [ 'content_address' => $badAddress ] )
			->where( [ 'content_id' => $slot->getContentId() ] )
			->caller( __METHOD__ )->execute();

		return $badAddress;
	}

	private function handleStatus( StatusValue $status ) {
		if ( !$status->isOK() ) {
			$this->fatalError( $status );
		}
		if ( !$status->isGood() ) {
			$this->error( $status );
		}
	}

}

// @codeCoverageIgnoreStart
$maintClass = FindBadBlobs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
