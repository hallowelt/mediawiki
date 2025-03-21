<?php

namespace Wikimedia\Tests\Message;

use MediaWiki\Json\JsonCodec;
use Wikimedia\Tests\SerializationTestTrait;

trait MessageSerializationTestTrait {
	use SerializationTestTrait;

	public static function getSerializedDataPath(): string {
		return __DIR__ . '/../../../../data/MessageValue';
	}

	public static function getTestInstancesAndAssertions(): array {
		$className = self::getClassToTest();
		return array_map( static function ( $test ) use ( $className ) {
			[ $args, $expected ] = $test;
			$obj = new $className( ...$args );
			return [
				'instance' => $obj,
				'assertions' => static function ( $testCase, $obj ) use ( $expected ) {
					$testCase->assertSame( $expected, $obj->dump() );
				},
			];
		}, self::provideConstruct() );
	}

	public static function getSupportedSerializationFormats(): array {
		$jsonCodec = new JsonCodec();
		return [ [
			'ext' => 'json',
			'serializer' => static function ( $obj ) use ( $jsonCodec ) {
				return $jsonCodec->serialize( $obj );
			},
			'deserializer' => static function ( $data ) use ( $jsonCodec ) {
				return $jsonCodec->deserialize( $data );
			},
		] ];
	}
}
