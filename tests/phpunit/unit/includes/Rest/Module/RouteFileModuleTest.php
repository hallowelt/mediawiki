<?php

namespace MediaWiki\Tests\Rest\Module;

use GuzzleHttp\Psr7\Uri;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\RouteFileModule;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Tests\Rest\RestTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Throwable;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Module\RouteFileModule
 */
class RouteFileModuleTest extends \MediaWikiUnitTestCase {
	use RestTestTrait;
	use DummyServicesTrait;

	private const CANONICAL_SERVER = 'https://wiki.example.com';
	private const INTERNAL_SERVER = 'http://api.local:8080';

	/** @var Throwable[] */
	private $reportedErrors = [];

	/**
	 * @param RequestInterface $request
	 * @param string|null $authError
	 * @param string[] $extraRoutes
	 *
	 * @return RouteFileModule
	 */
	private function createRouteFileModule(
		RequestInterface $request,
		$authError = null,
		$extraRoutes = []
	) {
		$routeFiles = [
			__DIR__ . '/moduleTestRoutes.json', // intermediate format with meta-data
			__DIR__ . '/moduleFlatRoutes.json', // old, flat format
		];

		/** @var MockObject|ErrorReporter $mockErrorReporter */
		$mockErrorReporter = $this->createNoOpMock( ErrorReporter::class, [ 'reportError' ] );
		$mockErrorReporter->method( 'reportError' )
			->willReturnCallback( function ( $e ) {
				$this->reportedErrors[] = $e;
			} );

		$config = [
			MainConfigNames::CanonicalServer => self::CANONICAL_SERVER,
			MainConfigNames::InternalServer => self::INTERNAL_SERVER,
			MainConfigNames::RestPath => '/rest',
		];

		$auth = new StaticBasicAuthorizer( $authError );
		$objectFactory = $this->getDummyObjectFactory();

		$authority = $this->mockAnonUltimateAuthority();
		$validator = new Validator( $objectFactory, $request, $authority );

		$router = $this->newRouter( [
			'routeFiles' => [],
			'request' => $request,
			'config' => $config,
			'errorReporter' => $mockErrorReporter,
			'basicAuth' => $auth,
			'validator' => $validator
		] );

		$module = new RouteFileModule(
			$routeFiles,
			$extraRoutes,
			$router,
			'mock.v1',
			new ResponseFactory( [] ),
			$auth,
			$objectFactory,
			$validator,
			$mockErrorReporter
		);

		return $module;
	}

	private function createMockStats( string $method, ...$with ): StatsdDataFactoryInterface {
		$stats = $this->createNoOpMock( StatsdDataFactoryInterface::class, [ $method ] );
		$stats->expects( $this->atLeastOnce() )->method( $method )->with( ...$with );
		return $stats;
	}

