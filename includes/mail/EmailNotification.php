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
 * @author Brooke Vibber
 * @author <mail@tgries.de>
 * @author Tim Starling
 * @author Luke Welling lwelling@wikimedia.org
 */

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Mail\RecentChangeMailComposer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserArray;
use MediaWiki\User\UserIdentity;

/**
 * Find watchers and create email notifications after a page is changed.
 *
 * After an edit is published to RCFeed, RecentChange::save calls EmailNotification.
 * Here we query the `watchlist` table (via WatchedItemStore) to find who is watching
 * a given page, format the emails in question, and dispatch emails to each of them
 * via the JobQueue.
 *
 * The current implementation sends independent emails to each watching user for
 * the following reason: Each email mentions the page edit time expressed in
 * the person's local time (UTC is shown additionally). To achieve this, we need to
 * find the individual timeoffset of each watching user from the preferences.
 *
 * Visit the documentation pages under
 * https://www.mediawiki.org/wiki/Help:Watching_pages
 *
 * @todo If the volume becomes too great, we could send out bulk mails (bcc:user1,user2...)
 * grouped by users having the same timeoffset in their preferences. This would however
 * need to carefully consider impact of failure rate, re-try behaviour, and idempotence.
 *
 * @todo Use UserOptionsLookup and other services, consider converting this to a service
 *
 * @since 1.11.0
 * @ingroup Mail
 */
class EmailNotification {

	protected string $pageStatus = '';

	/**
	 * Extensions that have hooks for
	 * UpdateUserMailerFormattedPageStatus (to provide additional
	 * pageStatus indicators) need a way to make sure that, when their
	 * hook is called in SendWatchlistemailNotification, they only
	 * handle notifications using their pageStatus indicator.
	 *
	 * @since 1.33
	 * @return string
	 */
	public function getPageStatus() {
		return $this->pageStatus;
	}

	/**
	 * Send emails corresponding to the user $editor editing the page $title.
	 *
	 * May be deferred via the job queue.
	 *
	 * @since 1.11.0
	 * @since 1.35 returns a boolean indicating whether an email job was created.
	 * @since 1.44 This method takes just RecentChange $recentChange, instead of multiple parameters
	 * @param RecentChange $recentChange
	 * @return bool Whether an email & notification job was created or not.
	 */
	public function notifyOnPageChange(
		RecentChange $recentChange
	): bool {
		$mwServices = MediaWikiServices::getInstance();
		$editor = $mwServices->getUserFactory()
			->newFromUserIdentity( $recentChange->getPerformerIdentity() );

		$title = Title::castFromPageReference( $recentChange->getPage() );
		if ( $title === null || $title->getNamespace() < 0 ) {
			return false;
		}

		$timestamp = $recentChange->mAttribs['rc_timestamp'];
		$summary = $recentChange->mAttribs['rc_comment'];
		$minorEdit = $recentChange->mAttribs['rc_minor'];
		$oldid = $recentChange->mAttribs['rc_last_oldid'];
		$pageStatus = $recentChange->mExtra['pageStatus'] ?? 'changed';

		$config = $mwServices->getMainConfig();

		// update wl_notificationtimestamp for watchers
		$watchers = [];
		if ( $config->get( MainConfigNames::EnotifWatchlist ) || $config->get( MainConfigNames::ShowUpdatedMarker ) ) {
			$watchers = $mwServices->getWatchedItemStore()->updateNotificationTimestamp(
				$editor,
				$title,
				$timestamp
			);
		}

		// Don't send email for bots
		if ( $editor->isBot() ) {
			return false;
		}

		$sendNotification = true;
		// $watchers deals with $wgEnotifWatchlist.
		// If nobody is watching the page, and there are no users notified on all changes
		// don't bother creating a job/trying to send emails, unless it's a
		// talk page with an applicable notification.
		if ( $watchers === [] &&
			!count( $config->get( MainConfigNames::UsersNotifiedOnAllChanges ) )
		) {
			$sendNotification = false;
			// Only send notification for non minor edits, unless $wgEnotifMinorEdits
			if ( !$minorEdit ||
				( $config->get( MainConfigNames::EnotifMinorEdits ) &&
					!$editor->isAllowed( 'nominornewtalk' ) )
			) {
				if ( $config->get( MainConfigNames::EnotifUserTalk )
					&& $title->getNamespace() === NS_USER_TALK
					&& $this->canSendUserTalkEmail( $editor, $title, $minorEdit )
				) {
					$sendNotification = true;
				}
			}
		}

		if ( $sendNotification ) {
			$mwServices->getJobQueueGroup()->lazyPush( new EnotifNotifyJob(
				$title,
				[
					'editor' => $editor->getName(),
					'editorID' => $editor->getId(),
					'timestamp' => $timestamp,
					'summary' => $summary,
					'minorEdit' => $minorEdit,
					'oldid' => $oldid,
					'watchers' => $watchers,
					'pageStatus' => $pageStatus,
					// not used yet, passed to support T388663 and T389618 in the future
					'rc_id' => $recentChange->getAttribute( 'rc_id' ),
				]
			) );
		}

		return $sendNotification;
	}

