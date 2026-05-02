<?php

namespace MediaWiki\Search;

/**
 * BaseSearchResultSet is the base class that must be extended by SearchEngine
 * search result set implementations.
 *
 * This base class is meant to hold B/C behaviors and to be useful it must never:
 * - be used as type hints (ISearchResultSet must be used for this)
 * - implement a constructor
 * - declare utility methods
 *
 * @stable to extend
 * @ingroup Search
 */
abstract class BaseSearchResultSet implements ISearchResultSet {

	/**
	 * @inheritDoc
	 */
	public function isApproximateTotalHits(): bool {
		return false;
	}

	/**
	 * Fetch an array of regular expression fragments for matching
	 * the search terms as parsed by this engine in a text extract.
	 * STUB
	 *
	 * @return string[]
	 * @deprecated since 1.34 (use SqlSearchResult)
	 */
	public function termMatches() {
		return [];
	}

	/**
	 * Frees the result set, if applicable.
	 * @deprecated since 1.34; noop
	 */
	public function free() {
	}
}
/** @deprecated class alias since 1.46 */
class_alias( BaseSearchResultSet::class, 'BaseSearchResultSet' );