	public function testWrongMethod() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock.v1/ModuleTest/hello' ),
			'method' => 'TRACE'
		] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/hello', $request );
		$this->assertSame( 405, $response->getStatusCode() );
		$this->assertSame( 'Method Not Allowed', $response->getReasonPhrase() );
		$this->assertSame( 'HEAD, GET', $response->getHeaderLine( 'Allow' ) );
	}

	public function testHeadToGet() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock.v1/ModuleTest/hello' ),
			'method' => 'HEAD'
		] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/hello', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testFlatRouteFile() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/foobar/ModuleTest/greetings/you' ),
			'method' => 'HEAD'
		] );
		$module = $this->createRouteFileModule( $request );

		$module->setStats( $this->createMockStats(
			'timing',
			'rest_api_latency._mock_v1_foobar_ModuleTest_greetings_-name-.HEAD.200',
			$this->greaterThan( 0 )
		) );

		$response = $module->execute( '/foobar/ModuleTest/greetings/you', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testNoMatch() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock.v1/Bogus' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/Bogus', $request );
		$this->assertSame( 404, $response->getStatusCode() );
		// TODO: add more information to the response body and test for its presence here
	}

	public function testHttpException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock.v1/ModuleTest/throw' ) ] );
		$module = $this->createRouteFileModule( $request );

		$module->setStats( $this->createMockStats(
			'increment',
			'rest_api_errors._mock_v1_ModuleTest_throw.GET.555'
		) );

		$response = $module->execute( '/ModuleTest/throw', $request );
		$this->assertSame( 555, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'Mock error', $data['message'] );
	}

	public function testFatalException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock.v1/ModuleTest/fatal' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/fatal', $request );
		$this->assertSame( 500, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertStringContainsString( 'RuntimeException', $data['message'] );
		$this->assertNotEmpty( $this->reportedErrors );
		$this->assertInstanceOf( RuntimeException::class, $this->reportedErrors[0] );
	}

	public function testRedirectException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock.v1/ModuleTest/throwRedirect' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/throwRedirect', $request );
		$this->assertSame( 301, $response->getStatusCode() );
		$this->assertSame( 'http://example.com', $response->getHeaderLine( 'Location' ) );
	}

	public function testResponseException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock.v1/ModuleTest/throwWrapped' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/throwWrapped', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testBasicAccess() {
		// Using the throwing handler is a way to assert that the handler is not executed
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock.v1/ModuleTest/throw' ) ] );
		$module = $this->createRouteFileModule( $request, 'test-error', [] );
		$response = $module->execute( '/ModuleTest/throw', $request );
		$this->assertSame( 403, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'test-error', $data['error'] );
	}

	public function testAdditionalEndpoints() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock.v1/ModuleTest/hello-again' )
		] );
		$module = $this->createRouteFileModule(
			$request,
			null,
			[ [
				'path' => '/ModuleTest/hello-again',
				'class' => 'MediaWiki\\Tests\\Rest\\Handler\\HelloHandler'
			] ]
		);
		$response = $module->execute( '/ModuleTest/hello-again', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public static function provideGetRouteUrl() {
		yield 'empty' => [ '', '', [], [] ];
		yield 'simple route' => [ '/foo/bar', '/foo/bar' ];
		yield 'simple route with query' =>
			[ '/foo/bar', '/foo/bar?x=1&y=2', [ 'x' => '1', 'y' => '2' ] ];
		yield 'simple route with strange query chars' =>
			[ '/foo+bar', '/foo+bar?x=%23&y=%25&z=%2B', [ 'x' => '#', 'y' => '%', 'z' => '+' ] ];
		yield 'route with simple path params' =>
			[ '/foo/{test}/baz', '/foo/bar/baz', [], [ 'test' => 'bar' ] ];
		yield 'route with strange path params' =>
			[ '/foo/{test}/baz', '/foo/b%25%2F%2Bz/baz', [], [ 'test' => 'b%/+z' ] ];
		yield 'space in path does not become a plus' =>
			[ '/foo/{test}/baz', '/foo/b%20z/baz', [], [ 'test' => 'b z' ] ];
		yield 'route with simple path params and query' =>
			[ '/foo/{test}/baz', '/foo/bar/baz?x=1', [ 'x' => '1' ], [ 'test' => 'bar' ] ];
	}

	public function testCacheData() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock.v1/route' ) ] );
		$module1 = $this->createRouteFileModule( $request );
		$module1wrapper = TestingAccessWrapper::newFromObject( $module1 );

		$cacheData = $module1->getCacheData();

		// Create a second module
		$module2 = $this->createRouteFileModule( $request );
		$module2wrapper = TestingAccessWrapper::newFromObject( $module2 );

		// Destroy module2's ability to load routes
		$module2wrapper->routeFiles = [ '/this/does/not/exist' ];

		// Make sure the config hash is set and matches.
		$module2wrapper->configHash = $module1wrapper->configHash;

		// Check that initFromCacheData() succeeds.
		$this->assertTrue( $module2->initFromCacheData( $cacheData ) );

		// Check that the matcher tree is deep-equal after initFromCacheData().
		$this->assertEquals( $module1wrapper->getMatchers(), $module2wrapper->getMatchers() );

		// Invalidate the cache data
		$cacheData[ Module::CACHE_CONFIG_HASH_KEY ] = 'foobar';

		// Check that initFromCacheData() fails.
		$this->assertFalse( $module2->initFromCacheData( $cacheData ) );

		// Check that the matcher tree is still deep-equal.
		$this->assertEquals( $module1wrapper->getMatchers(), $module2wrapper->getMatchers() );
	}

}
