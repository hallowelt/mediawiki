<?php

/**
 * Search suggestion sets
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

use MediaWiki\Title\Title;

/**
 * A set of search suggestions.
 * The set is always ordered by score, with the best match first.
 */
class SearchSuggestionSet {
	/**
	 * @var SearchSuggestion[]
	 */
	private $suggestions = [];

	/**
	 * @var array
	 */
	private $pageMap = [];

	/**
	 * @var bool Are more results available?
	 */
	private $hasMoreResults;

	/**
	 * Builds a new set of suggestions.
	 *
	 * NOTE: the array should be sorted by score (higher is better),
	 * in descending order.
	 * SearchSuggestionSet will not try to re-order this input array.
	 * Providing an unsorted input array is a mistake and will lead to
	 * unexpected behaviors.
	 *
	 * @param SearchSuggestion[] $suggestions (must be sorted by score)
	 * @param bool $hasMoreResults Are more results available?
	 */
	public function __construct( array $suggestions, $hasMoreResults = false ) {
		$this->hasMoreResults = $hasMoreResults;
		foreach ( $suggestions as $suggestion ) {
			$pageID = $suggestion->getSuggestedTitleID();
			if ( $pageID && empty( $this->pageMap[$pageID] ) ) {
				$this->pageMap[$pageID] = true;
			}
			$this->suggestions[] = $suggestion;
		}
	}

	/**
	 * @return bool Are more results available?
	 */
	public function hasMoreResults() {
		return $this->hasMoreResults;
	}

	/**
	 * Get the list of suggestions.
	 * @return SearchSuggestion[]
	 */
	public function getSuggestions() {
		return $this->suggestions;
	}

	/**
	 * Call array_map on the suggestions array
	 * @param callable $callback
	 * @return array
	 */
	public function map( $callback ) {
		return array_map( $callback, $this->suggestions );
	}

	/**
	 * Filter the suggestions array
	 * @param callable $callback Callable accepting single SearchSuggestion
	 *  instance returning bool false to remove the item.
	 * @return int The number of suggestions removed
	 */
	public function filter( $callback ) {
		$before = count( $this->suggestions );
		$this->suggestions = array_values( array_filter( $this->suggestions, $callback ) );
		return $before - count( $this->suggestions );
	}

	/**
	 * Add a new suggestion at the end.
	 * If the score of the new suggestion is greater than the worst one,
	 * the new suggestion score will be updated (worst - 1).
	 */
	public function append( SearchSuggestion $suggestion ) {
		$pageID = $suggestion->getSuggestedTitleID();
		if ( $pageID && isset( $this->pageMap[$pageID] ) ) {
			return;
		}
		if ( $this->getSize() > 0 && $suggestion->getScore() >= $this->getWorstScore() ) {
			$suggestion->setScore( $this->getWorstScore() - 1 );
		}
		$this->suggestions[] = $suggestion;
		if ( $pageID ) {
			$this->pageMap[$pageID] = true;
		}
	}

	/**
	 * Add suggestion set to the end of the current one.
	 */
	public function appendAll( SearchSuggestionSet $set ) {
		foreach ( $set->getSuggestions() as $sugg ) {
			$this->append( $sugg );
		}
	}

	/**
	 * Move the suggestion at index $key to the first position
	 * @param int $key
	 */
	public function rescore( $key ) {
		$removed = array_splice( $this->suggestions, $key, 1 );
		unset( $this->pageMap[$removed[0]->getSuggestedTitleID()] );
		$this->prepend( $removed[0] );
	}

	/**
	 * Add a new suggestion at the top. If the new suggestion score
	 * is lower than the best one its score will be updated (best + 1)
	 */
	public function prepend( SearchSuggestion $suggestion ) {
		$pageID = $suggestion->getSuggestedTitleID();
		if ( $pageID && isset( $this->pageMap[$pageID] ) ) {
			return;
		}
		if ( $this->getSize() > 0 && $suggestion->getScore() <= $this->getBestScore() ) {
			$suggestion->setScore( $this->getBestScore() + 1 );
		}
		array_unshift( $this->suggestions, $suggestion );
		if ( $pageID ) {
			$this->pageMap[$pageID] = true;
		}
	}

