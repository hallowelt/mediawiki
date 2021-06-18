<?php
/**
 * User edit count incrementing.
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
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Handles increment the edit count for a given set of users
 *
 * TODO we no longer need to store the instances of the relevant `UserIdentity`s, now that
 * User::$mEditCount was removed the only caching lives in UserEditTracker, we should
 * stop storing those objects.
 */
class UserEditCountUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var array[] Map of (user ID => ('increment': int, 'instances': UserIdentity[])) */
	private $infoByUser;

	/**
	 * @param UserIdentity $user
	 * @param int $increment
	 */
	public function __construct( UserIdentity $user, $increment ) {
		if ( !$user->getId() ) {
			throw new RuntimeException( "Got user ID of zero" );
		}
		$this->infoByUser = [
			$user->getId() => [ 'increment' => $increment, 'instances' => [ $user ] ]
		];
	}

	public function merge( MergeableUpdate $update ) {
		/** @var UserEditCountUpdate $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var UserEditCountUpdate $update';

		foreach ( $update->infoByUser as $userId => $info ) {
			if ( !isset( $this->infoByUser[$userId] ) ) {
				$this->infoByUser[$userId] = [ 'increment' => 0, 'instances' => [] ];
			}
			// Merge the increment amount
			$this->infoByUser[$userId]['increment'] += $info['increment'];
			// Merge the list of User instances to update in doUpdate()
			foreach ( $info['instances'] as $user ) {
				if ( !in_array( $user, $this->infoByUser[$userId]['instances'], true ) ) {
					$this->infoByUser[$userId]['instances'][] = $user;
				}
			}
		}
	}

	/**
	 * Commits the provided user edit count increments to the database
	 */
	public function doUpdate() {
		$mwServices = MediaWikiServices::getInstance();
		$lb = $mwServices->getDBLoadBalancer();
		$dbw = $lb->getConnectionRef( DB_PRIMARY );
		$editTracker = $mwServices->getUserEditTracker();
		$fname = __METHOD__;

		( new AutoCommitUpdate( $dbw, __METHOD__, function () use ( $lb, $dbw, $fname, $editTracker ) {
			foreach ( $this->infoByUser as $userId => $info ) {
				$dbw->update(
					'user',
					[ 'user_editcount=user_editcount+' . (int)$info['increment'] ],
					[ 'user_id' => $userId, 'user_editcount IS NOT NULL' ],
					$fname
				);
				/** @var UserIdentity[] $affectedInstances */
				$affectedInstances = $info['instances'];
				// Lazy initialization check...
				if ( $dbw->affectedRows() == 0 ) {
					// The user_editcount is probably NULL (e.g. not initialized).
					// Since this update runs after the new revisions were committed,
					// wait for the replica DB to catch up so they will be counted.
					$dbr = $lb->getConnectionRef( DB_REPLICA );
					// If $dbr is actually the primary DB, then clearing the snapshot
					// is harmless and waitForMasterPos() will just no-op.
					$dbr->flushSnapshot( $fname );
					$lb->waitForMasterPos( $dbr );
					$editTracker->initializeUserEditCount( $affectedInstances[0] );
				}

				// Clear the edit count in the UserEditTracker cache.
				$editTracker->clearUserEditCache( $affectedInstances[0] );
			}
		} ) )->doUpdate();
	}
}
