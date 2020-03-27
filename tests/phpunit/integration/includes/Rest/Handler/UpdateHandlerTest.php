<?php

namespace MediaWiki\Tests\Rest\Handler;

use ApiUsageException;
use FormatJson;
use HashConfig;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Rest\Handler\UpdateHandler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWikiIntegrationTestCase;
use MediaWikiTitleCodec;
use PHPUnit\Framework\MockObject\MockObject;
use Status;
use Title;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use WikitextContent;
use WikitextContentHandler;

/**
 * @covers \MediaWiki\Rest\Handler\UpdateHandler
 */
class UpdateHandlerTest extends MediaWikiIntegrationTestCase {

	use ActionModuleBasedHandlerTestTrait;

	private function newHandler( $resultData, $throwException = null, $csrfSafe = false ) {
		$config = new HashConfig( [
			'RightsUrl' => 'https://creativecommons.org/licenses/by-sa/4.0/',
			'RightsText' => 'CC-BY-SA 4.0'
		] );

		/** @var IContentHandlerFactory|MockObject $contentHandlerFactory */
		$contentHandlerFactory =
			$this->createNoOpMock(
				IContentHandlerFactory::class,
				[ 'isDefinedModel', 'getContentHandler' ]
			);

		$contentHandlerFactory
			->method( 'isDefinedModel' )
			->willReturnMap( [
				[ CONTENT_MODEL_WIKITEXT, true ],
			] );

		$contentHandlerFactory
			->method( 'getContentHandler' )
			->willReturn( new WikitextContentHandler() );

		/** @var MockObject|MediaWikiTitleCodec $titleCodec */
		$titleCodec = $this->getMockBuilder( MediaWikiTitleCodec::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'formatTitle', 'splitTitleString' ] )
			->getMock();

		$titleCodec->method( 'formatTitle' )
			->willReturnCallback( function ( $namespace, $text ) {
				return "ns:$namespace:" . ucfirst( $text );
			} );
		$titleCodec->method( 'splitTitleString' )
			->willReturnCallback( function ( $text ) {
				return [
					'interwiki' => '',
					'fragment' => '',
					'namespace' => 0,
					'dbkey' => str_replace( ' ', '_', $text )
				];
			} );

		/** @var RevisionLookup|MockObject $revisionLookup */
		$revisionLookup = $this->createNoOpMock(
			RevisionLookup::class,
			[ 'getRevisionById', 'getRevisionByTitle' ]
		);
		$revisionLookup->method( 'getRevisionById' )
			->willReturnCallback( function ( $id ) {
				$title = $this->makeMockTitle( __CLASS__ );
				$rev = new MutableRevisionRecord( $title );
				$rev->setId( $id );
				$rev->setContent( SlotRecord::MAIN, new WikitextContent( "Content of revision $id" ) );
				return $rev;
			} );
		$revisionLookup->method( 'getRevisionByTitle' )
			->willReturnCallback( function ( $title ) {
				$rev = new MutableRevisionRecord( Title::castFromLinkTarget( $title ) );
				$rev->setId( 1234 );
				$rev->setContent( SlotRecord::MAIN, new WikitextContent( "Current content of $title" ) );
				return $rev;
			} );

		$handler = new UpdateHandler(
			$config,
			$contentHandlerFactory,
			$titleCodec,
			$titleCodec,
			$revisionLookup
		);

		$apiMain = $this->getApiMain( $csrfSafe );
		$dummyModule = $this->getDummyApiModule( $apiMain, 'edit', $resultData, $throwException );

		$handler->setApiMain( $apiMain );
		$handler->overrideActionModule(
			'edit',
			'action',
			$dummyModule
		);

