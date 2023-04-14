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

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Job for clearing all of the "last viewed" timestamps for a user's watchlist, or setting them all
 * to the same value.
 *
 * Job parameters include:
 *   - userId: affected user ID [required]
 *   - casTime: UNIX timestamp of the event that triggered this job [required]
 *   - timestamp: value to set all of the "last viewed" timestamps to [optional, defaults to null]
 *
 * @since 1.31
 * @ingroup JobQueue
 */
class ClearWatchlistNotificationsJob extends Job implements GenericParameterJob {
	public function __construct( array $params ) {
		parent::__construct( 'clearWatchlistNotifications', $params );

		static $required = [ 'userId', 'casTime' ];
		$missing = implode( ', ', array_diff( $required, array_keys( $this->params ) ) );
		if ( $missing != '' ) {
			throw new InvalidArgumentException( "Missing parameter(s) $missing" );
		}

		$this->removeDuplicates = true;
	}

	public function run() {
		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$rowsPerQuery = $services->getMainConfig()->get( MainConfigNames::UpdateRowsPerQuery );

		$dbw = $lbFactory->getPrimaryDatabase();
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );
		$timestamp = $this->params['timestamp'] ?? null;
		if ( $timestamp === null ) {
			$timestampCond = 'wl_notificationtimestamp IS NOT NULL';
		} else {
			$timestamp = $dbw->timestamp( $timestamp );
			$timestampCond = 'wl_notificationtimestamp != ' . $dbw->addQuotes( $timestamp ) .
				' OR wl_notificationtimestamp IS NULL';
		}
		// New notifications since the reset should not be cleared
		$casTimeCond = $dbw->buildComparison(
			'<',
			[ 'wl_notificationtimestamp' => $dbw->timestamp( $this->params['casTime'] ) ] ) .
			' OR wl_notificationtimestamp IS NULL';

		$firstBatch = true;
		do {
			$idsToUpdate = $dbw->newSelectQueryBuilder()
				->select( 'wl_id' )
				->from( 'watchlist' )
				->where( [ 'wl_user' => $this->params['userId'] ] )
				->andWhere( $timestampCond )
				->andWhere( $casTimeCond )
				->limit( $rowsPerQuery )
				->caller( __METHOD__ )->fetchFieldValues();

			if ( $idsToUpdate ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'watchlist' )
					->set( [ 'wl_notificationtimestamp' => $timestamp ] )
					->where( [ 'wl_id' => $idsToUpdate ] )
					->andWhere( $casTimeCond )
					->caller( __METHOD__ )->execute();
				if ( !$firstBatch ) {
					$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
				}
				$firstBatch = false;
			}
		} while ( $idsToUpdate );
		return true;
	}
}
