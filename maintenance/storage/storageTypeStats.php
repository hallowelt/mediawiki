<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

class StorageTypeStats extends Maintenance {
	public function execute() {
		$dbr = $this->getReplicaDB();

		$endId = $dbr->newSelectQueryBuilder()
			->select( 'MAX(old_id)' )
			->from( 'text' )
			->caller( __METHOD__ )->fetchField();
		if ( !$endId ) {
			$this->fatalError( 'No text rows!' );
		}

		$binSize = intval( 10 ** ( floor( log10( $endId ) ) - 3 ) );
		if ( $binSize < 100 ) {
			$binSize = 100;
		}
		echo "Using bin size of $binSize\n";

		$stats = [];

		$classSql = <<<SQL
			IF(old_flags LIKE '%external%',
				IF(old_text REGEXP '^DB://[[:alnum:]]+/[0-9]+/[0-9a-f]{32}$',
					'CGZ pointer',
					IF(old_text REGEXP '^DB://[[:alnum:]]+/[0-9]+/[0-9]{1,6}$',
						'DHB pointer',
						IF(old_text REGEXP '^DB://[[:alnum:]]+/[0-9]+$',
							'simple pointer',
							'UNKNOWN pointer'
						)
					)
				),
				IF(old_flags LIKE '%object%',
					TRIM('"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(old_text, ':', 3), ':', -1)),
					'[none]'
				)
			)
SQL;

		for ( $rangeStart = 0; $rangeStart < $endId; $rangeStart += $binSize ) {
			if ( $rangeStart / $binSize % 10 == 0 ) {
				echo "$rangeStart\r";
			}
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'old_flags', 'class' => $classSql, 'count' => 'COUNT(*)' ] )
				->from( 'text' )
				->where( $dbr->expr( 'old_id', '>=', intval( $rangeStart ) ) )
				->andWhere( $dbr->expr( 'old_id', '<', intval( $rangeStart + $binSize ) ) )
				->groupBy( [ 'old_flags', 'class' ] )
				->caller( __METHOD__ )->fetchResultSet();

			foreach ( $res as $row ) {
				$flags = $row->old_flags;
				if ( $flags === '' ) {
					$flags = '[none]';
				}
				$class = $row->class;
				$count = $row->count;
				// @phan-suppress-next-line PhanImpossibleConditionInLoop False positive
				if ( !isset( $stats[$flags][$class] ) ) {
					$stats[$flags][$class] = [
						'count' => 0,
						'first' => $rangeStart,
						'last' => 0
					];
				}
				$entry =& $stats[$flags][$class];
				$entry['count'] += $count;
				$entry['last'] = max( $entry['last'], $rangeStart + $binSize );
				unset( $entry );
			}
		}
		echo "\n\n";

		$format = "%-29s %-39s %-19s %-29s\n";
		printf( $format, "Flags", "Class", "Count", "old_id range" );
		echo str_repeat( '-', 120 ) . "\n";
		foreach ( $stats as $flags => $flagStats ) {
			foreach ( $flagStats as $class => $entry ) {
				printf( $format, $flags, $class, $entry['count'],
					sprintf( "%-13d - %-13d", $entry['first'], $entry['last'] ) );
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = StorageTypeStats::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
