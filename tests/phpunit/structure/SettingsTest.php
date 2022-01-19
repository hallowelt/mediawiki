<?php

namespace MediaWiki\Tests\Structure;

use ExtensionRegistry;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\PhpIniSink;
use MediaWiki\Settings\SettingsBuilder;
use MediaWikiIntegrationTestCase;

/**
 * @coversNothing
 */
class SettingsTest extends MediaWikiIntegrationTestCase {

	public function testConfigSchemaIsLoadable() {
		$configBuilder = new ArrayConfigBuilder();
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			$configBuilder,
			$this->createNoOpMock( PhpIniSink::class )
		);
		$settingsBuilder->loadFile( 'includes/config-schema.yaml' );
		$settingsBuilder->apply();

		// Assert we've read some random config value
		$this->assertTrue( $configBuilder->build()->has( 'Server' ) );
	}

	/**
	 * Check that core default settings validate against the schema
	 */
	public function testConfigSchemaDefaultsValidate() {
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			new ArrayConfigBuilder(),
			$this->createNoOpMock( PhpIniSink::class )
		);
		$result = $settingsBuilder->loadFile( 'includes/config-schema.yaml' )
			->apply()
			->validate();
		$this->assertArrayEquals( [], $result->getErrors() );
	}

	/**
	 * Check that currently loaded settings validate against the schema.
	 */
	public function testCurrentSettingsValidate() {
		global $wgSettings;
		$result = $wgSettings->validate();
		$this->assertTrue( $result->isGood(), $result->__toString() );
	}

	/**
	 * Check that the result of loading config-schema.yaml is the same as DefaultSettings.php
	 * This test can be removed when DefaultSettings.php is removed.
	 */
	public function testDefaultSettingsConsistency() {
		$defaultSettingsProps = ( static function () {
			$IP = 'PLACEHOLDER_IP!';
			require __DIR__ . '/../../../includes/DefaultSettings.php';
			$vars = get_defined_vars();
			unset( $vars['input'] );
			unset( $vars['IP'] );
			$result = [];
			foreach ( $vars as $key => $value ) {
				$result[substr( $key, 2 )] = $value;
			}
			return $result;
		} )();

		$configBuilder = new ArrayConfigBuilder();
		$settingsBuilder = new SettingsBuilder(
			__DIR__ . '/../../..',
			$this->createNoOpMock( ExtensionRegistry::class ),
			$configBuilder,
			$this->createNoOpMock( PhpIniSink::class )
		);
		$settingsBuilder->loadFile( 'includes/config-schema.yaml' );
		$settingsBuilder->apply();

		foreach ( $defaultSettingsProps as $key => $value ) {
			if ( in_array( $key, [
				'Version', // deprecated alias to MW_VERSION
				'Conf', // instance of SiteConfiguration
				'AutoloadClasses', // conditionally initialized
				'StyleSheetPath', // Alias to another global
				'ExtensionDirectory', // Depends on $IP
				'StyleDirectory', // Depends on $IP
				'ServiceWiringFiles', // Depends on __DIR__
				'HTTPMaxTimeout', // Infinity default
				'HTTPMaxConnectTimeout', // Infinity default
			] ) ) {
				continue;
			}
			$this->assertTrue( $configBuilder->build()->has( $key ), "Missing $key" );
			$this->assertEquals( $value, $configBuilder->build()->get( $key ), "Wrong value for $key\n" );
		}
	}
}
