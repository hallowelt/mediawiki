<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;

/**
 * @group API
 * @group medium
 * @group Database
 *
 * @coversDefaultClass ApiQueryInfo
 */
class ApiQueryInfoTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'watchlist', 'watchlist_expiry' ]
		);

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
		] );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecute() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();
		$user = $this->getTestUser()->getUser();
		WatchAction::doWatch(
			$title,
			$user,
			User::CHECK_USER_RIGHTS,
			'30300101000000'
		);
		$watchItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		$expiry = $watchItemStore->getWatchedItem( $user, $title )->getExpiry( TS_ISO_8601 );

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'inprop' => 'watched|notificationtimestamp',
				'titles' => $title->getText() . '|' . 'NonExistingPage_lkasdoiewlmasdoiwem7483',
		], null, false, $user );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertSame( $page->getId(), $info['pageid'] );
		$this->assertSame( $title->getNamespace(), $info['ns'] );
		$this->assertSame( $title->getText(), $info['title'] );
		$this->assertSame( $title->getContentModel(), $info['contentmodel'] );
		$this->assertSame( $title->getPageLanguage()->getCode(), $info['pagelanguage'] );
		$this->assertSame( $title->getPageLanguage()->getHtmlCode(), $info['pagelanguagehtmlcode'] );
		$this->assertSame( $title->getPageLanguage()->getDir(), $info['pagelanguagedir'] );
		$this->assertSame( wfTimestamp( TS_ISO_8601, $title->getTouched() ), $info['touched'] );
		$this->assertSame( $title->getLatestRevID(), $info['lastrevid'] );
		$this->assertSame( $title->getLength(), $info['length'] );
		$this->assertSame( $title->isNewPage(), $info['new'] );
		$this->assertTrue( $info['watched'] );
		$this->assertSame( $expiry, $info['watchlistexpiry'] );
		$this->assertArrayNotHasKey( 'actions', $info );
		$this->assertArrayNotHasKey( 'linkclasses', $info );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecuteLinkClasses() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'inprop' => 'linkclasses',
				'inlinkcontext' => $title->getText(),
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'linkclasses', $info );
		$this->assertEquals( [], $info['linkclasses'] );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecuteEditActions() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit'
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'actions', $info );
		$this->assertArrayHasKey( 'edit', $info['actions'] );
		$this->assertTrue( $info['actions']['edit'] );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecuteEditActionsFull() {
		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit',
				'intestactionsdetail' => 'full',
		] );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'actions', $info );
		$this->assertArrayHasKey( 'edit', $info['actions'] );
		$this->assertIsArray( $info['actions']['edit'] );
		$this->assertSame( [], $info['actions']['edit'] );
	}

	/**
	 * @covers ::execute
	 * @covers ::extractPageInfo
	 */
	public function testExecuteEditActionsFullBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => 1,
			'enableAutoblock' => true,
		] );

		$blockStore = MediaWikiServices::getInstance()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		$page = $this->getExistingTestPage( 'Pluto' );
		$title = $page->getTitle();

		list( $data ) = $this->doApiRequest( [
				'action' => 'query',
				'prop' => 'info',
				'titles' => $title->getText(),
				'intestactions' => 'edit',
				'intestactionsdetail' => 'full',
		], null, false, $badActor );

		$blockStore->deleteBlock( $block );

		$this->assertArrayHasKey( 'query', $data );
		$this->assertArrayHasKey( 'pages', $data['query'] );
		$this->assertArrayHasKey( $page->getId(), $data['query']['pages'] );

		$info = $data['query']['pages'][$page->getId()];
		$this->assertArrayHasKey( 'actions', $info );
		$this->assertArrayHasKey( 'edit', $info['actions'] );
		$this->assertIsArray( $info['actions']['edit'] );
		$this->assertArrayHasKey( 0, $info['actions']['edit'] );
		$this->assertArrayHasKey( 'code', $info['actions']['edit'][0] );
		$this->assertSame( 'blocked', $info['actions']['edit'][0]['code'] );
		$this->assertArrayHasKey( 'data', $info['actions']['edit'][0] );
		$this->assertArrayHasKey( 'blockinfo', $info['actions']['edit'][0]['data'] );
		$this->assertArrayHasKey( 'blockid', $info['actions']['edit'][0]['data']['blockinfo'] );
		$this->assertSame( $block->getId(), $info['actions']['edit'][0]['data']['blockinfo']['blockid'] );
	}

}
