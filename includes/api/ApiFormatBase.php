<?php
/**
 *
 *
 * Created on Sep 19, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

/**
 * This is the abstract base class for API formatters.
 *
 * @ingroup API
 */
abstract class ApiFormatBase extends ApiBase {
	private $mIsHtml, $mFormat, $mUnescapeAmps, $mHelp;
	private $mBuffer, $mDisabled = false;
	private $mIsWrappedHtml = false;
	protected $mForceDefaultParams = false;

	/**
	 * If $format ends with 'fm', pretty-print the output in HTML.
	 * @param ApiMain $main
	 * @param string $format Format name
	 */
	public function __construct( ApiMain $main, $format ) {
		parent::__construct( $main, $format );

		$this->mIsHtml = ( substr( $format, -2, 2 ) === 'fm' ); // ends with 'fm'
		if ( $this->mIsHtml ) {
			$this->mFormat = substr( $format, 0, -2 ); // remove ending 'fm'
			$this->mIsWrappedHtml = $this->getMain()->getCheck( 'wrappedhtml' );
		} else {
			$this->mFormat = $format;
		}
		$this->mFormat = strtoupper( $this->mFormat );
	}

	/**
	 * Overriding class returns the MIME type that should be sent to the client.
	 *
	 * When getIsHtml() returns true, the return value here is used for syntax
	 * highlighting but the client sees text/html.
	 *
	 * @return string
	 */
	abstract public function getMimeType();

	/**
	 * Return a filename for this module's output.
	 * @note If $this->getIsWrappedHtml() || $this->getIsHtml(), you'll very
	 *  likely want to fall back to this class's version.
	 * @since 1.27
	 * @return string Generally this should be "api-result.$ext", and must be
	 *  encoded for inclusion in a Content-Disposition header's filename parameter.
	 */
	public function getFilename() {
		if ( $this->getIsWrappedHtml() ) {
			return 'api-result-wrapped.json';
		} elseif ( $this->getIsHtml() ) {
			return 'api-result.html';
		} else {
			$exts = MimeMagic::singleton()->getExtensionsForType( $this->getMimeType() );
			$ext = $exts ? strtok( $exts, ' ' ) : strtolower( $this->mFormat );
			return "api-result.$ext";
		}
	}

	/**
	 * Get the internal format name
	 * @return string
	 */
	public function getFormat() {
		return $this->mFormat;
	}

	/**
	 * Returns true when the HTML pretty-printer should be used.
	 * The default implementation assumes that formats ending with 'fm'
	 * should be formatted in HTML.
	 * @return bool
	 */
	public function getIsHtml() {
		return $this->mIsHtml;
	}

	/**
	 * Returns true when the special wrapped mode is enabled.
	 * @since 1.27
	 * @return bool
	 */
	protected function getIsWrappedHtml() {
		return $this->mIsWrappedHtml;
	}

	/**
	 * Disable the formatter.
	 *
	 * This causes calls to initPrinter() and closePrinter() to be ignored.
	 */
	public function disable() {
		$this->mDisabled = true;
	}

	/**
	 * Whether the printer is disabled
	 * @return bool
	 */
	public function isDisabled() {
		return $this->mDisabled;
	}

	/**
	 * Whether this formatter can handle printing API errors.
	 *
	 * If this returns false, then on API errors the default printer will be
	 * instantiated.
	 * @since 1.23
	 * @return bool
	 */
	public function canPrintErrors() {
		return true;
	}

	/**
	 * Ignore request parameters, force a default.
	 *
	 * Used as a fallback if errors are being thrown.
	 * @since 1.26
	 */
	public function forceDefaultParams() {
		$this->mForceDefaultParams = true;
	}

	/**
	 * Overridden to honor $this->forceDefaultParams(), if applicable
	 * @since 1.26
	 */
	protected function getParameterFromSettings( $paramName, $paramSettings, $parseLimit ) {
		if ( !$this->mForceDefaultParams ) {
			return parent::getParameterFromSettings( $paramName, $paramSettings, $parseLimit );
		}

		if ( !is_array( $paramSettings ) ) {
			return $paramSettings;
		} elseif ( isset( $paramSettings[self::PARAM_DFLT] ) ) {
			return $paramSettings[self::PARAM_DFLT];
		} else {
			return null;
		}
	}

