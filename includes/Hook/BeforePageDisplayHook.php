<?php

namespace MediaWiki\Hook;

use OutputPage;
use Skin;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforePageDisplayHook {
	/**
	 * This hook is called prior to outputting a page.
	 *
	 * @since 1.35
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return bool|void This hook must not abort; it must return true or no return value
	 */
	public function onBeforePageDisplay( $out, $skin );
}
