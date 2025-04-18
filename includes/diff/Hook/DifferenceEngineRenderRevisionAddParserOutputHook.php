<?php

namespace MediaWiki\Diff\Hook;

use DifferenceEngine;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOutput;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "DifferenceEngineRenderRevisionAddParserOutput" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface DifferenceEngineRenderRevisionAddParserOutputHook {
	/**
	 * Use this hook to change the parser output.
	 *
	 * @since 1.35
	 *
	 * @param DifferenceEngine $differenceEngine
	 * @param OutputPage $out
	 * @param ParserOutput $parserOutput
	 * @param WikiPage $wikiPage
	 * @return bool|void True or no return value to continue, or false to not add parser output via
	 *   OutputPage's addParserOutput method
	 */
	public function onDifferenceEngineRenderRevisionAddParserOutput(
		$differenceEngine, $out, $parserOutput, $wikiPage
	);
}
