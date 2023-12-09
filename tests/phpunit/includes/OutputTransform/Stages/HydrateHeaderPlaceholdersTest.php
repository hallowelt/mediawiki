<?php

namespace Mediawiki\OutputTransform\Stages;

use Mediawiki\OutputTransform\OutputTransformStage;
use Mediawiki\OutputTransform\OutputTransformStageTest;
use ParserOutput;

/**
 * @covers \Mediawiki\OutputTransform\Stages\HydrateHeaderPlaceholders
 */
class HydrateHeaderPlaceholdersTest extends OutputTransformStageTest {

	public function createStage(): OutputTransformStage {
		return new HydrateHeaderPlaceholders();
	}

	public function provideShouldRun(): array {
		return [
			[ new ParserOutput(), null, [] ]
		];
	}

	public function provideShouldNotRun(): array {
		return [];
	}

	public function provideTransform(): array {
		$text = "<h1><mw:slotheader>Header&amp;1</mw:slotheader></h1><h2><mw:slotheader>Header 2</mw:slotheader></h2>";
		$expectedText = "<h1>Header&1</h1><h2>Header 2</h2>";
		return [
			[ new ParserOutput( $text ), null, [], new ParserOutput( $expectedText ) ],
		];
	}
}
