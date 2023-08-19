<?php

namespace MediaWiki\Hook;

use Language;
use MediaWiki\Utils\MWTimestamp;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetHumanTimestamp" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetHumanTimestampHook {
	/**
	 * Use this hook to preemptively override the human-readable timestamp
	 * generated by Language::getHumanTimestamp().
	 *
	 * @since 1.35
	 *
	 * @param string &$output Output timestamp
	 * @param MWTimestamp $timestamp Current (user-adjusted) timestamp
	 * @param MWTimestamp $relativeTo Relative (user-adjusted) timestamp
	 * @param User $user User whose preferences are being used to make timestamp
	 * @param Language $lang Language that will be used to render the timestamp
	 * @return bool|void True or no return value to continue, or false to use
	 *   the custom output
	 */
	public function onGetHumanTimestamp( &$output, $timestamp, $relativeTo, $user,
		$lang
	);
}
