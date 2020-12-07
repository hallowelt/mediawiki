<?php

namespace MediaWiki\Rest\Handler;

use Config;
use LogicException;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use MediaWiki\Revision\RevisionLookup;
use RequestContext;
use Title;
use TitleFactory;
use TitleFormatter;
use Wikimedia\Assert\Assert;

/**
 * A handler that returns Parsoid HTML for the following routes:
 * - /page/{title}/html,
 * - /page/{title}/with_html
 * - /page/{title}/bare routes.
 * Currently the HTML is fetched from RESTBase, thus in order to use the routes,
 * RESTBase must be installed and VirtualRESTService for RESTBase needs to be configured.
 *
 * Class PageHTMLHandler
 * @package MediaWiki\Rest\Handler
 */
class PageHTMLHandler extends SimpleHandler {

	/** @var ParsoidHTMLHelper */
	private $htmlHelper;

	/** @var PageContentHelper */
	private $contentHelper;

	public function __construct(
		Config $config,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory,
		ParserCacheFactory $parserCacheFactory,
		WikiPageFactory $wikiPageFactory
	) {
		$this->contentHelper = new PageContentHelper(
			$config,
			$permissionManager,
			$revisionLookup,
			$titleFormatter,
			$titleFactory
		);
		$this->htmlHelper = new ParsoidHTMLHelper(
			$parserCacheFactory->getInstance( 'parsoid' ),
			$wikiPageFactory
		);
	}

	protected function postValidationSetup() {
		// TODO: inject user properly
		$user = RequestContext::getMain()->getUser();
		$this->contentHelper->init( $user, $this->getValidatedParams() );

		$title = $this->contentHelper->getTitle();
		if ( $title ) {
			$this->htmlHelper->init( $title );
		}
	}

	/**
	 * @param Title $title
	 * @return string
	 */
	private function constructHtmlUrl( Title $title ): string {
		return $this->getRouter()->getRouteUrl(
			'/v1/page/{title}/html',
			[ 'title' => $title->getPrefixedText() ]
		);
	}

	/**
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run(): Response {
		$this->contentHelper->checkAccess();

		$titleObj = $this->contentHelper->getTitle();

		// The call to $this->contentHelper->getTitle() should not return null if
		// $this->contentHelper->checkAccess() did not throw.
		Assert::invariant( $titleObj !== null, 'Title should be known' );

		$htmlType = $this->getHtmlType();
		switch ( $htmlType ) {
			case 'bare':
				$body = $this->contentHelper->constructMetadata();
				$body['html_url'] = $this->constructHtmlUrl( $titleObj );
				$response = $this->getResponseFactory()->createJson( $body );
				$this->contentHelper->setCacheControl( $response );
				break;
			case 'html':
				$parserOutput = $this->htmlHelper->getHtml();
				$response = $this->getResponseFactory()->create();
				// TODO: need to respect content-type returned by Parsoid.
				$response->setHeader( 'Content-Type', 'text/html' );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				$response->setBody( new StringStream( $parserOutput->getText() ) );
				break;
			case 'with_html':
				$parserOutput = $this->htmlHelper->getHtml();
				$body = $this->contentHelper->constructMetadata();
				$body['html'] = $parserOutput->getText();
				$response = $this->getResponseFactory()->createJson( $body );
				$this->contentHelper->setCacheControl( $response, $parserOutput->getCacheExpiry() );
				break;
			default:
				throw new LogicException( "Unknown HTML type $htmlType" );
		}

		return $response;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string|null
	 */
	protected function getETag(): ?string {
		if ( !$this->contentHelper->isAccessible() ) {
			return null;
		}

		if ( $this->getHtmlType() === 'bare' ) {
			return $this->contentHelper->getETag();
		} else {
			return $this->htmlHelper->getETag();
		}
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		if ( !$this->contentHelper->isAccessible() ) {
			return null;
		}

		if ( $this->getHtmlType() === 'bare' ) {
			return $this->contentHelper->getLastModified();
		} else {
			return $this->htmlHelper->getLastModified();
		}
	}

	private function getHtmlType(): string {
		return $this->getConfig()['format'];
	}

	public function needsWriteAccess(): bool {
		return false;
	}

	public function getParamSettings(): array {
		return $this->contentHelper->getParamSettings();
	}
}
