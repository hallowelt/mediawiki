<?php

wfLoadExtension( 'Arrays' );
wfLoadExtension( 'CategoryTree' );
wfLoadExtension( 'DynamicPageList3' );
#wfLoadExtension( 'HitCounters' );
wfLoadExtension( 'ImageMapEdit' );
wfLoadExtension( 'RSS' );
wfLoadExtension( 'TitleKey');
wfLoadExtension( 'EmbedVideo');
$GLOBALS['wgEmbedVideoFetchExternalThumbnails'] = false;
wfLoadExtension( 'FilterSpecialPages' );
wfLoadExtension( 'UserMerge' );
$wgUserMergeProtectedGroups = [];
$wgUserMergeUnmergeable = [];
wfLoadExtension( 'BlueSpiceDistributionConnector' );
$GLOBALS['wgAutoloadClasses']['EchoEvent'] = "$IP/extensions/BlueSpiceDistributionConnector/src/EchoEvent.php";
wfLoadExtension( 'UserFunctions' );
$GLOBALS['wgUFAllowedNamespaces'] = array_fill( 0, 5000, true );
wfLoadExtension( 'UrlGetParameters' );
wfLoadExtension( 'FlexiSkin' );
wfLoadExtension( 'Loops' );
wfLoadExtension( 'InviteSignup' );
$GLOBALS['wgGroupPermissions']['sysop']['invitesignup'] = true;
$GLOBALS['bsgPermissionConfig']['invitesignup'] = [
	'type' => 'global',
	'roles' => [ 'admin' ]
];
wfLoadExtension( 'MenuEditor' );
$GLOBALS['wgHooks']['SetupAfterCache'][] = function() {
	$GLOBALS[ 'wgMenuEditorMediawikiSidebarAllowedKeywords' ] = [];
};
$GLOBALS['wgMenuEditorToolbarOffset'] = 153;
wfLoadExtension( 'EnhancedUpload' );
$GLOBALS['wgEnhancedUploadOverrideStandardUpload'] = true;

wfLoadExtension( 'ContentDroplets' );
wfLoadExtension( 'FontAwesome' );
wfLoadExtension( 'VisualEditorPlus' );

wfLoadExtension( 'NotifyMe' );

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

$bsImagesPath =
	$GLOBALS['wgScriptPath']
	. '/extensions/BlueSpiceDistributionConnector/resources/images';

/*
 * Use an other image for MediaWiki.org
 */
$GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] = [
	'src' => $bsImagesPath . '/footer/MediaWiki.png',
	'height' => '27',
	'width' => '149'
] + $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'];

/*
 * We want to use an other image for this extensions but the config files are processed to early.
 * So we have to set the complete items.
 */
$GLOBALS['wgFooterIcons']['poweredby'] += [
	'bluespice' => [
		'src' => $bsImagesPath . '/footer/BlueSpice.png',
		'url' => 'http://bluespice.com',
		'alt' => 'Powered by BlueSpice',
		'height' => '27',
		'width' => '149'
	]
];

if ( array_key_exists( 'semanticmediawiki', $GLOBALS['wgFooterIcons']['poweredby'] ) ) {
	$footerIcons = [
		'mediawiki' => $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'],
		'bluespice' => $GLOBALS['wgFooterIcons']['poweredby']['bluespice'],
		'semanticmediawiki' => $GLOBALS['wgFooterIcons']['poweredby']['semanticmediawiki']
	];

	unset( $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] );
	unset( $GLOBALS['wgFooterIcons']['poweredby']['bluespice'] );
	unset( $GLOBALS['wgFooterIcons']['poweredby']['semanticmediawiki'] );

	$footerIcons += $GLOBALS['wgFooterIcons']['poweredby'];

	$GLOBALS['wgFooterIcons']['poweredby'] = $footerIcons;
}

/**
 * The name of the default logo changed with REL1_39 from
 * wiki.png to change-your-logo.svg.
 * The old name may be set in LocalSetings.php. This caused an issue
 * when trying to set the BlueSpice logo.
 */
$assetsDir = $GLOBALS['wgResourceBasePath'] . '/resources/assets/';
$blueSpiceLogoPath = "$bsImagesPath/bs_logo.png";

if ( strpos( $GLOBALS['wgLogos']['1x'], $assetsDir ) === 0 ) {
	$GLOBALS['wgLogos'] = [ '1x' => $blueSpiceLogoPath ];
}

if ( $GLOBALS['wgFavicon'] == '/favicon.ico' ){
	$GLOBALS['wgFavicon'] = "$bsImagesPath/favicon.ico";
}

unset( $bsImagesPath );
unset( $assetsDir );
unset( $blueSpiceLogoPath );

$GLOBALS['wgReservedUsernames'][] = 'BSMaintenance';
$GLOBALS['wgReservedUsernames'][] = 'Bsmaintenance';
$GLOBALS['wgReservedUsernames'][] = 'ContentStabilizationBot';
$GLOBALS['wgReservedUsernames'][] = 'Contentstabilizationbot';
$GLOBALS['wgReservedUsernames'][] = 'DynamicPageList3 extension';
$GLOBALS['wgReservedUsernames'][] = 'Dynamicpagelist3 extension';
$GLOBALS['wgReservedUsernames'][] = 'Maintenance script';
$GLOBALS['wgReservedUsernames'][] = 'Mediawiki default';
$GLOBALS['wgReservedUsernames'][] = 'MediaWiki default';
$GLOBALS['wgReservedUsernames'][] = 'Spam cleanup script';
$GLOBALS['wgReservedUsernames'][] = 'BlueSpice default';
$GLOBALS['wgReservedUsernames'][] = 'Bluespice default';

$GLOBALS['wgCapitalLinkOverrides'][ NS_FILE ] = true;
$GLOBALS['wgWhitelistRead'][] = 'Flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'File:Flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'Flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'File:Flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'File:flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'File:flexiskin-images-logo.png';

wfLoadExtension( 'CodeEditor' );
wfLoadExtension( 'Lingo' );
wfLoadExtension( 'OOJSPlus' );
wfLoadExtension( 'PageHeader' );
wfLoadExtension( 'StandardDialogs' );
wfLoadExtension( 'TemplateData' );
wfLoadExtension( 'TemplateStyles' );
wfLoadExtension( 'TwoColConflict' );

wfLoadExtension( 'VisualEditor' );
$GLOBALS['wgDefaultUserOptions']['visualeditor-enable'] = 1;
$GLOBALS['wgDefaultUserOptions']['visualeditor-enable-experimental'] = 1;
$GLOBALS['wgDefaultUserOptions']['visualeditor-newwikitext'] = 1;
$GLOBALS['wgDefaultUserOptions']['visualeditor-editor'] = "visualeditor";
$GLOBALS['wgHiddenPrefs'][] = 'visualeditor-enable';
$GLOBALS['wgHiddenPrefs'][] = 'visualeditor-newwikitext';
$GLOBALS['wgVisualEditorAvailableNamespaces'] = [
	NS_MAIN => true,
	NS_USER => true,
	"_merge_strategy" => "array_plus"
];
$GLOBALS['wgVisualEditorEnableWikitext'] = true;
$GLOBALS['wgVisualEditorShowBetaWelcome'] = false;
$GLOBALS['wgVisualEditorAllowExternalLinkPaste'] = true;

wfLoadExtension( 'WikiEditor' );
$GLOBALS['wgHiddenPrefs'][] = 'usebetatoolbar';
$GLOBALS['wgDefaultUserOptions']['usebetatoolbar'] = 1;