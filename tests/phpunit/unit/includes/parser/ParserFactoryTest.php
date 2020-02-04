<?php
use MediaWiki\BadFileLookup;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Special\SpecialPageFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ParserFactory
 */
class ParserFactoryTest extends MediaWikiUnitTestCase {
	function createFactory() {
		$options = $this->getMockBuilder( ServiceOptions::class )
		->disableOriginalConstructor()
		->setMethods( [ 'assertRequiredOptions', 'get' ] )->getMock();

		$options->expects( $this->never() )
			->method( $this->anythingBut( 'assertRequiredOptions', 'get' ) );

		$this->assertInstanceOf( ServiceOptions::class, $options );
		$factory = new ParserFactory(
			$options,
			$this->createNoOpMock( MagicWordFactory::class ),
			$this->createNoOpMock( Language::class ),
			"",
			$this->createNoOpMock( SpecialPageFactory::class ),
			$this->createNoOpMock( LinkRendererFactory::class ),
			$this->createNoOpMock( NamespaceInfo::class ),
			$this->createNoOpMock( LoggerInterface::class ),
			$this->createNoOpMock( BadFileLookup::class ),
			$this->createNoOpMock( LanguageConverterFactory::class )
		);
		return $factory;
	}

	/**
	 * @covers ParserFactory::__construct
	 */
	public function testConstructor() {
		$factory = $this->createFactory();
		$this->assertNotNull( $factory, "Factory should be created correctly" );
	}

	/**
	 * @covers ParserFactory::create
	 */
	function testCreate() {
		$factory = $this->createFactory();
		$parser = $factory->create();
		$this->assertNotNull( $factory, "Factory should be created correctly" );
		$this->assertNotNull( $parser, "Factory should create parser correctly" );
		$this->assertInstanceOf( Parser::class, $parser );

		$parserWrapper = TestingAccessWrapper::newFromObject( $parser );
		$factoryWrapper = TestingAccessWrapper::newFromObject( $factory );
		$this->assertSame(
			$factoryWrapper->languageConverterFactory,  $parserWrapper->languageConverterFactory
		);
		$this->assertSame(
			$factory, $parserWrapper->factory
		);
	}
}
