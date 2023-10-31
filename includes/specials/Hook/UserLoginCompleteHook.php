<?php

namespace MediaWiki\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserLoginComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserLoginCompleteHook {
	/**
	 * Use this hook to show custom content after a user has logged in via the web interface.
	 *
	 * For functionality that needs to run after any login (API or web) use UserLoggedIn.
	 *
	 * @since 1.35
	 * @since 1.42 The $direct parameter is always true when the hook is called.
	 *
	 * @param User $user The user object that was created on login
	 * @param string &$inject_html Any HTML to inject after the "logged in" message.
	 * @param bool $direct (bool) The hook is called directly after a successful login. This will
	 *   only happen once per login.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserLoginComplete( $user, &$inject_html, $direct );
}
