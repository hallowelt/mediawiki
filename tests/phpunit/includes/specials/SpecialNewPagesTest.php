<?php

namespace MediaWiki\Tests\Specials;

use DOMElement;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use SpecialPageTestBase;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialNewPages
 * @covers \MediaWiki\Pager\NewPagesPager
 */
class SpecialNewPagesTest extends SpecialPageTestBase {

	use TempUserTestTrait;

	private static UserIdentity $testUser1;

	/** @var Title[] */
	private static array $testUser1Pages;

	/** @var Title[] */
	private static array $allPages;

	private static int $editRevId;

	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Newpages' );
	}

	/**
	 * Asserts that the form fields for the Special:NewPages page are present.
	 *
	 * @param string $html The HTML returned by the special page
	 * @param bool $canAnonUsersCreatePages Whether anonymous users should be able to create pages
	 */
	private function verifyFormFieldsArePresent(
		string $html, bool $canAnonUsersCreatePages
	) {
		// Verify that the form labels are present. This is a good way to check that the form fields are present,
		// since the form labels should be generated by the form field definitions.
		$this->assertStringContainsString( '(namespace)', $html, 'Namespace filter not added to form' );
		$this->assertStringContainsString( '(newpages-username)', $html, 'Username filter not added to form' );
		$this->assertStringContainsString(
			'(namespace_association)', $html, 'Associated namespace filter not added to form'
		);
		$this->assertStringContainsString( '(tag-filter)', $html, 'Tag filter not added to form' );
		$this->assertStringContainsString( '(invert)', $html, 'Invert checkbox not added to form' );
		$this->assertStringContainsString( '(minimum-size)', $html, 'Size filter not added to form' );
		// Verify that the filter links are present in the form
		if ( $canAnonUsersCreatePages ) {
			$this->assertStringContainsString(
				'(newpages-showhide-registered', $html, 'Registered filter should be present'
			);
		} else {
			$this->assertStringNotContainsString(
				'(newpages-showhide-registered', $html, 'Registered filter should not be present'
			);
		}
		$this->assertStringContainsString( '(newpages-showhide-bots', $html, 'Missing bots filter' );
		$this->assertStringContainsString( '(newpages-showhide-redirect', $html, 'Missing redirect filter' );

		$this->assertStringContainsString( '(newpages-submit)', $html, 'Submit button text not as expected' );
	}

	/**
	 * @param bool $state Whether to allow or disallow anonymous users creating pages.
	 */
	private function setAnonsHaveRightsToCreatePages( bool $state ) {
		// Remove the 'createtalk' and 'createpage' rights from the '*' group if they are present for the test.
		$groupPermissionsValue = $this->getServiceContainer()->getMainConfig()
			->get( MainConfigNames::GroupPermissions );
		if ( $state ) {
			$groupPermissionsValue['*']['createtalk'] = true;
			$groupPermissionsValue['*']['createpage'] = true;
		} else {
			unset( $groupPermissionsValue['*']['createtalk'] );
			unset( $groupPermissionsValue['*']['createpage'] );
		}
		$this->overrideConfigValue( MainConfigNames::GroupPermissions, $groupPermissionsValue );
	}

	/**
	 * Helper method used to expect that one element matches the given selector inside the given parent element.
	 *
	 * @param DOMElement|Document $document The element to search through
	 * @param string $selector The CSS selector which should match only one element
	 * @return DOMElement|Element The matched element
	 */
	private function getAndExpectSingleMatchingElement( $document, string $selector ) {
		$matchingClass = DOMCompat::querySelectorAll( $document, $selector );
		$this->assertCount( 1, $matchingClass, "One element was expected to match $selector" );
		return $matchingClass[0];
	}

	/**
	 * Verifies that the given new pages line has the expected elements.
	 *
	 * @param DOMElement|Element $line The line element to verify
	 * @param RevisionRecord $firstRevision The first revision of the page
	 */
	private function verifyLineHasExpectedElements( $line, RevisionRecord $firstRevision ) {
		// Verify the timestamp element is present
		$this->getAndExpectSingleMatchingElement( $line, ".mw-newpages-time" );
		// Verify that the page name is as expected.
		$pageNameElement = $this->getAndExpectSingleMatchingElement(
			$line, ".mw-newpages-pagename"
		);
		$this->assertSame(
			$this->getServiceContainer()->getTitleFormatter()->getPrefixedText( $firstRevision->getPage() ),
			$pageNameElement->textContent
		);
		// Verify that the edit page and page history links are there
		$editLinkElement = $this->getAndExpectSingleMatchingElement( $line, ".mw-newpages-edit" );
		$this->assertSame( '(editlink)', $editLinkElement->textContent );
		$pageHistoryLinkElement = $this->getAndExpectSingleMatchingElement(
			$line, ".mw-newpages-history"
		);
		$this->assertSame( '(hist)', $pageHistoryLinkElement->textContent );
		// Verify that the user link is present and correct, including that the username is hidden if the current
		// authority cannot see it.
		$authority = RequestContext::getMain()->getAuthority();
		$userNameElement = $this->getAndExpectSingleMatchingElement( $line, ".mw-userlink" );
		if ( $firstRevision->userCan( RevisionRecord::DELETED_USER, $authority ) ) {
			$expectedUserText = $firstRevision->getUser( RevisionRecord::RAW )->getName();
		} else {
			$expectedUserText = '(rev-deleted-user)';
		}
		$this->assertSame( $expectedUserText, $userNameElement->textContent );
		// Verify that the comment is present if visible or hidden if not
		$commentElement = $this->getAndExpectSingleMatchingElement( $line, ".comment" );
		if ( $firstRevision->userCan( RevisionRecord::DELETED_COMMENT, $authority ) ) {
			$this->assertStringContainsString( $firstRevision->getComment()->text, $commentElement->textContent );
		} else {
			$this->assertStringContainsString( '(rev-deleted-comment)', $commentElement->textContent );
		}
	}

	/**
	 * Perform testing steps that are common to all of the tests in this file.
	 *
	 * @param Title[] $expectedPages
	 * @param Title[] $expectedPagesNotShown
	 * @param ?FauxRequest $fauxRequest
	 * @param bool $canAnonUsersCreatePages
	 * @return string
	 */
	private function testLoadPage(
		array $expectedPages, array $expectedPagesNotShown, ?FauxRequest $fauxRequest = null,
		bool $canAnonUsersCreatePages = false
	): string {
		$this->setAnonsHaveRightsToCreatePages( $canAnonUsersCreatePages );
		$this->overrideConfigValues( [
			MainConfigNames::UseNPPatrol => true,
			MainConfigNames::UseRCPatrol => true,
		] );
		// This is explicitly needed because the HTMLSizeFilterField uses the user's language and not the language
		// set by ::executeSpecialPage.
		$this->setUserLang( 'qqx' );
		// Call the special page and verify that the form fields are as expected.
		[ $html ] = $this->executeSpecialPage( '', $fauxRequest );
		$this->verifyFormFieldsArePresent( $html, $canAnonUsersCreatePages );
		// Verify that the pages which should be there are present in the page.
		$contributionsList = $this->getAndExpectSingleMatchingElement(
			DOMUtils::parseHTML( $html ), '.mw-contributions-list'
		);
		foreach ( $expectedPages as $page ) {
			// Find the line with the matching revision ID
			$firstRevision = $this->getServiceContainer()->getRevisionStore()->getFirstRevision( $page );
			$matchingLine = $this->getAndExpectSingleMatchingElement(
				$contributionsList, "li[data-mw-revid=\"{$firstRevision->getId()}\"]"
			);
			// Check that this matching line has the expected structure.
			$this->verifyLineHasExpectedElements( $matchingLine, $firstRevision );
		}
		// Check that the pages which shouldn't be there are not added to the page.
		foreach ( $expectedPagesNotShown as $page ) {
			$firstRevId = $this->getServiceContainer()->getRevisionStore()->getFirstRevision( $page )->getId();
			$matchingLines = DOMCompat::querySelectorAll( $contributionsList, "[data-mw-revid=\"$firstRevId\"]" );
			$this->assertCount(
				0, $matchingLines, "New page entry for revision $firstRevId was not expected"
			);
		}
		// Verify that the edit is never shown
		$matchingLines = DOMCompat::querySelectorAll(
			$contributionsList, '[data-mw-revid="' . self::$editRevId . '"]'
		);
		$this->assertCount(
			0, $matchingLines,
			'A revision ID which is not associated with a new page creation is present in Special:NewPages.'
		);
		// Return the HTML to allow further custom testing by the methods which called this method.
		return $html;
	}

	public function testLoadWithNoOptionsSpecified() {
		// Expect that by default all new main space page creations are shown, but no other pages.
		$expectedPages = [];
		$expectedPagesNotShown = [];
		foreach ( self::$allPages as $page ) {
			if ( $page->getNamespace() === NS_MAIN ) {
				$expectedPages[] = $page;
			} else {
				$expectedPagesNotShown[] = $page;
			}
		}
		$this->testLoadPage( $expectedPages, $expectedPagesNotShown );
	}

	public function testWhenFilteredToJustTestUser1Pages() {
		// Filter for all page creations by the first test user.
		$this->testLoadPage(
			self::$testUser1Pages, array_diff( self::$allPages, self::$testUser1Pages ),
			new FauxRequest( [ 'username' => self::$testUser1->getName(), 'namespace' => 'all' ] )
		);
	}

	public function testWhenFilteredToJustAnonCreations() {
		// Filter for all page creations by anon users in any namespace.
		$fauxRequest = new FauxRequest( [ 'hideliu' => true, 'namespace' => '' ] );
		$this->testLoadPage(
			array_diff( self::$allPages, self::$testUser1Pages ), self::$testUser1Pages, $fauxRequest,
			true
		);
	}

	public function addDBDataOnce() {
		// Create some pages so that there will be some entries in Special:NewPages.
		$testUser1 = $this->getMutableTestUser()->getUser();
		// Get the first test user to create a page and it's associated talk page in mainspace.
		$firstPage = $this->insertPage( 'SpecialNewPagesTest1', 'test', NS_MAIN, $testUser1 );
		$secondPage = $this->insertPage( 'SpecialNewPagesTest1', 'talk', NS_TALK, $testUser1 );
		// Get the first test user to create it's userpage
		$thirdPage = $this->insertPage( $testUser1->getName(), 'userpage', NS_USER, $testUser1 );
		// Get an anon user to create a page in the template namespace.
		$this->disableAutoCreateTempUser();
		$fourthPage = $this->insertPage(
			'SpecialNewPagesTest2', 'test', NS_TEMPLATE,
			$this->getServiceContainer()->getUserFactory()->newFromName( '127.0.0.1', UserFactory::RIGOR_NONE )
		);
		// Get the sysop test user to make an edit, to test it won't appear in Special:NewPages.
		$editStatus = $this->editPage( $firstPage['title'], 'testing1234', 'test edit' );
		$this->assertStatusGood( $editStatus );
		self::$testUser1 = $testUser1;
		self::$testUser1Pages = [ $firstPage['title'], $secondPage['title'], $thirdPage['title'], ];
		self::$allPages = array_merge( self::$testUser1Pages, [ $fourthPage['title'] ] );
		self::$editRevId = $editStatus->getNewRevision()->getId();
	}
}
