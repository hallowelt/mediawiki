<?php

$GLOBALS['wgBlockDisablesLogin'] = true;
$GLOBALS['wgEnableUploads'] = true;

//Default MediaWiki settings needed for BlueSpice
$GLOBALS['wgCapitalLinkOverrides'][NS_FILE] = false;
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
		if ( $logKey === 'upload' ) {
			continue;
		}
		if ( !isset( $GLOBALS['wgLogRestrictions'][$logKey] ) ) {
			$GLOBALS['wgLogRestrictions'][$logKey] = 'wikiadmin';
		}
	}
	unset( $GLOBALS['wgLogRestrictions'][''] );
};

// Exclude these system users from user store
$GLOBALS['wgReservedUsernames'][] = 'BlueSpice default';
$GLOBALS['wgReservedUsernames'][] = 'Bluespice default';
$GLOBALS['wgReservedUsernames'][] = 'BSMaintenance';
$GLOBALS['wgReservedUsernames'][] = 'Bsmaintenance';
$GLOBALS['wgReservedUsernames'][] = 'ContentStabilizationBot';
$GLOBALS['wgReservedUsernames'][] = 'Contentstabilizationbot';
$GLOBALS['wgReservedUsernames'][] = 'ContentTransferBot';
$GLOBALS['wgReservedUsernames'][] = 'Contenttransferbot';
$GLOBALS['wgReservedUsernames'][] = 'ContentTransfer bot';
$GLOBALS['wgReservedUsernames'][] = 'DynamicPageList3 extension';
$GLOBALS['wgReservedUsernames'][] = 'Dynamicpagelist3 extension';
$GLOBALS['wgReservedUsernames'][] = 'Maintenance script';
$GLOBALS['wgReservedUsernames'][] = 'Mediawiki default';
$GLOBALS['wgReservedUsernames'][] = 'MediaWiki default';
$GLOBALS['wgReservedUsernames'][] = 'Spam cleanup script';

$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeUsers'] = array_merge(
	$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeUsers'],
	$GLOBALS['wgReservedUsernames']
);


// Set default Permissions-Policy header
$GLOBALS['bsgDefaultPermissionsPolicyHeader'] = [
	'autoplay' => '',
	'camera' => '',
	'display-capture' => '',
	'encrypted-media' => '',
	'geolocation' => '',
	'microphone' => '',
	'payment' => '',
	'picture-in-picture' => '',
	'publickey-credentials-get' => '',
	'screen-wake-lock' => '',
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

$GLOBALS['wgSVGNativeRendering'] = true;
$GLOBALS['wgTiffThumbnailType'] = [ 'png', 'image/png' ];

// Hardcoded permissions, not part of role system
// Required for external authentication providers like LDAP, SAML, OIDC
$GLOBALS['wgGroupPermissions']['*']['autocreateaccount'] = true;
// Required for "reset password" functionality
$GLOBALS['wgGroupPermissions']['*']['editmyprivateinfo'] = true;

// Introduce new semiprotected restriction level - editor is a permission in this case
$GLOBALS['wgSemiprotectedRestrictionLevels'] = [ 'editor' ];
$GLOBALS['wgRestrictionLevels'] = [ '', 'editor', 'sysop' ];

$GLOBALS['wgGroupTypes'] = [
	'*'                => 'implicit',
	'user'             => 'implicit',
	'autoconfirmed'    => 'implicit',
	'sysop'            => 'core-minimal',
	'bureaucrat'       => 'core-extended',
	'bot'              => 'core-extended',
	'interface-admin'  => 'core-extended',
	'suppress'         => 'core-extended',
	'autoreview'       => 'extension-extended',
	'editor'           => 'extension-minimal',
	'review'           => 'extension-extended',
	'reviewer'         => 'extension-minimal',
	'smwcurator'       => 'extension-extended',
	'smweditor'        => 'extension-extended',
	'smwadministrator' => 'extension-extended',
	'widgeteditor'     => 'extension-extended'
];