	/**
	 * Immediate version of notifyOnPageChange().
	 *
	 * Send emails corresponding to the user $editor editing the page $title.
	 *
	 * @note Do not call directly. Use notifyOnPageChange so that wl_notificationtimestamp is updated.
	 *
	 * @since 1.11.0
	 * @param Authority $editor
	 * @param Title $title
	 * @param string $timestamp Edit timestamp
	 * @param string $summary Edit summary
	 * @param bool $minorEdit
	 * @param int $oldid Revision ID
	 * @param array $watchers Array of user IDs
	 * @param string $pageStatus
	 */
	public function actuallyNotifyOnPageChange(
		Authority $editor,
		$title,
		$timestamp,
		$summary,
		$minorEdit,
		$oldid,
		$watchers,
		$pageStatus = 'changed'
	) {
		# we use $wgPasswordSender as sender's address
		$mwServices = MediaWikiServices::getInstance();
		$config = $mwServices->getMainConfig();

		# The following code is only run, if several conditions are met:
		# 1. EmailNotification for pages (other than user_talk pages) must be enabled
		# 2. minor edits (changes) are only regarded if the global flag indicates so
		$this->pageStatus = $pageStatus;

		$formattedPageStatus = [ 'deleted', 'created', 'moved', 'restored', 'changed' ];

		$hookRunner = new HookRunner( $mwServices->getHookContainer() );
		$hookRunner->onUpdateUserMailerFormattedPageStatus( $formattedPageStatus );
		if ( !in_array( $this->pageStatus, $formattedPageStatus ) ) {
			throw new UnexpectedValueException( 'Not a valid page status!' );
		}

		$composer = new RecentChangeMailComposer(
			$editor,
			$title,
			$summary,
			$minorEdit,
			$oldid,
			$timestamp,
			$pageStatus
		);

		$userTalkId = false;

		if ( !$minorEdit ||
			( $config->get( MainConfigNames::EnotifMinorEdits ) &&
				!$editor->isAllowed( 'nominornewtalk' ) )
		) {
			if ( $config->get( MainConfigNames::EnotifUserTalk )
				&& $title->getNamespace() === NS_USER_TALK
				&& $this->canSendUserTalkEmail( $editor->getUser(), $title, $minorEdit )
			) {
				$targetUser = User::newFromName( $title->getText() );
				$composer->compose( $targetUser, RecentChangeMailComposer::USER_TALK );
				$userTalkId = $targetUser->getId();
			}

			if ( $config->get( MainConfigNames::EnotifWatchlist ) ) {
				$userOptionsLookup = $mwServices->getUserOptionsLookup();
				// Send updates to watchers other than the current editor
				// and don't send to watchers who are blocked and cannot login
				$userArray = UserArray::newFromIDs( $watchers );
				foreach ( $userArray as $watchingUser ) {
					if ( $userOptionsLookup->getOption( $watchingUser, 'enotifwatchlistpages' )
						&& ( !$minorEdit || $userOptionsLookup->getOption( $watchingUser, 'enotifminoredits' ) )
						&& $watchingUser->isEmailConfirmed()
						&& $watchingUser->getId() != $userTalkId
						&& !in_array( $watchingUser->getName(),
							$config->get( MainConfigNames::UsersNotifiedOnAllChanges ) )
						// @TODO Partial blocks should not prevent the user from logging in.
						//       see: https://phabricator.wikimedia.org/T208895
						&& !( $config->get( MainConfigNames::BlockDisablesLogin ) &&
							$watchingUser->getBlock() )
						&& $hookRunner->onSendWatchlistEmailNotification( $watchingUser, $title, $this )
					) {
						$composer->compose( $watchingUser, RecentChangeMailComposer::USER_TALK );
					}
				}
			}
		}

		foreach ( $config->get( MainConfigNames::UsersNotifiedOnAllChanges ) as $name ) {
			$admins = [];
			if ( $editor->getUser()->getName() == $name ) {
				// No point notifying the user that actually made the change!
				continue;
			}
			$user = User::newFromName( $name );
			if ( $user instanceof User ) {
				$admins[] = $user;
			}
			MediaWikiServices::getInstance()->getNotificationService()->notify(
				new \MediaWiki\Watchlist\RecentChangeNotification(
					$mwServices->getUserFactory()->newFromAuthority( $editor ),
					$title,
					$summary,
					$minorEdit,
					$oldid,
					$timestamp,
					$pageStatus
				),
				new \MediaWiki\Notification\RecipientSet( $admins )
			);

		}
		$composer->sendMails();
	}

