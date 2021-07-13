<?php

namespace MediaWiki\Tests\Unit\Page;

use ContentModelChange;
use HashConfig;
use MediaWiki\Page\PageCommandFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\RollbackPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\User\UserIdentity;
use MediaWikiUnitTestCase;
use MergeHistory;
use MovePage;
use Title;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * @covers \MediaWiki\Page\PageCommandFactory
 * @author DannyS712
 */
class PageCommandFactoryTest extends MediaWikiUnitTestCase {
	use MockServiceDependenciesTrait;

	private function getFactory() {
		// Create a PageCommandFactory with all of the services needed
		$config = new HashConfig( [
			// RollbackPage
			'UseRCPatrol' => true,
			'DisableAnonTalk' => false,

			// MovePage
			'CategoryCollation' => 'uppercase',
			'MaximumMovedPages' => 100,
		] );

		$database = $this->createMock( IDatabase::class );
		$loadBalancer = $this->createMock( ILoadBalancer::class );
		$loadBalancer->method( 'getConnection' )->willReturn( $database );

		// Helper method from MockServiceDependenciesTrait
		return $this->newServiceInstance(
			PageCommandFactory::class,
			[
				'config' => $config,
				'loadBalancer' => $loadBalancer,
			]
		);
	}

	public function testContentModelChange() {
		$contentModelChange = $this->getFactory()->newContentModelChange(
			$this->createMock( Authority::class ),
			$this->createMock( WikiPage::class ),
			CONTENT_MODEL_TEXT
		);
		$this->assertInstanceOf(
			ContentModelChange::class,
			$contentModelChange,
			'Creating ContentModelChange works'
		);
	}

	public function testMergeHistory() {
		$mergeHistory = $this->getFactory()->newMergeHistory(
			PageIdentityValue::localIdentity( 1, NS_MAIN, 'Source' ),
			PageIdentityValue::localIdentity( 2, NS_MAIN, 'Dest' ),
			null // $timestamp
		);
		$this->assertInstanceOf(
			MergeHistory::class,
			$mergeHistory,
			'Creating MergeHistory works'
		);
	}

	public function testMovePage() {
		$movePage = $this->getFactory()->newMovePage(
			$this->createNoOpMock( Title::class ),
			$this->createNoOpMock( Title::class )
		);
		$this->assertInstanceOf(
			MovePage::class,
			$movePage,
			'Creating MovePage works'
		);
	}

	public function testRollbackPage() {
		$rollbackPage = $this->getFactory()->newRollbackPage(
			$this->createMock( PageIdentity::class ),
			$this->createMock( Authority::class ),
			$this->createMock( UserIdentity::class )
		);
		$this->assertInstanceOf(
			RollbackPage::class,
			$rollbackPage,
			'Creating RollbackPage works'
		);
	}

}
