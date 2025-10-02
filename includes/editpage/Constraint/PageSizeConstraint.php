<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use InvalidArgumentException;
use StatusValue;
use Wikimedia\Message\MessageValue;

/**
 * Verify the page isn't larger than the maximum
 *
 * This is used for both checking the size //before// merging the edit, and checking the size
 * //after// applying the edit, and the result codes they return are different.
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class PageSizeConstraint implements IEditConstraint {

	/**
	 * Same constraint is used for two different errors, use these
	 * to specify which one should be used
	 */
	public const BEFORE_MERGE = 'check-before-edit-merge';
	public const AFTER_MERGE = 'check-after-edit-merge';

	private readonly int $maxSize;
	private readonly int $errorCode;

	/**
	 * @param int $maxSize In kilobytes, from $wgMaxArticleSize
	 * @param int $contentSize
	 * @param string $type
	 */
	public function __construct(
		int $maxSize,
		private readonly int $contentSize,
		private readonly string $type,
	) {
		$this->maxSize = $maxSize * 1024; // Convert from kilobytes

		if ( $type === self::BEFORE_MERGE ) {
			$this->errorCode = self::AS_CONTENT_TOO_BIG;
		} elseif ( $type === self::AFTER_MERGE ) {
			$this->errorCode = self::AS_MAX_ARTICLE_SIZE_EXCEEDED;
		} else {
			throw new InvalidArgumentException( "Invalid type: $type" );
		}
	}

	public function checkConstraint(): string {
		return $this->contentSize > $this->maxSize ?
			self::CONSTRAINT_FAILED :
			self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->contentSize > $this->maxSize ) {
			// Either self::AS_CONTENT_TOO_BIG, if it was too big before merging,
			// or self::AS_MAX_ARTICLE_SIZE_EXCEEDED, if it was too big after merging
			$statusValue->setResult( false, $this->errorCode );
			$statusValue->fatal( MessageValue::new( 'longpageerror' )
				->numParams( round( $this->contentSize / 1024, 3 ), $this->maxSize / 1024 )
			);
		}
		return $statusValue;
	}

	/**
	 * Get the type, so that the two different uses of this constraint can be told
	 * apart in debug logs.
	 * @internal
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

}
