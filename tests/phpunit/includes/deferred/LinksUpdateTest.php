<?php

use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Page\PageIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers LinksUpdate
 * @covers \MediaWiki\Deferred\LinksUpdate\CategoryLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\ExternalLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\GenericPageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\ImageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\InterwikiLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LangLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\LinksTableGroup
 * @covers \MediaWiki\Deferred\LinksUpdate\PageLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\PagePropsTable
 * @covers \MediaWiki\Deferred\LinksUpdate\TemplateLinksTable
 * @covers \MediaWiki\Deferred\LinksUpdate\TitleLinksTable
 *
 * @group LinksUpdate
 * @group Database
 * ^--- make sure temporary tables are used.
 */
class LinksUpdateTest extends MediaWikiLangTestCase {
	protected static $testingPageId;

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = array_merge( $this->tablesUsed,
			[
				'interwiki',
				'page_props',
				'pagelinks',
				'categorylinks',
				'langlinks',
				'externallinks',
				'imagelinks',
				'templatelinks',
				'iwlinks',
				'recentchanges',
			]
		);

		$dbw = wfGetDB( DB_PRIMARY );
		$dbw->replace(
			'interwiki',
			'iw_prefix',
			[
				'iw_prefix' => 'linksupdatetest',
				'iw_url' => 'http://testing.com/wiki/$1',
				'iw_api' => 'http://testing.com/w/api.php',
				'iw_local' => 0,
				'iw_trans' => 0,
				'iw_wikiid' => 'linksupdatetest',
			]
		);
		$this->setMwGlobals( 'wgRCWatchCategoryMembership', true );
	}

	public function addDBDataOnce() {
		$res = $this->insertPage( 'Testing' );
		self::$testingPageId = $res['id'];
		$this->insertPage( 'Some_other_page' );
		$this->insertPage( 'Template:TestingTemplate' );
	}

	protected function makeTitleAndParserOutput( $name, $id ) {
		// Force the value returned by getArticleID, even is
		// READ_LATEST is passed.

		/** @var Title|MockObject $t */
		$t = $this->getMockBuilder( Title::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getArticleID' ] )
			->getMock();
		$t->method( 'getArticleID' )->willReturn( $id );

		$tAccess = TestingAccessWrapper::newFromObject( $t );
		$tAccess->secureAndSplit( $name );

		$po = new ParserOutput();
		$po->setTitleText( $name );

		return [ $t, $po ];
	}

	/**
	 * @covers ParserOutput::addLink
	 */
	public function testUpdate_pagelinks() {
		/** @var Title $t */
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLink( Title::newFromText( "Foo" ) );
		$po->addLink( Title::newFromText( "Bar" ) );
		$po->addLink( Title::newFromText( "Special:Foo" ) ); // special namespace should be ignored
		$po->addLink( Title::newFromText( "linksupdatetest:Foo" ) ); // interwiki link should be ignored
		$po->addLink( Title::newFromText( "#Foo" ) ); // hash link should be ignored

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			[ 'pl_namespace', 'pl_title' ],
			'pl_from = ' . self::$testingPageId,
			[
				[ NS_MAIN, 'Bar' ],
				[ NS_MAIN, 'Foo' ],
			]
		);
		$this->assertArrayEquals( [
			Title::makeTitle( NS_MAIN, 'Foo' ),  // newFromText doesn't yield the same internal state....
			Title::makeTitle( NS_MAIN, 'Bar' ),
		], $update->getAddedLinks() );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );

		$po->addLink( Title::newFromText( "Bar" ) );
		$po->addLink( Title::newFromText( "Baz" ) );
		$po->addLink( Title::newFromText( "Talk:Baz" ) );

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			[ 'pl_namespace', 'pl_title' ],
			'pl_from = ' . self::$testingPageId,
			[
				[ NS_MAIN, 'Bar' ],
				[ NS_MAIN, 'Baz' ],
				[ NS_TALK, 'Baz' ],
			]
		);
		$this->assertArrayEquals( [
			Title::makeTitle( NS_MAIN, 'Baz' ),
			Title::makeTitle( NS_TALK, 'Baz' ),
		], $update->getAddedLinks() );
		$this->assertArrayEquals( [
			Title::makeTitle( NS_MAIN, 'Foo' ),
		], $update->getRemovedLinks() );
	}

	public function testUpdate_pagelinks_move() {
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLink( Title::newFromText( "Foo" ) );
		$this->assertLinksUpdate(
			$t,
			$po,
			'pagelinks',
			[ 'pl_namespace', 'pl_title', 'pl_from_namespace' ],
			'pl_from = ' . self::$testingPageId,
			[
				[ NS_MAIN, 'Foo', NS_MAIN ],
			]
		);

		list( $t, $po ) = $this->makeTitleAndParserOutput( "User:Testing", self::$testingPageId );
		$po->addLink( Title::newFromText( "Foo" ) );
		$this->assertMoveLinksUpdate(
			$t,
			new PageIdentityValue( 2, 0, "Foo", false ),
			$po,
			'pagelinks',
			[ 'pl_namespace', 'pl_title', 'pl_from_namespace' ],
			'pl_from = ' . self::$testingPageId,
			[
				[ NS_MAIN, 'Foo', NS_USER ],
			]
		);
	}

	/**
	 * @covers ParserOutput::addExternalLink
	 * @covers LinksUpdate::getAddedExternalLinks
	 * @covers LinksUpdate::getRemovedExternalLinks
	 */
	public function testUpdate_externallinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addExternalLink( "http://testing.com/wiki/Foo" );
		$po->addExternalLink( "http://testing.com/wiki/Bar" );

		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'externallinks',
			[ 'el_to', 'el_index' ],
			'el_from = ' . self::$testingPageId,
			[
				[ 'http://testing.com/wiki/Bar', 'http://com.testing./wiki/Bar' ],
				[ 'http://testing.com/wiki/Foo', 'http://com.testing./wiki/Foo' ],
			]
		);

		$this->assertArrayEquals( [
			"http://testing.com/wiki/Bar",
			"http://testing.com/wiki/Foo"
		], $update->getAddedExternalLinks() );

		$po = new ParserOutput();
		$po->setTitleText( $t->getPrefixedText() );
		$po->addExternalLink( 'http://testing.com/wiki/Bar' );
		$po->addExternalLink( 'http://testing.com/wiki/Baz' );
		$update = $this->assertLinksUpdate(
			$t,
			$po,
			'externallinks',
			[ 'el_to', 'el_index' ],
			'el_from = ' . self::$testingPageId,
			[
				[ 'http://testing.com/wiki/Bar', 'http://com.testing./wiki/Bar' ],
				[ 'http://testing.com/wiki/Baz', 'http://com.testing./wiki/Baz' ],
			]
		);

		$this->assertArrayEquals( [
			"http://testing.com/wiki/Baz"
		], $update->getAddedExternalLinks() );
		$this->assertArrayEquals( [
			"http://testing.com/wiki/Foo"
		], $update->getRemovedExternalLinks() );
	}

	/**
	 * @covers ParserOutput::addCategory
	 */
	public function testUpdate_categorylinks() {
		/** @var ParserOutput $po */
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addCategory( "Foo", "FOO" );
		$po->addCategory( "Bar", "BAR" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			'cl_from = ' . self::$testingPageId,
			[
				[ 'Bar', "BAR\nTESTING" ],
				[ 'Foo', "FOO\nTESTING" ]
			]
		);

		// Check category count
		$this->assertSelect(
			'category',
			[ 'cat_title', 'cat_pages' ],
			[ 'cat_title' => [ 'Foo', 'Bar', 'Baz' ] ],
			[
				[ 'Bar', 1 ],
				[ 'Foo', 1 ]
			]
		);

		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );
		$po->addCategory( "Bar", "Bar" );
		$po->addCategory( "Baz", "Baz" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			'cl_from = ' . self::$testingPageId,
			[
				[ 'Bar', "BAR\nTESTING" ],
				[ 'Baz', "BAZ\nTESTING" ]
			]
		);

		// Check category count decrement
		$this->assertSelect(
			'category',
			[ 'cat_title', 'cat_pages' ],
			[ 'cat_title' => [ 'Foo', 'Bar', 'Baz' ] ],
			[
				[ 'Bar', 1 ],
				[ 'Baz', 1 ],
			]
		);
	}

	public function testOnAddingAndRemovingCategory_recentChangesRowIsAdded() {
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		$title = Title::newFromText( 'Testing' );
		$wikiPage = new WikiPage( $title );
		$wikiPage->doUserEditContent(
			new WikitextContent( '[[Category:Foo]]' ),
			$this->getTestSysop()->getUser(),
			'added category'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$title,
			$wikiPage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Category:Foo' ),
			[ [ 'Foo', '[[:Testing]] added to category' ] ]
		);

		$wikiPage->doUserEditContent(
			new WikitextContent( '[[Category:Bar]]' ),
			$this->getTestSysop()->getUser(),
			'replaced category'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$title,
			$wikiPage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Category:Foo' ),
			[
				[ 'Foo', '[[:Testing]] added to category' ],
				[ 'Foo', '[[:Testing]] removed from category' ],
			]
		);

		$this->assertRecentChangeByCategorization(
			$title,
			$wikiPage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Category:Bar' ),
			[
				[ 'Bar', '[[:Testing]] added to category' ],
			]
		);
	}

	public function testOnAddingAndRemovingCategoryToTemplates_embeddingPagesAreIgnored() {
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		$templateTitle = Title::newFromText( 'Template:TestingTemplate' );
		$templatePage = new WikiPage( $templateTitle );

		$wikiPage = new WikiPage( Title::newFromText( 'Testing' ) );
		$wikiPage->doUserEditContent(
			new WikitextContent( '{{TestingTemplate}}' ),
			$this->getTestSysop()->getUser(),
			'added template'
		);
		$this->runAllRelatedJobs();

		$otherWikiPage = new WikiPage( Title::newFromText( 'Some_other_page' ) );
		$otherWikiPage->doUserEditContent(
			new WikitextContent( '{{TestingTemplate}}' ),
			$this->getTestSysop()->getUser(),
			'added template'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$templateTitle,
			$templatePage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Baz' ),
			[]
		);

		$templatePage->doUserEditContent(
			new WikitextContent( '[[Category:Baz]]' ),
			$this->getTestSysop()->getUser(),
			'added category'
		);
		$this->runAllRelatedJobs();

		$this->assertRecentChangeByCategorization(
			$templateTitle,
			$templatePage->getParserOutput( ParserOptions::newCanonical( 'canonical' ) ),
			Title::newFromText( 'Baz' ),
			[ [
				'Baz',
				'[[:Template:TestingTemplate]] added to category, ' .
				'[[Special:WhatLinksHere/Template:TestingTemplate|this page is included within other pages]]'
			] ]
		);
	}

	public function testUpdate_categorylinks_move() {
		$this->setMwGlobals( 'wgCategoryCollation', 'uppercase' );

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Old", self::$testingPageId );

		$po->addCategory( "Foo", "FOO" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			'cl_from = ' . self::$testingPageId,
			[
				[ 'Foo', "FOO\nOLD" ]
			]
		);

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "New", self::$testingPageId );

		$po->addCategory( "Foo", "FOO" );

		// An update to cl_sortkey is not expected if there was no move
		$this->assertLinksUpdate(
			$t,
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			'cl_from = ' . self::$testingPageId,
			[
				[ 'Foo', "FOO\nOLD" ]
			]
		);

		// With move notification, update to cl_sortkey is expected
		$this->assertMoveLinksUpdate(
			$t,
			new PageIdentityValue( 2, 0, "new", false ),
			$po,
			'categorylinks',
			[ 'cl_to', 'cl_sortkey' ],
			'cl_from = ' . self::$testingPageId,
			[
				[ 'Foo', "FOO\nNEW" ]
			]
		);
	}

	/**
	 * @covers ParserOutput::addInterwikiLink
	 */
	public function testUpdate_iwlinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$target1 = Title::makeTitleSafe( NS_MAIN, "T1", '', 'linksupdatetest' );
		$target2 = Title::makeTitleSafe( NS_MAIN, "T2", '', 'linksupdatetest' );
		$target3 = Title::makeTitleSafe( NS_MAIN, "T3", '', 'linksupdatetest' );
		$po->addInterwikiLink( $target1 );
		$po->addInterwikiLink( $target2 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'iwlinks',
			[ 'iwl_prefix', 'iwl_title' ],
			'iwl_from = ' . self::$testingPageId,
			[
				[ 'linksupdatetest', 'T1' ],
				[ 'linksupdatetest', 'T2' ],
			]
		);

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addInterwikiLink( $target2 );
		$po->addInterwikiLink( $target3 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'iwlinks',
			[ 'iwl_prefix', 'iwl_title' ],
			'iwl_from = ' . self::$testingPageId,
			[
				[ 'linksupdatetest', 'T2' ],
				[ 'linksupdatetest', 'T3' ]
			]
		);
	}

	/**
	 * @covers ParserOutput::addTemplate
	 */
	public function testUpdate_templatelinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$target1 = Title::newFromText( "Template:T1" );
		$target2 = Title::newFromText( "Template:T2" );
		$target3 = Title::newFromText( "Template:T3" );

		$po->addTemplate( $target1, 23, 42 );
		$po->addTemplate( $target2, 23, 42 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'templatelinks',
			[ 'tl_namespace', 'tl_title' ],
			'tl_from = ' . self::$testingPageId,
			[
				[ NS_TEMPLATE, 'T1' ],
				[ NS_TEMPLATE, 'T2' ],
			]
		);

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addTemplate( $target2, 23, 42 );
		$po->addTemplate( $target3, 23, 42 );

		$this->assertLinksUpdate(
			$t,
			$po,
			'templatelinks',
			[ 'tl_namespace', 'tl_title' ],
			'tl_from = ' . self::$testingPageId,
			[
				[ NS_TEMPLATE, 'T2' ],
				[ NS_TEMPLATE, 'T3' ],
			]
		);
	}

	/**
	 * @covers ParserOutput::addImage
	 */
	public function testUpdate_imagelinks() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addImage( "1.png" );
		$po->addImage( "2.png" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'imagelinks',
			'il_to',
			'il_from = ' . self::$testingPageId,
			[ [ '1.png' ], [ '2.png' ] ]
		);

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addImage( "2.png" );
		$po->addImage( "3.png" );

		$this->assertLinksUpdate(
			$t,
			$po,
			'imagelinks',
			'il_to',
			'il_from = ' . self::$testingPageId,
			[ [ '2.png' ], [ '3.png' ] ]
		);
	}

	/**
	 * @covers ParserOutput::addLanguageLink
	 */
	public function testUpdate_langlinks() {
		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );

		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$po->addLanguageLink( 'De:1' );
		$po->addLanguageLink( 'En:1' );
		$po->addLanguageLink( 'Fr:1' );

		$this->assertLinksUpdate(
			$t,
			$po,
			'langlinks',
			[ 'll_lang', 'll_title' ],
			'll_from = ' . self::$testingPageId,
			[
				[ 'De', '1' ],
				[ 'En', '1' ],
				[ 'Fr', '1' ]
			]
		);

		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );
		$po->addLanguageLink( 'En:2' );
		$po->addLanguageLink( 'Fr:1' );

		$this->assertLinksUpdate(
			$t,
			$po,
			'langlinks',
			[ 'll_lang', 'll_title' ],
			'll_from = ' . self::$testingPageId,
			[
				[ 'En', '2' ],
				[ 'Fr', '1' ]
			]
		);
	}

	/**
	 * @covers ParserOutput::setPageProperty
	 */
	public function testUpdate_page_props() {
		/** @var ParserOutput $po */
		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$fields = [ 'pp_propname', 'pp_value', 'pp_sortkey' ];
		$cond = 'pp_page = ' . self::$testingPageId;

		$po->setPageProperty( 'deleted', 1 );
		$po->setPageProperty( 'changed', 1 );
		$this->assertLinksUpdate(
			$t, $po, 'page_props', $fields, $cond,
			[
				[ 'changed', '1', 1 ],
				[ 'deleted', '1', 1 ]
			]
		);

		list( $t, $po ) = $this->makeTitleAndParserOutput( "Testing", self::$testingPageId );

		$expected = [];
		$po->setPageProperty( "bool", true );
		$expected[] = [ "bool", true ];

		$po->setPageProperty( 'changed', 2 );
		$expected[] = [ 'changed', 2 ];

		$po->setPageProperty( "float", 4.0 + 1.0 / 4.0 );
		$expected[] = [ "float", 4.0 + 1.0 / 4.0 ];

		$po->setPageProperty( "int", -7 );
		$expected[] = [ "int", -7 ];

		$po->setPageProperty( "string", "33 bar" );
		$expected[] = [ "string", "33 bar" ];

		// compute expected sortkey values
		foreach ( $expected as &$row ) {
			$value = $row[1];

			if ( is_int( $value ) || is_float( $value ) || is_bool( $value ) ) {
				$row[] = floatval( $value );
			} else {
				$row[] = null;
			}
		}

		$update = $this->assertLinksUpdate(
			$t, $po, 'page_props', $fields, 'pp_page = ' . self::$testingPageId, $expected );

		$expectedAssoc = [];
		foreach ( $expected as list( $name, $value ) ) {
			$expectedAssoc[$name] = $value;
		}
		$this->assertArrayEquals( $expectedAssoc, $update->getAddedProperties() );
		$this->assertArrayEquals(
			[
				'changed' => '1',
				'deleted' => '1'
			],
			$update->getRemovedProperties()
		);
	}

	// @todo test recursive, too!

	protected function assertLinksUpdate( Title $title, ParserOutput $parserOutput,
		$table, $fields, $condition, array $expectedRows
	) {
		return $this->assertMoveLinksUpdate( $title, null, $parserOutput,
			$table, $fields, $condition, $expectedRows );
	}

	protected function assertMoveLinksUpdate(
		Title $title, ?PageIdentityValue $oldTitle, ParserOutput $parserOutput,
		$table, $fields, $condition, array $expectedRows
	) {
		$update = new LinksUpdate( $title, $parserOutput );
		$update->setStrictTestMode();
		if ( $oldTitle ) {
			$update->setMoveDetails( $oldTitle );
		}

		$update->doUpdate();

		$this->assertSelect( $table, $fields, $condition, $expectedRows );
		return $update;
	}

	protected function assertRecentChangeByCategorization(
		Title $pageTitle, ParserOutput $parserOutput, Title $categoryTitle, $expectedRows
	) {
		$this->assertSelect(
			[ 'recentchanges', 'comment' ],
			[ 'rc_title', 'comment_text' ],
			[
				'rc_type' => RC_CATEGORIZE,
				'rc_namespace' => NS_CATEGORY,
				'rc_title' => $categoryTitle->getDBkey(),
				'comment_id = rc_comment_id',
			],
			$expectedRows
		);
	}

	private function runAllRelatedJobs() {
		$queueGroup = JobQueueGroup::singleton();
		while ( $job = $queueGroup->pop( 'refreshLinksPrioritized' ) ) {
			$job->run();
			$queueGroup->ack( $job );
		}
		while ( $job = $queueGroup->pop( 'categoryMembershipChange' ) ) {
			$job->run();
			$queueGroup->ack( $job );
		}
	}

	public function testIsRecursive() {
		list( $title, $po ) = $this->makeTitleAndParserOutput( 'Test', 1 );
		$linksUpdate = new LinksUpdate( $title, $po );
		$this->assertTrue( $linksUpdate->isRecursive(), 'LinksUpdate is recursive by default' );

		$linksUpdate = new LinksUpdate( $title, $po, true );
		$this->assertTrue( $linksUpdate->isRecursive(),
			'LinksUpdate is recursive when asked to be recursive' );

		$linksUpdate = new LinksUpdate( $title, $po, false );
		$this->assertFalse( $linksUpdate->isRecursive(),
			'LinksUpdate is not recursive when asked to be not recursive' );
	}
}
