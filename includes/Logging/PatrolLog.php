<?php
/**
 * Specific methods for the patrol log.
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Rob Church <robchur@gmail.com>
 * @author Niklas Laxström
 */

namespace MediaWiki\Logging;

use MediaWiki\MediaWikiServices;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\User\UserIdentity;

/**
 * Class containing static functions for working with
 * logs of patrol events
 *
 * @deprecated since 1.47
 */
class PatrolLog {

	/**
	 * Record a log event for a change being patrolled
	 *
	 * @deprecated Since 1.47. Use {@link PatrolManager::createPatrolLog} instead.
	 *   The new method does not filter out automatic patrol events and requires
	 *   a RecentChange object instead of also accepting an ID
	 *
	 * @param int|RecentChange $rc Change identifier or RecentChange object
	 * @param bool $auto Was this patrol event automatic?
	 * @param UserIdentity $user User performing the action
	 * @param string|string[]|null $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 *
	 * @return bool
	 */
	public static function record( $rc, $auto, UserIdentity $user, $tags = null ) {
		wfDeprecated( __METHOD__, '1.47' );
		// Do not log autopatrol actions: T184485
		if ( $auto ) {
			return false;
		}

		$services = MediaWikiServices::getInstance();
		if ( !$rc instanceof RecentChange ) {
			$services->getRecentChangeLookup()->getRecentChangeById( $rc );
			if ( !$rc ) {
				return false;
			}
		}

		$services->getPatrolManager()->createPatrolLog( $rc, $user, $tags );

		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PatrolLog::class, 'PatrolLog' );
