<?php

use MediaWiki\Config\SiteConfiguration;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Site\TestSites;
use MediaWiki\WikiMap\WikiMap;
use MediaWiki\WikiMap\WikiReference;
use Wikimedia\Rdbms\DatabaseDomain;

/**
 * @covers \MediaWiki\WikiMap\WikiMap
 *
 * @group Database
 */
class WikiMapTest extends MediaWikiLangTestCase {

	protected function setUp(): void {
		parent::setUp();

		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'enwiki' => 'http://en.example.org',
				'ruwiki' => '//ru.example.org',
				'nopathwiki' => '//nopath.example.org',
				'thiswiki' => '//this.wiki.org'
			],
			'wgArticlePath' => [
				'enwiki' => '/w/$1',
				'ruwiki' => '/wiki/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];
		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::LocalDatabases => [ 'enwiki', 'ruwiki', 'nopathwiki' ],
			MainConfigNames::CanonicalServer => '//this.wiki.org',
			MainConfigNames::DBname => 'thiswiki',
			MainConfigNames::DBprefix => ''
		] );

		TestSites::insertIntoDb();
	}

	public static function provideGetWiki() {
		// As provided by $wgConf
		$enwiki = new WikiReference( 'http://en.example.org', '/w/$1' );
		$ruwiki = new WikiReference( '//ru.example.org', '/wiki/$1' );

		// Created from site objects
		$nlwiki = new WikiReference( 'https://nl.wikipedia.org', '/wiki/$1' );
		// enwiktionary doesn't have an interwiki id, thus this falls back to minor = lang code
		$enwiktionary = new WikiReference( 'https://en.wiktionary.org', '/wiki/$1' );

		return [
			'unknown' => [ null, 'xyzzy' ],
			'enwiki (wgConf)' => [ $enwiki, 'enwiki' ],
			'ruwiki (wgConf)' => [ $ruwiki, 'ruwiki' ],
			'nlwiki (sites)' => [ $nlwiki, 'nlwiki', false ],
			'enwiktionary (sites)' => [ $enwiktionary, 'enwiktionary', false ],
			'non MediaWiki site' => [ null, 'spam', false ],
			'boguswiki' => [ null, 'boguswiki' ],
			'nopathwiki' => [ null, 'nopathwiki' ],
		];
	}

	/**
	 * @dataProvider provideGetWiki
	 */
	public function testGetWiki( $expected, $wikiId, $useWgConf = true ) {
		if ( !$useWgConf ) {
			$this->setMwGlobals( [
				'wgConf' => new SiteConfiguration(),
			] );
		}

		$this->assertEquals( $expected, WikiMap::getWiki( $wikiId ) );
	}

	public static function provideGetWikiName() {
		return [
			'unknown' => [ 'xyzzy', 'xyzzy' ],
			'enwiki' => [ 'en.example.org', 'enwiki' ],
			'ruwiki' => [ 'ru.example.org', 'ruwiki' ],
			'enwiktionary (sites)' => [ 'en.wiktionary.org', 'enwiktionary' ],
		];
	}

	/**
	 * @dataProvider provideGetWikiName
	 */
	public function testGetWikiName( $expected, $wikiId ) {
		$this->assertEquals( $expected, WikiMap::getWikiName( $wikiId ) );
	}

	public static function provideMakeForeignLink() {
		return [
			'unknown' => [ false, 'xyzzy', 'Foo' ],
			'enwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="http://en.example.org/w/Foo">Foo</a>',
				'enwiki',
				'Foo'
			],
			'ruwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="//ru.example.org/wiki/%D0%A4%D1%83">вар</a>',
				'ruwiki',
				'Фу',
				'вар'
			],
			'enwiktionary (sites)' => [
				'<a class="external" rel="nofollow" ' .
					'href="https://en.wiktionary.org/wiki/Kitten">Kittens!</a>',
				'enwiktionary',
				'Kitten',
				'Kittens!'
			],
		];
	}

	/**
	 * @dataProvider provideMakeForeignLink
	 */
	public function testMakeForeignLink( $expected, $wikiId, $page, $text = null ) {
		$this->assertEquals(
			$expected,
			WikiMap::makeForeignLink( $wikiId, $page, $text )
		);
	}

	public static function provideForeignUserLink() {
		return [
			'unknown' => [ false, 'xyzzy', 'Foo' ],
			'enwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="http://en.example.org/w/User:Foo">User:Foo</a>',
				'enwiki',
				'Foo'
			],
			'ruwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="//ru.example.org/wiki/User:%D0%A4%D1%83">вар</a>',
				'ruwiki',
				'Фу',
				'вар'
			],
			'enwiktionary (sites)' => [
				'<a class="external" rel="nofollow" ' .
					'href="https://en.wiktionary.org/wiki/User:Dummy">Whatever</a>',
				'enwiktionary',
				'Dummy',
				'Whatever'
			],
		];
	}

	/**
	 * @dataProvider provideForeignUserLink
	 */
	public function testForeignUserLink( $expected, $wikiId, $user, $text = null ) {
		$this->assertEquals( $expected, WikiMap::foreignUserLink( $wikiId, $user, $text ) );
	}

	public static function provideGetForeignURL() {
		return [
			'unknown' => [ false, 'xyzzy', 'Foo' ],
			'enwiki' => [ 'http://en.example.org/w/Foo', 'enwiki', 'Foo' ],
			'enwiktionary (sites)' => [
				'https://en.wiktionary.org/wiki/Testme',
				'enwiktionary',
				'Testme'
			],
			'ruwiki with fragment' => [
				'//ru.example.org/wiki/%D0%A4%D1%83#%D0%B2%D0%B0%D1%80',
				'ruwiki',
				'Фу',
				'вар'
			],
		];
	}

	/**
	 * @dataProvider provideGetForeignURL
	 */
	public function testGetForeignURL( $expected, $wikiId, $page, $fragment = null ) {
		$this->assertEquals( $expected, WikiMap::getForeignURL( $wikiId, $page, $fragment ) );
	}

	/**
	 * @covers \MediaWiki\WikiMap\WikiMap::getCanonicalServerInfoForAllWikis()
	 */
	public function testGetCanonicalServerInfoForAllWikis() {
		$expected = [
			'thiswiki' => [
				'url' => '//this.wiki.org',
				'parts' => [ 'scheme' => '', 'host' => 'this.wiki.org', 'delimiter' => '//' ]
			],
			'enwiki' => [
				'url' => 'http://en.example.org',
				'parts' => [
					'scheme' => 'http', 'host' => 'en.example.org', 'delimiter' => '://' ]
			],
			'ruwiki' => [
				'url' => '//ru.example.org',
				'parts' => [ 'scheme' => '', 'host' => 'ru.example.org', 'delimiter' => '//' ]
			]
		];

		$this->assertArrayEquals(
			$expected,
			WikiMap::getCanonicalServerInfoForAllWikis(),
			true,
			true
		);
	}

	public static function provideGetWikiFromUrl() {
		return [
			[ 'http://this.wiki.org', 'thiswiki' ],
			[ 'https://this.wiki.org', 'thiswiki' ],
			[ '//this.wiki.org', 'thiswiki' ],
			[ 'http://this.wiki.org/$1', 'thiswiki' ],
			[ 'https://this.wiki.org/$2', 'thiswiki' ],
			[ 'http://en.example.org', 'enwiki' ],
			[ 'https://en.example.org', 'enwiki' ],
			[ 'http://en.example.org/$1', 'enwiki' ],
			[ 'https://en.example.org/$2', 'enwiki' ],
			[ 'http://ru.example.org', 'ruwiki' ],
			[ 'https://ru.example.org', 'ruwiki' ],
			[ 'http://ru.example.org/$1', 'ruwiki' ],
			[ 'https://ru.example.org/$2', 'ruwiki' ],
			[ 'http://not.defined.org', false ],
			[ 'ftp://this.wiki.org', false ],
		];
	}

	/**
	 * @dataProvider provideGetWikiFromUrl
	 * @covers \MediaWiki\WikiMap\WikiMap::getWikiFromUrl()
	 */
	public function testGetWikiFromUrl( $url, $wiki ) {
		$this->assertEquals( $wiki, WikiMap::getWikiFromUrl( $url ) );
	}

	public static function provideGetWikiIdFromDbDomain() {
		return [
			[ 'db-prefix_', 'db-prefix_' ],
			[ WikiMap::getCurrentWikiId(), WikiMap::getCurrentWikiId() ],
			[ new DatabaseDomain( 'db-dash', null, 'prefix_' ), 'db-dash-prefix_' ],
			[ WikiMap::getCurrentWikiId(), WikiMap::getCurrentWikiId() ],
			[ new DatabaseDomain( 'db-dash', null, 'prefix_' ), 'db-dash-prefix_' ],
			[ new DatabaseDomain( 'db', 'mediawiki', 'prefix_' ), 'db-prefix_' ], // schema ignored
			[ new DatabaseDomain( 'db', 'custom', 'prefix_' ), 'db-custom-prefix_' ],
		];
	}

	/**
	 * @dataProvider provideGetWikiIdFromDbDomain
	 * @covers \MediaWiki\WikiMap\WikiMap::getWikiIdFromDbDomain()
	 */
	public function testGetWikiIdFromDbDomain( $domain, $wikiId ) {
		$this->assertEquals( $wikiId, WikiMap::getWikiIdFromDbDomain( $domain ) );
	}

	/**
	 * @covers \MediaWiki\WikiMap\WikiMap::isCurrentWikiDbDomain()
	 * @covers \MediaWiki\WikiMap\WikiMap::getCurrentWikiDbDomain()
	 */
	public function testIsCurrentWikiDomain() {
		$this->overrideConfigValue( MainConfigNames::DBmwschema, 'mediawiki' );

		$localDomain = WikiMap::getCurrentWikiDbDomain()->getId();
		$this->assertTrue( WikiMap::isCurrentWikiDbDomain( $localDomain ) );

		$localDomain = DatabaseDomain::newFromId( $localDomain );
		$domain1 = new DatabaseDomain(
			$localDomain->getDatabase(), 'someschema', $localDomain->getTablePrefix() );
		$domain2 = new DatabaseDomain(
			$localDomain->getDatabase(), null, $localDomain->getTablePrefix() );

		$this->assertFalse( WikiMap::isCurrentWikiDbDomain( $domain1 ), 'Schema not ignored' );
		$this->assertFalse( WikiMap::isCurrentWikiDbDomain( $domain2 ), 'Null schema not ignored' );

		$this->assertTrue( WikiMap::isCurrentWikiDbDomain( WikiMap::getCurrentWikiDbDomain() ) );
	}

	public static function provideIsCurrentWikiId() {
		return [
			[ 'db', 'db', null, '' ],
			[ 'db-schema-', 'db', 'schema', '' ],
			[ 'db', 'db', 'mediawiki', '' ], // common b/c case
			[ 'db-prefix_', 'db', null, 'prefix_' ],
			[ 'db-schema-prefix_', 'db', 'schema', 'prefix_' ],
			[ 'db-prefix_', 'db', 'mediawiki', 'prefix_' ], // common b/c case
			// Bad hyphen cases (best effort support)
			[ 'db-stuff', 'db-stuff', null, '' ],
			[ 'db-stuff-prefix_', 'db-stuff', null, 'prefix_' ],
			[ 'db-stuff-schema-', 'db-stuff', 'schema', '' ],
			[ 'db-stuff-schema-prefix_', 'db-stuff', 'schema', 'prefix_' ],
			[ 'db-stuff-prefix_', 'db-stuff', 'mediawiki', 'prefix_' ] // common b/c case
		];
	}

	/**
	 * @dataProvider provideIsCurrentWikiId
	 * @covers \MediaWiki\WikiMap\WikiMap::isCurrentWikiId()
	 * @covers \MediaWiki\WikiMap\WikiMap::getCurrentWikiDbDomain()
	 * @covers \MediaWiki\WikiMap\WikiMap::getWikiIdFromDbDomain()
	 */
	public function testIsCurrentWikiId( $wikiId, $db, $schema, $prefix ) {
		$this->overrideConfigValues( [
			MainConfigNames::DBname => $db,
			MainConfigNames::DBmwschema => $schema,
			MainConfigNames::DBprefix => $prefix
		] );

		$this->assertTrue( WikiMap::isCurrentWikiId( $wikiId ), "ID matches" );
		$this->assertNotTrue( WikiMap::isCurrentWikiId( $wikiId . '-more' ), "Bogus ID" );
	}
}
