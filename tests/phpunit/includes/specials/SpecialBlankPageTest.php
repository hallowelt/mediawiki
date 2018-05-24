<?php

/**
 * @license GPL-2.0-or-later
 * @author Addshore
 *
 * @covers SpecialBlankpage
 */
class SpecialBlankPageTest extends SpecialPageTestBase {

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		return new SpecialBlankpage();
	}

	public function testHasWikiMsg() {
		list( $html, ) = $this->executeSpecialPage();
		$this->assertContains( wfMessage( 'intentionallyblankpage' )->text(), $html );
	}

}
