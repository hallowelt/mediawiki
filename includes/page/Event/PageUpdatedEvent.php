<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Page\Event;

use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing a page updated. A PageUpdatedEvent is triggered
 * when a page's current revision changes, even if the content did not change
 * (for a dummy revision). A reconciliation version of this event may be
 * triggered even when the page's current version did not change (on null edits),
 * to provide an opportunity to listeners to recover from data loss and
 * corruption by re-generating any derived data.
 *
 * PageUpdatedEvent is emitted by DerivedPageDataUpdater, typically triggered by
 * PageUpdater. User activities that trigger a PageUpdated event include:
 * - editing, including page creation and null-edits
 * - moving pages
 * - undeleting pages
 * - importing revisions
 * - Any activity that creates a dummy revision, such as changing the page's
 *   protection level.
 *
 * Extensions that want to subscribe to this event should list
 * "PageUpdated" as a subscribed event type.
 * Subscribers based on EventSubscriberBase should implement the
 * handlePageUpdatedEventAfterCommit() listener method to be informed when
 * a page update has been committed to the database.
 *
 * See the documentation of EventSubscriberBase and DomainEventSource for
 * more options and details.
 *
 * @todo: rename to something more descriptive, like
 * PageContentUpdatedEvent.
 *
 * @unstable until 1.45
 */
class PageUpdatedEvent extends PageEvent implements PageUpdateCauses {

	public const TYPE = 'PageUpdated';

	/**
	 * @var string Do not notify other users (e.g. via RecentChanges or
	 * watchlist).
	 * See EDIT_SILENT.
	 */
	public const FLAG_SILENT = 'silent';

	/**
	 * @var string The update was performed by a bot.
	 * See EDIT_FORCE_BOT.
	 */
	public const FLAG_BOT = 'bot';

	/**
	 * @var string The page update is a side effect and does not represent an
	 * active user contribution.
	 * See EDIT_IMPLICIT.
	 */
	public const FLAG_IMPLICIT = 'implicit';

	/**
	 * All available flags and their default values.
	 */
	public const DEFAULT_FLAGS = [
		self::FLAG_SILENT => false,
		self::FLAG_BOT => false,
		self::FLAG_IMPLICIT => false,
	];

	private RevisionSlotsUpdate $slotsUpdate;
	private RevisionRecord $newRevision;
	private ?RevisionRecord $oldRevision;
	private ?EditResult $editResult;

	private int $patrolStatus;

	/**
	 * @param string $cause See the self::CAUSE_XXX constants.
	 * @param ProperPageIdentity $page The page affected by the update.
	 * @param UserIdentity $performer The user performing the update.
	 * @param RevisionSlotsUpdate $slotsUpdate Page content changed by the update.
	 * @param RevisionRecord $newRevision The revision object resulting from the
	 *        update.
	 * @param RevisionRecord|null $oldRevision The revision that used to be
	 *        current before the updated.
	 * @param EditResult|null $editResult An EditResult representing the effects
	 *        of an edit.
	 * @param array<string> $tags Applicable tags, see ChangeTags.
	 * @param array<string,bool> $flags See the self::FLAG_XXX constants.
	 * @param int $patrolStatus See PageUpdater::setRcPatrolStatus()
	 */
	public function __construct(
		string $cause,
		ProperPageIdentity $page,
		UserIdentity $performer,
		RevisionSlotsUpdate $slotsUpdate,
		RevisionRecord $newRevision,
		?RevisionRecord $oldRevision,
		?EditResult $editResult,
		array $tags = [],
		array $flags = [],
		int $patrolStatus = 0
	) {
		parent::__construct( $cause, $page, $performer, $tags, $flags, $newRevision->getTimestamp() );
		$this->declareEventType( self::TYPE );

		Assert::parameter( $page->exists(), '$page', 'must exist' );
		Assert::parameter(
			$page->isSamePageAs( $newRevision->getPage() ),
			'$newRevision',
			'must belong to $page'
		);

		if ( $oldRevision ) {
			Assert::parameter(
				$page->isSamePageAs( $newRevision->getPage() ),
				'$oldRevision',
				'must belong to $page'
			);
		}

		$this->slotsUpdate = $slotsUpdate;
		$this->newRevision = $newRevision;
		$this->oldRevision = $oldRevision;
		$this->editResult = $editResult;
		$this->patrolStatus = $patrolStatus;
	}

	/**
	 * @deprecated since 1.44, use isCreation() instead.
	 * @note Unreleased but used in GrowthExperiments
	 */
	public function isNew(): bool {
		return $this->isCreation();
	}

	/**
	 * Whether the updated created the page.
	 * A deleted/archived page is not considered to "exist".
	 * When undeleting a page, the page will be restored using its old page ID,
	 * so the "created" page may have an ID that was seen previously.
	 */
	public function isCreation(): bool {
		return $this->oldRevision === null;
	}

