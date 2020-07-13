<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This is a base class for all Query modules.
 * It provides some common functionality such as constructing various SQL
 * queries.
 *
 * @stable for subclassing
 *
 * @ingroup API
 */
abstract class ApiQueryBase extends ApiBase {
	use ApiQueryBlockInfoTrait;

	private $mQueryModule, $mDb;

	/**
	 * @var SelectQueryBuilder
	 */
	private $queryBuilder;

	/**
	 * @stable to call
	 * @param ApiQuery $queryModule
	 * @param string $moduleName
	 * @param string $paramPrefix
	 */
	public function __construct( ApiQuery $queryModule, $moduleName, $paramPrefix = '' ) {
		parent::__construct( $queryModule->getMain(), $moduleName, $paramPrefix );
		$this->mQueryModule = $queryModule;
		$this->mDb = null;
		$this->resetQueryParams();
	}

	/************************************************************************//**
	 * @name   Methods to implement
	 * @{
	 */

	/**
	 * Get the cache mode for the data generated by this module. Override
	 * this in the module subclass. For possible return values and other
	 * details about cache modes, see ApiMain::setCacheMode()
	 *
	 * Public caching will only be allowed if *all* the modules that supply
	 * data for a given request return a cache mode of public.
	 *
	 * @stable to override
	 * @param array $params
	 * @return string
	 */
	public function getCacheMode( $params ) {
		return 'private';
	}

	/**
	 * Override this method to request extra fields from the pageSet
	 * using $pageSet->requestField('fieldName')
	 *
	 * Note this only makes sense for 'prop' modules, as 'list' and 'meta'
	 * modules should not be using the pageset.
	 *
	 * @stable to override
	 * @param ApiPageSet $pageSet
	 */
	public function requestExtraData( $pageSet ) {
	}

	/** @} */

	/************************************************************************//**
	 * @name   Data access
	 * @{
	 */

	/**
	 * Get the main Query module
	 * @return ApiQuery
	 */
	public function getQuery() {
		return $this->mQueryModule;
	}

	/** @inheritDoc */
	public function getParent() {
		return $this->getQuery();
	}

	/**
	 * Get the Query database connection (read-only)
	 * @stable to override
	 * @return IDatabase
	 */
	protected function getDB() {
		if ( $this->mDb === null ) {
			$this->mDb = $this->getQuery()->getDB();
		}

		return $this->mDb;
	}

	/**
	 * Selects the query database connection with the given name.
	 * See ApiQuery::getNamedDB() for more information
	 * @param string $name Name to assign to the database connection
	 * @param int $db One of the DB_* constants
	 * @param string|string[] $groups Query groups
	 * @return IDatabase
	 */
	public function selectNamedDB( $name, $db, $groups ) {
		$this->mDb = $this->getQuery()->getNamedDB( $name, $db, $groups );
		return $this->mDb;
	}

	/**
	 * Get the PageSet object to work on
	 * @stable to override
	 * @return ApiPageSet
	 */
	protected function getPageSet() {
		return $this->getQuery()->getPageSet();
	}

	/** @} */

	/************************************************************************//**
	 * @name   Querying
	 * @{
	 */

	/**
	 * Blank the internal arrays with query parameters
	 */
	protected function resetQueryParams() {
		$this->queryBuilder = null;
	}

	/**
	 * Get the SelectQueryBuilder.
	 *
	 * This is lazy initialised since getDB() fails in ApiQueryAllImages if it
	 * is called before the constructor completes.
	 *
	 * @return SelectQueryBuilder
	 */
	protected function getQueryBuilder() {
		if ( $this->queryBuilder === null ) {
			$this->queryBuilder = $this->getDB()->newSelectQueryBuilder();
		}
		return $this->queryBuilder;
	}

	/**
	 * Add a set of tables to the internal array
	 * @param string|array $tables Table name or array of table names
	 *  or nested arrays for joins using parentheses for grouping
	 * @param string|null $alias Table alias, or null for no alias. Cannot be
	 *  used with multiple tables
	 */
	protected function addTables( $tables, $alias = null ) {
		if ( is_array( $tables ) ) {
			if ( $alias !== null ) {
				ApiBase::dieDebug( __METHOD__, 'Multiple table aliases not supported' );
			}
			$this->getQueryBuilder()->rawTables( $tables );
		} else {
			$this->getQueryBuilder()->table( $tables, $alias );
		}
	}

