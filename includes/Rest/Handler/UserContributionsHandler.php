<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use RequestContext;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * @since 1.35
 */
class UserContributionsHandler extends Handler {

	/**
	 * @var ContributionsLookup
	 */
	private $contributionsLookup;

	/** Hard limit results to 20 revisions */
	private const MAX_LIMIT = 20;

	public function __construct( ContributionsLookup $contributionsLookup ) {
		$this->contributionsLookup = $contributionsLookup;
	}

	/**
	 * @return array|ResponseInterface
	 * @throws LocalizedHttpException
	 */
	public function execute() {
		// TODO: Implement execute() method.
		$user = RequestContext::getMain()->getUser();

		if ( $user->isAnon() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-anon' ), 401
			);
		}

		$limit = $this->getValidatedParams()['limit'];
		$segment = $this->getValidatedParams()['segment'];
		$tag = $this->getValidatedParams()['tag'];
		$contributionsSegment =
			$this->contributionsLookup->getContributions( $user, $limit, $user, $segment, $tag );

		$revisions = $this->getRevisionsList( $contributionsSegment );
		$urls = $this->constructURLs( $contributionsSegment );

		$response = $urls + [ 'revisions' => $revisions ];

		return $response;
	}

	/**
	 * Returns list of revisions
	 *
	 * @param ContributionsSegment $segment
	 *
	 * @return array[]
	 */
	private function getRevisionsList( ContributionsSegment $segment ) : array {
		$revisionsData = [];
		foreach ( $segment->getRevisions() as $revision ) {
			$id = $revision->getId();
			$revisionsData[] = [
				"id" => $id,
				"comment" => $revision->getComment()->text,
				"timestamp" => wfTimestamp( TS_ISO_8601, $revision->getTimestamp() ),
				"delta" => $segment->getDeltaForRevision( $id ) ,
				"size" => $revision->getSize(),
				"tags" => $segment->getTagsForRevision( $id ),
				"page" => [
					"id" => $revision->getPageId(),
					"key" => $revision->getPageAsLinkTarget()->getDBkey(),
					"title" => $revision->getPageAsLinkTarget()->getText()
				]
			];
		}
		return $revisionsData;
	}

	/**
	 * @param ContributionsSegment $segment
	 *
	 * @return string[]
	 */
	private function constructURLs( ContributionsSegment $segment ) {
		$limit = $this->getValidatedParams()['limit'];
		$tag = $this->getValidatedParams()['tag'];
		$urls = [];
		$query = [ 'limit' => $limit, 'tag' => $tag ];

		if ( $segment->isOldest() ) {
			$urls['older'] = null;
		} else {
			$urls['older'] = $this->getRouteUrl( [], $query + [ 'segment' => $segment->getBefore() ] );
		}

		$urls['newer'] = $this->getRouteUrl( [], $query + [ 'segment' => $segment->getAfter() ] );
		$urls['latest'] = $this->getRouteUrl( [], $query );
		return $urls;
	}

	public function getParamSettings() {
		return [
			'limit' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => self::MAX_LIMIT,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => self::MAX_LIMIT,
			],
			'segment' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => ''
			],
			'tag' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => null,
			],
		];
	}

}
