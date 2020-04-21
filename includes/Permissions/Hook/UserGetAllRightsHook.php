<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetAllRightsHook {
	/**
	 * After calculating a list of all available rights.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$rights Array of rights, which may be added to.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetAllRights( &$rights );
}
