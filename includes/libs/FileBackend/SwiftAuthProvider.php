<?php

namespace Wikimedia\FileBackend;

use Psr\Log\LoggerInterface;
use Wikimedia\Http\MultiHttpClient;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;

/**
 * Provides authentication to swift file backend
 *
 * @ingroup FileBackend
 * @internal
 */
class SwiftAuthProvider {
	private const DEFAULT_HTTP_OPTIONS = [ 'httpVersion' => 'v1.1' ];
	public const AUTH_FAILURE_ERROR = 'Could not connect due to prior authentication failure';
	/** @var int TTL in seconds */
	private $authTTL;
	/** @var string Authentication base URL (without version) */
	private $swiftAuthUrl;
	/** @var string Override of storage base URL */
	private $swiftStorageUrl;
	/** Persistent cache for authentication credential */
	private BagOStuff $credentialCache;
	/** @var string Swift user (account:user) to authenticate as */
	private $swiftUser;
	/** @var string Secret key for user */
	private $swiftKey;

	/** @var array|null */
	private $authCreds;
	/** @var int|null UNIX timestamp */
	private $authErrorTimestamp = null;
	private MultiHttpClient $http;
	private BagOStuff $srvCache;
	private LoggerInterface $logger;

	public function __construct( array $config, MultiHttpClient $http, LoggerInterface $logger ) {
		// Required settings
		$this->swiftAuthUrl = $config['swiftAuthUrl'];
		$this->swiftUser = $config['swiftUser'];
		$this->swiftKey = $config['swiftKey'];
		$this->swiftStorageUrl = $config['swiftStorageUrl'] ?? null;
		// Optional settings
		$this->authTTL = $config['swiftAuthTTL'] ?? 15 * 60; // some sensible number

		$this->srvCache = $config['srvCache'] ?? new EmptyBagOStuff();

		// Cache auth token information to avoid RTTs
		if ( !empty( $config['cacheAuthInfo'] ) ) {
			$this->credentialCache = $this->srvCache;
		} else {
			$this->credentialCache = new EmptyBagOStuff();
		}
		$this->http = $http;
		$this->setLogger( $logger );
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
		$this->http->setLogger( $logger );
	}

	/**
	 * Get the cached auth token.
	 *
	 * @return array|null Credential map
	 */
	public function getAuthentication() {
		if ( $this->authErrorTimestamp !== null ) {
			$interval = time() - $this->authErrorTimestamp;
			if ( $interval < 60 ) {
				$this->logger->debug(
					'rejecting request since auth failure occurred {interval} seconds ago',
					[ 'interval' => $interval ]
				);
				return null;
			} else { // actually retry this time
				$this->authErrorTimestamp = null;
			}
		}
		// Authenticate with proxy and get a session key...
		if ( !$this->authCreds ) {
			$cacheKey = $this->getCredsCacheKey( $this->swiftUser );
			$creds = $this->credentialCache->get( $cacheKey );
			if (
				isset( $creds['auth_token'] ) &&
				isset( $creds['storage_url'] ) &&
				isset( $creds['expiry_time'] ) &&
				$creds['expiry_time'] > time()
			) {
				// Cache hit; reuse the cached credentials cache
				$this->setAuthCreds( $creds );
			} else {
				// Cache miss; re-authenticate to get the credentials
				$this->refreshAuthentication();
			}
		}

		return $this->authCreds;
	}

	/**
	 * Update the auth credentials
	 *
	 * @param array|null $creds
	 */
	private function setAuthCreds( ?array $creds ) {
		$this->logger->debug( 'Using auth token with expiry_time={expiry_time}',
			[
				'expiry_time' => isset( $creds['expiry_time'] )
					? gmdate( 'c', $creds['expiry_time'] ) : 'null'
			]
		);
		$this->authCreds = $creds;
	}

	/**
	 * Fetch the auth token from the server, without caching.
	 *
	 * @return array|null Credential map
	 */
	public function refreshAuthentication() {
		[ $rcode, , $rhdrs, $rbody, ] = $this->http->run( [
			'method' => 'GET',
			'url' => "{$this->swiftAuthUrl}/v1.0",
			'headers' => [
				'x-auth-user' => $this->swiftUser,
				'x-auth-key' => $this->swiftKey
			]
		], self::DEFAULT_HTTP_OPTIONS );

		if ( $rcode >= 200 && $rcode <= 299 ) { // OK
			if ( isset( $rhdrs['x-auth-token-expires'] ) ) {
				$ttl = intval( $rhdrs['x-auth-token-expires'] );
			} else {
				$ttl = $this->authTTL;
			}
			$expiryTime = time() + $ttl;
			$creds = [
				'auth_token' => $rhdrs['x-auth-token'],
				'storage_url' => $this->swiftStorageUrl ?? $rhdrs['x-storage-url'],
				'expiry_time' => $expiryTime,
			];
			$this->credentialCache->set(
				$this->getCredsCacheKey( $this->swiftUser ),
				$creds,
				$expiryTime
			);
		} elseif ( $rcode === 401 ) {
			$msg = "HTTP {code} ({desc}) in '{func}': {err}";
			$msgParams = [
				'code'   => $rcode,
				'err'   => "Authentication failed.",
				'func'   => __METHOD__,
			];
			$this->logger->error( $msg, $msgParams );
			$this->authErrorTimestamp = time();
			$creds = null;
		} else {
			$msg = "HTTP {code} ({desc}) in '{func}': {err}";
			$msgParams = [
				'code'   => $rcode,
				'err'   => "HTTP return code: $rcode",
				'func'   => __METHOD__,
			];
			$this->logger->error( $msg, $msgParams );
			$this->authErrorTimestamp = time();
			$creds = null;
		}
		$this->setAuthCreds( $creds );
		return $creds;
	}

	/**
	 * @return array
	 */
	public function authTokenHeaders( array $creds ) {
		return [ 'x-auth-token' => $creds['auth_token'] ];
	}

	/**
	 * Get the cache key for a container
	 *
	 * @param string $username
	 * @return string
	 */
	private function getCredsCacheKey( $username ) {
		return 'swiftcredentials:' . md5( $username . ':' . $this->swiftAuthUrl );
	}

	/**
	 * Get a synthetic response to return from requestWithAuth() or requestMultiWithAuth()
	 * if the request could not be issued due to failure of a prior authentication request.
	 * This failure should not be logged as an HTTP error since the original failure would
	 * have been logged.
	 *
	 * @return array
	 */
	public function getAuthFailureResponse() {
		return [
			'code' => 0,
			0 => 0,
			'reason' => '',
			1 => '',
			'headers' => [],
			2 => [],
			'body' => '',
			3 => '',
			'error' => self::AUTH_FAILURE_ERROR,
			4 => self::AUTH_FAILURE_ERROR
		];
	}
}
