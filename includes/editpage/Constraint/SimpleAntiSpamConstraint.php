<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use StatusValue;

/**
 * Verify simple anti spam measure of an extra hidden text field
 *
 * @since 1.36
 * @internal
 */
class SimpleAntiSpamConstraint implements IEditConstraint {

	/**
	 * @param LoggerInterface $logger for logging hits
	 * @param string $input
	 * @param UserIdentity $user for logging hits
	 * @param Title $title for logging hits
	 */
	public function __construct(
		private readonly LoggerInterface $logger,
		private readonly string $input,
		private readonly UserIdentity $user,
		private readonly Title $title,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->input === '' ) {
			return self::CONSTRAINT_PASSED;
		}
		$this->logger->debug(
			'{name} editing "{title}" submitted bogus field "{input}"',
			[
				'name' => $this->user->getName(),
				'title' => $this->title->getPrefixedText(),
				'input' => $this->input
			]
		);
		return self::CONSTRAINT_FAILED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->input !== '' ) {
			$statusValue->fatal( 'spamprotectionmatch', '' );
			$statusValue->value = self::AS_SPAM_ERROR;
		}
		return $statusValue;
	}

}
