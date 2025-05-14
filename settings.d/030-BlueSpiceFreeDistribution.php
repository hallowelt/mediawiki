<?php

wfLoadExtension( 'Arrays' );
wfLoadExtension( 'BlueSpiceDistributionConnector' );
wfLoadExtension( 'CategoryTree' );
wfLoadExtension( 'CodeEditor' );
wfLoadExtension( 'ContentDroplets' );
wfLoadExtension( 'DynamicPageList3' );

wfLoadExtension( 'EmbedVideo');
$GLOBALS['wgEmbedVideoFetchExternalThumbnails'] = false;

wfLoadExtension( 'EnhancedStandardUIs' );
$GLOBALS[ 'wgEnhancedUIsVersionHistoryToolbarOffset' ] = 123;
$GLOBALS[ 'wgEnhancedUIsAllPagesPaginatorOffset' ] = 64;
$GLOBALS[ 'wgEnhancedUIsAllPagesOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsFilelistOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsSpecialSpecialPagesOverride' ] = true;

wfLoadExtension( 'EnhancedUpload' );
$GLOBALS['wgEnhancedUploadOverrideStandardUpload'] = true;

wfLoadExtension( 'FilterSpecialPages' );

wfLoadExtension( 'FlexiSkin' );
if( !is_array( $GLOBALS['wgWhitelistRead'] ) ) { $GLOBALS['wgWhitelistRead'] = []; }
$GLOBALS['wgWhitelistRead'][] = 'Flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'File:Flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'Flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'File:Flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'File:flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'File:flexiskin-images-logo.png';

wfLoadExtension( 'FontAwesome' );
wfLoadExtension( 'HitCounters' );
wfLoadExtension( 'ImageMapEdit' );

wfLoadExtension( 'InviteSignup' );
$GLOBALS['wgGroupPermissions']['sysop']['invitesignup'] = true;
$GLOBALS['bsgPermissionConfig']['invitesignup'] = [
	'type' => 'global',
	'roles' => [ 'admin' ]
];

wfLoadExtension( 'Lingo' );
wfLoadExtension( 'Loops' );
wfLoadExtension( 'Math' );

wfLoadExtension( 'MenuEditor' );
$GLOBALS['wgHooks']['SetupAfterCache'][] = function() {
	$GLOBALS[ 'wgMenuEditorMediawikiSidebarAllowedKeywords' ] = [];
};
$GLOBALS['wgMenuEditorToolbarOffset'] = 123;

wfLoadExtension( 'NotifyMe' );
wfLoadExtension( 'Echo', $GLOBALS['IP'] . '/extensions/BlueSpiceDistributionConnector/extension-shims/Echo/extension.json' );

wfLoadExtension( 'OOJSPlus' );
wfLoadExtension( 'PageHeader' );
wfLoadExtension( 'PDFCreator' );
wfLoadExtension( 'PdfHandler' );
wfLoadExtension( 'RSS' );
wfLoadExtension( 'StandardDialogs' );
wfLoadExtension( 'TemplateData' );
wfLoadExtension( 'TemplateStyles' );
wfLoadExtension( 'TwoColConflict' );
wfLoadExtension( 'UrlGetParameters' );

wfLoadExtension( 'UserFunctions' );
$GLOBALS['wgUFAllowedNamespaces'] = array_fill( 0, 5000, true );
$GLOBALS['wgUFEnabledPersonalDataFunctions'] = [ 'username' ];

wfLoadExtension( 'UserMerge' );
$GLOBALS['wgUserMergeProtectedGroups'] = [];
$GLOBALS['wgUserMergeUnmergeable'] = [];

wfLoadExtension( 'UserProfile' );

wfLoadExtension( 'Variables' );

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

wfLoadExtension( 'VisualEditorPlus' );
wfLoadExtension( 'VueJsPlus' );

wfLoadExtension( 'WikiEditor' );
$GLOBALS['wgHiddenPrefs'][] = 'usebetatoolbar';
$GLOBALS['wgDefaultUserOptions']['usebetatoolbar'] = 1;
