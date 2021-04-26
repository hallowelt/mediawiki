<?php

namespace MediaWiki\Tests\Unit\Auth;

use Config;
use MediaWiki\Auth\AbstractAuthenticationProvider;
use MediaWiki\Auth\AuthManager;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\User\UserNameUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractAuthenticationProvider
 */
class AbstractAuthenticationProviderTest extends \MediaWikiUnitTestCase {
	public function testAbstractAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( AbstractAuthenticationProvider::class );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		// test AbstractAuthenticationProvider::init
		$logger = $this->getMockForAbstractClass( LoggerInterface::class );
		$authManager = $this->createMock( AuthManager::class );
		$hookContainer = $this->createMock( HookContainer::class );
		$config = $this->getMockForAbstractClass( Config::class );
		$userNameUtils = $this->createNoOpMock( UserNameUtils::class );
		$provider->init( $logger, $authManager, $hookContainer, $config, $userNameUtils );
		$this->assertSame( $logger, $providerPriv->logger );
		$this->assertSame( $authManager, $providerPriv->manager );
		$this->assertSame( $hookContainer, $providerPriv->hookContainer );
		$this->assertSame( $config, $providerPriv->config );
		$this->assertSame( $userNameUtils, $providerPriv->userNameUtils );

		// test AbstractAuthenticationProvider::setLogger
		$obj = $this->getMockForAbstractClass( LoggerInterface::class );
		$provider->setLogger( $obj );
		$this->assertSame( $obj, $providerPriv->logger, 'setLogger' );

		// test AbstractAuthenticationProvider::setManager
		$obj = $this->createMock( AuthManager::class );
		$provider->setManager( $obj );
		$this->assertSame( $obj, $providerPriv->manager, 'setManager' );

		// test AbstractAuthenticationProvider::setConfig
		$obj = $this->getMockForAbstractClass( Config::class );
		$provider->setConfig( $obj );
		$this->assertSame( $obj, $providerPriv->config, 'setConfig' );

		// test AbstractAuthenticationProvider::getUniqueId
		$this->assertIsString( $provider->getUniqueId(), 'getUniqueId' );
	}
}
