<?php

namespace MediaWiki\RecentChanges;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdatedEvent;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserIdentity;
use RecentChange;

/**
 * The ingress subscriber for the change tracking component. It updates change
 * tracking state according to domain events coming from other components.
 *
 * @internal
 */
class ChangeTrackingEventIngress extends EventSubscriberBase {

	/**
	 * The events handled by this ingress subscriber.
	 * @see registerListeners()
	 */
	public const EVENTS = [
		PageUpdatedEvent::TYPE
	];

	/**
	 * Object spec used for lazy instantiation.
	 * Using this spec with DomainEventSource::registerSubscriber defers
	 * instantiation until one of the listed events is dispatched.
	 * Declaring it as a constant avoids the overhead of using reflection
	 * for auto-wiring.
	 */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [ // see __construct
			'ChangeTagsStore',
			'UserEditTracker'
		],
		'events' => [ // see registerListeners()
			PageUpdatedEvent::TYPE
		],
	];

	private ChangeTagsStore $changeTagsStore;
	private UserEditTracker $userEditTracker;

	public function __construct(
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker
	) {
		// NOTE: keep in sync with self::OBJECT_SPEC
		$this->changeTagsStore = $changeTagsStore;
		$this->userEditTracker = $userEditTracker;
	}

	public static function newForTesting(
		ChangeTagsStore $changeTagsStore,
		UserEditTracker $userEditTracker
	) {
		$ingress = new self( $changeTagsStore, $userEditTracker );
		$ingress->initSubscriber( self::OBJECT_SPEC );
		return $ingress;
	}

	/**
	 * Listener method for PageUpdatedEvent, to be registered with an
	 * DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageUpdatedEventAfterCommit( PageUpdatedEvent $event ) {
		if ( $event->isRevisionChange()
			&& !$event->hasFlag( PageUpdatedEvent::FLAG_SILENT )
		) {
			$this->updateRecentChangesAfterPageUpdated(
				$event->getNewRevision(),
				$event->getOldRevision(),
				$event->hasFlag( PageUpdatedEvent::FLAG_BOT ),
				$event->getPatrolStatus(),
				$event->getTags(),
				$event->getEditResult()
			);
		} elseif ( $event->getTags() ) {
			$this->updateChangeTagsAfterPageUpdated(
				$event->getTags(),
				$event->getNewRevision()->getId(),
			);
		}

		if ( $event->isContentChange()
			&& !$event->hasFlag( PageUpdatedEvent::FLAG_AUTOMATED )
		) {
			$this->updateUserEditTrackerAfterPageUpdated(
				$event->getAuthor()
			);
		}
	}

	private function updateChangeTagsAfterPageUpdated( array $tags, int $revId ) {
		$this->changeTagsStore->addTags( $tags, null, $revId );
	}

	private function updateRecentChangesAfterPageUpdated(
		RevisionRecord $newRevisionRecord,
		?RevisionRecord $oldRevisionRecord,
		bool $forceBot,
		int $patrolStatus,
		array $tags,
		?EditResult $editResult
	) {
		// Update recentchanges
		if ( !$oldRevisionRecord ) {
			RecentChange::notifyNew(
				$newRevisionRecord->getTimestamp(),
				$newRevisionRecord->getPage(),
				$newRevisionRecord->isMinor(),
				$newRevisionRecord->getUser( RevisionRecord::RAW ),
				$newRevisionRecord->getComment( RevisionRecord::RAW )->text,
				$forceBot, // $event->hasFlag( EDIT_FORCE_BOT ),
				'',
				$newRevisionRecord->getSize(),
				$newRevisionRecord->getId(),
				$patrolStatus,
				$tags
			);
		} else {
			// Add RC row to the DB
			RecentChange::notifyEdit(
				$newRevisionRecord->getTimestamp(),
				$newRevisionRecord->getPage(),
				$newRevisionRecord->isMinor(),
				$newRevisionRecord->getUser( RevisionRecord::RAW ),
				$newRevisionRecord->getComment( RevisionRecord::RAW )->text,
				$oldRevisionRecord->getId(),
				$newRevisionRecord->getTimestamp(),
				$forceBot,
				'',
				$oldRevisionRecord->getSize(),
				$newRevisionRecord->getSize(),
				$newRevisionRecord->getId(),
				$patrolStatus,
				$tags,
				$editResult
			);
		}
	}

	private function updateUserEditTrackerAfterPageUpdated( UserIdentity $author ) {
		$this->userEditTracker->incrementUserEditCount( $author );
	}

}