	/**
	 * Remove a suggestion from the set.
	 * Removes the first suggestion that has the same article id or the same suggestion text.
	 * @param SearchSuggestion $suggestion
	 * @return bool true if something was removed
	 */
	public function remove( SearchSuggestion $suggestion ): bool {
		foreach ( $this->suggestions as $k => $s ) {
			$titleId = $s->getSuggestedTitleID();
			if ( ( $titleId != null && $titleId === $suggestion->getSuggestedTitleID() )
				|| $s->getText() === $suggestion->getText()
			) {
				array_splice( $this->suggestions, $k, 1 );
				unset( $this->pageMap[$s->getSuggestedTitleID()] );
				return true;
			}
		}
		return false;
	}

	/**
	 * @return float the best score in this suggestion set
	 */
	public function getBestScore() {
		if ( !$this->suggestions ) {
			return 0;
		}
		return $this->suggestions[0]->getScore();
	}

	/**
	 * @return float the worst score in this set
	 */
	public function getWorstScore() {
		if ( !$this->suggestions ) {
			return 0;
		}
		return end( $this->suggestions )->getScore();
	}

	/**
	 * @return int the number of suggestion in this set
	 */
	public function getSize() {
		return count( $this->suggestions );
	}

	/**
	 * Remove any extra elements in the suggestions set
	 * @param int $limit the max size of this set.
	 */
	public function shrink( $limit ) {
		if ( count( $this->suggestions ) > $limit ) {
			$this->suggestions = array_slice( $this->suggestions, 0, $limit );
			$this->pageMap = self::buildPageMap( $this->suggestions );
			$this->hasMoreResults = true;
		}
	}

	/**
	 * Build an array of true with the page ids present in $suggestion as keys.
	 *
	 * @param array $suggestions
	 * @return array<int,bool>
	 */
	private static function buildPageMap( array $suggestions ): array {
		$pageMap = [];
		foreach ( $suggestions as $suggestion ) {
			$pageID = $suggestion->getSuggestedTitleID();
			if ( $pageID ) {
				$pageMap[$pageID] = true;
			}
		}
		return $pageMap;
	}

	/**
	 * Builds a new set of suggestion based on a title array.
	 * Useful when using a backend that supports only Titles.
	 *
	 * NOTE: Suggestion scores will be generated.
	 *
	 * @param Title[] $titles
	 * @param bool $hasMoreResults Are more results available?
	 * @return SearchSuggestionSet
	 */
	public static function fromTitles( array $titles, $hasMoreResults = false ) {
		$score = count( $titles );
		$suggestions = array_map( static function ( $title ) use ( &$score ) {
			return SearchSuggestion::fromTitle( $score--, $title );
		}, $titles );
		return new SearchSuggestionSet( $suggestions, $hasMoreResults );
	}

	/**
	 * Builds a new set of suggestion based on a string array.
	 *
	 * NOTE: Suggestion scores will be generated.
	 *
	 * @param string[] $titles
	 * @param bool $hasMoreResults Are more results available?
	 * @return SearchSuggestionSet
	 */
	public static function fromStrings( array $titles, $hasMoreResults = false ) {
		$score = count( $titles );
		$suggestions = array_map( static function ( $title ) use ( &$score ) {
			return SearchSuggestion::fromText( $score--, $title );
		}, $titles );
		return new SearchSuggestionSet( $suggestions, $hasMoreResults );
	}

	/**
	 * @return SearchSuggestionSet an empty suggestion set
	 */
	public static function emptySuggestionSet() {
		return new SearchSuggestionSet( [] );
	}
}
