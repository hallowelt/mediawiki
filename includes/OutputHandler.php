<?php
/**
 * Functions to be used with PHP's output buffer.
 *
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

namespace MediaWiki;

/**
 * @since 1.31
 */
class OutputHandler {
	/**
	 * Standard output handler for use with ob_start.
	 *
	 * Output buffers using this method should only be started from MW_SETUP_CALLBACK,
	 * and only if there are no parent output buffers.
	 *
	 * @param string $s Web response output
	 * @return string
	 */
	public static function handle( $s ) {
		global $wgDisableOutputCompression, $wgMangleFlashPolicy;

		if ( $wgMangleFlashPolicy ) {
			$s = self::mangleFlashPolicy( $s );
		}

		// Sanity check if a compression output buffer is already enabled via php.ini. Such
		// buffers exists at the start of the request and are reflected by ob_get_level().
		$phpHandlesCompression = (
			ini_get( 'output_handler' ) === 'ob_gzhandler' ||
			ini_get( 'zlib.output_handler' ) === 'ob_gzhandler' ||
			!in_array(
				strtolower( ini_get( 'zlib.output_compression' ) ),
				[ '', 'off', '0' ]
			)
		);

		if (
			// Compression is not already handled by an internal PHP buffer
			!$phpHandlesCompression &&
			// Compression is not disabled by the application entry point
			!defined( 'MW_NO_OUTPUT_COMPRESSION' ) &&
			// Compression is not disabled by site configuration
			$wgDisableOutputCompression
		) {
			$s = self::handleGzip( $s );
		}

		if (
			// Response body length does not depend on internal PHP compression buffer
			!$phpHandlesCompression &&
			// Response body length does not depend on mangling by a custom buffer
			!ini_get( 'output_handler' ) &&
			!ini_get( 'zlib.output_handler' )
		) {
			self::emitContentLength( strlen( $s ) );
		}

		return $s;
	}

	/**
	 * Get the "file extension" that some client apps will estimate from
	 * the currently-requested URL.
	 *
	 * This isn't a WebRequest method, because we need it before the class loads.
	 * @todo As of 2018, this actually runs after autoloader in Setup.php, so
	 * WebRequest seems like a good place for this.
	 *
	 * @return string
	 */
	private static function findUriExtension() {
		// @todo FIXME: this sort of dupes some code in WebRequest::getRequestUrl()
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			// Strip the query string...
			$path = explode( '?', $_SERVER['REQUEST_URI'], 2 )[0];
		} elseif ( isset( $_SERVER['SCRIPT_NAME'] ) ) {
			// Probably IIS. QUERY_STRING appears separately.
			$path = $_SERVER['SCRIPT_NAME'];
		} else {
			// Can't get the path from the server? :(
			return '';
		}

		$period = strrpos( $path, '.' );
		if ( $period !== false ) {
			return strtolower( substr( $path, $period ) );
		}
		return '';
	}

	/**
	 * Handler that compresses data with gzip if allowed by the Accept header.
	 *
	 * Unlike ob_gzhandler, it works for HEAD requests too. This assumes that the application
	 * processes them as normal GET request and that the webserver is tasked with stripping out
	 * the response body before sending the response the client.
	 *
	 * @param string $s Web response output
	 * @return string
	 */
	private static function handleGzip( $s ) {
		if ( !function_exists( 'gzencode' ) ) {
			wfDebug( __METHOD__ . "() skipping compression (gzencode unavailable)" );
			return $s;
		}
		if ( headers_sent() ) {
			wfDebug( __METHOD__ . "() skipping compression (headers already sent)" );
			return $s;
		}

		$ext = self::findUriExtension();
		if ( $ext == '.gz' || $ext == '.tgz' ) {
			// Don't do gzip compression if the URL path ends in .gz or .tgz
			// This confuses Safari and triggers a download of the page,
			// even though it's pretty clearly labeled as viewable HTML.
			// Bad Safari! Bad!
			return $s;
		}

		if ( $s === '' ) {
			// Do not gzip empty HTTP responses since that would not only bloat the body
			// length, but it would result in invalid HTTP responses when the HTTP status code
			// is one that must not be accompanied by a body (e.g. "204 No Content").
			return $s;
		}

		if ( wfClientAcceptsGzip() ) {
			wfDebug( __METHOD__ . "() is compressing output" );
			header( 'Content-Encoding: gzip' );
			$s = gzencode( $s, 6 );
		}

		// Set vary header if it hasn't been set already
		$headers = headers_list();
		$foundVary = false;
		foreach ( $headers as $header ) {
			$headerName = strtolower( substr( $header, 0, 5 ) );
			if ( $headerName == 'vary:' ) {
				$foundVary = true;
				break;
			}
		}
		if ( !$foundVary ) {
			header( 'Vary: Accept-Encoding' );
		}
		return $s;
	}

	/**
	 * Mangle flash policy tags which open up the site to XSS attacks.
	 *
	 * @param string $s Web response output
	 * @return string
	 */
	private static function mangleFlashPolicy( $s ) {
		# Avoid weird excessive memory usage in PCRE on big articles
		if ( preg_match( '/\<\s*cross-domain-policy(?=\s|\>)/i', $s ) ) {
			return preg_replace( '/\<(\s*)(cross-domain-policy(?=\s|\>))/i', '<$1NOT-$2', $s );
		} else {
			return $s;
		}
	}

	/**
	 * Set the Content-Length header if possible
	 *
	 * This sets Content-Length for the following cases:
	 *  - When the response body is meaningful (HTTP 200/404).
	 *  - On any HTTP 1.0 request response. This improves cooperation with certain CDNs.
	 *
	 * This assumes that HEAD requests are processed as GET requests by MediaWiki and that
	 * the webserver is tasked with stripping out the body.
	 *
	 * Setting Content-Length can prevent clients from getting stuck waiting on PHP to finish
	 * while deferred updates are running.
	 *
	 * @param int $length
	 */
	private static function emitContentLength( $length ) {
		if ( headers_sent() ) {
			wfDebug( __METHOD__ . "() headers already sent" );
			return;
		}

		if (
			in_array( http_response_code(), [ 200, 404 ], true ) ||
			( $_SERVER['SERVER_PROTOCOL'] ?? null ) === 'HTTP/1.0'
		) {
			header( "Content-Length: $length" );
		}
	}
}
