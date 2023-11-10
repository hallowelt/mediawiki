<?php

if ( !wfIsWindows() ) {
	$wgLocalTZoffset = date("Z") / 60;
}
$wgLocaltimezone = 'Europe/Berlin';
$wgNamespacesWithSubpages[NS_MAIN] = true;
$wgBlockDisablesLogin = true;
$wgEnableUploads = true;

//Default MediaWiki settings needed for BlueSpice
$GLOBALS['wgNamespacesWithSubpages'][NS_MAIN] = true;
$GLOBALS['wgCSPHeader'] = true;
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
$GLOBALS['wgMetaNamespace'] = "Site";
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
