<?php

use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class TextContentTest extends MediaWikiLangTestCase {
	protected $context;

	protected function setUp(): void {
		parent::setUp();

		// Anon user
		$user = new User();
		$user->setName( '127.0.0.1' );

		$this->context = new RequestContext();
		$this->context->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
		$this->context->setUser( $user );

		RequestContext::getMain()->setTitle( $this->context->getTitle() );

		$this->overrideConfigValues( [
			MainConfigNames::TextModelsToParse => [
				CONTENT_MODEL_WIKITEXT,
				CONTENT_MODEL_CSS,
				CONTENT_MODEL_JAVASCRIPT,
			],
			MainConfigNames::CapitalLinks => true,
		] );
		$this->clearHook( 'ContentGetParserOutput' );
	}

	/**
	 * @param string $text
	 * @return TextContent
	 */
	public function newContent( $text ) {
		return new TextContent( $text );
	}

	public static function dataGetRedirectTarget() {
		return [
			[ '#REDIRECT [[Test]]',
				null,
			],
		];
	}

	/**
	 * @dataProvider dataGetRedirectTarget
	 * @covers \MediaWiki\Content\TextContent::getRedirectTarget
	 */
	public function testGetRedirectTarget( $text, $expected ) {
		$content = $this->newContent( $text );
		$t = $content->getRedirectTarget();

		if ( $expected === null ) {
			$this->assertNull( $t, "text should not have generated a redirect target: $text" );
		} else {
			$this->assertEquals( $expected, $t->getPrefixedText() );
		}
	}

	/**
	 * @dataProvider dataGetRedirectTarget
	 * @covers \MediaWiki\Content\TextContent::isRedirect
	 */
	public function testIsRedirect( $text, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $expected !== null, $content->isRedirect() );
	}

	public static function dataIsCountable() {
		return [
			[ '',
				null,
				'any',
				true
			],
			[ 'Foo',
				null,
				'any',
				true
			],
		];
	}

	/**
	 * @dataProvider dataIsCountable
	 * @covers \MediaWiki\Content\TextContent::isCountable
	 */
	public function testIsCountable( $text, $hasLinks, $mode, $expected ) {
		$this->overrideConfigValue( MainConfigNames::ArticleCountMethod, $mode );

		$content = $this->newContent( $text );

		$v = $content->isCountable( $hasLinks );

		$this->assertEquals(
			$expected,
			$v,
			'isCountable() returned unexpected value ' . var_export( $v, true )
				. ' instead of ' . var_export( $expected, true )
				. " in mode `$mode` for text \"$text\""
		);
	}

	public static function dataGetTextForSummary() {
		return [
			[ "hello\nworld.",
				16,
				'hello world.',
			],
			[ 'hello world.',
				8,
				'hello...',
			],
			[ '[[hello world]].',
				8,
				'[[hel...',
			],
		];
	}

	/**
	 * @dataProvider dataGetTextForSummary
	 * @covers \MediaWiki\Content\TextContent::getTextForSummary
	 */
	public function testGetTextForSummary( $text, $maxlength, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $expected, $content->getTextForSummary( $maxlength ) );
	}

	/**
	 * @covers \MediaWiki\Content\TextContent::copy
	 */
	public function testCopy() {
		$content = $this->newContent( 'hello world.' );
		$copy = $content->copy();

		$this->assertTrue( $content->equals( $copy ), 'copy must be equal to original' );
		$this->assertEquals( 'hello world.', $copy->getText() );
	}

	/**
	 * @covers \MediaWiki\Content\TextContent::getSize
	 * @covers \MediaWiki\Content\TextContent::getText
	 * @covers \MediaWiki\Content\TextContent::getTextForSearchIndex
	 * @covers \MediaWiki\Content\TextContent::getNativeData
	 * @covers \MediaWiki\Content\TextContent::getWikitextForTransclusion
	 */
	public function testGetTextMethods() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( 12, $content->getSize() );
		$this->assertEquals( 'hello world.', $content->getText() );
		$this->assertEquals( 'hello world.', $content->getTextForSearchIndex() );
		$this->assertEquals( 'hello world.', $content->getNativeData() );
		$this->assertEquals( 'hello world.', $content->getWikitextForTransclusion() );
	}

	/**
	 * @covers \MediaWiki\Content\TextContent::getModel
	 */
	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getModel() );
	}

	/**
	 * @covers \MediaWiki\Content\TextContent::getContentHandler
	 */
	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_TEXT, $content->getContentHandler()->getModelID() );
	}

	public static function dataIsEmpty() {
		return [
			[ '', true ],
			[ '  ', false ],
			[ '0', false ],
			[ 'hallo welt.', false ],
		];
	}

	/**
	 * @dataProvider dataIsEmpty
	 * @covers \MediaWiki\Content\TextContent::isEmpty
	 */
	public function testIsEmpty( $text, $empty ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $empty, $content->isEmpty() );
	}

	public static function dataEquals() {
		return [
			[ new TextContent( "hallo" ), null, false ],
			[ new TextContent( "hallo" ), new TextContent( "hallo" ), true ],
			[ new TextContent( "hallo" ), new JavaScriptContent( "hallo" ), false ],
			[ new TextContent( "hallo" ), new WikitextContent( "hallo" ), false ],
			[ new TextContent( "hallo" ), new TextContent( "HALLO" ), false ],
		];
	}

	/**
	 * @dataProvider dataEquals
	 * @covers \MediaWiki\Content\TextContent::equals
	 */
	public function testEquals( Content $a, Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public static function provideConvert() {
		return [
			[ // #0
				'Hallo Welt',
				CONTENT_MODEL_WIKITEXT,
				'lossless',
				'Hallo Welt'
			],
			[ // #1
				'Hallo Welt',
				CONTENT_MODEL_WIKITEXT,
				'lossless',
				'Hallo Welt'
			],
			[ // #1
				'Hallo Welt',
				CONTENT_MODEL_CSS,
				'lossless',
				'Hallo Welt'
			],
			[ // #1
				'Hallo Welt',
				CONTENT_MODEL_JAVASCRIPT,
				'lossless',
				'Hallo Welt'
			],
		];
	}

	/**
	 * @dataProvider provideConvert
	 * @covers \MediaWiki\Content\TextContent::convert
	 */
	public function testConvert( $text, $model, $lossy, $expectedNative ) {
		$content = $this->newContent( $text );

		/** @var TextContent $converted */
		$converted = $content->convert( $model, $lossy );

		if ( $expectedNative === false ) {
			$this->assertFalse( $converted, "conversion to $model was expected to fail!" );
		} else {
			$this->assertInstanceOf( Content::class, $converted );
			$this->assertEquals( $expectedNative, $converted->getText() );
		}
	}

	/**
	 * @covers \MediaWiki\Content\TextContent::normalizeLineEndings
	 * @dataProvider provideNormalizeLineEndings
	 */
	public function testNormalizeLineEndings( $input, $expected ) {
		$this->assertEquals( $expected, TextContent::normalizeLineEndings( $input ) );
	}

	public static function provideNormalizeLineEndings() {
		return [
			[
				"Foo\r\nbar",
				"Foo\nbar"
			],
			[
				"Foo\rbar",
				"Foo\nbar"
			],
			[
				"Foobar\n  ",
				"Foobar"
			]
		];
	}

	/**
	 * @covers \MediaWiki\Content\TextContent::__construct
	 * @covers \MediaWiki\Content\TextContentHandler::serializeContent
	 */
	public function testSerialize() {
		$cnt = $this->newContent( 'testing text' );

		$this->assertSame( 'testing text', $cnt->serialize() );
	}

}
