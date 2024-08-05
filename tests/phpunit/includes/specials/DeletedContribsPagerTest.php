<?php

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Pager\DeletedContribsPager;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @group Database
 * @covers \MediaWiki\Pager\DeletedContribsPager
 */
class DeletedContribsPagerTest extends MediaWikiIntegrationTestCase {
	private static User $user;

	/** @var DeletedContribsPager */
	private $pager;

	/** @var HookContainer */
	private $hookContainer;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var IConnectionProvider */
	private $dbProvider;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var CommentFormatter */
	private $commentFormatter;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->hookContainer = $services->getHookContainer();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->dbProvider = $services->getConnectionProvider();
		$this->revisionStore = $services->getRevisionStore();
		$this->commentFormatter = $services->getCommentFormatter();
		$this->linkBatchFactory = $services->getLinkBatchFactory();
		$this->pager = $this->getDeletedContribsPager();
	}

	private function getDeletedContribsPager( $target = 'Some test user', $namespace = 0 ) {
		return new DeletedContribsPager(
			RequestContext::getMain(),
			$this->hookContainer,
			$this->linkRenderer,
			$this->dbProvider,
			$this->revisionStore,
			$this->commentFormatter,
			$this->linkBatchFactory,
			$target,
			$namespace
		);
	}

	/**
	 * Flow uses DeletedContribsPager::reallyDoQuery hook to provide something other then
	 * stdClass as a row, and then manually formats its own row in ContributionsLineEnding.
	 * Emulate this behaviour and check that it works.
	 */
	public function testDeletedContribProvidedByHook() {
		$this->setTemporaryHook( 'DeletedContribsPager::reallyDoQuery', static function ( &$data ) {
			$data = [ [ new class() {
				public $ar_timestamp = 12345;
				public $testing = 'TESTING';
				public $ar_namespace = NS_MAIN;
				public $ar_title = 'Test';
				public $ar_rev_id = null;
			} ] ];
		} );
		$this->setTemporaryHook( 'DeletedContributionsLineEnding', function ( $pager, &$ret, $row ) {
			$this->assertSame( 'TESTING', $row->testing );
			$ret .= 'FROM_HOOK!';
		} );
		$pager = $this->getDeletedContribsPager();
		$this->assertStringContainsString( 'FROM_HOOK!', $pager->getBody() );
	}

	public static function provideEmptyResultIntegration() {
		$cases = [
			[ 'target' => '', 'namespace' => '' ],
			[ 'target' => '127.0.0.1', 'namespace' => '' ],
			[ 'target' => 'UserWithNoEdits', 'namespace' => 1 ],
		];
		foreach ( $cases as $case ) {
			yield [ $case ];
		}
	}

	/**
	 * Confirm that the query is valid for various filter options.
	 *
	 * @dataProvider provideEmptyResultIntegration
	 */
	public function testEmptyResultIntegration( $options ) {
		$pager = $this->getDeletedContribsPager(
			$options['target'],
			$options['namespace'],
		);
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 0, $pager->getNumRows() );
	}

	public function testPopulatedIntegrationNoPermissions() {
		$pager = $this->getDeletedContribsPager( self::$user->getName() );

		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 1, $pager->getNumRows() );
	}

	public function testPopulatedIntegrationWithPermissions() {
		$this->setGroupPermissions( [ '*' => [
			'deletedhistory' => true,
			'deletedtext' => true,
			'undelete' => true,
		] ] );

		$pager = $this->getDeletedContribsPager( self::$user->getName() );
		$this->assertIsString( $pager->getBody() );
		$this->assertSame( 2, $pager->getNumRows() );
	}

	public function addDBDataOnce() {
		self::$user = $this->getTestUser()->getUser();
		$title = Title::makeTitle( NS_MAIN, 'DeletedContribsPagerTest' );

		// Make two edits (one will be suppressed)
		$this->editPage( $title, '', '', NS_MAIN, self::$user );
		$status = $this->editPage( $title, 'Test content.', '', NS_MAIN, self::$user );

		// Delete the page where the edits were made
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $page );

		// Suppress the second edit
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'archive' )
			->set( [
				'ar_deleted' => RevisionRecord::DELETED_USER | RevisionRecord::DELETED_TEXT,
				// This is to ensure the minor edits path doesn't encounter an error
				'ar_minor_edit' => 1,
			] )
			->where( [
				'ar_rev_id' => $status->getNewRevision()->getId()
			] )
			->execute();
	}
}
