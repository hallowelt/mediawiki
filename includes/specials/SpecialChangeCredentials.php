<?php

namespace MediaWiki\Specials;

use AuthManagerSpecialPage;
use LogicException;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\SessionManager;
use MediaWiki\Title\Title;
use Message;
use Status;

/**
 * Special change to change credentials (such as the password).
 *
 * Also does most of the work for SpecialRemoveCredentials.
 */
class SpecialChangeCredentials extends AuthManagerSpecialPage {
	protected static $allowedActions = [ AuthManager::ACTION_CHANGE ];

	protected static $messagePrefix = 'changecredentials';

	/** Change action needs user data; remove action does not */
	protected static $loadUserData = true;

	/**
	 * @param AuthManager $authManager
	 */
	public function __construct( AuthManager $authManager ) {
		parent::__construct( 'ChangeCredentials', 'editmyprivateinfo' );
		$this->setAuthManager( $authManager );
	}

	protected function getGroupName() {
		return 'login';
	}

	public function isListed() {
		$this->loadAuth( '' );
		return (bool)$this->authRequests;
	}

	public function doesWrites() {
		return true;
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_CHANGE;
	}

	protected function getPreservedParams( $withToken = false ) {
		$request = $this->getRequest();
		$params = parent::getPreservedParams( $withToken );
		$params += [
			'returnto' => $request->getVal( 'returnto' ),
			'returntoquery' => $request->getVal( 'returntoquery' ),
		];
		return $params;
	}

	public function execute( $subPage ) {
		$this->setHeaders();
		$this->outputHeader();

		$this->loadAuth( $subPage );

		if ( !$subPage ) {
			$this->showSubpageList();
			return;
		}

		if ( !$this->authRequests ) {
			// messages used: changecredentials-invalidsubpage, removecredentials-invalidsubpage
			$this->showSubpageList( $this->msg( static::$messagePrefix . '-invalidsubpage', $subPage ) );
			return;
		}

		$out = $this->getOutput();
		$out->addModules( 'mediawiki.special.changecredentials' );
		$out->addBacklinkSubtitle( $this->getPageTitle() );
		$status = $this->trySubmit();

		if ( $status === false || !$status->isOK() ) {
			$this->displayForm( $status );
			return;
		}

		$response = $status->getValue();

		switch ( $response->status ) {
			case AuthenticationResponse::PASS:
				$this->success();
				break;
			case AuthenticationResponse::FAIL:
				$this->displayForm( Status::newFatal( $response->message ) );
				break;
			default:
				throw new LogicException( 'invalid AuthenticationResponse' );
		}
	}

	protected function loadAuth( $subPage, $authAction = null, $reset = false ) {
		parent::loadAuth( $subPage, $authAction );
		if ( $subPage ) {
			$foundReqs = [];
			foreach ( $this->authRequests as $req ) {
				if ( $req->getUniqueId() === $subPage ) {
					$foundReqs[] = $req;
				}
			}
			if ( count( $foundReqs ) > 1 ) {
				throw new LogicException( 'Multiple AuthenticationRequest objects with same ID!' );
			}
			$this->authRequests = $foundReqs;
		}
	}

	/** @inheritDoc */
	public function onAuthChangeFormFields(
		array $requests, array $fieldInfo, array &$formDescriptor, $action
	) {
		parent::onAuthChangeFormFields( $requests, $fieldInfo, $formDescriptor, $action );

		// Add some UI flair for password changes, the most common use case for this page.
		if ( AuthenticationRequest::getRequestByClass( $this->authRequests,
			PasswordAuthenticationRequest::class )
		) {
			$formDescriptor = self::mergeDefaultFormDescriptor( $fieldInfo, $formDescriptor, [
				'password' => [
					'autocomplete' => 'new-password',
					'placeholder-message' => 'createacct-yourpassword-ph',
					'help-message' => 'createacct-useuniquepass',
				],
				'retype' => [
					'autocomplete' => 'new-password',
					'placeholder-message' => 'createacct-yourpasswordagain-ph',
				],
				// T263927 - the Chromium password form guide recommends always having a username field
				'username' => [
					'type' => 'text',
					'baseField' => 'password',
					'autocomplete' => 'username',
					'nodata' => true,
					'readonly' => true,
					'cssclass' => 'mw-htmlform-hidden-field',
					'label-message' => 'userlogin-yourname',
					'placeholder-message' => 'userlogin-yourname-ph',
				],
			] );
		}
	}

