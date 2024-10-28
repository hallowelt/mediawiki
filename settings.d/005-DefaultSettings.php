<?php

if ( !wfIsWindows() ) {
	$wgLocalTZoffset = date("Z") / 60;
}
$wgLocaltimezone = 'Europe/Berlin';
$wgBlockDisablesLogin = true;
$wgEnableUploads = true;

//Default MediaWiki settings needed for BlueSpice
$GLOBALS['wgNamespacesWithSubpages'][NS_MAIN] = true;
$GLOBALS['wgCSPHeader'] = [
	// Single quotes around 'self' are required!
	'object-src' => "'self'",
	'script-src' => [
		'*.unpkg.com',
		// Services from Extension:EmbedVideo (StarCitizen fork)
		// do not need to be set explicitly, as they will be set
		// dynamically. See https://github.com/StarCitizenWiki/mediawiki-extensions-EmbedVideo/commit/e9735f53a5fab4e6d513bcb901e98951e7dccf10
	]
];
$GLOBALS['wgBreakFrames'] = true;
$GLOBALS['bsgRSSUrlWhitelist'] = array(
	"https://blog.bluespice.com/feed/",
	"https://blog.hallowelt.com/feed/",
);
$GLOBALS['wgExternalLinkTarget'] = '_blank';
$GLOBALS['wgCapitalLinkOverrides'][ NS_FILE ] = false;
$GLOBALS['wgRestrictDisplayTitle'] = false; //Otherwise only titles that normalize to the same DB key are allowed
$GLOBALS['wgUrlProtocols'][] = "file://";
$GLOBALS['wgAllowJavaUploads'] = true;
$GLOBALS['wgParserCacheType'] = CACHE_NONE;
$GLOBALS['wgExtensionFunctions'][] = function() {
	$GLOBALS['wgMetaNamespace'] = "Site";
};
$GLOBALS['wgJobRunRate'] = 0;
$GLOBALS['wgMaxUploadSize'] = 1024 * 1024 * 1024;
$GLOBALS['wgAllowHTMLEmail'] = true;

/**
 * Allow authentication extensions like "Auth_remoteuser", "SimpleSAMLphp" or
 * "LDAPAuthentication2" to create users.
 */
$GLOBALS['wgExtensionFunctions'][] = function() {
	$GLOBALS['wgGroupPermissions']['*']['autocreateaccount'] = true;
};

/**
 * ERM20479: Prevent unregulated access to logs
 */
$GLOBALS['wgExtensionFunctions'][] = function() {
	$logKeys = $GLOBALS['wgLogTypes'];
	foreach ( $logKeys as $logKey ) {
		if ( !isset( $GLOBALS['wgLogRestrictions'][$logKey] ) ) {
			$GLOBALS['wgLogRestrictions'][$logKey] = 'wikiadmin';
		}
	}
	unset( $GLOBALS['wgLogRestrictions'][''] );
};

// Exclude these system users from user store
$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeUsers'][] = 'BSMaintenance';
$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeUsers'][] = 'DynamicPageList3 extension';
$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeUsers'][] = 'Maintenance script';
$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeUsers'][] = 'BlueSpice default';

// Set default Permissions-Policy header
$GLOBALS['bsgDefaultPermissionsPolicyHeader'] = [
	'autoplay' => '',
	'camera' => '',
	'display-capture' => '',
	'document-domain' => '',
	'encrypted-media' => '',
	'geolocation' => '',
	'microphone' => '',
	'payment' => '',
	'picture-in-picture' => '',
	'publickey-credentials-get' => '',
	'screen-wake-lock' => '',
	// Required for ExtJS class loader
	'sync-xhr' => 'self',
	'usb' => '',
	'web-share' => '',
	'xr-spatial-tracking' => ''
];
$GLOBALS['wgHooks']['BeforePageDisplay'][] = function() {
	$policies = $GLOBALS['bsgDefaultPermissionsPolicyHeader'];
	$response = RequestContext::getMain()->getOutput()->getRequest()->response();
	$policiesText = implode( ',', array_map( function( $key, $value ) {
		return "$key=($value)";
	}, array_keys( $policies ), $policies ) );
	$response->header( "Permissions-Policy: $policiesText" );
};