	/**
	 * Add a set of JOIN conditions to the internal array
	 *
	 * JOIN conditions are formatted as [ tablename => [ jointype, conditions ] ]
	 * e.g. [ 'page' => [ 'LEFT JOIN', 'page_id=rev_page' ] ].
	 * Conditions may be a string or an addWhere()-style array.
	 * @param array $join_conds JOIN conditions
	 */
	protected function addJoinConds( $join_conds ) {
		if ( !is_array( $join_conds ) ) {
			ApiBase::dieDebug( __METHOD__, 'Join conditions have to be arrays' );
		}
		$this->getQueryBuilder()->joinConds( $join_conds );
	}

	/**
	 * Add a set of fields to select to the internal array
	 * @param array|string $value Field name or array of field names
	 */
	protected function addFields( $value ) {
		$this->getQueryBuilder()->fields( $value );
	}

	/**
	 * Same as addFields(), but add the fields only if a condition is met
	 * @param array|string $value See addFields()
	 * @param bool $condition If false, do nothing
	 * @return bool $condition
	 */
	protected function addFieldsIf( $value, $condition ) {
		if ( $condition ) {
			$this->addFields( $value );

			return true;
		}

		return false;
	}

	/**
	 * Add a set of WHERE clauses to the internal array.
	 *
	 * The array should be appropriate for passing as $conds to
	 * IDatabase::select(). Arrays from multiple calls are merged with
	 * array_merge(). A string is treated as a single-element array.
	 *
	 * When passing `'field' => $arrayOfIDs` where the IDs are taken from user
	 * input, consider using addWhereIDsFld() instead.
	 *
	 * @see IDatabase::select()
	 * @param string|array $value
	 */
	protected function addWhere( $value ) {
		if ( is_array( $value ) ) {
			// Sanity check: don't insert empty arrays,
			// Database::makeList() chokes on them
			if ( count( $value ) ) {
				$this->getQueryBuilder()->where( $value );
			}
		} else {
			$this->getQueryBuilder()->where( $value );
		}
	}

	/**
	 * Same as addWhere(), but add the WHERE clauses only if a condition is met
	 * @param string|array $value
	 * @param bool $condition If false, do nothing
	 * @return bool $condition
	 */
	protected function addWhereIf( $value, $condition ) {
		if ( $condition ) {
			$this->addWhere( $value );

			return true;
		}

		return false;
	}

	/**
	 * Equivalent to addWhere( [ $field => $value ] )
	 *
	 * When $value is an array of integer IDs taken from user input,
	 * consider using addWhereIDsFld() instead.
	 *
	 * @param string $field Field name
	 * @param int|string|string[]|int[] $value Value; ignored if null or empty array
	 */
	protected function addWhereFld( $field, $value ) {
		if ( $value !== null && !( is_array( $value ) && !$value ) ) {
			$this->getQueryBuilder()->where( [ $field => $value ] );
		}
	}

	/**
	 * Like addWhereFld for an integer list of IDs
	 *
	 * When passed wildly out-of-range values for integer comparison,
	 * the database may choose a poor query plan. This method validates the
	 * passed IDs against the range of values in the database to omit
	 * out-of-range values.
	 *
	 * This should be used when the IDs are derived from arbitrary user input;
	 * it is not necessary if the IDs are already known to be within a sensible
	 * range.
	 *
	 * This should not be used when there is not a suitable index on $field to
	 * quickly retrieve the minimum and maximum values.
	 *
	 * @since 1.33
	 * @param string $table Table name
	 * @param string $field Field name
	 * @param int[] $ids IDs
	 * @return int Count of IDs actually included
	 */
	protected function addWhereIDsFld( $table, $field, $ids ) {
		// Use count() to its full documented capabilities to simultaneously
		// test for null, empty array or empty countable object
		if ( count( $ids ) ) {
			$ids = $this->filterIDs( [ [ $table, $field ] ], $ids );

			if ( $ids === [] ) {
				// Return nothing, no IDs are valid
				$this->getQueryBuilder()->where( '0 = 1' );
			} else {
				$this->getQueryBuilder()->where( [ $field => $ids ] );
			}
		}
		return count( $ids );
	}

