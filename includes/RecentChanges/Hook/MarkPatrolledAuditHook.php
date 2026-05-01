<?php

namespace MediaWiki\RecentChanges\Hook;

use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "MarkPatrolledAudit" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MarkPatrolledAuditHook {
	/**
	 * This hook is called after an edit is marked patrolled.
	 *
	 * This hook is for auditing of patrolling only. Use the {@link MarkPatrolledHook} hook
	 * to abort the patrol or customise the tags applied to the patrol log entry.
	 *
	 * @since 1.47
	 * @param RecentChange $recentChange The RecentChange object associated with the edit that was patrolled
	 * @param UserIdentity $userIdentity User who marked the edit patrolled
	 * @param int $logId The log ID for the patrolled edit
	 */
	public function onMarkPatrolledAudit( RecentChange $recentChange, UserIdentity $userIdentity, int $logId ): void;
}
