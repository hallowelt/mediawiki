<?php

return;

/* Disable possibility to change password
 * Maybe needed with ldap or other SSO installations
 */

 $GLOBALS['wgHooks']['BeforePageDisplay'][] = function( OutputPage &$out, Skin &$skin ) {
	if ( $out->getUser()->isAnon() ) {
		return true;
	}

	$style = "<style type=\"text/css\">\n";
	$style .= "div#userloginForm div.mw-form-related-link-container { display: none; }\n";
	$style .= "</style>\n";

	$out->addHeadItem( 'hide-password-change', $style );

	return true;

};

$GLOBALS['wgPasswordResetRoutes'] = [
	'username' => false,
	'email' => false,
];

$GLOBALS['wgHooks']['SpecialPage_initList'][] = function ( &$list ) {
	unset( $list['ChangeCredentials'] );
	return true;
};

$GLOBALS['wgHooks']['GetPreferences'][] = function ( $user, &$preferences ) {
	unset( $preferences['password']) ;
	return true;
};

$GLOBALS['wgExtensionFunctions'][] = function() {
	$GLOBALS['wgHooks']['MakeGlobalVariablesScript'][] = function ( &$vars, $out ) {
		if ( isset( $vars['bsTaskAPIPermissions'] ) ) {
			$vars['bsTaskAPIPermissions']->usermanager['editPassword'] = false;
		}
		return true;
	};
};
