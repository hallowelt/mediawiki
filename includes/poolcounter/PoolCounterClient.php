<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\PoolCounter;

use MediaWiki\Status\Status;

/**
 * @since 1.16
 */
class PoolCounterClient extends PoolCounter {
	/**
	 * @var ?resource the socket connection to the poolcounterd.  Closing this
	 * releases all locks acquired.
	 */
	private $conn;

	/**
	 * @var string The server host name
	 */
	private $hostName;

	/**
	 * @var PoolCounterConnectionManager
	 */
	private $manager;

	public function setManager( PoolCounterConnectionManager $manager ): void {
		$this->manager = $manager;
	}

	/**
	 * @return Status
	 */
	public function getConn() {
		if ( !$this->conn ) {
			$status = $this->manager->get( $this->key );
			if ( !$status->isOK() ) {
				return $status;
			}
			$this->conn = $status->value['conn'];
			$this->hostName = $status->value['hostName'];

			// Set the read timeout to be 1.5 times the pool timeout.
			// This allows the server to time out gracefully before we give up on it.
			stream_set_timeout( $this->conn, 0, (int)( $this->timeout * 1e6 * 1.5 ) );
		}
		// TODO: Convert from Status to StatusValue
		return Status::newGood( $this->conn );
	}

	/**
	 * @param string|int|float ...$args
	 * @return Status
	 */
	public function sendCommand( ...$args ) {
		$args = str_replace( ' ', '%20', $args );
		$cmd = implode( ' ', $args );
		$status = $this->getConn();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;
		$this->logger->debug( "Sending pool counter command: $cmd" );
		if ( fwrite( $conn, "$cmd\n" ) === false ) {
			return Status::newFatal( 'poolcounter-write-error', $this->hostName );
		}
		$response = fgets( $conn );
		if ( $response === false ) {
			return Status::newFatal( 'poolcounter-read-error', $this->hostName );
		}
		$response = rtrim( $response, "\r\n" );
		$this->logger->debug( "Got pool counter response: $response" );
		$parts = explode( ' ', $response, 2 );
		$responseType = $parts[0];
		switch ( $responseType ) {
			case 'LOCKED':
				$this->onAcquire();
				break;
			case 'RELEASED':
				$this->onRelease();
				break;
			case 'DONE':
			case 'NOT_LOCKED':
			case 'QUEUE_FULL':
			case 'TIMEOUT':
			case 'LOCK_HELD':
				break;
			case 'ERROR':
			default:
				$parts = explode( ' ', $parts[1], 2 );
				$errorMsg = $parts[1] ?? '(no message given)';
				return Status::newFatal( 'poolcounter-remote-error', $errorMsg, $this->hostName );
		}
		return Status::newGood( constant( "PoolCounter::$responseType" ) );
	}

	/**
	 * @param int|null $timeout
	 * @return Status
	 */
	public function acquireForMe( $timeout = null ) {
		$status = $this->precheckAcquire();
		if ( !$status->isGood() ) {
			return $status;
		}
		return $this->sendCommand( 'ACQ4ME', $this->key, $this->workers, $this->maxqueue,
			$timeout ?? $this->timeout );
	}

	/**
	 * @param int|null $timeout
	 * @return Status
	 */
	public function acquireForAnyone( $timeout = null ) {
		$status = $this->precheckAcquire();
		if ( !$status->isGood() ) {
			return $status;
		}
		return $this->sendCommand( 'ACQ4ANY', $this->key, $this->workers, $this->maxqueue,
			$timeout ?? $this->timeout );
	}

	/**
	 * @return Status
	 */
	public function release() {
		$status = $this->sendCommand( 'RELEASE' );

		if ( $this->conn ) {
			$this->manager->close( $this->conn );
			$this->conn = null;
		}

		return $status;
	}
}
