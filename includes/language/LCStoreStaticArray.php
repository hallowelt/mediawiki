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

use Wikimedia\StaticArrayWriter;

/**
 * Localisation cache storage based on PHP files and static arrays.
 *
 * @since 1.26
 * @ingroup Language
 */
class LCStoreStaticArray implements LCStore {
	/** @var string|null Current language code. */
	private $currentLang = null;

	/** @var array Localisation data. */
	private $data = [];

	/** @var string|null File name. */
	private $fname = null;

	/** @var string Directory for cache files. */
	private $directory;

	public function __construct( $conf = [] ) {
		$this->directory = $conf['directory'];
	}

	public function startWrite( $code ) {
		if ( !is_dir( $this->directory ) && !wfMkdirParents( $this->directory, null, __METHOD__ ) ) {
			throw new MWException( "Unable to create the localisation store " .
				"directory \"{$this->directory}\"" );
		}

		$this->currentLang = $code;
		$this->fname = $this->directory . '/' . $code . '.l10n.php';
		$this->data[$code] = [];
		if ( is_file( $this->fname ) ) {
			$this->data[$code] = require $this->fname;
		}
	}

	public function set( $key, $value ) {
		$this->data[$this->currentLang][$key] = self::encode( $value );
	}

	/**
	 * Determine whether this array contains only scalar values.
	 *
	 * @param array $arr
	 * @return bool
	 */
	private static function isValueArray( array $arr ) {
		foreach ( $arr as $value ) {
			if ( is_scalar( $value )
				|| $value === null
				|| ( is_array( $value ) && self::isValueArray( $value ) )
			) {
				continue;
			}
			return false;
		}
		return true;
	}

	/**
	 * Encodes a value into an array format
	 *
	 * @param mixed $value
	 * @return array|mixed
	 * @throws RuntimeException
	 */
	public static function encode( $value ) {
		if ( is_array( $value ) && self::isValueArray( $value ) ) {
			// Type: scalar [v]alue.
			// Optimization: Write large arrays as one value to avoid recursive decoding cost.
			return [ 'v', $value ];
		}
		if ( is_array( $value ) || is_object( $value ) ) {
			// Type: [s]serialized.
			// Optimization: Avoid recursive decoding cost. Write arrays with an objects
			// as one serialised value.
			return [ 's', serialize( $value ) ];
		}
		if ( is_scalar( $value ) || $value === null ) {
			// Optimization: Reduce file size by not wrapping scalar values.
			return $value;
		}

		throw new RuntimeException( 'Cannot encode ' . var_export( $value, true ) );
	}

	/**
	 * Decode something that was encoded with encode
	 *
	 * @param mixed $encoded
	 * @return array|mixed
	 * @throws RuntimeException
	 */
	public static function decode( $encoded ) {
		if ( !is_array( $encoded ) ) {
			// Unwrapped scalar value
			return $encoded;
		}

		list( $type, $data ) = $encoded;

		switch ( $type ) {
			case 'v':
				// Value array (1.35+) or unwrapped scalar value (1.32 and earlier)
				return $data;
			case 's':
				return unserialize( $data );
			case 'a':
				// Support: MediaWiki 1.34 and earlier (older file format)
				return array_map( [ __CLASS__, 'decode' ], $data );
			default:
				throw new RuntimeException(
					'Unable to decode ' . var_export( $encoded, true ) );
		}
	}

	public function finishWrite() {
		$writer = new StaticArrayWriter();
		$out = $writer->create(
			$this->data[$this->currentLang],
			'Generated by LCStoreStaticArray.php -- do not edit!'
		);
		file_put_contents( $this->fname, $out );
		// Release the data to manage the memory in rebuildLocalisationCache
		unset( $this->data[$this->currentLang] );
		$this->currentLang = null;
		$this->fname = null;
	}

	public function get( $code, $key ) {
		if ( !array_key_exists( $code, $this->data ) ) {
			$fname = $this->directory . '/' . $code . '.l10n.php';
			if ( !is_file( $fname ) ) {
				return null;
			}
			$this->data[$code] = require $fname;
		}
		$data = $this->data[$code];
		if ( array_key_exists( $key, $data ) ) {
			return self::decode( $data[$key] );
		}
		return null;
	}
}
