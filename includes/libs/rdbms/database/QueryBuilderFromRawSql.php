<?php
/**
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
namespace Wikimedia\Rdbms;

use Wikimedia\Rdbms\Platform\SQLPlatform;

/**
 * This is to contain any regex on SQL work and get rid of them eventually
 *
 * This is a radioactive swamp and an extremely flawed and buggy last resort
 * for when the information has not been provided via Query object.
 * Bugs are to be expected in the regexes here.
 *
 * @ingroup Database
 * @internal
 * @since 1.41
 */
class QueryBuilderFromRawSql {

	/**
	 * @param string $sql
	 * @param int $flags
	 * @param string $tablePrefix
	 * @return Query
	 */
	public static function buildQuery( string $sql, $flags, string $tablePrefix = '' ) {
		if ( !$flags ) {
			if ( !self::isWriteQuery( $sql ) ) {
				$flags = SQLPlatform::QUERY_CHANGE_NONE;
			} else {
				$flags = SQLPlatform::QUERY_CHANGE_ROWS;
			}
			if ( self::isCreateTemporaryTable( $sql ) ) {
				$flags |= SQLPlatform::QUERY_CREATE_TEMP;
			}
		}
		$queryVerb = self::getQueryVerb( $sql );
		$writeTable = self::getWriteTable( $sql, $tablePrefix );
		return new Query( $sql, $flags, $queryVerb, $writeTable );
	}

	private static function isWriteQuery( $rawSql ) {
		// Treat SELECT queries without FOR UPDATE queries as non-writes. This matches
		// how MySQL enforces read_only (FOR SHARE and LOCK IN SHADE MODE are allowed).
		// Handle (SELECT ...) UNION (SELECT ...) queries in a similar fashion.
		if ( preg_match( '/^\s*\(?SELECT\b/i', $rawSql ) ) {
			return (bool)preg_match( '/\bFOR\s+UPDATE\)?\s*$/i', $rawSql );
		}
		// BEGIN and COMMIT queries are considered non-write queries here.
		// Database backends and drivers (MySQL, MariaDB, php-mysqli) generally
		// treat these as write queries, in that their results have "affected rows"
		// as meta data as from writes, instead of "num rows" as from reads.
		// But, we treat them as non-write queries because when reading data (from
		// either replica or primary DB) we use transactions to enable repeatable-read
		// snapshots, which ensures we get consistent results from the same snapshot
		// for all queries within a request. Use cases:
		// - Treating these as writes would trigger ChronologyProtector (see method doc).
		// - We use this method to reject writes to replicas, but we need to allow
		//   use of transactions on replicas for read snapshots. This is fine given
		//   that transactions by themselves don't make changes, only actual writes
		//   within the transaction matter, which we still detect.
		return !preg_match(
			'/^\s*(BEGIN|ROLLBACK|COMMIT|SAVEPOINT|RELEASE|SET|SHOW|EXPLAIN|USE)\b/i',
			$rawSql
		);
	}

	/**
	 * @param string $sql SQL query
	 * @return string
	 */
	private static function getQueryVerb( $sql ) {
		// Distinguish ROLLBACK from ROLLBACK TO SAVEPOINT and CREATE from CREATE TEMPORARY
		return preg_match(
			'/^\s*(rollback\s+to\s+savepoint|create\s+temporary|[a-z]+)/i',
			$sql,
			$m
		) ? strtoupper( $m[1] ) : '';
	}

	/**
	 * @param string $sql
	 * @param string $tablePrefix
	 * @return string|null
	 */
	private static function getWriteTable( $sql, $tablePrefix ) {
		// Regex for basic queries that can create/change/drop temporary tables.
		// For simplicity, this only looks for tables with sensible alphanumeric names.
		// Temporary tables only need simple programming names anyway.
		$regex = <<<REGEX
		/^
			(?:
				(?:INSERT|REPLACE)\s+(?:\w+\s+)*?INTO
				| UPDATE(?:\s+OR\s+\w+|\s+IGNORE|\s+ONLY)?
				| DELETE\s+(?:\w+\s+)*?FROM(?:\s+ONLY)?
				| CREATE\s+(?:TEMPORARY\s+)?TABLE(?:\s+IF\s+NOT\s+EXISTS)?
				| DROP\s+(?:TEMPORARY\s+)?TABLE(?:\s+IF\s+EXISTS)?
				| TRUNCATE\s+(?:TEMPORARY\s+)?TABLE
				| ALTER\s+TABLE
			) \s+
			(\w+|`\w+`|'\w+'|"\w+")
		/ix
		REGEX;
		if ( preg_match( $regex, $sql, $m ) ) {
			$tableName = trim( $m[1], "\"'`" );
			if ( str_starts_with( $tableName, $tablePrefix ) ) {
				$tableName = substr( $tableName, strlen( $tablePrefix ) );
			}
			return $tableName;
		}
		return null;
	}

	private static function isCreateTemporaryTable( $sql ) {
		return (bool)preg_match( '/^CREATE\s+TEMPORARY\s+TABLE\s+/i', $sql );
	}

	/**
	 * Removes most variables from an SQL query and replaces them with X or N for numbers.
	 * It's only slightly flawed. Don't use for anything important.
	 *
	 * @param string $sql A SQL Query
	 *
	 * @return string
	 */
	public static function generalizeSQL( $sql ) {
		# This does the same as the regexp below would do, but in such a way
		# as to avoid crashing php on some large strings.
		# $sql = preg_replace( "/'([^\\\\']|\\\\.)*'|\"([^\\\\\"]|\\\\.)*\"/", "'X'", $sql );

		$sql = str_replace( "\\\\", '', $sql );
		$sql = str_replace( "\\'", '', $sql );
		$sql = str_replace( "\\\"", '', $sql );
		$sql = preg_replace( "/'.*'/s", "'X'", $sql );
		$sql = preg_replace( '/".*"/s', "'X'", $sql );

		# All newlines, tabs, etc replaced by single space
		$sql = preg_replace( '/\s+/', ' ', $sql );

		# All numbers => N,
		# except the ones surrounded by characters, e.g. l10n
		$sql = preg_replace( '/-?\d+(,-?\d+)+/', 'N,...,N', $sql );
		$sql = preg_replace( '/(?<![a-zA-Z])-?\d+(?![a-zA-Z])/', 'N', $sql );

		return $sql;
	}
}