	protected function getAuthFormDescriptor( $requests, $action ) {
		if ( !static::$loadUserData ) {
			return [];
		} else {
			$descriptor = parent::getAuthFormDescriptor( $requests, $action );

			$any = false;
			foreach ( $descriptor as &$field ) {
				if ( $field['type'] === 'password' && $field['name'] !== 'retype' ) {
					$any = true;
					if ( isset( $field['cssclass'] ) ) {
						$field['cssclass'] .= ' mw-changecredentials-validate-password';
					} else {
						$field['cssclass'] = 'mw-changecredentials-validate-password';
					}
				}
			}

			if ( $any ) {
				$this->getOutput()->addModules( 'mediawiki.misc-authed-ooui' );
			}

			return $descriptor;
		}
	}

	protected function getAuthForm( array $requests, $action ) {
		$form = parent::getAuthForm( $requests, $action );
		$req = reset( $requests );
		$info = $req->describeCredentials();

		$form->addPreHtml(
			Html::openElement( 'dl' )
			. Html::element( 'dt', [], $this->msg( 'credentialsform-provider' )->text() )
			. Html::element( 'dd', [], $info['provider']->text() )
			. Html::element( 'dt', [], $this->msg( 'credentialsform-account' )->text() )
			. Html::element( 'dd', [], $info['account']->text() )
			. Html::closeElement( 'dl' )
		);

		// messages used: changecredentials-submit removecredentials-submit
		$form->setSubmitTextMsg( static::$messagePrefix . '-submit' );
		$form->showCancel()->setCancelTarget( $this->getReturnUrl() ?: Title::newMainPage() );
		$form->setSubmitID( 'change_credentials_submit' );
		return $form;
	}

	protected function needsSubmitButton( array $requests ) {
		// Change/remove forms show are built from a single AuthenticationRequest and do not allow
		// for redirect flow; they always need a submit button.
		return true;
	}

	public function handleFormSubmit( $data ) {
		// remove requests do not accept user input
		$requests = $this->authRequests;
		if ( static::$loadUserData ) {
			$requests = AuthenticationRequest::loadRequestsFromSubmission( $this->authRequests, $data );
		}

		$response = $this->performAuthenticationStep( $this->authAction, $requests );

		// we can't handle FAIL or similar as failure here since it might require changing the form
		return Status::newGood( $response );
	}

	/**
	 * @param Message|null $error
	 */
	protected function showSubpageList( $error = null ) {
		$out = $this->getOutput();

		if ( $error ) {
			$out->addHTML( $error->parse() );
		}

		$groupedRequests = [];
		foreach ( $this->authRequests as $req ) {
			$info = $req->describeCredentials();
			$groupedRequests[$info['provider']->text()][] = $req;
		}

		$linkRenderer = $this->getLinkRenderer();
		$out->addHTML( Html::openElement( 'dl' ) );
		foreach ( $groupedRequests as $group => $members ) {
			$out->addHTML( Html::element( 'dt', [], $group ) );
			foreach ( $members as $req ) {
				/** @var AuthenticationRequest $req */
				$info = $req->describeCredentials();
				$out->addHTML( Html::rawElement( 'dd', [],
					$linkRenderer->makeLink(
						$this->getPageTitle( $req->getUniqueId() ),
						$info['account']->text()
					)
				) );
			}
		}
		$out->addHTML( Html::closeElement( 'dl' ) );
	}

	protected function success() {
		$session = $this->getRequest()->getSession();
		$user = $this->getUser();
		$out = $this->getOutput();
		$returnUrl = $this->getReturnUrl();

		// change user token and update the session
		SessionManager::singleton()->invalidateSessionsForUser( $user );
		$session->setUser( $user );
		$session->resetId();

		if ( $returnUrl ) {
			$out->redirect( $returnUrl );
		} else {
			// messages used: changecredentials-success removecredentials-success
			$out->addHTML(
				Html::successBox(
					$out->msg( static::$messagePrefix . '-success' )->parse()
				)
			);
			$out->returnToMain();
		}
	}

	/**
	 * @return string|null
	 */
	protected function getReturnUrl() {
		$request = $this->getRequest();
		$returnTo = $request->getText( 'returnto' );
		$returnToQuery = $request->getText( 'returntoquery', '' );

		if ( !$returnTo ) {
			return null;
		}

		$title = Title::newFromText( $returnTo );
		return $title->getFullUrlForRedirect( $returnToQuery );
	}

	protected function getRequestBlacklist() {
		return $this->getConfig()->get( MainConfigNames::ChangeCredentialsBlacklist );
	}
}

/**
 * @deprecated since 1.41
 */
class_alias( SpecialChangeCredentials::class, 'SpecialChangeCredentials' );
