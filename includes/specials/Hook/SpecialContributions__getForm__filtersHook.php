<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialContributions__getForm__filtersHook {
	/**
	 * Called with a list of filters to render
	 * on Special:Contributions.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $sp SpecialContributions object, for context
	 * @param ?mixed &$filters List of filter object definitions (compatible with OOUI form)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialContributions__getForm__filters( $sp, &$filters );
}
