<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialNewPagesFiltersHook {
	/**
	 * Called after building form options at NewPages.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $special the special page object
	 * @param ?mixed &$filters associative array of filter definitions. The keys are the HTML
	 *   name/URL parameters. Each key maps to an associative array with a 'msg'
	 *   (message key) and a 'default' value.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialNewPagesFilters( $special, &$filters );
}
