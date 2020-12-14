<?php

namespace MediaWiki\Tests\Rest\Helper;

use HashConfig;
use MediaWiki\Rest\Handler\RevisionContentHelper;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWikiIntegrationTestCase;
use Title;

/**
 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper
 * @group Database
 */
class RevisionContentHelperTest extends MediaWikiIntegrationTestCase {

	private const NO_REVISION_ETAG = '"b620cd7841f9ea8f545f11cc44ce794f848fa2d3"';

	protected function setUp(): void {
		parent::setUp();

		// Clean up these tables after each test
		$this->tablesUsed = [
			'page',
			'revision',
			'comment',
			'text',
			'content'
		];
	}

	/**
	 * @return RevisionContentHelper
	 */
	private function newHelper( $params = [], $user = null ): RevisionContentHelper {
		$helper = new RevisionContentHelper(
			new HashConfig( [
				'RightsUrl' => 'https://example.com/rights',
				'RightsText' => 'some rights',
			] ),
			$this->getServiceContainer()->getPermissionManager(),
			$this->getServiceContainer()->getRevisionLookup(),
			$this->getServiceContainer()->getTitleFormatter(),
			$this->getServiceContainer()->getTitleFactory()
		);

		$user = $user ?: $this->getTestUser()->getUser();
		$helper->init( $user, $params );
		return $helper;
	}

	private function getExistingPageWithRevisions( $name ) {
		$page = $this->getNonexistingTestPage( $name );

		$this->editPage( $page, 'First revision of ' . $name );
		$revisions['first'] = $page->getRevisionRecord();

		$this->editPage( $page, 'DEAD BEEF' );
		$revisions['latest'] = $page->getRevisionRecord();

		return [ $page, $revisions ];
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getRole()
	 */
	public function testGetRole() {
		$helper = $this->newHelper();
		$this->assertSame( SlotRecord::MAIN, $helper->getRole() );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitle()
	 */
	public function testGetTitle() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$helper = $this->newHelper( [ 'id' => $revisions['first']->getId() ] );
		$this->assertSame( $page->getTitle()->getPrefixedDBKey(), $helper->getTitleText() );

		$this->assertInstanceOf( Title::class, $helper->getTitle() );
		$this->assertSame(
			$page->getTitle()->getPrefixedDBKey(),
			$helper->getTitle()->getPrefixedDBkey()
		);
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getContent()
	 */
	public function testGetTargetRevisionAndContent() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );

		$helper = $this->newHelper( [ 'id' => $revisions['first']->getId() ] );

		$targetRev = $helper->getTargetRevision();
		$this->assertInstanceOf( RevisionRecord::class, $targetRev );
		$this->assertSame( $revisions['first']->getId(), $targetRev->getId() );

		$pageContent = $helper->getContent();
		$this->assertSame(
			$revisions['first']->getContent( SlotRecord::MAIN )->serialize(),
			$pageContent->serialize()
		);
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitle()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::checkAccess()
	 */
	public function testNoTitle() {
		$helper = $this->newHelper();

		$this->assertNull( $helper->getTitleText() );
		$this->assertFalse( $helper->getTitle() );

		$this->assertFalse( $helper->hasContent() );
		$this->assertFalse( $helper->isAccessible() );

		$this->assertFalse( $helper->getTargetRevision() );

		$this->assertNull( $helper->getLastModified() );
		$this->assertSame( self::NO_REVISION_ETAG, $helper->getETag() );

		try {
			$helper->getContent();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 404, $ex->getCode() );
		}

		try {
			$helper->checkAccess();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 404, $ex->getCode() );
		}
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitle()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::checkAccess()
	 */
	public function testNonExistingRevision() {
		$helper = $this->newHelper( [ 'id' => 287436534 ] );

		$this->assertSame( 287436534, $helper->getRevisionId() );
		$this->assertNull( $helper->getTitleText() );
		$this->assertFalse( $helper->getTitle() );

		$this->assertFalse( $helper->hasContent() );
		$this->assertFalse( $helper->isAccessible() );

		$this->assertFalse( $helper->getTargetRevision() );

		$this->assertNull( $helper->getLastModified() );
		$this->assertSame( self::NO_REVISION_ETAG, $helper->getETag() );

		try {
			$helper->getContent();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 404, $ex->getCode() );
		}

		try {
			$helper->checkAccess();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 404, $ex->getCode() );
		}
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitleText()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTitle()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::isAccessible()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::hasContent()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getTargetRevision()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getContent()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getLastModified()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getETag()
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::checkAccess()
	 */
	public function testForbidenPage() {
		$this->mergeMwGlobalArrayValue(
			'wgGroupPermissions', [
				'*' => [ 'read' => false ],
				'user' => [ 'read' => false ],
				'autoconfirmed' => [ 'read' => false ],
			]
		);

		$user = $this->getTestUser()->getUser();

		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$title = $page->getTitle();
		$helper = $this->newHelper( [ 'id' => $revisions['first']->getId() ], $user );

		$this->assertSame( $title->getPrefixedDBkey(), $helper->getTitleText() );
		$this->assertSame( $title->getPrefixedDBkey(), $helper->getTitle()->getPrefixedDBkey() );

		$this->assertTrue( $helper->hasContent() );
		$this->assertFalse( $helper->isAccessible() );

		$this->assertNull( $helper->getLastModified() );

		try {
			$helper->checkAccess();
			$this->fail( 'Expected HttpException' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::getParamSettings()
	 */
	public function testParameterSettings() {
		$helper = $this->newHelper();
		$settings = $helper->getParamSettings();
		$this->assertArrayHasKey( 'id', $settings );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::setCacheControl()
	 */
	public function testCacheControl() {
		$helper = $this->newHelper();

		$response = new Response();

		$helper->setCacheControl( $response ); // default
		$this->assertStringContainsString( 'max-age=5', $response->getHeaderLine( 'Cache-Control' ) );

		$helper->setCacheControl( $response, 2 ); // explicit
		$this->assertStringContainsString( 'max-age=2', $response->getHeaderLine( 'Cache-Control' ) );

		$helper->setCacheControl( $response, 1000 * 1000 ); // too big
		$this->assertStringContainsString( 'max-age=5', $response->getHeaderLine( 'Cache-Control' ) );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\RevisionContentHelper::constructMetadata()
	 */
	public function testConstructMetadata() {
		[ $page, $revisions ] = $this->getExistingPageWithRevisions( __METHOD__ );
		$title = $page->getTitle();

		$revision = $revisions['first'];
		$content = $revision->getContent( SlotRecord::MAIN );
		$expected = [
			'id' => $revision->getId(),
			'size' => $revision->getSize(),
			'minor' => $revision->isMinor(),
			'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() ),
			'content_model' => $content->getModel(),
			'page' => [
				'id' => $title->getArticleID(),
				'key' => $title->getPrefixedDBkey(),
				'title' => $title->getPrefixedText(),
			],
			'license' => [
				'url' => 'https://example.com/rights',
				'title' => 'some rights',
			],
			'user' => [
				'id' => $revision->getUser()->getId(),
				'name' => $revision->getUser()->getName(),
			],
			'comment' => '',
			'delta' => null, // first revision doesn't have a delta for now
		];

		$helper = $this->newHelper( [ 'id' => $revision->getId() ] );
		$data = $helper->constructMetadata();
		$this->assertEquals( $expected, $data );
	}

}
