<?php

use MediaWiki\User\UserIdentity;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @group Database
 * @covers \ChangesList
 */
class ChangesListTest extends MediaWikiIntegrationTestCase {

	private TestRecentChangesHelper $testRecentChangesHelper;

	protected function setUp(): void {
		parent::setUp();

		$this->setUserLang( 'qqx' );
		$this->testRecentChangesHelper = new TestRecentChangesHelper();
	}

	private function getChangesList() {
		$user = $this->getMutableTestUser()->getUser();
		$context = $this->testRecentChangesHelper->getTestContext( $user );
		$context->setLanguage( 'qqx' );

		return new ChangesList( $context );
	}

	private function getLogChange( UserIdentity $user, $logType, $logAction, $logComment ) {
		return $this->testRecentChangesHelper->makeLogRecentChange(
			$logType, $logAction, $user, 'Abc', '20131103212153', 0, 0,
			[ 'rc_comment_text' => $logComment ]
		);
	}

	public function testInsertLogEntry() {
		$changesList = $this->getChangesList();
		$recentChange = $this->getLogChange(
			$changesList->getContext()->getUser(), 'delete', 'delete', 'Test delete'
		);

		// Validate that the hook is called correctly by implementing a hook handler for it and checking
		// the parameters / adding items to parameters passed by reference.
		$this->setTemporaryHook( 'ChangesListInsertLogEntry', function (
			DatabaseLogEntry $entry, IContextSource $context, &$html, &$classes, &$attribs
		) use ( $changesList, $recentChange ) {
			$this->assertSame( $changesList->getContext(), $context );
			$this->assertSame( $entry->getId(), $recentChange->getAttribute( 'rc_logid' ) );
			$html .= ' hook-added';
			$classes[] = 'class-added-by-hook';
			$attribs['test'] = 'attrib-added-by-hook';
			$attribs['data-mw-abc'] = 'should-not-be-added';
		} );

		// Get the log entry HTML line and check that the wrapping element is present.
		$html = $changesList->insertLogEntry( $recentChange );
		$htmlElement = DOMUtils::parseHTML( $html );
		$wrappingElement = DOMCompat::querySelector(
			$htmlElement, '.mw-changeslist-log-entry.class-added-by-hook'
		);
		$this->assertNotNull( $wrappingElement );
		$lineInnerHtml = $wrappingElement->nodeValue;

		$this->assertStringContainsString( '(logentry-delete-delete', $lineInnerHtml );
		$this->assertStringContainsString(
			$changesList->getContext()->getUser()->getName(), $lineInnerHtml,
			'Performer username not present in log line'
		);
		$this->assertStringContainsString(
			'Test delete', $lineInnerHtml, 'Log comment not present in log line'
		);
		$this->assertStringEndsWith( 'hook-added', $lineInnerHtml, 'Hook did not successfully modify HTML' );
	}
}
