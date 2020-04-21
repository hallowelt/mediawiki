<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EnhancedChangesList__getLogTextHook {
	/**
	 * to alter, remove or add to the links of a
	 * group of changes in EnhancedChangesList.
	 * Hook subscribers can return false to omit this line from recentchanges.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $changesList EnhancedChangesList object
	 * @param ?mixed &$links The links that were generated by EnhancedChangesList
	 * @param ?mixed $block The RecentChanges objects in that block
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onEnhancedChangesList__getLogText( $changesList, &$links,
		$block
	);
}
