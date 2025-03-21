<?php

/**
 * TODO convert to a Unit test
 *
 * @covers \CustomUppercaseCollation
 */
class CustomUppercaseCollationTest extends MediaWikiIntegrationTestCase {

	/** @var CustomUppercaseCollation */
	private $collation;

	protected function setUp(): void {
		parent::setUp();
		$this->collation = new CustomUppercaseCollation(
			$this->getServiceContainer()->getLanguageFactory(),
			[
				'D',
				'C',
				'Cs',
				[ 'V', 'W' ],
				'B',
			],
			'en' // digital transformation language
		);
	}

	/**
	 * @dataProvider providerOrder
	 */
	public function testOrder( $first, $second, $msg ) {
		$sortkey1 = $this->collation->getSortKey( $first );
		$sortkey2 = $this->collation->getSortKey( $second );

		$this->assertTrue( strcmp( $sortkey1, $sortkey2 ) < 0, $msg );
	}

	public static function providerOrder() {
		return [
			[ 'X', 'Z', 'Maintain order of unrearranged' ],
			[ 'D', 'C', 'Actually resorts' ],
			[ 'D', 'B', 'resort test 2' ],
			[ 'Adobe', 'Abode', 'not first letter' ],
			[ '💩 ', 'C', 'Test relocated to end' ],
			[ 'c', 'b', 'lowercase' ],
			[ 'x', 'z', 'lowercase original' ],
			[ 'Cz', 'Cs', 'digraphs' ],
			[ 'C50D', 'C100', 'Numbers' ],
			[ 'V', 'b', 'Equal weight groups' ],
			[ 'W', 'b', 'Equal weight groups' ],
		];
	}

	/**
	 * @dataProvider provideGetFirstLetter
	 */
	public function testGetFirstLetter( $string, $first ) {
		$this->assertSame( $this->collation->getFirstLetter( $string ), $first );
	}

	public static function provideGetFirstLetter() {
		return [
			[ 'Do', 'D' ],
			[ 'do', 'D' ],
			[ 'Ao', 'A' ],
			[ 'afdsa', 'A' ],
			[ "\u{F3000}Foo", 'D' ],
			[ "\u{F3001}Foo", 'C' ],
			[ "\u{F3002}Foo", 'Cs' ],
			[ "\u{F3004}Foo", 'B' ],
			[ "\u{F3005}Foo", "\u{F3005}" ],
			[ 'C', 'C' ],
			[ 'Cz', 'C' ],
			[ 'Cs', 'Cs' ],
			[ 'CS', 'Cs' ],
			[ 'cs', 'Cs' ],
			[ 'V', 'V' ],
			[ 'v', 'V' ],
			[ 'w', 'V' ],
			[ 'W', 'V' ],
		];
	}
}
