<?php

/**
 * Base class for testing special pages.
 *
 * @since 1.26
 *
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 * @author Addshore
 * @author Thiemo Kreuz
 */
abstract class SpecialPageTestBase extends MediaWikiTestCase {

	private $obLevel;

	protected function setUp() : void {
		parent::setUp();

		$this->obLevel = ob_get_level();
	}

	protected function tearDown() : void {
		$obLevel = ob_get_level();

		while ( ob_get_level() > $this->obLevel ) {
			ob_end_clean();
		}

		try {
			if ( $obLevel !== $this->obLevel ) {
				$this->fail(
					"Test changed output buffer level: was {$this->obLevel} before test, but $obLevel after test."
				);
			}
		} finally {
			parent::tearDown();
		}
	}

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	abstract protected function newSpecialPage();

	/**
	 * @param string $subPage The subpage parameter to call the page with
	 * @param WebRequest|null $request Web request that may contain URL parameters, etc
	 * @param Language|string|null $language The language which should be used in the context
	 * @param User|null $user The user which should be used in the context of this special page
	 *
	 * @throws Exception
	 * @return array [ string, WebResponse ] A two-elements array containing the HTML output
	 * generated by the special page as well as the response object.
	 */
	protected function executeSpecialPage(
		$subPage = '',
		WebRequest $request = null,
		$language = null,
		User $user = null
	) {
		return ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->newSpecialPage(),
			$subPage,
			$request,
			$language,
			$user
		);
	}

}
