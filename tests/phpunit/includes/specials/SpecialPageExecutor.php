<?php

use MediaWiki\Permissions\Authority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;

/**
 * @author Addshore
 *
 * @since 1.27
 */
class SpecialPageExecutor {

	/**
	 * @param SpecialPage $page The special page to execute
	 * @param string|null $subPage The subpage parameter to call the page with
	 * @param WebRequest|null $request Web request that may contain URL parameters, etc
	 * @param Language|string|null $language The language which should be used in the context;
	 * if not specified, the pseudo-code 'qqx' is used
	 * @param Authority|null $performer The user which should be used in the context of this special page
	 * @param bool $fullHtml if true, the entirety of the generated HTML will be returned, this
	 * includes the opening <!DOCTYPE> declaration and closing </html> tag. If false, only value
	 * of OutputPage::getHTML() will be returned except if the page is redirect or where OutputPage
	 * is completely disabled.
	 *
	 * @return array [ string, WebResponse ] A two-elements array containing the HTML output
	 * generated by the special page as well as the response object.
	 */
	public function executeSpecialPage(
		SpecialPage $page,
		$subPage = '',
		WebRequest $request = null,
		$language = null,
		Authority $performer = null,
		$fullHtml = false
	) {
		$context = $this->newContext( $request, $language, $performer );

		$output = new OutputPage( $context );
		$context->setOutput( $output );

		$page->setContext( $context );
		$output->setTitle( $page->getPageTitle() );

		$html = $this->getHTMLFromSpecialPage( $page, $subPage, $fullHtml );
		$response = $context->getRequest()->response();

		if ( $response instanceof FauxResponse ) {
			$code = $response->getStatusCode();

			if ( $code > 0 ) {
				$response->header( 'Status: ' . $code . ' ' . HttpStatus::getMessage( $code ) );
			}
		}

		return [ $html, $response ];
	}

	/**
	 * @param WebRequest|null $request
	 * @param Language|string|null $language Defaults to 'qqx'
	 * @param Authority|null $performer
	 *
	 * @return DerivativeContext
	 */
	private function newContext(
		WebRequest $request = null,
		$language = null,
		Authority $performer = null
	) {
		$context = new DerivativeContext( RequestContext::getMain() );

		$context->setRequest( $request ?: new FauxRequest() );

		$context->setLanguage( $language ?: 'qqx' );

		if ( $performer !== null ) {
			$context->setAuthority( $performer );
		}

		$this->setEditTokenFromUser( $context );

		// Make sure the skin context is correctly set https://phabricator.wikimedia.org/T200771
		$context->getSkin()->setContext( $context );

		return $context;
	}

	/**
	 * If we are trying to edit and no token is set, supply one.
	 *
	 * @param DerivativeContext $context
	 */
	private function setEditTokenFromUser( DerivativeContext $context ) {
		$request = $context->getRequest();

		// Edits via GET are a security issue and should not succeed. On the other hand, not all
		// POST requests are edits, but should ignore unused parameters.
		if ( !$request->getCheck( 'wpEditToken' ) && $request->wasPosted() ) {
			$request->setVal( 'wpEditToken', $context->getUser()->getEditToken() );
		}
	}

	/**
	 * @param SpecialPage $page
	 * @param string|null $subPage
	 * @param bool $fullHtml
	 *
	 * @return string HTML
	 */
	private function getHTMLFromSpecialPage( SpecialPage $page, $subPage, $fullHtml ) {
		ob_start();

		try {
			$page->execute( $subPage );

			$output = $page->getOutput();

			if ( $output->getRedirect() !== '' ) {
				$output->output();
				$html = ob_get_contents();
			} elseif ( $output->isDisabled() ) {
				$html = ob_get_contents();
			} elseif ( $fullHtml ) {
				$html = $output->output( true );
			} else {
				$html = $output->getHTML();
			}
		} finally {
			ob_end_clean();
		}

		return $html;
	}

}
