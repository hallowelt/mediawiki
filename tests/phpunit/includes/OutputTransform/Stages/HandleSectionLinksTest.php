<?php

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\HandleSectionLinks;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use MediaWiki\Tests\OutputTransform\TestUtils;
use ParserOptions;

/** @covers \MediaWiki\OutputTransform\Stages\HandleSectionLinks */
class HandleSectionLinksTest extends OutputTransformStageTestBase {

	public function createStage(): OutputTransformStage {
		return new HandleSectionLinks( $this->getServiceContainer()->getTitleFactory() );
	}

	public function provideShouldRun(): array {
		return [ [ new ParserOutput(), null, [] ] ];
	}

	public function provideShouldNotRun(): array {
		return [];
	}

	private static function newParserOutput(
		?string $rawText = null,
		?ParserOptions $parserOptions = null,
		string ...$flags
	) {
		$po = new ParserOutput();
		if ( $rawText !== null ) {
			$po->setRawText( $rawText );
		}
		if ( $parserOptions !== null ) {
			$po->setFromParserOptions( $parserOptions );
		}
		foreach ( $flags as $f ) {
			$po->setOutputFlag( $f );
		}
		return $po;
	}

	public function provideTransform(): iterable {
		yield "TEST_DOC default: with links" => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [],
			self::newParserOutput( TestUtils::TEST_DOC_WITH_LINKS )
		];
		yield "TEST_DOC default ParserOptions: with links" => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			ParserOptions::newFromAnon(), [],
			self::newParserOutput( TestUtils::TEST_DOC_WITH_LINKS )
		];
		yield 'TEST_DOC disabled via $options: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [ 'enableSectionEditLinks' => false ],
			self::newParserOutput( TestUtils::TEST_DOC_WITHOUT_LINKS )
		];
		$pOptsNoLinks = ParserOptions::newFromAnon();
		$pOptsNoLinks->setSuppressSectionEditLinks();
		yield 'TEST_DOC disabled via ParserOptions: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC, $pOptsNoLinks ),
			$pOptsNoLinks, [],
			self::newParserOutput( TestUtils::TEST_DOC_WITHOUT_LINKS, $pOptsNoLinks )
		];
		yield 'TEST_DOC enabled via $options: with links' => [
			self::newParserOutput( TestUtils::TEST_DOC ),
			null, [ 'enableSectionEditLinks' => true ],
			self::newParserOutput( TestUtils::TEST_DOC_WITH_LINKS )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS default: with links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITH_LINKS )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS disabled via $options: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [ 'enableSectionEditLinks' => false ],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITHOUT_LINKS )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS disabled via ParserOptions: no links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS, $pOptsNoLinks ),
			$pOptsNoLinks, [],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITHOUT_LINKS, $pOptsNoLinks )
		];
		yield 'TEST_DOC_ANGLE_BRACKETS enabled via $options: with links' => [
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS ),
			null, [ 'enableSectionEditLinks' => true ],
			self::newParserOutput( TestUtils::TEST_DOC_ANGLE_BRACKETS_WITH_LINKS )
		];
	}
}
