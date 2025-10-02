<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Content;

use MediaWiki\Config\Config;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;

/**
 * Content handler for JavaScript pages.
 *
 * @todo Create a ScriptContentHandler base class, do highlighting stuff there?
 *
 * @since 1.21
 * @ingroup Content
 */
class JavaScriptContentHandler extends CodeContentHandler {

	private array $textModelsToParse;
	private ParserFactory $parserFactory;
	private UserOptionsLookup $userOptionsLookup;

	public function __construct(
		string $modelId,
		Config $config,
		ParserFactory $parserFactory,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_JAVASCRIPT ] );
		$this->textModelsToParse = $config->get( MainConfigNames::TextModelsToParse ) ?? [];
		$this->parserFactory = $parserFactory;
		$this->userOptionsLookup = $userOptionsLookup;
	}

	/**
	 * @return class-string<JavaScriptContent>
	 */
	protected function getContentClass() {
		return JavaScriptContent::class;
	}

	/** @inheritDoc */
	public function supportsRedirects() {
		return true;
	}

	/**
	 * Create a redirect that is also valid JavaScript
	 *
	 * @param Title $destination
	 * @param string $text ignored
	 * @return JavaScriptContent
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		// The parameters are passed as a string so the / is not url-encoded by wfArrayToCgi
		$url = $destination->getFullURL( 'action=raw&ctype=text/javascript', false, PROTO_RELATIVE );
		$class = $this->getContentClass();
		// Don't needlessly encode ampersands in URLs (T107289).
		// Avoid FormatJson or Html::encodeJsCall to ensure long-term byte-identical stability,
		// as required for JavaScriptContent::getRedirectTarget validation.
		$redirectContent = '/* #REDIRECT */mw.loader.load('
			. json_encode( $url, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
			. ');';
		return new $class( $redirectContent );
	}

	public function preSaveTransform(
		Content $content,
		PreSaveTransformParams $pstParams
	): Content {
		'@phan-var JavascriptContent $content';

		$parserOptions = $pstParams->getParserOptions();
		// @todo Make pre-save transformation optional for script pages (T34858)
		if ( !$this->userOptionsLookup->getBoolOption( $pstParams->getUser(), 'pst-cssjs' ) ) {
			// Allow bot users to disable the pre-save transform for CSS/JS (T236828).
			$parserOptions = clone $parserOptions;
			$parserOptions->setPreSaveTransform( false );
		}

		$text = $content->getText();
		$pst = $this->parserFactory->getInstance()->preSaveTransform(
			$text,
			$pstParams->getPage(),
			$pstParams->getUser(),
			$parserOptions
		);

		$contentClass = $this->getContentClass();
		return new $contentClass( $pst );
	}

	/**
	 * Fills the provided ParserOutput object with information derived from the content.
	 * Unless $cpo->getGenerateHtml was false, this includes an HTML representation of the content.
	 *
	 * For content models listed in $wgTextModelsToParse, this method will call the MediaWiki
	 * wikitext parser on the text to extract any (wikitext) links, magic words, etc.
	 *
	 * Subclasses may override this to provide custom content processing..
	 *
	 * @stable to override
	 *
	 * @since 1.38
	 * @param Content $content
	 * @param ContentParseParams $cpoParams
	 * @param ParserOutput &$output The output object to fill (reference).
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
		'@phan-var JavaScriptContent $content';
		if ( in_array( $content->getModel(), $this->textModelsToParse ) ) {
			// parse just to get links etc into the database, HTML is replaced below.
			$output = $this->parserFactory->getInstance()
				->parse(
					$content->getText(),
					$cpoParams->getPage(),
					WikiPage::makeParserOptionsFromTitleAndModel(
						$cpoParams->getPage(),
						$content->getModel(),
						'canonical'
					),
					true,
					true,
					$cpoParams->getRevId()
				);
		}

		if ( $cpoParams->getGenerateHtml() ) {
			// Return JavaScript wrapped in a <pre> tag.
			$html = Html::element(
				'pre',
				[ 'class' => [ 'mw-code', 'mw-js' ], 'dir' => 'ltr' ],
				"\n" . $content->getText() . "\n"
			) . "\n";
		} else {
			$html = null;
		}

		$output->clearWrapperDivClass();
		$output->setRawText( $html );
		// Suppress the TOC (T307691)
		$output->setOutputFlag( ParserOutputFlags::NO_TOC );
		$output->setSections( [] );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( JavaScriptContentHandler::class, 'JavaScriptContentHandler' );