	/**
	 * Add a WHERE clause corresponding to a range, and an ORDER BY
	 * clause to sort in the right direction
	 * @param string $field Field name
	 * @param string $dir If 'newer', sort in ascending order, otherwise
	 *  sort in descending order
	 * @param string|null $start Value to start the list at. If $dir == 'newer'
	 *  this is the lower boundary, otherwise it's the upper boundary
	 * @param string|null $end Value to end the list at. If $dir == 'newer' this
	 *  is the upper boundary, otherwise it's the lower boundary
	 * @param bool $sort If false, don't add an ORDER BY clause
	 */
	protected function addWhereRange( $field, $dir, $start, $end, $sort = true ) {
		$isDirNewer = ( $dir === 'newer' );
		$after = ( $isDirNewer ? '>=' : '<=' );
		$before = ( $isDirNewer ? '<=' : '>=' );
		$db = $this->getDB();

		if ( $start !== null ) {
			$this->addWhere( $field . $after . $db->addQuotes( $start ) );
		}

		if ( $end !== null ) {
			$this->addWhere( $field . $before . $db->addQuotes( $end ) );
		}

		if ( $sort ) {
			$this->getQueryBuilder()->orderBy( $field, $isDirNewer ? null : 'DESC' );
		}
	}

	/**
	 * Add a WHERE clause corresponding to a range, similar to addWhereRange,
	 * but converts $start and $end to database timestamps.
	 * @see addWhereRange
	 * @param string $field
	 * @param string $dir
	 * @param string|int|null $start
	 * @param string|int|null $end
	 * @param bool $sort
	 */
	protected function addTimestampWhereRange( $field, $dir, $start, $end, $sort = true ) {
		$db = $this->getDB();
		$this->addWhereRange( $field, $dir,
			$db->timestampOrNull( $start ), $db->timestampOrNull( $end ), $sort );
	}

	/**
	 * Add an option such as LIMIT or USE INDEX. If an option was set
	 * before, the old value will be overwritten
	 * @param string $name Option name
	 * @param int|string|string[]|null $value Option value
	 */
	protected function addOption( $name, $value = null ) {
		$this->getQueryBuilder()->option( $name, $value );
	}

	/**
	 * Execute a SELECT query based on the values in the internal arrays
	 * @param string $method Function the query should be attributed to.
	 *  You should usually use __METHOD__ here
	 * @param array $extraQuery Query data to add but not store in the object
	 *  Format is [
	 *    'tables' => ...,
	 *    'fields' => ...,
	 *    'where' => ...,
	 *    'options' => ...,
	 *    'join_conds' => ...
	 *  ]
	 * @param array|null &$hookData If set, the ApiQueryBaseBeforeQuery and
	 *  ApiQueryBaseAfterQuery hooks will be called, and the
	 *  ApiQueryBaseProcessRow hook will be expected.
	 * @return IResultWrapper
	 */
	protected function select( $method, $extraQuery = [], array &$hookData = null ) {
		$queryBuilder = clone $this->getQueryBuilder();
		if ( isset( $extraQuery['tables'] ) ) {
			$queryBuilder->rawTables( (array)$extraQuery['tables'] );
		}
		if ( isset( $extraQuery['fields'] ) ) {
			$queryBuilder->fields( (array)$extraQuery['fields'] );
		}
		if ( isset( $extraQuery['where'] ) ) {
			$queryBuilder->where( (array)$extraQuery['where'] );
		}
		if ( isset( $extraQuery['options'] ) ) {
			$queryBuilder->options( (array)$extraQuery['options'] );
		}
		if ( isset( $extraQuery['join_conds'] ) ) {
			$queryBuilder->joinConds( (array)$extraQuery['join_conds'] );
		}

		if ( $hookData !== null && Hooks::isRegistered( 'ApiQueryBaseBeforeQuery' ) ) {
			$info = $queryBuilder->getQueryInfo();
			$this->getHookRunner()->onApiQueryBaseBeforeQuery(
				$this, $info['tables'], $info['fields'], $info['conds'],
				$info['options'], $info['join_conds'], $hookData
			);
			$queryBuilder = $this->getDB()->newSelectQueryBuilder()->queryInfo( $info );
		}

		$queryBuilder->caller( $method );
		$res = $queryBuilder->fetchResultSet();

		if ( $hookData !== null ) {
			$this->getHookRunner()->onApiQueryBaseAfterQuery( $this, $res, $hookData );
		}

		return $res;
	}