	/**
	 * Whether this event represents a change to the current revision ID
	 * associated with the page. In other words, the page's current revision
	 * after the change is different from the page's current revision before
	 * the change.
	 *
	 * This method will return true under most circumstances.
	 * It will however return false for reconciliation requests like null edits.
	 * In that case, isReconciliationRequest() should return true.
	 *
	 * @note Listeners should generally not use this method to check if
	 * event processing can be skipped, since that would mean ignoring
	 * reconciliation requests used to recover from data loss or corruption.
	 * The preferred way to check if processing would be redundant is
	 * isNominalContentChange().
	 *
	 * @see DomainEvent::isReconciliationRequest()
	 * @see DomainEvent::isNominalContentChange()
	 */
	public function changedCurrentRevisionId(): bool {
		return $this->oldRevision === null
			|| $this->oldRevision->getId() !== $this->newRevision->getId();
	}

	/**
	 * Whether the update nominally changed the content of the page.
	 * This is the case if:
	 * - the update actually changed the page's content, see isEffectiveContentChange().
	 * - the event is a reconciliation request, see isReconciliationRequest().
	 *
	 * On other words, this will return true for actual changes and null edits,
	 * but will return false for "dummy revisions".
	 *
	 * @note This is preferred over isEffectiveContentChange() for listeners
	 * aiming to avoid redundant processing when the content didn't change.
	 * The purpose of reconciliation requests is to re-trigger such processing
	 * to recover from data loss and corruption, even when there was no actual
	 * change in content.
	 *
	 * @see isEffectiveContentChange()
	 * @see DomainEvent::isReconciliationRequest()
	 */
	public function isNominalContentChange(): bool {
		return $this->isEffectiveContentChange() || $this->isReconciliationRequest();
	}

	/**
	 * Whether the update effectively changed the content of the page.
	 *
	 * This will return false for "dummy revisions" that represent an entry
	 * in the page history but do not modify the content. It will also be false
	 * for reconciliation events (null edits).
	 *
	 * @note Listeners aiming to skip processing of events that didn't change
	 * the content for optimization should use isNominalContentChange() instead.
	 * That way, they would not skip processing for reconciliation requests,
	 * providing a way to recover from data loss and corruption.
	 *
	 * @see isNominalContentChange()
	 */
	public function isEffectiveContentChange(): bool {
		return $this->oldRevision === null
			|| $this->oldRevision->getSha1() !== $this->newRevision->getSha1();
	}

	/**
	 * Returns the author of the new revision.
	 * Note that this may be different from the user returned by
	 * getPerformer() for update events caused e.g. by
	 * undeletion or imports.
	 */
	public function getAuthor(): UserIdentity {
		return $this->newRevision->getUser( RevisionRecord::RAW );
	}

	/**
	 * Returns which slots were changed, added, or removed by the update.
	 */
	public function getSlotsUpdate(): RevisionSlotsUpdate {
		return $this->slotsUpdate;
	}

	/**
	 * Whether the given slot was modified by the page update.
	 * Slots that were removed do not count as modified.
	 * This is a convenience method for
	 * $this->getSlotsUpdate()->isModifiedSlot( $slotRole ).
	 */
	public function isModifiedSlot( string $slotRole ): bool {
		return $this->getSlotsUpdate()->isModifiedSlot( $slotRole );
	}

	/**
	 * An EditResult representing the effects of the update.
	 * Can be used to determine whether the edit was a revert
	 * and which edits were reverted.
	 *
	 * This may return null for updates that do not result from edits,
	 * such as imports or undeletions.
	 */
	public function getEditResult(): ?EditResult {
		return $this->editResult;
	}

	/**
	 * Returned the revision that used to be current before the update.
	 * Will be null if the edit created the page.
	 * Will be the same as getNewRevision() if the edit was a "null-edit".
	 *
	 * Note that this is not necessarily the new revision's parent revision.
	 * For instance, when undeleting a page, the old revision will be null
	 * because the page didn't exist before, even if the undeleted page has
	 * many revisions and the new current revision indeed has a parent revision.
	 *
	 * The parent revision can be determined by calling
	 * getNewRevision()->getParentId().
	 */
	public function getOldRevision(): ?RevisionRecord {
		return $this->oldRevision;
	}

	/**
	 * The revision that became the current one because of the update.
	 */
	public function getNewRevision(): RevisionRecord {
		return $this->newRevision;
	}

	/**
	 * Returns the page update's initial patrol status.
	 * @see PageUpdater::setRcPatrolStatus()
	 * @see RecentChange::PRC_XXX
	 */
	public function getPatrolStatus(): int {
		return $this->patrolStatus;
	}

	/**
	 * Whether the update should be omitted from update feeds presented to the
	 * user.
	 */
	public function isSilent(): bool {
		return $this->hasFlag( self::FLAG_SILENT );
	}

	/**
	 * Whether the update was performed automatically without the user's
	 * initiative.
	 */
	public function isImplicit(): bool {
		return $this->hasFlag( self::FLAG_IMPLICIT );
	}

	/**
	 * Whether the update is a revert to a previous state of the page.
	 */
	public function isRevert(): bool {
		return $this->editResult && $this->editResult->isRevert();
	}

	/**
	 * Whether the update was performed by a bot.
	 */
	public function isBotUpdate(): bool {
		return $this->hasFlag( self::FLAG_BOT );
	}

}

/** @deprecated temporary alias, remove before 1.44 release */
class_alias( PageUpdatedEvent::class, 'MediaWiki\Storage\PageUpdatedEvent' );
