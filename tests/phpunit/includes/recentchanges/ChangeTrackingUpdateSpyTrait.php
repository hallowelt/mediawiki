<?php

namespace MediaWiki\Tests\recentchanges;

use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserEditTracker;
use StatusValue;

/**
 * Trait for asserting that the change tracking component is getting notified
 * about changes as expected.
 */
trait ChangeTrackingUpdateSpyTrait {

	/**
	 * Register expectations about updates that should get triggered.
	 * The parameters of this method represent known kinds of updates.
	 * If a parameter is added, tests calling this method should be forced
	 * to specify their expectations with respect to that kind of update.
	 * For this reason, this method should not be split, and all parameters
	 * should be required.
	 */
	private function expectChangeTrackingUpdates(
		int $rcEdit,
		int $rcOther,
		int $userEditCount,
		int $talkPageNotifications
	) {
		// Hack for spying on RC insertions
		$rcEditStatus = $this->createMock( StatusValue::class );
		$rcEditStatus->expects( $this->exactly( $rcEdit ) )
			->method( 'setOK' );

		$rcOtherStatus = $this->createMock( StatusValue::class );
		$rcOtherStatus->expects( $this->exactly( $rcOther ) )
			->method( 'setOK' );

		$this->setTemporaryHook(
			'RecentChange_save',
			static function ( RecentChange $rc ) use ( $rcEditStatus, $rcOtherStatus ) {
				// Only count types recorded by ChangeTrackingEventIngress
				$type = (int)$rc->getAttributes()['rc_type'];
				if ( $type === RC_EDIT || $type === RC_NEW ) {
					$rcEditStatus->setOK( true );
				} else {
					$rcOtherStatus->setOK( true );
				}
			}
		);

		// Spy for UserEditTracker
		$userEditTracker = $this->createNoOpMock(
			UserEditTracker::class,
			[ 'incrementUserEditCount', 'setCachedUserEditCount', 'clearUserEditCache' ]
		);

		$userEditTracker->expects( $this->exactly( $userEditCount ) )
			->method( 'incrementUserEditCount' );

		$this->setService( 'UserEditTracker', $userEditTracker );

		// Spy for TalkPageNotificationManager
		$talkPageNotificationManager = $this->createNoOpMock(
			TalkPageNotificationManager::class,
			[ 'setUserHasNewMessages', 'removeUserHasNewMessages', 'clearInstanceCache' ]
		);

		$talkPageNotificationManager
			->expects( $this->exactly( max( +$talkPageNotifications, 0 ) ) )
			->method( 'setUserHasNewMessages' );

		$talkPageNotificationManager
			->expects( $this->exactly( max( -$talkPageNotifications, 0 ) ) )
			->method( 'removeUserHasNewMessages' );

		$this->setService( 'TalkPageNotificationManager', $talkPageNotificationManager );
	}

}