	/**
	 * Call the ApiQueryBaseProcessRow hook
	 *
	 * Generally, a module that passed $hookData to self::select() will call
	 * this just before calling ApiResult::addValue(), and treat a false return
	 * here in the same way it treats a false return from addValue().
	 *
	 * @since 1.28
	 * @param object $row Database row
	 * @param array &$data Data to be added to the result
	 * @param array &$hookData Hook data from ApiQueryBase::select()
	 * @return bool Return false if row processing should end with continuation
	 */
	protected function processRow( $row, array &$data, array &$hookData ) {
		return $this->getHookRunner()->onApiQueryBaseProcessRow( $this, $row, $data, $hookData );
	}

	/** @} */

	/************************************************************************//**
	 * @name   Utility methods
	 * @{
	 */

	/**
	 * Add information (title and namespace) about a Title object to a
	 * result array
	 * @param array &$arr Result array à la ApiResult
	 * @param Title $title
	 * @param string $prefix Module prefix
	 */
	public static function addTitleInfo( &$arr, $title, $prefix = '' ) {
		$arr[$prefix . 'ns'] = (int)$title->getNamespace();
		$arr[$prefix . 'title'] = $title->getPrefixedText();
	}

	/**
	 * Add a sub-element under the page element with the given page ID
	 * @param int $pageId Page ID
	 * @param array $data Data array à la ApiResult
	 * @return bool Whether the element fit in the result
	 */
	protected function addPageSubItems( $pageId, $data ) {
		$result = $this->getResult();
		ApiResult::setIndexedTagName( $data, $this->getModulePrefix() );

		return $result->addValue( [ 'query', 'pages', (int)$pageId ],
			$this->getModuleName(),
			$data );
	}

	/**
	 * Same as addPageSubItems(), but one element of $data at a time
	 * @param int $pageId Page ID
	 * @param mixed $item Data à la ApiResult
	 * @param string|null $elemname XML element name. If null, getModuleName()
	 *  is used
	 * @return bool Whether the element fit in the result
	 */
	protected function addPageSubItem( $pageId, $item, $elemname = null ) {
		if ( $elemname === null ) {
			$elemname = $this->getModulePrefix();
		}
		$result = $this->getResult();
		$fit = $result->addValue( [ 'query', 'pages', $pageId,
			$this->getModuleName() ], null, $item );
		if ( !$fit ) {
			return false;
		}
		$result->addIndexedTagName( [ 'query', 'pages', $pageId,
			$this->getModuleName() ], $elemname );

		return true;
	}

	/**
	 * Set a query-continue value
	 * @param string $paramName Parameter name
	 * @param int|string|array $paramValue Parameter value
	 */
	protected function setContinueEnumParameter( $paramName, $paramValue ) {
		$this->getContinuationManager()->addContinueParam( $this, $paramName, $paramValue );
	}

