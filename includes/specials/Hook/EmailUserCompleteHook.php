<?php

namespace MediaWiki\Hook;

use MediaWiki\Mail\MailAddress;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "EmailUserComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface EmailUserCompleteHook {
	/**
	 * This hook is called after sending email from one user to another.
	 *
	 * @since 1.35
	 *
	 * @param MailAddress $to MailAddress object of receiving user
	 * @param MailAddress $from MailAddress object of sending user
	 * @param string $subject subject of the mail
	 * @param string $text text of the mail
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEmailUserComplete( $to, $from, $subject, $text );
}
