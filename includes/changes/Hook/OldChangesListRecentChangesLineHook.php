<?php

namespace MediaWiki\Hook;

use OldChangesList;
use RecentChange;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OldChangesListRecentChangesLineHook {
	/**
	 * Use this hook to customize a recent changes line.
	 *
	 * @since 1.35
	 *
	 * @param OldChangesList $changeslist
	 * @param string &$s HTML of the form `<li>...</li>` containing one RC entry
	 * @param RecentChange $rc
	 * @param array &$classes Array of CSS classes for the `<li>` element
	 * @param array &$attribs Associative array of other HTML attributes for the `<li>` element.
	 *   Currently only data attributes reserved to MediaWiki are allowed
	 *   (see Sanitizer::isReservedDataAttribute).
	 * @return bool|void True or no return value to continue, or false to omit the line from
	 *   RecentChanges and Watchlist special pages
	 */
	public function onOldChangesListRecentChangesLine( $changeslist, &$s, $rc,
		&$classes, &$attribs
	);
}
