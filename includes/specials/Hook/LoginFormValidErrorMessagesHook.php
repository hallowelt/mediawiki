<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LoginFormValidErrorMessagesHook {
	/**
	 * Called in LoginForm when a function gets valid
	 * error messages. Allows to add additional error messages (except messages already
	 * in LoginForm::$validErrorMessages).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$messages Already added messages (inclusive messages from
	 *   LoginForm::$validErrorMessages)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLoginFormValidErrorMessages( &$messages );
}
