<?php

namespace MediaWiki\Tests\User;

use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWikiIntegrationTestCase;
use stdClass;
use UserRightsProxy;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\MaintainableDBConnRef;

/**
 * @coversDefaultClass UserRightsProxy
 *
 * @author Zabe
 */
class UserRightsProxyTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgLocalDatabases' => [ 'foowiki' ],
		] );

		$dbMock = $this->createMock( MaintainableDBConnRef::class );

		$row = new stdClass;
		$row->user_name = 'UserRightsProxyTest';
		$row->user_id = 12345;
		$dbMock->method( 'selectRow' )->willReturn( $row );

		$lbMock = $this->createMock( ILoadBalancer::class );
		$lbMock->method( 'getMaintenanceConnectionRef' )->willReturn( $dbMock );

		$lbFactoryMock = $this->createMock( LBFactory::class );
		$lbFactoryMock->method( 'getMainLB' )->willReturn( $lbMock );

		$this->setService( 'DBLoadBalancerFactory', $lbFactoryMock );
	}

	/**
	 * @covers ::validDatabase
	 */
	public function testValidDatabase() {
		$this->assertTrue( UserRightsProxy::validDatabase( 'foowiki' ) );
		$this->assertFalse( UserRightsProxy::validDatabase( 'barwiki' ) );
	}

	/**
	 * @covers ::newFromId
	 * @covers ::newFromLookup
	 * @covers ::getId
	 */
	public function testNewFromId() {
		$id = 12345;
		$userRightsProxy = UserRightsProxy::newFromId( 'foowiki', $id );
		$this->assertInstanceOf( UserRightsProxy::class, $userRightsProxy );
		$this->assertSame( $id, $userRightsProxy->getId() );
	}

	/**
	 * @covers ::newFromName
	 * @covers ::newFromLookup
	 * @covers ::getName
	 */
	public function testNewFromName() {
		$name = 'UserRightsProxyTest';
		$userRightsProxy = UserRightsProxy::newFromName( 'foowiki', $name );
		$this->assertInstanceOf( UserRightsProxy::class, $userRightsProxy );
		$this->assertSame( $name . '@foowiki', $userRightsProxy->getName() );
	}

	/**
	 * @covers ::newFromName
	 * @covers ::newFromLookup
	 */
	public function testInvalidDB() {
		$userRightsProxy = UserRightsProxy::newFromName( 'barwiki', 'test' );
		$this->assertNull( $userRightsProxy );
	}

	/**
	 * @covers ::getUserPage
	 */
	public function testGetUserPage() {
		$userRightsProxy = UserRightsProxy::newFromName( 'foowiki', 'UserRightsProxyTest' );
		$this->assertSame(
			$userRightsProxy->getUserPage()->getLinkURL(),
			'/index.php/User:UserRightsProxyTest@foowiki'
		);
	}

	/**
	 * @covers ::addGroup
	 * @covers ::getGroupMemberships
	 * @covers ::getGroups
	 * @covers ::removeGroup
	 */
	public function testGroupMethods() {
		$userGroupManagerMock = $this->createMock( UserGroupManager::class );
		$userGroupManagerMock
			->expects( $this->exactly( 2 ) )
			->method( 'addUserToGroup' );
		$userGroupManagerMock
			->expects( $this->once() )
			->method( 'removeUserFromGroup' );
		$userGroupManagerMock
			->expects( $this->once() )
			->method( 'getUserGroupMemberships' )
			->willReturn( [ 'bot' => new stdClass, 'sysop' => new stdClass ] );
		$userGroupManagerFactoryMock = $this->createMock( UserGroupManagerFactory::class );
		$userGroupManagerFactoryMock
			->method( 'getUserGroupManager' )
			->willReturn( $userGroupManagerMock );
		$this->setService( 'UserGroupManagerFactory', $userGroupManagerFactoryMock );

		$userRightsProxy = UserRightsProxy::newFromName( 'foowiki', 'UserRightsProxyTest' );

		$userRightsProxy->addGroup( 'bot' );
		$expiryTime = wfTimestamp( TS_MW, time() + 100500 );
		$userRightsProxy->addGroup( 'sysop', $expiryTime );

		$groups = $userRightsProxy->getGroupMemberships();
		$this->assertArrayHasKey( 'bot', $groups );
		$this->assertArrayHasKey( 'sysop', $groups );

		$userRightsProxy->removeGroup( 'sysop' );

		$userGroupManagerMock2 = $this->createMock( UserGroupManager::class );
		$userGroupManagerMock2
			->expects( $this->exactly( 2 ) )
			->method( 'getUserGroupMemberships' )
			->willReturn( [ 'bot' => new stdClass ] );
		$userGroupManagerFactoryMock2 = $this->createMock( UserGroupManagerFactory::class );
		$userGroupManagerFactoryMock2
			->method( 'getUserGroupManager' )
			->willReturn( $userGroupManagerMock2 );
		$this->setService( 'UserGroupManagerFactory', $userGroupManagerFactoryMock2 );

		$userRightsProxy = UserRightsProxy::newFromName( 'foowiki', 'UserRightsProxyTest' );

		$this->assertArrayHasKey( 'bot', $userRightsProxy->getGroupMemberships() );
		$this->assertSame( [ 'bot' ], $userRightsProxy->getGroups() );
	}

	/**
	 * @covers ::setOption
	 * @covers ::saveSettings
	 * @covers ::invalidateCache
	 */
	public function testOptions() {
		$key = 'foo';
		$value = 'bar';

		$dbMock = $this->createMock( MaintainableDBConnRef::class );
		$row = new stdClass;
		$row->user_name = 'UserRightsProxyTest';
		$row->user_id = 12345;
		$dbMock->method( 'selectRow' )->willReturn( $row );
		$dbMock->method( 'timestamp' )->willReturn( 'timestamp' );
		$dbMock->method( 'getDomainID' )->willReturn( 'foowiki' );

		$dbMock->expects( $this->once() )
			->method( 'replace' )
			->with(
				'user_properties',
				[ [ 'up_user', 'up_property' ] ],
				[
					[
						'up_user' => 12345,
						'up_property' => $key,
						'up_value' => $value,
					]
				]
			);
		$dbMock->expects( $this->once() )
			->method( 'update' )
			->with(
				'user',
				[ 'user_touched' => 'timestamp' ],
				[ 'user_id' => 12345 ]
			);

		$dbMock->expects( $this->once() )->method( 'onTransactionPreCommitOrIdle' );

		$lbMock = $this->createMock( ILoadBalancer::class );
		$lbMock->method( 'getMaintenanceConnectionRef' )->willReturn( $dbMock );

		$lbFactoryMock = $this->createMock( LBFactory::class );
		$lbFactoryMock->method( 'getMainLB' )->willReturn( $lbMock );

		$this->setService( 'DBLoadBalancerFactory', $lbFactoryMock );
		$userRightsProxy = UserRightsProxy::newFromName( 'foowiki', 'UserRightsProxyTest' );

		$userRightsProxy->setOption( $key, $value );
		$userRightsProxy->saveSettings();
	}
}
