<?php
// Suppress UnusedPluginSuppression because Phan on PHP 7.4 and PHP 8.1 need different suppressions
// @phan-file-suppress UnusedPluginSuppression,UnusedPluginFileSuppression

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Context\RequestContext;
use MediaWiki\OutputTransform\ContentDOMTransformStage;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Title\TitleFactory;
use ParserOptions;
use Psr\Log\LoggerInterface;
use Skin;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Utils\DOMCompat;

/**
 * Add anchors and other heading formatting, and replace the section link placeholders.
 * @internal
 */
class HandleParsoidSectionLinks extends ContentDOMTransformStage {

	private LoggerInterface $logger;
	private TitleFactory $titleFactory;

	public function __construct( LoggerInterface $logger, TitleFactory $titleFactory ) {
		$this->logger = $logger;
		$this->titleFactory = $titleFactory;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		// Only run this stage if it is parsoid content *and* section edit
		// links are enabled.
		return (
			( $options['isParsoidContent'] ?? false ) &&
			( $options['enableSectionEditLinks'] ?? true ) &&
			!$po->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS )
		);
	}

	public function transformDOM(
		Document $dom, ParserOutput $po, ?ParserOptions $popts, array &$options
	): Document {
		$skin = $this->resolveSkin( $options );
		$titleText = $po->getTitleText();
		// Transform:
		//  <section data-mw-section-id=...>
		//   <h2 id=...><span id=... typeof="mw:FallbackId"></span> ... </h2>
		//   ...section contents..
		// To:
		//  <section data-mw-section-id=...>
		//   <div class="mw-heading mw-heading2">
		//    <h2 id=...><span id=... typeof="mw:FallbackId"></span> ... </h2>
		//    <span class="mw-editsection">...section edit link...</span>
		//   </div>
		// That is, we're wrapping a <div> around the <h2> generated by
		// Parsoid, and then adding a <span> with the section edit link
		// inside that <div>
		//
		// If COLLAPSIBLE_SECTIONS is set, then we also wrap a <div>
		// around the section contents.
		$toc = $po->getTOCData();
		$sections = ( $toc !== null ) ? $toc->getSections() : [];
		// use the TOC data to extract the headings:
		foreach ( $sections as $section ) {
			$fromTitle = $section->fromTitle;
			if ( $fromTitle === null ) {
				// T353489: don't wrap bare <h> tags
				continue;
			}
			$h = $dom->getElementById( $section->anchor );
			if ( $h === null ) {
				$this->logger->error(
					__METHOD__ . ': Heading missing for anchor',
					$section->toLegacy()
				);
				continue;
			}
			$div = $dom->createElement( 'div' );
			'@phan-var Element $div'; // assert Element
			$editPage = $this->titleFactory->newFromTextThrow( $fromTitle );
			$html = $skin->doEditSectionLink(
				$editPage, $section->index, $h->textContent,
				$skin->getLanguage()
			);
			DOMCompat::setInnerHTML( $div, $html );

			// Reuse existing wrapper if present.
			$maybeWrapper = $h->parentNode;
			'@phan-var \Wikimedia\Parsoid\DOM\Element $maybeWrapper';
			if (
				DOMCompat::nodeName( $maybeWrapper ) === 'div' &&
				DOMCompat::getClassList( $maybeWrapper )->contains( 'mw-heading' )
			) {
				// Transfer section edit link children to existing wrapper
				// All contents of the div (the section edit link) will be
				// inserted immediately following the <h> tag
				$ref = $h->nextSibling;
				while ( $div->firstChild !== null ) {
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal
					$maybeWrapper->insertBefore( $div->firstChild, $ref );
				}
				$div = $maybeWrapper; // for use below
			} else {
				// Move <hX> to new wrapper: the div contents are currently
				// the section edit link. We first replace the h with the
				// div, then insert the <h> as the first child of the div
				// so the section edit link is immediately following the <h>.
				$div->setAttribute(
					'class', 'mw-heading mw-heading' . $section->hLevel
				);
				$h->parentNode->replaceChild( $div, $h );
				// Work around bug in phan (https://github.com/phan/phan/pull/4837)
				// by asserting that $div->firstChild is non-null here.  Actually,
				// ::insertBefore will work fine if $div->firstChild is null (if
				// "doEditSectionLink" returned nothing, for instance), but
				// phan incorrectly thinks the second argument must be non-null.
				$divFirstChild = $div->firstChild;
				'@phan-var \DOMNode $divFirstChild'; // asserting non-null
				$div->insertBefore( $h, $divFirstChild );
			}
			// Create collapsible section wrapper if requested.
			if ( $po->getOutputFlag( ParserOutputFlags::COLLAPSIBLE_SECTIONS ) ) {
				$contentsDiv = $dom->createElement( 'div' );
				while ( $div->nextSibling !== null ) {
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal
					$contentsDiv->appendChild( $div->nextSibling );
				}
				$div->parentNode->appendChild( $contentsDiv );
			}
		}
		return $dom;
	}

	/**
	 * Extracts the skin from the $options array, with a fallback on request context skin
	 * @param array $options
	 * @return Skin
	 */
	private function resolveSkin( array $options ): Skin {
		$skin = $options[ 'skin' ] ?? null;
		if ( !$skin ) {
			// T348853 passing $skin will be mandatory in the future
			$skin = RequestContext::getMain()->getSkin();
		}
		return $skin;
	}
}