		return $handler;
	}

	public function provideExecute() {
		yield "create with token" => [
			[ // Request data received by UpdateHandler
				'method' => 'PUT',
				'pathParams' => [ 'title' => 'Foo' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'bodyContents' => json_encode( [
					'token' => 'TOKEN',
					'source' => 'Lorem Ipsum',
					'comment' => 'Testing'
				] ),
			],
			[ // Fake request expected to be passed into ApiEditPage
				'title' => 'Foo',
				'text' => 'Lorem Ipsum',
				'summary' => 'Testing',
				'createonly' => '1',
				'token' => 'TOKEN',
			],
			[ // Mock response returned by ApiEditPage
				"edit" => [
					"new" => true,
					"result" => "Success",
					"pageid" => 94542,
					"title" => "Sandbox",
					"contentmodel" => "wikitext",
					"oldrevid" => 0,
					"newrevid" => 371707,
					"newtimestamp" => "2018-12-18T16:59:42Z",
				]
			],
			[ // Response expected to be generated by UpdateHandler
				'id' => 94542,
				'title' => 'ns:0:Foo',
				'key' => 'ns:0:Foo',
				'content_model' => 'wikitext',
				'latest' => [
					'id' => 371707,
					'timestamp' => "2018-12-18T16:59:42Z"
				],
				'license' => [
					'url' => 'https://creativecommons.org/licenses/by-sa/4.0/',
					'title' => 'CC-BY-SA 4.0'
				],
				'source' => 'Content of revision 371707'
			],
			false
		];

		yield "create with model" => [
			[ // Request data received by UpdateHandler
				'method' => 'POST',
				'pathParams' => [ 'title' => 'Foo' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'bodyContents' => json_encode( [
					'token' => 'TOKEN',
					'source' => 'Lorem Ipsum',
					'comment' => 'Testing',
					'content_model' => CONTENT_MODEL_WIKITEXT,
				] ),
			],
			[ // Fake request expected to be passed into ApiEditPage
				'title' => 'Foo',
				'text' => 'Lorem Ipsum',
				'summary' => 'Testing',
				'contentmodel' => 'wikitext',
				'createonly' => '1',
				'token' => 'TOKEN',
			],
			[ // Mock response returned by ApiEditPage
				"edit" => [
					"new" => true,
					"result" => "Success",
					"pageid" => 94542,
					"title" => "Sandbox",
					"contentmodel" => "wikitext",
					"oldrevid" => 0,
					"newrevid" => 371707,
					"newtimestamp" => "2018-12-18T16:59:42Z",
				]
			],
			[ // Response expected to be generated by UpdateHandler
				'id' => 94542,
				'title' => 'ns:0:Foo',
				'key' => 'ns:0:Foo',
				'content_model' => 'wikitext',
				'latest' => [
					'id' => 371707,
					'timestamp' => "2018-12-18T16:59:42Z"
				],
				'license' => [
					'url' => 'https://creativecommons.org/licenses/by-sa/4.0/',
					'title' => 'CC-BY-SA 4.0'
				],
				'source' => 'Content of revision 371707'
			],
			false
		];

		yield "update with token" => [
			[ // Request data received by UpdateHandler
				'method' => 'PUT',
				'pathParams' => [ 'title' => 'foo bar' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'bodyContents' => json_encode( [
					'token' => 'TOKEN',
					'source' => 'Lorem Ipsum',
					'comment' => 'Testing',
					'latest' => [ 'id' => 789123 ],
				] ),
			],
			[ // Fake request expected to be passed into ApiEditPage
				'title' => 'foo bar',
				'text' => 'Lorem Ipsum',
				'summary' => 'Testing',
				'nocreate' => '1',
				'baserevid' => '789123',
				'token' => 'TOKEN',
			],
			[ // Mock response returned by ApiEditPage
				"edit" => [
					"result" => "Success",
					"pageid" => 94542,
					"title" => "Sandbox",
					"contentmodel" => "wikitext",
					"oldrevid" => 371705,
					"newrevid" => 371707,
					"newtimestamp" => "2018-12-18T16:59:42Z",
				]
			],
			[ // Response expected to be generated by UpdateHandler
				'id' => 94542,
				'content_model' => 'wikitext',
				'title' => 'ns:0:Foo bar',
				'key' => 'ns:0:Foo_bar',
				'latest' => [
					'id' => 371707,
					'timestamp' => "2018-12-18T16:59:42Z"
				],
				'license' => [
					'url' => 'https://creativecommons.org/licenses/by-sa/4.0/',
					'title' => 'CC-BY-SA 4.0'
				],
				'source' => 'Content of revision 371707'
			],
			false
		];

		yield "update with model" => [
			[ // Request data received by UpdateHandler
				'method' => 'POST',
				'pathParams' => [ 'title' => 'foo bar' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'bodyContents' => json_encode( [
					'source' => 'Lorem Ipsum',
					'comment' => 'Testing',
					'content_model' => CONTENT_MODEL_WIKITEXT,
					'latest' => [ 'id' => 789123 ],
				] ),
			],
			[ // Fake request expected to be passed into ApiEditPage
				'title' => 'foo bar',
				'text' => 'Lorem Ipsum',
				'summary' => 'Testing',
				'contentmodel' => 'wikitext',
				'nocreate' => '1',
				'baserevid' => '789123',
				'token' => '+\\',
			],
			[ // Mock response returned by ApiEditPage
				"edit" => [
					"result" => "Success",
					"pageid" => 94542,
					"title" => "Sandbox",
					"contentmodel" => "wikitext",
					"oldrevid" => 371705,
					"newrevid" => 371707,
					"newtimestamp" => "2018-12-18T16:59:42Z",
				]
			],
			[ // Response expected to be generated by UpdateHandler
				'id' => 94542,
				'content_model' => 'wikitext',
				'title' => 'ns:0:Foo bar',
				'key' => 'ns:0:Foo_bar',
				'latest' => [
					'id' => 371707,
					'timestamp' => "2018-12-18T16:59:42Z"
				],
				'license' => [
					'url' => 'https://creativecommons.org/licenses/by-sa/4.0/',
					'title' => 'CC-BY-SA 4.0'
				],
				'source' => 'Content of revision 371707'
			],
			true
		];

		yield "update without token" => [
			[ // Request data received by UpdateHandler
				'method' => 'PUT',
				'pathParams' => [ 'title' => 'Foo' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'bodyContents' => json_encode( [
					'source' => 'Lorem Ipsum',
					'comment' => 'Testing',
					'content_model' => CONTENT_MODEL_WIKITEXT,
					'latest' => [ 'id' => 789123 ],
				] ),
			],
			[ // Fake request expected to be passed into ApiEditPage
				'title' => 'Foo',
				'text' => 'Lorem Ipsum',
				'summary' => 'Testing',
				'contentmodel' => 'wikitext',
				'nocreate' => '1',
				'baserevid' => '789123',
				'token' => '+\\', // use known-good token for current user (anon)
			],
			[ // Mock response returned by ApiEditPage
				"edit" => [
					"result" => "Success",
					"pageid" => 94542,
					"title" => "Sandbox",
					"contentmodel" => "wikitext",
					"oldrevid" => 371705,
					"newrevid" => 371707,
					"newtimestamp" => "2018-12-18T16:59:42Z",
				]
			],
			[ // Response expected to be generated by UpdateHandler
				'id' => 94542,
				'content_model' => 'wikitext',
				'latest' => [
					'id' => 371707,
					'timestamp' => "2018-12-18T16:59:42Z"
				],
				'license' => [
					'url' => 'https://creativecommons.org/licenses/by-sa/4.0/',
					'title' => 'CC-BY-SA 4.0'
				],
			],
			true
		];
	}

	/**
	 * @dataProvider provideExecute
	 */
	public function testExecute(
		$requestData,
		$expectedActionParams,
		$actionResult,
		$expectedResponse,
		$csrfSafe
	) {
		$request = new RequestData( $requestData );

		$handler = $this->newHandler( $actionResult, null, $csrfSafe );

		$responseData = $this->executeHandlerAndGetBodyData( $handler, $request );

		// Check parameters passed to ApiEditPage by UpdateHandler based on $requestData
		foreach ( $expectedActionParams as $key => $value ) {
			$this->assertSame(
				$handler->getApiMain()->getVal( $key ),
				$value,
				"ApiEditPage param: $key"
			);
		}

		// Check response that UpdateHandler created after receiving $actionResult from ApiEditPage
		foreach ( $expectedResponse as $key => $value ) {
			$this->assertArrayHasKey( $key, $responseData );
			$this->assertSame(
				$value,
				$responseData[ $key ],
				"UpdateHandler response field: $key"
			);
		}
	}

	public function provideBodyValidation() {
		yield "missing source field" => [
			[ // Request data received by UpdateHandler
				'method' => 'PUT',
				'pathParams' => [ 'title' => 'Foo' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'bodyContents' => json_encode( [
					'token' => 'TOKEN',
					'comment' => 'Testing',
					'content_model' => CONTENT_MODEL_WIKITEXT,
				] ),
			],
			new MessageValue( 'rest-missing-body-field', [ 'source' ] ),
		];
		yield "missing comment field" => [
			[ // Request data received by UpdateHandler
				'method' => 'PUT',
				'pathParams' => [ 'title' => 'Foo' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'bodyContents' => json_encode( [
					'token' => 'TOKEN',
					'source' => 'Lorem Ipsum',
					'content_model' => CONTENT_MODEL_WIKITEXT,
				] ),
			],
			new MessageValue( 'rest-missing-body-field', [ 'comment' ] ),
		];
	}

	/**
	 * @dataProvider provideBodyValidation
	 */
	public function testBodyValidation( array $requestData, MessageValue $expectedMessage ) {
		$request = new RequestData( $requestData );

		$handler = $this->newHandler( [] );

		$exception = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( 400, $exception->getCode(), 'HTTP status' );
		$this->assertInstanceOf( LocalizedHttpException::class, $exception );

		/** @var LocalizedHttpException $exception */
		$this->assertEquals( $expectedMessage, $exception->getMessageValue() );
	}

	public function testBodyValidation_extraneousToken() {
		$requestData = [
			'method' => 'PUT',
			'pathParams' => [ 'title' => 'Foo' ],
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'bodyContents' => json_encode( [
				'token' => 'TOKEN',
				'comment' => 'Testing',
				'source' => 'Lorem Ipsum',
				'content_model' => 'wikitext'
			] ),
		];

		$request = new RequestData( $requestData );

		$handler = $this->newHandler( [], null, true );

		$exception = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( 400, $exception->getCode(), 'HTTP status' );
		$this->assertInstanceOf( LocalizedHttpException::class, $exception );

		$expectedMessage = new MessageValue( 'rest-extraneous-csrf-token' );
		$this->assertEquals( $expectedMessage, $exception->getMessageValue() );
	}

	public function provideHeaderValidation() {
		yield "bad content type" => [
			[ // Request data received by UpdateHandler
				'method' => 'PUT',
				'pathParams' => [ 'title' => 'Foo' ],
				'headers' => [
					'Content-Type' => 'text/plain',
				],
				'bodyContents' => json_encode( [
					'token' => 'TOKEN',
					'source' => 'Lorem Ipsum',
					'comment' => 'Testing',
					'content_model' => CONTENT_MODEL_WIKITEXT,
				] ),
			],
			415
		];
	}

	/**
	 * @dataProvider provideHeaderValidation
	 */
	public function testHeaderValidation( array $requestData, $expectedStatus ) {
		$request = new RequestData( $requestData );

		$handler = $this->newHandler( [] );

		$exception = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( $expectedStatus, $exception->getCode(), 'HTTP status' );
	}

	public function provideErrorMapping() {
		yield "missingtitle" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-missingtitle' ) ),
			new LocalizedHttpException( new MessageValue( 'apierror-missingtitle' ), 404 ),
		];
		yield "protectedpage" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-protectedpage' ) ),
			new LocalizedHttpException( new MessageValue( 'apierror-protectedpage' ), 403 ),
		];
		yield "articleexists" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-articleexists' ) ),
			new LocalizedHttpException(
				new MessageValue( 'rest-update-cannot-create-page', [ 'Foo' ] ),
				409
			),
		];
		yield "editconflict" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-editconflict' ) ),
			new LocalizedHttpException( new MessageValue( 'apierror-editconflict' ), 409 ),
		];
		yield "ratelimited" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-ratelimited' ) ),
			new LocalizedHttpException( new MessageValue( 'apierror-ratelimited' ), 429 ),
		];
		yield "badtoken" => [
			new ApiUsageException(
				null,
				Status::newFatal( 'apierror-badtoken', [ 'plaintext' => 'BAD' ] )
			),
			new LocalizedHttpException(
				new MessageValue(
					'apierror-badtoken',
					[ new ScalarParam( ParamType::PLAINTEXT, 'BAD' ) ]
				), 403
			),
		];

		// Unmapped errors should be passed through with a status 400.
		yield "no-direct-editing" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-no-direct-editing' ) ),
			new LocalizedHttpException( new MessageValue( 'apierror-no-direct-editing' ), 400 ),
		];
		yield "badformat" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-badformat' ) ),
			new LocalizedHttpException( new MessageValue( 'apierror-badformat' ), 400 ),
		];
		yield "emptypage" => [
			new ApiUsageException( null, Status::newFatal( 'apierror-emptypage' ) ),
			new LocalizedHttpException( new MessageValue( 'apierror-emptypage' ), 400 ),
		];
	}

	/**
	 * @dataProvider provideErrorMapping
	 */
	public function testErrorMapping(
		ApiUsageException $apiUsageException,
		HttpException $expectedHttpException
	) {
		$requestData = [ // Request data received by UpdateHandler
			'method' => 'POST',
			'pathParams' => [ 'title' => 'Foo' ],
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'bodyContents' => json_encode( [
				'source' => 'Lorem Ipsum',
				'comment' => 'Testing',
				'content_model' => CONTENT_MODEL_WIKITEXT,
			] ),
		];
		$request = new RequestData( $requestData );

		$handler = $this->newHandler( [], $apiUsageException );

		$exception = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( $expectedHttpException->getMessage(), $exception->getMessage() );
		$this->assertSame( $expectedHttpException->getCode(), $exception->getCode(), 'HTTP status' );

		$errorData = $exception->getErrorData();
		if ( $expectedHttpException->getErrorData() ) {
			foreach ( $expectedHttpException->getErrorData() as $key => $value ) {
				$this->assertSame( $value, $errorData[$key], 'Error data key $key' );
			}
		}

		if ( $expectedHttpException instanceof LocalizedHttpException ) {
			/** @var LocalizedHttpException $exception */
			$this->assertEquals(
				$expectedHttpException->getMessageValue(),
				$exception->getMessageValue()
			);
		}
	}

	public function testConflictOutput() {
		$requestData = [ // Request data received by UpdateHandler
			'method' => 'POST',
			'pathParams' => [ 'title' => 'Foo' ],
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'bodyContents' => json_encode( [
				'latest' => [
					'id' => 17,
				],
				'source' => 'Lorem Ipsum',
				'comment' => 'Testing'
			] ),
		];
		$request = new RequestData( $requestData );

		$apiUsageException = new ApiUsageException( null, Status::newFatal( 'apierror-editconflict' ) );
		$handler = $this->newHandler( [], $apiUsageException );
		$handler->setJsonDiffFunction( [ $this, 'fakeJsonDiff' ] );

		$exception = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( 409, $exception->getCode(), 'HTTP status' );

		$expectedData = [
			'local' => [
				'from' => 'Content of revision 17',
				'to' => 'Lorem Ipsum',
			],
			'remote' => [
				'from' => 'Content of revision 17',
				'to' => 'Current content of 0:Foo',
			],
			'base' => 17,
			'current' => 1234
		];

		$errorData = $exception->getErrorData();
		foreach ( $expectedData as $key => $value ) {
			$this->assertSame( $value, $errorData[$key], "Error data key $key" );
		}
	}

	public function fakeJsonDiff( $fromText, $toText ) {
		return FormatJson::encode( [
			'from' => $fromText,
			'to' => $toText
		] );
	}

}
