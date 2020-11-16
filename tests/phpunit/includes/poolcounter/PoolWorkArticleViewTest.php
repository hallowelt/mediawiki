<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * @covers PoolWorkArticleView
 * @group Database
 */
class PoolWorkArticleViewTest extends MediaWikiIntegrationTestCase {

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord $rev
	 * @param null $options
	 * @param bool $useParserCache
	 *
	 * @return PoolWorkArticleView
	 */
	private function newPoolWorkArticleView(
		WikiPage $page,
		RevisionRecord $rev = null,
		$options = null,
		$useParserCache = true
	) {
		if ( !$options ) {
			$options = ParserOptions::newCanonical( 'canonical' );
		}

		if ( !$rev ) {
			$rev = $page->getRevisionRecord();
		}

		$parserCache = $this->getServiceContainer()->getParserCache();
		$revisionRenderer = $this->getServiceContainer()->getRevisionRenderer();

		return new PoolWorkArticleView(
			$page,
			$rev,
			$options,
			$useParserCache,
			$revisionRenderer,
			$parserCache
		);
	}

	private function makeRevision( WikiPage $page, $text ) {
		$user = $this->getTestUser()->getUser();
		$updater = $page->newPageUpdater( $user );

		$updater->setContent( SlotRecord::MAIN, new WikitextContent( $text ) );
		return $updater->saveRevision( CommentStoreComment::newUnsavedComment( 'testing' ) );
	}

	public function testDoWorkLoadRevision() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $this->makeRevision( $page, 'First!' );
		$rev2 = $this->makeRevision( $page, 'Second!' );

		$work = $this->newPoolWorkArticleView( $page, $rev1, $options, false );
		$work->execute();
		$this->assertStringContainsString( 'First', $work->getParserOutput()->getText() );

		$work = $this->newPoolWorkArticleView( $page, $rev2, $options, false );
		$work->execute();
		$this->assertStringContainsString( 'Second', $work->getParserOutput()->getText() );
	}

	public function testDoWorkParserCache() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $this->makeRevision( $page, 'First!' );

		$work = $this->newPoolWorkArticleView( $page, $rev1, $options, true );
		$work->execute();

		$cache = MediaWikiServices::getInstance()->getParserCache();
		$out = $cache->get( $page, $options );

		$this->assertNotNull( $out );
		$this->assertNotFalse( $out );
		$this->assertStringContainsString( 'First', $out->getText() );
	}

	public function testDoWorkWithFakeRevision() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev = $this->makeRevision( $page, 'NOPE' );

		// Make a fake revision with different content and no revision ID or page ID,
		// and make sure the fake content is used.
		$fakeRev = new MutableRevisionRecord( $page->getTitle() );
		$fakeRev->setContent( SlotRecord::MAIN, new WikitextContent( 'YES!' ) );

		$work = $this->newPoolWorkArticleView( $page, $fakeRev, $options, false );
		$work->execute();

		$text = $work->getParserOutput()->getText();
		$this->assertStringContainsString( 'YES!', $text );
		$this->assertStringNotContainsString( 'NOPE', $text );
	}

	public function provideMagicWords() {
		yield 'PAGEID' => [
			'Test {{PAGEID}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getPageId();
			}
		];
		yield 'REVISIONID' => [
			'Test {{REVISIONID}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getId();
			}
		];
		yield 'REVISIONUSER' => [
			'Test {{REVISIONUSER}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getUser()->getName();
			}
		];
		yield 'REVISIONTIMESTAMP' => [
			'Test {{REVISIONTIMESTAMP}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getTimestamp();
			}
		];
	}

	/**
	 * @dataProvider provideMagicWords
	 */
	public function testMagicWords( $wikitext, $callback ) {
		static $counter = 1;

		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getNonexistingTestPage( __METHOD__ . $counter++ );
		$this->editPage( $page, $wikitext );
		$rev = $page->getRevisionRecord();

		// NOTE: provide the input as a string and let the PoolWorkArticleView create a fake
		// revision internally, to see if the magic words work with that fake. They should
		// work if the Parser causes the actual revision to be loaded when needed.
		$work = $this->newPoolWorkArticleView(
			$page,
			$page->getRevisionRecord(),
			$options,
			false
		);
		$work->execute();

		$expected = strval( $callback( $rev ) );
		$output = $work->getParserOutput();

		$this->assertStringContainsString( $expected, $output->getText() );
	}

	public function testDoWorkDeletedContent() {
		$options = ParserOptions::newCanonical( 'canonical' );
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $page->getRevisionRecord();

		// make another revision, since the latest revision cannot be deleted.
		$rev2 = $this->makeRevision( $page, 'Next' );

		// make a fake revision with deleted different content
		$fakeRev = new MutableRevisionRecord( $page->getTitle() );
		$fakeRev->setId( $rev1->getId() );
		$fakeRev->setPageId( $page->getId() );
		$fakeRev->setContent( SlotRecord::MAIN, new WikitextContent( 'SECRET' ) );
		$fakeRev->setVisibility( RevisionRecord::DELETED_TEXT );

		// rendering of a deleted revision should work, audience checks are bypassed
		$work = $this->newPoolWorkArticleView( $page, $fakeRev, $options, false );
		$this->assertTrue( $work->execute() );
	}

}
