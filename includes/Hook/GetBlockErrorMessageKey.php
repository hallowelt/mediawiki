<?php

namespace MediaWiki\Hook;

use MediaWiki\Block\Block;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetBlockErrorMessageKey" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetBlockErrorMessageKey {
	/**
	 * This hook is called in BlockErrorFormatter to allow
	 * extensions to override the message that will be displayed
	 * to the user.
	 *
	 * @since 1.40
	 *
	 * @param Block $block
	 * @param string &$key
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetBlockErrorMessageKey( Block $block, string &$key );
}
