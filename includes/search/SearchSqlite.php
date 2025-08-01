<?php
/**
 * SQLite search backend, based upon SearchMysql
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
 *
 * @file
 * @ingroup Search
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\IDatabase;

/**
 * Search engine hook for SQLite
 * @ingroup Search
 */
class SearchSqlite extends SearchDatabase {
	/**
	 * Whether fulltext search is supported by current schema
	 * @return bool
	 */
	private function fulltextSearchSupported() {
		$dbr = $this->dbProvider->getReplicaDatabase();
		$sql = (string)$dbr->newSelectQueryBuilder()
			->select( 'sql' )
			->from( 'sqlite_master' )
			->where( [ 'tbl_name' => $dbr->tableName( 'searchindex', 'raw' ) ] )
			->caller( __METHOD__ )->fetchField();

		return ( stristr( $sql, 'fts' ) !== false );
	}

	/**
	 * Parse the user's query and transform it into an SQL fragment which will
	 * become part of a WHERE clause
	 *
	 * @param string $filteredText
	 * @param bool $fulltext
	 * @return string
	 */
	private function parseQuery( $filteredText, $fulltext ) {
		$lc = $this->legalSearchChars( self::CHARS_NO_SYNTAX ); // Minus syntax chars (" and *)
		$searchon = '';
		$this->searchTerms = [];

		$m = [];
		if ( preg_match_all( '/([-+<>~]?)(([' . $lc . ']+)(\*?)|"[^"]*")/',
				$filteredText, $m, PREG_SET_ORDER ) ) {
			foreach ( $m as $bits ) {
				AtEase::suppressWarnings();
				[ /* all */, $modifier, $term, $nonQuoted, $wildcard ] = $bits;
				AtEase::restoreWarnings();

				if ( $nonQuoted != '' ) {
					$term = $nonQuoted;
					$quote = '';
				} else {
					$term = str_replace( '"', '', $term );
					$quote = '"';
				}

				if ( $searchon !== '' ) {
					$searchon .= ' ';
				}

				// Some languages such as Serbian store the input form in the search index,
				// so we may need to search for matches in multiple writing system variants.

				$converter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
					->getLanguageConverter();
				$convertedVariants = $converter->autoConvertToAllVariants( $term );
				if ( is_array( $convertedVariants ) ) {
					$variants = array_unique( array_values( $convertedVariants ) );
				} else {
					$variants = [ $term ];
				}

				// The low-level search index does some processing on input to work
				// around problems with minimum lengths and encoding in MySQL's
				// fulltext engine.
				// For Chinese this also inserts spaces between adjacent Han characters.
				$strippedVariants = array_map(
					[ MediaWikiServices::getInstance()->getContentLanguage(),
						'normalizeForSearch' ],
					$variants );

				// Some languages such as Chinese force all variants to a canonical
				// form when stripping to the low-level search index, so to be sure
				// let's check our variants list for unique items after stripping.
				$strippedVariants = array_unique( $strippedVariants );

				$searchon .= $modifier;
				if ( count( $strippedVariants ) > 1 ) {
					$searchon .= '(';
				}
				$count = 0;
				foreach ( $strippedVariants as $stripped ) {
					if ( $nonQuoted && strpos( $stripped, ' ' ) !== false ) {
						// Hack for Chinese: we need to toss in quotes for
						// multiple-character phrases since normalizeForSearch()
						// added spaces between them to make word breaks.
						$stripped = '"' . trim( $stripped ) . '"';
					}
					if ( $count > 0 ) {
						$searchon .= " OR ";
					}
					$searchon .= "$quote$stripped$quote$wildcard ";
					++$count;
				}
				if ( count( $strippedVariants ) > 1 ) {
					$searchon .= ')';
				}

				// Match individual terms or quoted phrase in result highlighting...
				// Note that variants will be introduced in a later stage for highlighting!
				$regexp = $this->regexTerm( $term, $wildcard );
				$this->searchTerms[] = $regexp;
			}

		} else {
			wfDebug( __METHOD__ . ": Can't understand search query '{$filteredText}'" );
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$searchon = $dbr->addQuotes( $searchon );
		$field = $this->getIndexField( $fulltext );

		return " $field MATCH $searchon ";
	}

	private function regexTerm( string $string, string $wildcard ): string {
		$regex = preg_quote( $string, '/' );
		if ( MediaWikiServices::getInstance()->getContentLanguage()->hasWordBreaks() ) {
			if ( $wildcard ) {
				// Don't cut off the final bit!
				$regex = "\b$regex";
			} else {
				$regex = "\b$regex\b";
			}
		} else {
			// For Chinese, words may legitimately abut other words in the text literal.
			// Don't add \b boundary checks... note this could cause false positives
			// for Latin chars.
		}
		return $regex;
	}

	/** @inheritDoc */
	public function legalSearchChars( $type = self::CHARS_ALL ) {
		$searchChars = parent::legalSearchChars( $type );
		if ( $type === self::CHARS_ALL ) {
			// " for phrase, * for wildcard
			$searchChars = "\"*" . $searchChars;
		}
		return $searchChars;
	}

	/**
	 * Perform a full text search query and return a result set.
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet|null
	 */
	protected function doSearchTextInDB( $term ) {
		return $this->searchInternal( $term, true );
	}

	/**
	 * Perform a title-only search query and return a result set.
	 *
	 * @param string $term Raw search term
	 * @return SqlSearchResultSet|null
	 */
	protected function doSearchTitleInDB( $term ) {
		return $this->searchInternal( $term, false );
	}

	protected function searchInternal( string $term, bool $fulltext ): ?SqlSearchResultSet {
		if ( !$this->fulltextSearchSupported() ) {
			return null;
		}

		$filteredTerm =
			$this->filter( MediaWikiServices::getInstance()->getContentLanguage()->lc( $term ) );
		$dbr = $this->dbProvider->getReplicaDatabase();
		// The real type is still IDatabase, but IReplicaDatabase is used for safety.
		'@phan-var IDatabase $dbr';
		// phpcs:ignore MediaWiki.Usage.DbrQueryUsage.DbrQueryFound
		$resultSet = $dbr->query( $this->getQuery( $filteredTerm, $fulltext ), __METHOD__ );

		$total = null;
		// phpcs:ignore MediaWiki.Usage.DbrQueryUsage.DbrQueryFound
		$totalResult = $dbr->query( $this->getCountQuery( $filteredTerm, $fulltext ), __METHOD__ );
		$row = $totalResult->fetchObject();
		if ( $row ) {
			$total = intval( $row->c );
		}
		$totalResult->free();

		return new SqlSearchResultSet( $resultSet, $this->searchTerms, $total );
	}

	/**
	 * Return a partial WHERE clause to limit the search to the given namespaces
	 * @return string
	 */
	private function queryNamespaces() {
		if ( $this->namespaces === null ) {
			return '';  # search all
		}
		if ( $this->namespaces === [] ) {
			$namespaces = NS_MAIN;
		} else {
			$dbr = $this->dbProvider->getReplicaDatabase();
			$namespaces = $dbr->makeList( $this->namespaces );
		}
		return 'AND page_namespace IN (' . $namespaces . ')';
	}

	/**
	 * Returns a query with limit for number of results set.
	 * @param string $sql
	 * @return string
	 */
	private function limitResult( $sql ) {
		return $this->dbProvider->getReplicaDatabase()->limitResult( $sql, $this->limit, $this->offset );
	}

	/**
	 * Construct the full SQL query to do the search.
	 * The guts shoulds be constructed in queryMain()
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return string
	 */
	private function getQuery( $filteredTerm, $fulltext ) {
		return $this->limitResult(
			$this->queryMain( $filteredTerm, $fulltext ) . ' ' .
			$this->queryNamespaces()
		);
	}

	/**
	 * Picks which field to index on, depending on what type of query.
	 * @param bool $fulltext
	 * @return string
	 */
	private function getIndexField( $fulltext ) {
		return $fulltext ? 'si_text' : 'si_title';
	}

	/**
	 * Get the base part of the search query.
	 *
	 * @param string $filteredTerm
	 * @param bool $fulltext
	 * @return string
	 */
	private function queryMain( $filteredTerm, $fulltext ) {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$dbr = $this->dbProvider->getReplicaDatabase();
		$page = $dbr->tableName( 'page' );
		$searchindex = $dbr->tableName( 'searchindex' );
		return "SELECT $searchindex.rowid, page_namespace, page_title " .
			"FROM $searchindex CROSS JOIN $page " .
			"WHERE page_id=$searchindex.rowid AND $match";
	}

	private function getCountQuery( string $filteredTerm, bool $fulltext ): string {
		$match = $this->parseQuery( $filteredTerm, $fulltext );
		$dbr = $this->dbProvider->getReplicaDatabase();
		$page = $dbr->tableName( 'page' );
		$searchindex = $dbr->tableName( 'searchindex' );
		return "SELECT COUNT(*) AS c " .
			"FROM $searchindex CROSS JOIN $page " .
			"WHERE page_id=$searchindex.rowid AND $match " .
			$this->queryNamespaces();
	}

	/**
	 * Create or update the search index record for the given page.
	 * Title and text should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $text
	 */
	public function update( $id, $title, $text ) {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}
		// @todo find a method to do it in a single request,
		// couldn't do it so far due to typelessness of FTS3 tables.
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'searchindex' )
			->where( [ 'rowid' => $id ] )
			->caller( __METHOD__ )->execute();
		$dbw->newInsertQueryBuilder()
			->insertInto( 'searchindex' )
			->row( [ 'rowid' => $id, 'si_title' => $title, 'si_text' => $text ] )
			->caller( __METHOD__ )->execute();
	}

	/**
	 * Update a search index record's title only.
	 * Title should be pre-processed.
	 *
	 * @param int $id
	 * @param string $title
	 */
	public function updateTitle( $id, $title ) {
		if ( !$this->fulltextSearchSupported() ) {
			return;
		}

		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'searchindex' )
			->set( [ 'si_title' => $title ] )
			->where( [ 'rowid' => $id ] )
			->caller( __METHOD__ )->execute();
	}
}