	/**
	 * @param UserIdentity $editor
	 * @param Title $title
	 * @param bool $minorEdit
	 * @return bool
	 */
	private function canSendUserTalkEmail( UserIdentity $editor, $title, $minorEdit ) {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();

		if ( !$config->get( MainConfigNames::EnotifUserTalk ) || $title->getNamespace() !== NS_USER_TALK ) {
			return false;
		}

		$userOptionsLookup = $services->getUserOptionsLookup();
		$targetUser = User::newFromName( $title->getText() );

		if ( !$targetUser || $targetUser->isAnon() ) {
			wfDebug( __METHOD__ . ": user talk page edited, but user does not exist" );
		} elseif ( $targetUser->getId() == $editor->getId() ) {
			wfDebug( __METHOD__ . ": user edited their own talk page, no notification sent" );
		} elseif ( $targetUser->isTemp() ) {
			wfDebug( __METHOD__ . ": talk page owner is a temporary user so doesn't have email" );
		} elseif ( $config->get( MainConfigNames::BlockDisablesLogin ) &&
			$targetUser->getBlock()
		) {
			// @TODO Partial blocks should not prevent the user from logging in.
			//       see: https://phabricator.wikimedia.org/T208895
			wfDebug( __METHOD__ . ": talk page owner is blocked and cannot login, no notification sent" );
		} elseif ( $userOptionsLookup->getOption( $targetUser, 'enotifusertalkpages' )
			&& ( !$minorEdit || $userOptionsLookup->getOption( $targetUser, 'enotifminoredits' ) )
		) {
			if ( !$targetUser->isEmailConfirmed() ) {
				wfDebug( __METHOD__ . ": talk page owner doesn't have validated email" );
			} elseif ( !( new HookRunner( $services->getHookContainer() ) )
				->onAbortTalkPageEmailNotification( $targetUser, $title )
			) {
				wfDebug( __METHOD__ . ": talk page update notification is aborted for this user" );
			} else {
				wfDebug( __METHOD__ . ": sending talk page update notification" );
				return true;
			}
		} else {
			wfDebug( __METHOD__ . ": talk page owner doesn't want notifications" );
		}
		return false;
	}

}
