<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page\Event;

use MediaWiki\DomainEvent\DomainEvent;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Base class for domain events representing changes to pages.
 * Page events include cover all changes to the page entity aggregate.
 *
 * The base class itself doesn't cary much information and is not
 * particularly useful on its own.
 *
 * @since 1.45
 */
abstract class PageEvent extends DomainEvent {

	public const TYPE = 'Page';

	/**
	 * @var string This is a reconciliation event, triggered in order to give
	 *      listeners an opportunity to catch up on missed events or recreate
	 *      corrupted data. Can be triggered by a user action such as a null
	 *      edit, or by a maintenance script.
	 */
	public const FLAG_RECONCILIATION_REQUEST = 'reconciliation_request';

	private string $cause;
	private int $pageId;

	private UserIdentity $performer;

	/** @var array<string> */
	private array $tags;

	/** @var array<string,bool> */
	private array $flags;

	/**
	 * @param string $cause See the constants in PageUpdateCauses.
	 * @param int $pageId
	 * @param UserIdentity $performer The user performing the update.
	 * @param array<string> $tags Applicable tags, see ChangeTags.
	 * @param array<string,bool> $flags See the self::FLAG_XXX constants.
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct(
		string $cause,
		int $pageId,
		UserIdentity $performer,
		array $tags = [],
		array $flags = [],
		$timestamp = false
	) {
		parent::__construct(
			$timestamp,
			$flags[self::FLAG_RECONCILIATION_REQUEST] ?? false
		);

		$this->declareEventType( self::TYPE );

		Assert::parameterElementType( 'string', $tags, '$tags' );
		Assert::parameterKeyType( 'integer', $tags, '$tags' );

		Assert::parameterElementType( 'boolean', $flags, '$flags' );
		Assert::parameterKeyType( 'string', $flags, '$flags' );

		$this->cause = $cause;
		$this->pageId = $pageId;
		$this->performer = $performer;
		$this->tags = $tags;
		$this->flags = $flags;
	}

	/**
	 * Returns the ID of the page affected by the change.
	 * Note that the ID may no longer be valid after the change (e.g. if the
	 * page was deleted).
	 */
	public function getPageId(): int {
		return $this->pageId;
	}

	/**
	 * Returns the user that performed the update.
	 * For an edit, this will be the same as the user returned by getAuthor().
	 * However, it may be a different user for update events caused e.g. by
	 * undeletion or imports.
	 */
	public function getPerformer(): UserIdentity {
		return $this->performer;
	}

	/**
	 * Checks flags describing the page update.
	 * Use with FLAG_XXX constants declared by subclasses.
	 */
	protected function hasFlag( string $name ): bool {
		return $this->flags[$name] ?? false;
	}

	/**
	 * Indicates the cause of the update.
	 * See the constants in PageUpdateCauses.
	 * @return string
	 */
	public function getCause(): string {
		return $this->cause;
	}

	/**
	 * Checks whether the update had the given cause.
	 *
	 * @see PageUpdateCauses constants
	 */
	public function hasCause( string $cause ): bool {
		return $this->cause === $cause;
	}

	/**
	 * Returns any tags applied to the edit.
	 * @see ChangeTags
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * Checks for a tag associated the page update.
	 *
	 * @see ChangeTags
	 */
	public function hasTag( string $name ): bool {
		return in_array( $name, $this->tags );
	}

}
