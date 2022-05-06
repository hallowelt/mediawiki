<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use HashConfig;
use InvalidArgumentException;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\MergeStrategy;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\ConfigSchemaAggregator
 */
class ConfigSchemaAggregatorTest extends TestCase {

	public function testAddSchema() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'foo', [ 'type' => 'string', ] );
		$aggregator->addSchema( 'bar', [ 'type' => 'number', ] );
		$this->assertTrue( $aggregator->hasSchemaFor( 'foo' ) );
		$this->assertFalse( $aggregator->hasSchemaFor( 'xyzzy' ) );
		$this->assertSame( [ 'type' => 'number', ], $aggregator->getSchemaFor( 'bar' ) );
	}

	public function testAddSchemaOverrideFails() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'foo', [ 'type' => 'string', ] );
		$this->expectException( SettingsBuilderException::class );
		$aggregator->addSchema( 'foo', [ 'type' => 'string', ] );
	}

	public function testSetAndGetDefaults() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'no_default', [ 'type' => 'string', ] );
		$aggregator->addSchema( 'with_default', [ 'type' => 'string', 'default' => 'bla', ] );
		$aggregator->addDefaults( [ 'another_with_default' => 'blabla' ] );
		$this->assertFalse( $aggregator->hasDefaultFor( 'no_default' ) );
		$this->assertTrue( $aggregator->hasDefaultFor( 'with_default' ) );
		$this->assertTrue( $aggregator->hasDefaultFor( 'another_with_default' ) );
		$this->assertSame( 'bla', $aggregator->getDefaultFor( 'with_default' ) );
		$this->assertSame( [ 'default' => 'blabla' ], $aggregator->getSchemaFor( 'another_with_default' ) );
		$this->assertEquals( [
			'with_default' => 'bla',
			'another_with_default' => 'blabla',
		], $aggregator->getDefaults() );
	}

	public function testDefaultOverrideFails() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'foo', [ 'default' => 'bla', ] );
		$this->expectException( SettingsBuilderException::class );
		$aggregator->addDefaults( [ 'foo' => 'xyz', ] );
	}

	public function testSetAndGetTypes() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'no_type', [ 'default' => 'xyz', ] );
		$aggregator->addSchema( 'with_type', [ 'type' => 'string', 'default' => 'bla', ] );
		$aggregator->addTypes( [ 'another_with_type' => 'number' ] );
		$this->assertSame( [ 'type' => 'number' ], $aggregator->getSchemaFor( 'another_with_type' ) );
		$this->assertEquals( [
			'with_type' => 'string',
			'another_with_type' => 'number',
		], $aggregator->getTypes() );
	}

	public function testTypeOverrideFails() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'foo', [ 'type' => 'string', ] );
		$this->expectException( SettingsBuilderException::class );
		$aggregator->addTypes( [ 'foo' => 'number', ] );
	}

	public function testSetAndGetMergeStrategies() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'no_mergeStrategy', [ 'default' => 'xyz', ] );
		$aggregator->addSchema( 'with_mergeStrategy',
			[ 'mergeStrategy' => 'array_plus', 'default' => 'bla', ] );
		$aggregator->addMergeStrategies( [ 'another_with_mergeStrategy' => 'array_merge' ] );
		$this->assertSame(
			[ 'mergeStrategy' => 'array_merge' ],
			$aggregator->getSchemaFor( 'another_with_mergeStrategy' )
		);
		$this->assertEquals( [
			'with_mergeStrategy' => 'array_plus',
			'another_with_mergeStrategy' => 'array_merge',
		], $aggregator->getMergeStrategies() );
	}

	public function testMergeStrategieOverrideFails() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchema( 'foo', [ 'mergeStrategy' => 'array_merge', ] );
		$this->expectException( SettingsBuilderException::class );
		$aggregator->addMergeStrategies( [ 'foo' => 'array_plus', ] );
	}

	public function provideGetMergeStrategiesFor() {
		yield 'no schema' => [ null, null ];
		yield 'no strategy' => [ [ 'default' => '' ], null ];
		yield 'with strategy' => [ [ 'mergeStrategy' => 'array_merge' ], 'array_merge' ];

		yield 'with strategy and type=array' => [
			[
				'type' => 'array',
				'mergeStrategy' => 'replace'
			],
			'replace'
		];

		yield 'without strategy and type=array' => [
			[ 'type' => 'array' ],
			'array_merge'
		];

		yield 'with strategy and type=object' => [
			[
				'type' => 'object',
				'mergeStrategy' => 'array_plus_2d'
			],
			'array_plus_2d'
		];

		yield 'without strategy and type=object' => [
			[ 'type' => 'object' ],
			'array_plus'
		];
	}

	/**
	 * @dataProvider provideGetMergeStrategiesFor
	 */
	public function testGetMergeStrategyFor( $schema, $expected ) {
		$aggregator = new ConfigSchemaAggregator();

		if ( $schema ) {
			$aggregator->addSchema( 'test', $schema );
		}

		$strategy = $aggregator->getMergeStrategyFor( 'test' );
		$this->assertSame(
			$expected,
			$strategy ? $strategy->getName() : null
		);
	}

	public function provideValidate() {
		yield 'invalid config' => [
			'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
			'config' => [ 'foo' => 1 ],
			'valid' => false,
		];
		yield 'all good' => [
			'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
			'config' => [ 'foo' => 'bar', ],
			'valid' => true,
		];
		yield 'missing key' => [
			'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
			'config' => [ 'bar' => 'bar' ],
			'valid' => false,
		];
		yield 'no schema was added' => [
			'config-schema' => [],
			'config' => [ 'foo' => 'bar', ],
			'valid' => true,
		];
		yield 'key is in config but has no schema' => [
			'config-schema' => [ 'foo' => [ 'type' => 'array', 'mergeStrategy' => MergeStrategy::ARRAY_MERGE ], ],
			'config' => [ 'foo' => [], 'baz' => false, ],
			'valid' => true,
		];
		yield 'assoc array where list is expected' => [
			'config-schema' => [ 'foo' => [ 'type' => 'array', ], ],
			'config' => [ 'foo' => [ 'x' => 1 ] ],
			'valid' => false,
		];
		yield 'map with numeric keys' => [
			'config-schema' => [ 'foo' => [ 'type' => 'object', ], ],
			'config' => [ 'foo' => [ 0 => 'x', 1 => 'y' ] ],
			'valid' => true,
		];
		yield 'array with ignoreKeys' => [
			'config-schema' => [ 'foo' => [ 'type' => 'array', 'ignoreKeys' => true ], ],
			'config' => [ 'foo' => [ 'x' => 'bla', 'blabla' ] ],
			'valid' => true,
		];
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidateConfig( array $schemas, array $config, bool $valid ) {
		$aggregator = $this->newConfigSchemaAggregator( $schemas );
		$status = $aggregator->validateConfig( new HashConfig( $config ) );
		$this->assertSame( $valid, $status->isOK() );
	}

	public function testValidateValue() {
		$aggregator = $this->newConfigSchemaAggregator( [ 'foo' => [ 'type' => 'integer' ] ] );
		$this->assertTrue( $aggregator->validateValue( 'foo', 1 )->isOK() );
		$this->assertFalse( $aggregator->validateValue( 'foo', 'bar' )->isOK() );
	}

	public function testValidateInvalidSchema() {
		$this->expectException( InvalidArgumentException::class );
		$aggregator = $this->newConfigSchemaAggregator( [ 'foo' => [ 'type' => 1 ] ] );
		$aggregator->validateConfig( new HashConfig( [ 'foo' => 'bar' ] ) );
	}

	private function newConfigSchemaAggregator( array $schemas = [] ) {
		$aggregator = new ConfigSchemaAggregator();

		foreach ( $schemas as $key => $sch ) {
			$aggregator->addSchema( $key, $sch );
		}

		return $aggregator;
	}
}
