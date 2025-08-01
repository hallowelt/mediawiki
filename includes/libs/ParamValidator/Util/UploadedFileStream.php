<?php

namespace Wikimedia\ParamValidator\Util;

use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Stringable;
use Throwable;
use Wikimedia\AtEase\AtEase;

/**
 * Implementation of StreamInterface for a file in $_FILES
 *
 * This exists so ParamValidator needn't depend on any specific PSR-7
 * implementation for a class implementing UploadedFileInterface. It shouldn't
 * be used directly by other code.
 *
 * @internal
 * @since 1.34
 */
class UploadedFileStream implements Stringable, StreamInterface {

	/** @var resource|null File handle */
	private $fp;

	/** @var int|false|null File size. False if not set yet. */
	private $size = false;

	/**
	 * Call, throwing on error
	 * @param callable $func Callable to call
	 * @param array $args Arguments
	 * @param mixed $fail Failure return value
	 * @param string $msg Message prefix
	 * @return mixed
	 * @throws RuntimeException if $func returns $fail
	 */
	private static function quietCall( callable $func, array $args, $fail, $msg ) {
		error_clear_last();
		$ret = AtEase::quietCall( $func, ...$args );
		if ( $ret === $fail ) {
			$err = error_get_last();
			throw new RuntimeException( "$msg: " . ( $err['message'] ?? 'Unknown error' ) );
		}
		return $ret;
	}

	/**
	 * @param string $filename
	 */
	public function __construct( $filename ) {
		$this->fp = self::quietCall( 'fopen', [ $filename, 'r' ], false, 'Failed to open file' );
	}

	/**
	 * Check if the stream is open
	 * @throws RuntimeException if closed
	 */
	private function checkOpen() {
		if ( !$this->fp ) {
			throw new RuntimeException( 'Stream is not open' );
		}
	}

	public function __destruct() {
		$this->close();
	}

	public function __toString() {
		try {
			$this->seek( 0 );
			return $this->getContents();
		} catch ( Throwable ) {
			// Not allowed to throw
			return '';
		}
	}

	public function close() {
		if ( $this->fp ) {
			// Spec doesn't care about close errors.
			try {
				// PHP 7 emits warnings, suppress
				AtEase::quietCall( 'fclose', $this->fp );
			} catch ( \TypeError ) {
				// While PHP 8 throws exceptions, ignore
			}
			$this->fp = null;
		}
	}

	/** @inheritDoc */
	public function detach() {
		$ret = $this->fp;
		$this->fp = null;
		return $ret;
	}

	/** @inheritDoc */
	public function getSize() {
		if ( $this->size === false ) {
			$this->size = null;

			if ( $this->fp ) {
				// Spec doesn't care about errors here.
				try {
					$stat = AtEase::quietCall( 'fstat', $this->fp );
				} catch ( \TypeError ) {
				}
				$this->size = $stat['size'] ?? null;
			}
		}

		return $this->size;
	}

	/** @inheritDoc */
	public function tell() {
		$this->checkOpen();
		return self::quietCall( 'ftell', [ $this->fp ], -1, 'Cannot determine stream position' );
	}

	/** @inheritDoc */
	public function eof() {
		// Spec doesn't care about errors here.
		try {
			return !$this->fp || AtEase::quietCall( 'feof', $this->fp );
		} catch ( \TypeError ) {
			return true;
		}
	}

	/** @inheritDoc */
	public function isSeekable() {
		return (bool)$this->fp;
	}

	/** @inheritDoc */
	public function seek( $offset, $whence = SEEK_SET ) {
		$this->checkOpen();
		self::quietCall( 'fseek', [ $this->fp, $offset, $whence ], -1, 'Seek failed' );
	}

	/** @inheritDoc */
	public function rewind() {
		$this->seek( 0 );
	}

	/** @inheritDoc */
	public function isWritable() {
		return false;
	}

	/** @inheritDoc */
	public function write( $string ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		$this->checkOpen();
		throw new RuntimeException( 'Stream is read-only' );
	}

	/** @inheritDoc */
	public function isReadable() {
		return (bool)$this->fp;
	}

	/** @inheritDoc */
	public function read( $length ) {
		$this->checkOpen();
		return self::quietCall( 'fread', [ $this->fp, $length ], false, 'Read failed' );
	}

	/** @inheritDoc */
	public function getContents() {
		$this->checkOpen();
		return self::quietCall( 'stream_get_contents', [ $this->fp ], false, 'Read failed' );
	}

	/** @inheritDoc */
	public function getMetadata( $key = null ) {
		$this->checkOpen();
		$ret = self::quietCall( 'stream_get_meta_data', [ $this->fp ], false, 'Metadata fetch failed' );
		if ( $key !== null ) {
			$ret = $ret[$key] ?? null;
		}
		return $ret;
	}

}
