<?php

namespace MediaWiki\Auth;

/**
 * Authentication request for the reason given for account creation.
 * Used in logs and for notification.
 * @stable for subclassing
 */
class CreationReasonAuthenticationRequest extends AuthenticationRequest {
	/** @var string Account creation reason (only used when creating for someone else) */
	public $reason;

	public $required = self::OPTIONAL;

	/**
	 * @inheritDoc
	 * @stable for overriding
	 */
	public function getFieldInfo() {
		return [
			'reason' => [
				'type' => 'string',
				'label' => wfMessage( 'createacct-reason' ),
				'help' => wfMessage( 'createacct-reason-help' ),
			],
		];
	}
}
