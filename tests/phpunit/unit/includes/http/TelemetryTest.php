<?php

namespace MediaWiki\Tests\Unit\Http;

use MediaWiki\Http\Telemetry;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Http\Telemetry
 */
class TelemetryTest extends MediaWikiUnitTestCase {

	public function testItOverridesTheRequestIdWhenAsked() {
		$newId = 'new_id';
		$sut = new Telemetry( [ 'UNIQUE_ID' => 'test' ], false );
		$sut->overrideRequestId( $newId );
		$this->assertEquals( $newId, $sut->getRequestId() );
	}

	public function testItReturnsOpenTelemetryProperties() {
		$sut = new Telemetry( [
			'HTTP_TRACESTATE' => 'val1',
			'HTTP_TRACEPARENT' => 'val2'
		], true );

		$this->assertEquals( 'val1', $sut->getTracestate() );
		$this->assertEquals( 'val2', $sut->getTraceparent() );
	}

	public function testOpenTelemetryPropertiesAreSkippedWhenAllowExternalReqIDIsSetToFalse() {
		$sut = new Telemetry( [
			'HTTP_TRACESTATE' => 'val1',
			'HTTP_TRACEPARENT' => 'val2'
		], false );

		$this->assertNull( $sut->getTracestate() );
		$this->assertNull( $sut->getTraceparent() );
	}

	/**
	 * @dataProvider provideRequestHeaders
	 */
	public function testItHandlesRequestIdHeaders( $allowExternalReqId, $server, $expected ) {
		$sut = new Telemetry( $server, $allowExternalReqId );

		$this->assertEquals( $expected, $sut->getRequestId() );
	}

	public function testItGeneratesRequestIdWhenHeadersNotPresentAndExternalReqIdIsSet() {
		$sut = new Telemetry( [], true );
		$this->assertNotEmpty( $sut->getRequestId() );
	}

	public function testItGeneratesRequestIdWhenHeadersNotPresentAndExternalReqIdIsNotSet() {
		$sut = new Telemetry( [], false );
		$this->assertNotEmpty( $sut->getRequestId() );
	}

	public static function provideRequestHeaders() {
		yield [
			false,
			[ 'UNIQUE_ID' => 'unique_hash' ],
			'unique_hash'
		];
		yield [
			false,
			[
				'HTTP_X_REQUEST_ID' => 'request_id',
				'UNIQUE_ID' => 'unique_hash'
			],
			'unique_hash'
		];
		yield [
			true,
			[
				'HTTP_X_REQUEST_ID' => 'request_id',
				'UNIQUE_ID' => 'unique_hash'
			],
			'request_id'
		];
		yield [
			true,
			[
				'UNIQUE_ID' => 'unique_hash'
			],
			'unique_hash'
		];
	}

}
