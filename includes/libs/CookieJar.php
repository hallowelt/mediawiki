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
 * @ingroup HTTP
 */

/**
 * Cookie jar to use with MWHttpRequest. Does not handle cookie unsetting.
 */
class CookieJar {

	/** @var Cookie[] */
	private array $cookie = [];

	/**
	 * Set a cookie in the cookie jar. Make sure only one cookie per-name exists.
	 * @see Cookie::set()
	 * @param string $name
	 * @param string $value
	 * @param string[] $attr
	 */
	public function setCookie( string $name, string $value, array $attr ): void {
		/* cookies: case insensitive, so this should work.
		 * We'll still send the cookies back in the same case we got them, though.
		 */
		$index = strtoupper( $name );

		if ( isset( $this->cookie[$index] ) ) {
			$this->cookie[$index]->set( $value, $attr );
		} else {
			$this->cookie[$index] = new Cookie( $name, $value, $attr );
		}
	}

	/**
	 * @see Cookie::serializeToHttpRequest
	 */
	public function serializeToHttpRequest( string $path, string $domain ): string {
		$cookies = [];

		foreach ( $this->cookie as $c ) {
			$serialized = $c->serializeToHttpRequest( $path, $domain );
			if ( $serialized ) {
				$cookies[] = $serialized;
			}
		}

		return implode( '; ', $cookies );
	}

	/**
	 * Parse the content of an Set-Cookie HTTP Response header.
	 *
	 * @param string $cookie
	 * @param string $domain Cookie's domain
	 */
	public function parseCookieResponseHeader( string $cookie, string $domain ): void {
		$bit = array_map( 'trim', explode( ';', $cookie ) );

		$parts = explode( '=', array_shift( $bit ), 2 );
		$name = $parts[0];
		$value = $parts[1] ?? '';

		$attr = [];
		foreach ( $bit as $piece ) {
			$parts = explode( '=', $piece, 2 );
			$attr[ strtolower( $parts[0] ) ] = $parts[1] ?? true;
		}

		if ( !isset( $attr['domain'] ) ) {
			$attr['domain'] = $domain;
		} elseif ( !Cookie::validateCookieDomain( $attr['domain'], $domain ) ) {
			return;
		}

		$this->setCookie( $name, $value, $attr );
	}

}
