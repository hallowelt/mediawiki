<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Message\Message;
use MediaWiki\User\User;

/**
 * Reset the local password, if signalled via `$this->manager->setAuthenticationSessionData()`
 *
 * The authentication data key is 'reset-pass'; the data is an object with the
 * following properties:
 * - msg: Message object to display to the user
 * - hard: Boolean, if true the reset cannot be skipped.
 * - req: Optional PasswordAuthenticationRequest to use to actually reset the
 *   password. Won't be displayed to the user.
 *
 * @ingroup Auth
 * @since 1.27
 */
class ResetPasswordSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {

	/** @inheritDoc */
	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	/** @inheritDoc */
	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	/** @inheritDoc */
	public function continueSecondaryAuthentication( $user, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	/** @inheritDoc */
	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	/** @inheritDoc */
	public function continueSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	/**
	 * Try to reset the password
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	protected function tryReset( User $user, array $reqs ) {
		$data = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		if ( !$data ) {
			return AuthenticationResponse::newAbstain();
		}

		if ( is_array( $data ) ) {
			$data = (object)$data;
		}
		if ( !is_object( $data ) ) {
			throw new \UnexpectedValueException( 'reset-pass is not valid' );
		}

		if ( !isset( $data->msg ) ) {
			throw new \UnexpectedValueException( 'reset-pass msg is missing' );
		} elseif ( !$data->msg instanceof Message ) {
			throw new \UnexpectedValueException( 'reset-pass msg is not valid' );
		} elseif ( !isset( $data->hard ) ) {
			throw new \UnexpectedValueException( 'reset-pass hard is missing' );
		} elseif ( isset( $data->req ) && (
			!$data->req instanceof PasswordAuthenticationRequest ||
			!array_key_exists( 'retype', $data->req->getFieldInfo() )
		) ) {
			throw new \UnexpectedValueException( 'reset-pass req is not valid' );
		}

		if ( !$data->hard ) {
			$req = ButtonAuthenticationRequest::getRequestByName( $reqs, 'skipReset' );
			if ( $req ) {
				$this->manager->removeAuthenticationSessionData( 'reset-pass' );
				return AuthenticationResponse::newPass();
			}
		}

		/** @var PasswordAuthenticationRequest $needReq */
		$needReq = $data->req ?? new PasswordAuthenticationRequest();
		'@phan-var PasswordAuthenticationRequest $needReq';
		if ( !$needReq->action ) {
			$needReq->action = AuthManager::ACTION_CHANGE;
		}
		$needReq->required = $data->hard ? AuthenticationRequest::REQUIRED
			: AuthenticationRequest::OPTIONAL;
		$needReqs = [ $needReq ];
		if ( !$data->hard ) {
			$needReqs[] = new ButtonAuthenticationRequest(
				'skipReset',
				wfMessage( 'authprovider-resetpass-skip-label' ),
				wfMessage( 'authprovider-resetpass-skip-help' )
			);
		}

		/** @var PasswordAuthenticationRequest $req */
		$req = AuthenticationRequest::getRequestByClass( $reqs, get_class( $needReq ) );
		'@phan-var PasswordAuthenticationRequest $req';
		if ( !$req || !array_key_exists( 'retype', $req->getFieldInfo() ) ) {
			return AuthenticationResponse::newUI( $needReqs, $data->msg, 'warning' );
		}

		if ( $req->password !== $req->retype ) {
			return AuthenticationResponse::newUI( $needReqs, new Message( 'badretype' ), 'error' );
		}

		$req->username = $user->getName();
		$status = $this->manager->allowsAuthenticationDataChange( $req );
		if ( !$status->isGood() ) {
			return AuthenticationResponse::newUI( $needReqs, $status->getMessage(), 'error' );
		}
		$scope = LoggerFactory::getContext()->addScoped( [
			'context.passwordResetOnLogin' => $data->hard ? 'forced' : 'suggested',
		] );
		$this->manager->changeAuthenticationData( $req );

		$this->manager->removeAuthenticationSessionData( 'reset-pass' );
		return AuthenticationResponse::newPass();
	}
}