	/**
	 * Initialize the printer function and prepare the output headers.
	 * @param bool $unused Always false since 1.25
	 */
	public function initPrinter( $unused = false ) {
		if ( $this->mDisabled ) {
			return;
		}

		$mime = $this->getIsWrappedHtml()
			? 'text/mediawiki-api-prettyprint-wrapped'
			: ( $this->getIsHtml() ? 'text/html' : $this->getMimeType() );

		// Some printers (ex. Feed) do their own header settings,
		// in which case $mime will be set to null
		if ( $mime === null ) {
			return; // skip any initialization
		}

		$this->getMain()->getRequest()->response()->header( "Content-Type: $mime; charset=utf-8" );

		// Set X-Frame-Options API results (bug 39180)
		$apiFrameOptions = $this->getConfig()->get( 'ApiFrameOptions' );
		if ( $apiFrameOptions ) {
			$this->getMain()->getRequest()->response()->header( "X-Frame-Options: $apiFrameOptions" );
		}

		// Set a Content-Disposition header so something downloading an API
		// response uses a halfway-sensible filename (T128209).
		$filename = $this->getFilename();
		$this->getMain()->getRequest()->response()->header(
			"Content-Disposition: inline; filename=\"{$filename}\""
		);
	}

	/**
	 * Finish printing and output buffered data.
	 */
	public function closePrinter() {
		if ( $this->mDisabled ) {
			return;
		}

		$mime = $this->getMimeType();
		if ( $this->getIsHtml() && $mime !== null ) {
			$format = $this->getFormat();
			$lcformat = strtolower( $format );
			$result = $this->getBuffer();

			$context = new DerivativeContext( $this->getMain() );
			$context->setSkin( SkinFactory::getDefaultInstance()->makeSkin( 'apioutput' ) );
			$context->setTitle( SpecialPage::getTitleFor( 'ApiHelp' ) );
			$out = new OutputPage( $context );
			$context->setOutput( $out );

			$out->addModuleStyles( 'mediawiki.apipretty' );
			$out->setPageTitle( $context->msg( 'api-format-title' ) );

			if ( !$this->getIsWrappedHtml() ) {
				// When the format without suffix 'fm' is defined, there is a non-html version
				if ( $this->getMain()->getModuleManager()->isDefined( $lcformat, 'format' ) ) {
					$msg = $context->msg( 'api-format-prettyprint-header' )->params( $format, $lcformat );
				} else {
					$msg = $context->msg( 'api-format-prettyprint-header-only-html' )->params( $format );
				}

				$header = $msg->parseAsBlock();
				$out->addHTML(
					Html::rawElement( 'div', [ 'class' => 'api-pretty-header' ],
						ApiHelp::fixHelpLinks( $header )
					)
				);
			}

			if ( Hooks::run( 'ApiFormatHighlight', [ $context, $result, $mime, $format ] ) ) {
				$out->addHTML(
					Html::element( 'pre', [ 'class' => 'api-pretty-content' ], $result )
				);
			}

			if ( $this->getIsWrappedHtml() ) {
				// This is a special output mode mainly intended for ApiSandbox use
				$time = microtime( true ) - $this->getConfig()->get( 'RequestTime' );
				$json = FormatJson::encode(
					[
						'html' => $out->getHTML(),
						'modules' => array_values( array_unique( array_merge(
							$out->getModules(),
							$out->getModuleScripts(),
							$out->getModuleStyles()
						) ) ),
						'time' => round( $time * 1000 ),
					],
					false, FormatJson::ALL_OK
				);

				// Bug 66776: wfMangleFlashPolicy() is needed to avoid a nasty bug in
				// Flash, but what it does isn't friendly for the API, so we need to
				// work around it.
				if ( preg_match( '/\<\s*cross-domain-policy\s*\>/i', $json ) ) {
					$json = preg_replace(
						'/\<(\s*cross-domain-policy\s*)\>/i', '\\u003C$1\\u003E', $json
					);
				}

				echo $json;
			} else {
				// API handles its own clickjacking protection.
				// Note, that $wgBreakFrames will still override $wgApiFrameOptions for format mode.
				$out->allowClickjacking();
				$out->output();
			}
		} else {
			// For non-HTML output, clear all errors that might have been
			// displayed if display_errors=On
			ob_clean();

			echo $this->getBuffer();
		}
	}

	/**
	 * Append text to the output buffer.
	 * @param string $text
	 */
	public function printText( $text ) {
		$this->mBuffer .= $text;
	}

	/**
	 * Get the contents of the buffer.
	 * @return string
	 */
	public function getBuffer() {
		return $this->mBuffer;
	}

	public function getAllowedParams() {
		$ret = [];
		if ( $this->getIsHtml() ) {
			$ret['wrappedhtml'] = [
				ApiBase::PARAM_DFLT => false,
				ApiBase::PARAM_HELP_MSG => 'apihelp-format-param-wrappedhtml',

			];
		}
		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=query&meta=siteinfo&siprop=namespaces&format=' . $this->getModuleName()
				=> [ 'apihelp-format-example-generic', $this->getFormat() ]
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Data_formats';
	}