	/**
	 * Convert an input title or title prefix into a dbkey.
	 *
	 * $namespace should always be specified in order to handle per-namespace
	 * capitalization settings.
	 *
	 * @param string $titlePart Title part
	 * @param int $namespace Namespace of the title
	 * @return string DBkey (no namespace prefix)
	 */
	public function titlePartToKey( $titlePart, $namespace = NS_MAIN ) {
		$t = Title::makeTitleSafe( $namespace, $titlePart . 'x' );
		if ( !$t || $t->hasFragment() ) {
			// Invalid title (e.g. bad chars) or contained a '#'.
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $titlePart ) ] );
		}
		if ( $namespace != $t->getNamespace() || $t->isExternal() ) {
			// This can happen in two cases. First, if you call titlePartToKey with a title part
			// that looks like a namespace, but with $defaultNamespace = NS_MAIN. It would be very
			// difficult to handle such a case. Such cases cannot exist and are therefore treated
			// as invalid user input. The second case is when somebody specifies a title interwiki
			// prefix.
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $titlePart ) ] );
		}

		return substr( $t->getDBkey(), 0, -1 );
	}

	/**
	 * Convert an input title or title prefix into a TitleValue.
	 *
	 * @since 1.35
	 * @param string $titlePart Title part
	 * @param int $defaultNamespace Default namespace if none is given
	 * @return TitleValue
	 */
	protected function parsePrefixedTitlePart( $titlePart, $defaultNamespace = NS_MAIN ) {
		try {
			$titleParser = MediaWikiServices::getInstance()->getTitleParser();
			$t = $titleParser->parseTitle( $titlePart . 'X', $defaultNamespace );
		} catch ( MalformedTitleException $e ) {
			$t = null;
		}

		if ( !$t || $t->hasFragment() || $t->isExternal() || $t->getDBkey() === 'X' ) {
			// Invalid title (e.g. bad chars) or contained a '#'.
			$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $titlePart ) ] );
		}

		return new TitleValue( $t->getNamespace(), substr( $t->getDBkey(), 0, -1 ) );
	}

	/**
	 * Convert an input title or title prefix into a namespace constant and dbkey.
	 *
	 * @since 1.26
	 * @deprecated sine 1.35, use parsePrefixedTitlePart() instead.
	 * @param string $titlePart Title part parsePrefixedTitlePart instead
	 * @param int $defaultNamespace Default namespace if none is given
	 * @return array (int, string) Namespace number and DBkey
	 */
	public function prefixedTitlePartToKey( $titlePart, $defaultNamespace = NS_MAIN ) {
		wfDeprecated( __METHOD__, '1.35' );
		$t = $this->parsePrefixedTitlePart( $titlePart, $defaultNamespace );
		return [ $t->getNamespace(), $t->getDBkey() ];
	}

	/**
	 * @param string $hash
	 * @return bool
	 */
	public function validateSha1Hash( $hash ) {
		return (bool)preg_match( '/^[a-f0-9]{40}$/', $hash );
	}

	/**
	 * @param string $hash
	 * @return bool
	 */
	public function validateSha1Base36Hash( $hash ) {
		return (bool)preg_match( '/^[a-z0-9]{31}$/', $hash );
	}

	/**
	 * Check whether the current user has permission to view revision-deleted
	 * fields.
	 * @return bool
	 */
	public function userCanSeeRevDel() {
		return $this->getPermissionManager()->userHasAnyRight(
			$this->getUser(),
			'deletedhistory',
			'deletedtext',
			'suppressrevision',
			'viewsuppressed'
		);
	}

	/**
	 * Preprocess the result set to fill the GenderCache with the necessary information
	 * before using self::addTitleInfo
	 *
	 * @param IResultWrapper $res Result set to work on.
	 *  The result set must have _namespace and _title fields with the provided field prefix
	 * @param string $fname The caller function name, always use __METHOD__
	 * @param string $fieldPrefix Prefix for fields to check gender for
	 */
	protected function executeGenderCacheFromResultWrapper(
		IResultWrapper $res, $fname = __METHOD__, $fieldPrefix = 'page'
	) {
		if ( !$res->numRows() ) {
			return;
		}

		$services = MediaWikiServices::getInstance();
		if ( !$services->getContentLanguage()->needsGenderDistinction() ) {
			return;
		}

		$nsInfo = $services->getNamespaceInfo();
		$namespaceField = $fieldPrefix . '_namespace';
		$titleField = $fieldPrefix . '_title';

		$usernames = [];
		foreach ( $res as $row ) {
			if ( $nsInfo->hasGenderDistinction( $row->$namespaceField ) ) {
				$usernames[] = $row->$titleField;
			}
		}

		if ( $usernames === [] ) {
			return;
		}

		$genderCache = $services->getGenderCache();
		$genderCache->doQuery( $usernames, $fname );
	}

	/** @} */

	/************************************************************************//**
	 * @name   Deprecated methods
	 * @{
	 */

	/**
	 * Filters hidden users (where the user doesn't have the right to view them)
	 * Also adds relevant block information
	 *
	 * @deprecated since 1.34, use ApiQueryBlockInfoTrait instead
	 * @param bool $showBlockInfo
	 */
	public function showHiddenUsersAddBlockInfo( $showBlockInfo ) {
		wfDeprecated( __METHOD__, '1.34' );
		$this->addBlockInfoToQuery( $showBlockInfo );
	}

	/** @} */
}
