<?php
/**
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

require_once __DIR__ . '/Maintenance.php';

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\RawSQLValue;

/**
 * Upgrade script to populate the rc_source field
 * Maintenance script to populate the rc_source field.
 *
 * @ingroup RecentChanges
 * @ingroup Maintenance
 * @since 1.22
 */
class PopulateRecentChangesSource extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Populates rc_source field of the recentchanges table with the data in rc_type.' );
		$this->setBatchSize( 100 );
	}

	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_PRIMARY );
		$batchSize = $this->getBatchSize();
		if ( !$dbw->fieldExists( 'recentchanges', 'rc_source', __METHOD__ ) ) {
			$this->error( 'rc_source field in recentchanges table does not exist.' );
		}

		$start = $dbw->newSelectQueryBuilder()
			->select( 'MIN(rc_id)' )
			->from( 'recentchanges' )
			->caller( __METHOD__ )->fetchField();
		if ( !$start ) {
			$this->output( "Nothing to do.\n" );

			return true;
		}
		$end = $dbw->newSelectQueryBuilder()
			->select( 'MAX(rc_id)' )
			->from( 'recentchanges' )
			->caller( __METHOD__ )->fetchField();
		$end += $batchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $batchSize - 1;

		$updatedValues = $this->buildUpdateCondition( $dbw );

		while ( $blockEnd <= $end ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'recentchanges' )
				->set( $updatedValues )
				->where( [
					'rc_source' => '',
					$dbw->expr( 'rc_id', '>=', (int)$blockStart ),
					$dbw->expr( 'rc_id', '<=', (int)$blockEnd ),
				] )
				->caller( __METHOD__ )
				->execute();

			$this->output( "." );
			$this->waitForReplication();

			$blockStart += $batchSize;
			$blockEnd += $batchSize;
		}

		$this->output( "\nDone.\n" );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function buildUpdateCondition( IDatabase $dbw ) {
		$rcNew = $dbw->addQuotes( RC_NEW );
		$rcSrcNew = $dbw->addQuotes( RecentChange::SRC_NEW );
		$rcEdit = $dbw->addQuotes( RC_EDIT );
		$rcSrcEdit = $dbw->addQuotes( RecentChange::SRC_EDIT );
		$rcLog = $dbw->addQuotes( RC_LOG );
		$rcSrcLog = $dbw->addQuotes( RecentChange::SRC_LOG );
		$rcExternal = $dbw->addQuotes( RC_EXTERNAL );
		$rcSrcExternal = $dbw->addQuotes( RecentChange::SRC_EXTERNAL );

		return [ 'rc_source' => new RawSQLValue( "CASE
					WHEN rc_type = $rcNew THEN $rcSrcNew
					WHEN rc_type = $rcEdit THEN $rcSrcEdit
					WHEN rc_type = $rcLog THEN $rcSrcLog
					WHEN rc_type = $rcExternal THEN $rcSrcExternal
					ELSE ''
				END" ) ];
	}
}

$maintClass = PopulateRecentChangesSource::class;
require_once RUN_MAINTENANCE_IF_MAIN;