	/************************************************************************//**
	 * @name   Deprecated
	 * @{
	 */

	/**
	 * Specify whether or not sequences like &amp;quot; should be unescaped
	 * to &quot; . This should only be set to true for the help message
	 * when rendered in the default (xmlfm) format. This is a temporary
	 * special-case fix that should be removed once the help has been
	 * reworked to use a fully HTML interface.
	 *
	 * @deprecated since 1.25
	 * @param bool $b Whether or not ampersands should be escaped.
	 */
	public function setUnescapeAmps( $b ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->mUnescapeAmps = $b;
	}

	/**
	 * Whether this formatter can format the help message in a nice way.
	 * By default, this returns the same as getIsHtml().
	 * When action=help is set explicitly, the help will always be shown
	 * @deprecated since 1.25
	 * @return bool
	 */
	public function getWantsHelp() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getIsHtml();
	}

	/**
	 * Sets whether the pretty-printer should format *bold*
	 * @deprecated since 1.25
	 * @param bool $help
	 */
	public function setHelp( $help = true ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->mHelp = $help;
	}

	/**
	 * Pretty-print various elements in HTML format, such as xml tags and
	 * URLs. This method also escapes characters like <
	 * @deprecated since 1.25
	 * @param string $text
	 * @return string
	 */
	protected function formatHTML( $text ) {
		wfDeprecated( __METHOD__, '1.25' );

		// Escape everything first for full coverage
		$text = htmlspecialchars( $text );

		if ( $this->mFormat === 'XML' ) {
			// encode all comments or tags as safe blue strings
			$text = str_replace( '&lt;', '<span style="color:blue;">&lt;', $text );
			$text = str_replace( '&gt;', '&gt;</span>', $text );
		}

		// identify requests to api.php
		$text = preg_replace( '#^(\s*)(api\.php\?[^ <\n\t]+)$#m', '\1<a href="\2">\2</a>', $text );
		if ( $this->mHelp ) {
			// make lines inside * bold
			$text = preg_replace( '#^(\s*)(\*[^<>\n]+\*)(\s*)$#m', '$1<b>$2</b>$3', $text );
		}

		// Armor links (bug 61362)
		$masked = [];
		$text = preg_replace_callback( '#<a .*?</a>#', function ( $matches ) use ( &$masked ) {
			$sha = sha1( $matches[0] );
			$masked[$sha] = $matches[0];
			return "<$sha>";
		}, $text );

		// identify URLs
		$protos = wfUrlProtocolsWithoutProtRel();
		// This regex hacks around bug 13218 (&quot; included in the URL)
		$text = preg_replace(
			"#(((?i)$protos).*?)(&quot;)?([ \\'\"<>\n]|&lt;|&gt;|&quot;)#",
			'<a href="\\1">\\1</a>\\3\\4',
			$text
		);

		// Unarmor links
		$text = preg_replace_callback( '#<([0-9a-f]{40})>#', function ( $matches ) use ( &$masked ) {
			$sha = $matches[1];
			return isset( $masked[$sha] ) ? $masked[$sha] : $matches[0];
		}, $text );

		/**
		 * Temporary fix for bad links in help messages. As a special case,
		 * XML-escaped metachars are de-escaped one level in the help message
		 * for legibility. Should be removed once we have completed a fully-HTML
		 * version of the help message.
		 */
		if ( $this->mUnescapeAmps ) {
			$text = preg_replace( '/&amp;(amp|quot|lt|gt);/', '&\1;', $text );
		}

		return $text;
	}

	/**
	 * @see ApiBase::getDescription
	 * @deprecated since 1.25
	 */
	public function getDescription() {
		return $this->getIsHtml() ? ' (pretty-print in HTML)' : '';
	}

	/**
	 * Set the flag to buffer the result instead of printing it.
	 * @deprecated since 1.25, output is always buffered
	 * @param bool $value
	 */
	public function setBufferResult( $value ) {
	}

	/**
	 * Formerly indicated whether the formatter needed metadata from ApiResult.
	 *
	 * ApiResult previously (indirectly) used this to decide whether to add
	 * metadata or to ignore calls to metadata-setting methods, which
	 * unfortunately made several methods that should have been static have to
	 * be dynamic instead. Now ApiResult always stores metadata and formatters
	 * are required to ignore it or filter it out.
	 *
	 * @deprecated since 1.25
	 * @return bool Always true
	 */
	public function getNeedsRawData() {
		wfDeprecated( __METHOD__, '1.25' );
		return true;
	}

	/**@}*/
}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
