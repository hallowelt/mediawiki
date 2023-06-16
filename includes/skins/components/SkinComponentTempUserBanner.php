<?php

namespace MediaWiki\Skin;

use MediaWiki\Html\Html;
use MessageLocalizer;
use User;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 *
 * NOTE: This class is currently *not registered* via the SkinComponentRegistry
 * and cannot be called via Skin::getComponent.
 * Because of it's unsuitability for rendering via mustache templates
 * (it renders it's own HTML and returns no data),
 * it is appended directly to skin data in Skin::getTemplateData.
 * @unstable
 */
class SkinComponentTempUserBanner implements SkinComponent {
	/** @var string */
	private $loginUrl;
	/** @var string */
	private $createAccountUrl;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var bool */
	private $isTempUser;
	/** @var string */
	private $username;
	/** @var string */
	private $userpageUrl;

	/**
	 * @param string|array $returnto
	 * @param MessageLocalizer $localizer
	 * @param User $user
	 */
	public function __construct( $returnto, $localizer, $user ) {
		$this->loginUrl = SkinComponentUtils::makeSpecialUrl( 'Userlogin', $returnto );
		$this->createAccountUrl = SkinComponentUtils::makeSpecialUrl( 'CreateAccount', $returnto );
		$this->localizer = $localizer;
		$this->isTempUser = $user->isTemp();
		$this->username = $user->getName(); // getUser
		$this->userpageUrl = $user->getUserPage()->getFullURL();
	}

	private function createLoginLink() {
		return Html::rawElement( 'a',
		[
			'href' => $this->loginUrl,
			'id' => 'pt-login',
			'title' => $this->localizer->msg( 'tooltip-pt-login' ),
			'class' => 'cdx-button cdx-button--fake-button cdx-button--fake-button--enabled'
		],
		$this->localizer->msg( 'pt-login' ) );
	}

	private function createAccountLink() {
		return Html::rawElement( 'a',
			[
				'href' => $this->createAccountUrl,
				'id' => 'pt-createaccount',
				'title' => $this->localizer->msg( 'tooltip-pt-createaccount' ),
				'class' => 'cdx-button cdx-button--fake-button cdx-button--fake-button--enabled'
			],
			$this->localizer->msg( 'pt-createaccount' )
		);
	}

	private function renderBannerHTML() {
		return Html::rawElement( 'div', [ 'class' => 'mw-temp-user-banner' ],
			HTML::rawElement( 'p', [],
				$this->localizer->msg( 'temp-user-banner-description' )->text() .
				$this->localizer->msg( 'colon-separator' )->text() .
				HTML::rawElement( 'span', [ 'class' => 'mw-temp-user-banner-username' ], $this->username )
			) .
			$this->createLoginLink() .
			$this->createAccountLink() .
			HTML::rawElement( 'div', [ 'class' => 'mw-temp-user-tooltip' ],
				HTML::rawElement( 'p', [], $this->localizer->msg( 'temp-user-banner-tooltip-title' )->text() ) .
				HTML::rawElement( 'p', [], $this->localizer->msg( 'temp-user-banner-tooltip-description' )->parse() )
			)
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return [
			'html' => ( $this->isTempUser ) ? $this->renderBannerHTML() : ''
		];
	}
}
