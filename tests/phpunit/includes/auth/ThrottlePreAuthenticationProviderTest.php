<?php

namespace MediaWiki\Auth;

use HashConfig;
use MediaWiki\User\UserNameUtils;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use stdClass;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\ThrottlePreAuthenticationProvider
 */
class ThrottlePreAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {
	public function testConstructor() {
		$provider = new ThrottlePreAuthenticationProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			'AccountCreationThrottle' => [ [
				'count' => 123,
				'seconds' => 86400,
			] ],
			'PasswordAttemptThrottle' => [ [
				'count' => 5,
				'seconds' => 300,
			] ],
		] );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$this->createNoOpMock( AuthManager::class ),
			$this->createHookContainer(),
			$config,
			$this->createNoOpMock( UserNameUtils::class )
		);
		$this->assertSame( [
			'accountCreationThrottle' => [ [ 'count' => 123, 'seconds' => 86400 ] ],
			'passwordAttemptThrottle' => [ [ 'count' => 5, 'seconds' => 300 ] ]
		], $providerPriv->throttleSettings );
		$accountCreationThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->accountCreationThrottle );
		$this->assertSame( [ [ 'count' => 123, 'seconds' => 86400 ] ],
			$accountCreationThrottle->conditions );
		$passwordAttemptThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->passwordAttemptThrottle );
		$this->assertSame( [ [ 'count' => 5, 'seconds' => 300 ] ],
			$passwordAttemptThrottle->conditions );

		$provider = new ThrottlePreAuthenticationProvider( [
			'accountCreationThrottle' => [ [ 'count' => 43, 'seconds' => 10000 ] ],
			'passwordAttemptThrottle' => [ [ 'count' => 11, 'seconds' => 100 ] ],
		] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			'AccountCreationThrottle' => [ [
				'count' => 123,
				'seconds' => 86400,
			] ],
			'PasswordAttemptThrottle' => [ [
				'count' => 5,
				'seconds' => 300,
			] ],
		] );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$this->createNoOpMock( AuthManager::class ),
			$this->createHookContainer(),
			$config,
			$this->createNoOpMock( UserNameUtils::class )
		);
		$this->assertSame( [
			'accountCreationThrottle' => [ [ 'count' => 43, 'seconds' => 10000 ] ],
			'passwordAttemptThrottle' => [ [ 'count' => 11, 'seconds' => 100 ] ],
		], $providerPriv->throttleSettings );

		$cache = new \HashBagOStuff();
		$provider = new ThrottlePreAuthenticationProvider( [ 'cache' => $cache ] );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			'AccountCreationThrottle' => [ [ 'count' => 1, 'seconds' => 1 ] ],
			'PasswordAttemptThrottle' => [ [ 'count' => 1, 'seconds' => 1 ] ],
		] );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$this->createNoOpMock( AuthManager::class ),
			$this->createHookContainer(),
			$config,
			$this->createNoOpMock( UserNameUtils::class )
		);
		$accountCreationThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->accountCreationThrottle );
		$this->assertSame( $cache, $accountCreationThrottle->cache );
		$passwordAttemptThrottle = TestingAccessWrapper::newFromObject(
			$providerPriv->passwordAttemptThrottle );
		$this->assertSame( $cache, $passwordAttemptThrottle->cache );
	}

	public function testDisabled() {
		$provider = new ThrottlePreAuthenticationProvider( [
			'accountCreationThrottle' => [],
			'passwordAttemptThrottle' => [],
			'cache' => new \HashBagOStuff(),
		] );
		$provider->init(
			$this->createNoOpMock( NullLogger::class ),
			$this->getServiceContainer()->getAuthManager(),
			$this->createHookContainer(),
			new HashConfig( [
				'AccountCreationThrottle' => null,
				'PasswordAttemptThrottle' => null,
			] ),
			$this->createNoOpMock( UserNameUtils::class )
		);

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation(
				\User::newFromName( 'Created' ),
				\User::newFromName( 'Creator' ),
				[]
			)
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAuthentication( [] )
		);
	}

	/**
	 * @dataProvider provideTestForAccountCreation
	 * @param string $creatorname
	 * @param bool $succeed
	 * @param bool $hook
	 */
	public function testTestForAccountCreation( $creatorname, $succeed, $hook ) {
		$provider = new ThrottlePreAuthenticationProvider( [
			'accountCreationThrottle' => [ [ 'count' => 2, 'seconds' => 86400 ] ],
			'cache' => new \HashBagOStuff(),
		] );
		$provider->init(
			new NullLogger(),
			$this->getServiceContainer()->getAuthManager(),
			$this->getServiceContainer()->getHookContainer(),
			new HashConfig( [
				'AccountCreationThrottle' => null,
				'PasswordAttemptThrottle' => null,
			] ),
			$this->createNoOpMock( UserNameUtils::class )
		);

		$user = \User::newFromName( 'RandomUser' );
		$creator = \User::newFromName( $creatorname );
		if ( $hook ) {
			$mock = $this->getMockBuilder( stdClass::class )
				->addMethods( [ 'onExemptFromAccountCreationThrottle' ] )
				->getMock();
			$mock->method( 'onExemptFromAccountCreationThrottle' )
				->willReturn( false );
			$this->mergeMwGlobalArrayValue( 'wgHooks', [
				'ExemptFromAccountCreationThrottle' => [ $mock ],
			] );
		}

		$this->assertTrue(

			$provider->testForAccountCreation( $user, $creator, [] )->isOK(),
			'attempt #1'
		);
		$this->assertTrue(

			$provider->testForAccountCreation( $user, $creator, [] )->isOK(),
			'attempt #2'
		);
		$this->assertEquals(
			$succeed ? true : false,
			$provider->testForAccountCreation( $user, $creator, [] )->isOK(),
			'attempt #3'
		);
	}

	public static function provideTestForAccountCreation() {
		return [
			'Normal user' => [ 'NormalUser', false, false ],
			'Sysop' => [ 'UTSysop', true, false ],
			'Normal user with hook' => [ 'NormalUser', true, true ],
		];
	}

	public function testTestForAuthentication() {
		$provider = new ThrottlePreAuthenticationProvider( [
			'passwordAttemptThrottle' => [ [ 'count' => 2, 'seconds' => 86400 ] ],
			'cache' => new \HashBagOStuff(),
		] );
		$provider->init(
			new NullLogger(),
			$this->getServiceContainer()->getAuthManager(),
			$this->createHookContainer(),
			new HashConfig( [
				'AccountCreationThrottle' => null,
				'PasswordAttemptThrottle' => null,
			] ),
			$this->createNoOpMock( UserNameUtils::class )
		);

		$req = new UsernameAuthenticationRequest;
		$req->username = 'SomeUser';
		for ( $i = 1; $i <= 3; $i++ ) {
			$status = $provider->testForAuthentication( [ $req ] );
			$this->assertEquals( $i < 3, $status->isGood(), "attempt #$i" );
		}
		$this->assertCount( 1, $status->getErrors() );
		$msg = new \Message( $status->getErrors()[0]['message'], $status->getErrors()[0]['params'] );
		$this->assertEquals( 'login-throttled', $msg->getKey() );

		$provider->postAuthentication( \User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newFail( wfMessage( 'foo' ) ) );
		$this->assertFalse( $provider->testForAuthentication( [ $req ] )->isGood(), 'after FAIL' );

		$provider->postAuthentication( \User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newPass() );
		$this->assertTrue( $provider->testForAuthentication( [ $req ] )->isGood(), 'after PASS' );

		$req1 = new UsernameAuthenticationRequest;
		$req1->username = 'foo';
		$req2 = new UsernameAuthenticationRequest;
		$req2->username = 'bar';
		$this->assertTrue( $provider->testForAuthentication( [ $req1, $req2 ] )->isGood() );

		$req = new UsernameAuthenticationRequest;
		$req->username = 'Some user';
		$provider->testForAuthentication( [ $req ] );
		$req->username = 'Some_user';
		$provider->testForAuthentication( [ $req ] );
		$req->username = 'some user';
		$status = $provider->testForAuthentication( [ $req ] );
		$this->assertFalse( $status->isGood(), 'denormalized usernames are normalized' );
	}

	public function testPostAuthentication() {
		$provider = new ThrottlePreAuthenticationProvider( [
			'passwordAttemptThrottle' => [],
			'cache' => new \HashBagOStuff(),
		] );
		$provider->init(
			new TestLogger(),
			$this->getServiceContainer()->getAuthManager(),
			$this->createHookContainer(),
			new HashConfig( [
				'AccountCreationThrottle' => null,
				'PasswordAttemptThrottle' => null,
			] ),
			$this->createNoOpMock( UserNameUtils::class )
		);
		$provider->postAuthentication( \User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newPass() );

		$provider = new ThrottlePreAuthenticationProvider( [
			'passwordAttemptThrottle' => [ [ 'count' => 2, 'seconds' => 86400 ] ],
			'cache' => new \HashBagOStuff(),
		] );
		$logger = new \TestLogger( true );
		$provider->init(
			$logger,
			$this->getServiceContainer()->getAuthManager(),
			$this->createHookContainer(),
			new HashConfig( [
				'AccountCreationThrottle' => null,
				'PasswordAttemptThrottle' => null,
			] ),
			$this->createNoOpMock( UserNameUtils::class )
		);
		$provider->postAuthentication( \User::newFromName( 'SomeUser' ),
			AuthenticationResponse::newPass() );
		$this->assertSame( [
			[ \Psr\Log\LogLevel::INFO, 'throttler data not found for {user}' ],
		], $logger->getBuffer() );
	}
}
