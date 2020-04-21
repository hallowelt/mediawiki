<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ContentSecurityPolicyScriptSourceHook {
	/**
	 * Modify the allowed CSP script sources.
	 * Note that you also have to use ContentSecurityPolicyDefaultSource if you
	 * want non-script sources to be loaded from
	 * whatever you add.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$scriptSrc Array of CSP directives
	 * @param ?mixed $policyConfig Current configuration for the CSP header
	 * @param ?mixed $mode ContentSecurityPolicy::REPORT_ONLY_MODE or
	 *   ContentSecurityPolicy::FULL_MODE depending on type of header
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onContentSecurityPolicyScriptSource( &$scriptSrc,
		$policyConfig, $mode
	);
}
