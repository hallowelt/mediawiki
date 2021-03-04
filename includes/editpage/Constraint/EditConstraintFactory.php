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

namespace MediaWiki\EditPage\Constraint;

use Content;
use IContextSource;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\Spi;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserIdentity;
use ReadOnlyMode;
use Title;
use User;

/**
 * Constraints reflect possible errors that need to be checked
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class EditConstraintFactory {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		// PageSizeConstraint
		'MaxArticleSize',
	];

	/** @var ServiceOptions */
	private $options;

	/** @var Spi */
	private $loggerFactory;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var HookContainer */
	private $hookContainer;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var SpamChecker */
	private $spamRegexChecker;

	/**
	 * Some constraints have dependencies that need to be injected,
	 * this class serves as a factory for all of the different constraints
	 * that need dependencies injected.
	 *
	 * The checks in EditPage use wfDebugLog and logged to different channels, hence the need
	 * for multiple loggers retrieved from the Spi. The channels used are:
	 * - SimpleAntiSpam (in SimpleAntiSpamConstraint)
	 * - SpamRegex (in SpamRegexConstraint)
	 *
	 * TODO can they be combined into the same channel?
	 *
	 * @param ServiceOptions $options
	 * @param Spi $loggerFactory
	 * @param PermissionManager $permissionManager
	 * @param HookContainer $hookContainer
	 * @param ReadOnlyMode $readOnlyMode
	 * @param SpamChecker $spamRegexChecker
	 */
	public function __construct(
		ServiceOptions $options,
		Spi $loggerFactory,
		PermissionManager $permissionManager,
		HookContainer $hookContainer,
		ReadOnlyMode $readOnlyMode,
		SpamChecker $spamRegexChecker
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		// Multiple
		$this->options = $options;
		$this->loggerFactory = $loggerFactory;
		$this->permissionManager = $permissionManager;

		// EditFilterMergedContentHookConstraint
		$this->hookContainer = $hookContainer;

		// ReadOnlyConstraint
		$this->readOnlyMode = $readOnlyMode;

		// SpamRegexConstraint
		$this->spamRegexChecker = $spamRegexChecker;
	}

	/**
	 * @param Authority $performer
	 * @param Title $title
	 * @param string $newContentModel
	 * @return ContentModelChangeConstraint
	 */
	public function newContentModelChangeConstraint(
		Authority $performer,
		Title $title,
		string $newContentModel
	) : ContentModelChangeConstraint {
		return new ContentModelChangeConstraint(
			$performer,
			$title,
			$newContentModel
		);
	}

	/**
	 * @param Authority $performer
	 * @param Title $title
	 * @return CreationPermissionConstraint
	 */
	public function newCreationPermissionConstraint(
		Authority $performer,
		Title $title
	) : CreationPermissionConstraint {
		return new CreationPermissionConstraint(
			$performer,
			$title
		);
	}

	/**
	 * @param Content $content
	 * @param IContextSource $context
	 * @param string $summary
	 * @param bool $minorEdit
	 * @return EditFilterMergedContentHookConstraint
	 */
	public function newEditFilterMergedContentHookConstraint(
		Content $content,
		IContextSource $context,
		string $summary,
		bool $minorEdit
	) : EditFilterMergedContentHookConstraint {
		return new EditFilterMergedContentHookConstraint(
			$this->hookContainer,
			$content,
			$context,
			$summary,
			$minorEdit
		);
	}

	/**
	 * @param Authority $performer
	 * @return EditRightConstraint
	 */
	public function newEditRightConstraint( Authority $performer ) : EditRightConstraint {
		return new EditRightConstraint( $performer );
	}

	/**
	 * @param Content $newContent
	 * @param LinkTarget $title
	 * @param Authority $performer
	 * @return ImageRedirectConstraint
	 */
	public function newImageRedirectConstraint(
		Content $newContent,
		LinkTarget $title,
		Authority $performer
	) : ImageRedirectConstraint {
		return new ImageRedirectConstraint(
			$newContent,
			$title,
			$performer
		);
	}

	/**
	 * @param int $contentSize
	 * @param string $type
	 * @return PageSizeConstraint
	 */
	public function newPageSizeConstraint(
		int $contentSize,
		string $type
	) : PageSizeConstraint {
		return new PageSizeConstraint(
			$this->options->get( 'MaxArticleSize' ),
			$contentSize,
			$type
		);
	}

	/**
	 * @return ReadOnlyConstraint
	 */
	public function newReadOnlyConstraint() : ReadOnlyConstraint {
		return new ReadOnlyConstraint(
			$this->readOnlyMode
		);
	}

	/**
	 * @param string $input
	 * @param UserIdentity $user
	 * @param Title $title
	 * @return SimpleAntiSpamConstraint
	 */
	public function newSimpleAntiSpamConstraint(
		string $input,
		UserIdentity $user,
		Title $title
	) : SimpleAntiSpamConstraint {
		return new SimpleAntiSpamConstraint(
			$this->loggerFactory->getLogger( 'SimpleAntiSpam' ),
			$input,
			$user,
			$title
		);
	}

	/**
	 * @param string $summary
	 * @param string $section
	 * @param string $sectionHeading
	 * @param string $text
	 * @param string $reqIP
	 * @param Title $title
	 * @return SpamRegexConstraint
	 */
	public function newSpamRegexConstraint(
		string $summary,
		string $section,
		string $sectionHeading,
		string $text,
		string $reqIP,
		Title $title
	) : SpamRegexConstraint {
		return new SpamRegexConstraint(
			$this->loggerFactory->getLogger( 'SpamRegex' ),
			$this->spamRegexChecker,
			$summary,
			$section,
			$sectionHeading,
			$text,
			$reqIP,
			$title
		);
	}

	/**
	 * @param LinkTarget $title
	 * @param User $user
	 * @return UserBlockConstraint
	 */
	public function newUserBlockConstraint(
		LinkTarget $title,
		User $user
	) : UserBlockConstraint {
		return new UserBlockConstraint(
			$this->permissionManager,
			$title,
			$user
		);
	}

}
