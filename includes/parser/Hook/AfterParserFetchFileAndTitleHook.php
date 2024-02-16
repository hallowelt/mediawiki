<?php

namespace MediaWiki\Hook;

use ImageGalleryBase;
use MediaWiki\Parser\Parser;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AfterParserFetchFileAndTitle" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AfterParserFetchFileAndTitleHook {
	/**
	 * This hook is called after an image gallery is formed by Parser,
	 * just before adding its HTML to parser output.
	 *
	 * @since 1.35
	 *
	 * @param Parser $parser Parser that called the hook
	 * @param ImageGalleryBase $ig Gallery, an object of one of the gallery classes (inheriting from
	 *   ImageGalleryBase)
	 * @param string &$html HTML generated by the gallery
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAfterParserFetchFileAndTitle( $parser, $ig, &$html );
}
