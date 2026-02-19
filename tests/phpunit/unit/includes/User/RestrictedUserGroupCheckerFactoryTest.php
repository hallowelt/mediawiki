<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Config\SiteConfiguration;
use MediaWiki\MainConfigNames;
use MediaWiki\User\RestrictedUserGroupCheckerFactory;
use MediaWiki\User\UserRequirementsConditionChecker;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\RestrictedUserGroupCheckerFactory
 */
class RestrictedUserGroupCheckerFactoryTest extends MediaWikiUnitTestCase {

	private function createFactory(): RestrictedUserGroupCheckerFactory {
		$options = new ServiceOptions(
			RestrictedUserGroupCheckerFactory::CONSTRUCTOR_OPTIONS,
			[
				MainConfigNames::RestrictedGroups => [
					'interface-admin' => [],
				],
			]
		);
		$conditionChecker = $this->createStub( UserRequirementsConditionChecker::class );

		return new RestrictedUserGroupCheckerFactory( $options, $conditionChecker );
	}

	public function testLocalCheckerIsCreatedWithOptions() {
		$factory = $this->createFactory();
		$checker = $factory->getRestrictedUserGroupChecker();

		$this->assertTrue( $checker->isGroupRestricted( 'interface-admin' ) );
	}

	public function testRemoteCheckerIsCreated() {
		$mockSiteConfiguration = $this->createMock( SiteConfiguration::class );
		$mockSiteConfiguration->method( 'get' )
			->willReturnCallback( static function ( $setting, $wiki ) {
				if ( $setting !== 'wgRestrictedGroups' ) {
					return null;
				}

				$settingsPerWiki = [
					WikiMap::getCurrentWikiId() => [ 'interface-admin' => [] ],
					'wiki1' => [ 'sysop' => [] ],
					'wiki2' => [ 'bureaucrat' => [] ],
				];

				if ( isset( $settingsPerWiki[$wiki] ) ) {
					return $settingsPerWiki[$wiki];
				}
				return null;
			} );

		global $wgConf;
		$wgConf = $mockSiteConfiguration;

		$factory = $this->createFactory();
		$checker = $factory->getRestrictedUserGroupChecker( 'wiki1' );

		$this->assertFalse( $checker->isGroupRestricted( 'interface-admin' ) );
		$this->assertTrue( $checker->isGroupRestricted( 'sysop' ) );
		$this->assertFalse( $checker->isGroupRestricted( 'bureaucrat' ) );
	}
}
