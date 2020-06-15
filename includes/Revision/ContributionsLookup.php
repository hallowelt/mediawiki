<?php

namespace MediaWiki\Revision;

use ContribsPager;
use FauxRequest;
use MediaWiki\User\UserIdentity;
use RequestContext;

/**
 * @since 1.35
 */
class ContributionsLookup {

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * ContributionsLookup constructor.
	 *
	 * @param RevisionStore $revisionStore
	 */
	public function __construct( RevisionStore $revisionStore ) {
		$this->revisionStore = $revisionStore;
	}

	/**
	 * Constructs fake query parameters to be passed to ContribsPager
	 *
	 * @param int $limit Maximum number of revisions to return.
	 * @param string $segment Indicates which segment of the contributions to return.
	 * The segment should consist of 2 parts separated by a pipe character.
	 * The first part is mapped to the 'dir' parameter.
	 * The second part is mapped to the 'offset' parameter.
	 * The value for the offset is opaque and is ultimately supplied by ContribsPager::getPagingQueries().
	 * @return array
	 */
	private function getPagerParams( int $limit, string $segment ) {
		$dir = 'next';
		$seg = explode( '|', $segment, 2 );
		if ( count( $seg ) > 1 ) {
			if ( $seg[0] === 'after' ) {
				$dir = 'prev';
				$segment = $seg[1];
			} elseif ( $seg[0] == 'before' ) {
				$dir = 'next';
				$segment = $seg[1];
			} else {
				$dir = null;
				$segment = null;
			}
		} else {
			$segment = null;
		}
		return [
			'limit' => $limit,
			'offset' => $segment,
			'dir' => $dir
		];
	}

	/**
	 * @param UserIdentity $user
	 *
	 * @param int $limit
	 * @param string $segment
	 * @return ContributionsSegment
	 */
	public function getRevisionsByUser(
		UserIdentity $user,
		int $limit,
		string $segment = ''
	): ContributionsSegment {
		// FIXME: set acting user
		$context = new RequestContext();
		$paramArr = $this->getPagerParams( $limit, $segment );
		$context->setRequest( new FauxRequest( $paramArr ) );

		// TODO: explore moving this to factory method for testing
		$pager = new ContribsPager( $context, [
			'target' => $user->getName(),
		] );
		$revisions = [];
		$count = 0;
		if ( $pager->getNumRows() > 0 ) {
			foreach ( $pager->mResult as $row ) {
				// We retrieve and ignore one extra record to see if we are on the oldest segment.
				if ( ++$count > $limit ) {
					break;
				}

				// TODO: pre-load title batch?
				$revision = $this->revisionStore->newRevisionFromRow( $row, 0 );
				$revisions[] = $revision;
			}
		}

		$flags = [
			'newest' => $pager->mIsFirst,
			'oldest' => $pager->mIsLast,
		];

		// TODO: Make me an option in IndexPager
		$pager->mIsFirst = false; // XXX: nasty...
		$pagingQueries = $pager->getPagingQueries();

		$prev = $pagingQueries['prev']['offset'] ?? null;
		$next = $pagingQueries['next']['offset'] ?? null;

		$after = $prev ? 'after|' . $prev : null; // later in time
		$before = $next ? 'before|' . $next : null; // earlier in time

		// TODO: Possibly return public $pager properties to segment for populating URLS ($mIsFirst, $mIsLast)
		// HACK: Force result set order to be descending. Sorting logic in ContribsPager::reallyDoQuery is confusing.
		if ( $paramArr['dir'] === 'prev' ) {
			$revisions = array_reverse( $revisions );
		}

		return new ContributionsSegment( $revisions, $before, $after, $flags );
	}
}
