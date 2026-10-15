<?php

$GLOBALS['bsgEnabledExtensions']['BlueSpiceDistributionConnector'] = true;
$GLOBALS['bsgEnabledExtensions']['CategoryTree'] = true;
$GLOBALS['bsgEnabledExtensions']['CodeEditor'] = true;
$GLOBALS['bsgEnabledExtensions']['ContentDroplets'] = true;
$GLOBALS['bsgEnabledExtensions']['DynamicPageList4'] = true;

$GLOBALS['bsgEnabledExtensions']['EmbedVideo'] = true;
$GLOBALS['wgEmbedVideoFetchExternalThumbnails'] = false;

$GLOBALS['bsgEnabledExtensions']['EnhancedStandardUIs'] = true;
$GLOBALS[ 'wgEnhancedUIsVersionHistoryToolbarOffset' ] = 123;
$GLOBALS[ 'wgEnhancedUIsAllPagesPaginatorOffset' ] = 64;
$GLOBALS[ 'wgEnhancedUIsAllPagesOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsFilelistOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsSpecialSpecialPagesOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsSpecialPreferencesOverride' ] = true;
# Some MediaWiki user prefences are confusing and/or incompatible with BlueSpice.
# Therefore we hide them in the new "enhanced" user preferences of Extension EnhancedStandardUIs.
# Section:Info
$GLOBALS['wgHiddenPrefs'][] = 'username';
$GLOBALS['wgHiddenPrefs'][] = 'usergroups';
$GLOBALS['wgHiddenPrefs'][] = 'editcount';
$GLOBALS['wgHiddenPrefs'][] = 'registrationdate';
# Section:Appearance
$GLOBALS['wgDefaultUserOptions']['popups'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'popups';
$GLOBALS['wgDefaultUserOptions']['popups-reference-previews'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'popups-reference-previews';
$GLOBALS['wgDefaultUserOptions']['diffonly'] = 0;
$GLOBALS['wgHiddenPrefs'][] = 'diffonly';
$GLOBALS['wgDefaultUserOptions']['norollbackdiff'] = 0;
$GLOBALS['wgHiddenPrefs'][] = 'norollbackdiff';
$GLOBALS['wgDefaultUserOptions']['revisionslider-disable'] = 0;
$GLOBALS['wgHiddenPrefs'][] = 'revisionslider-disable';
$GLOBALS['wgDefaultUserOptions']['showrollbackconfirmation'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'showrollbackconfirmation';
# Tab:Editing
# Section: General
$GLOBALS['wgDefaultUserOptions']['twocolconflict-enabled'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'twocolconflict-enabled';
# Section: Editor
$GLOBALS['wgHiddenPrefs'][] = 'editfont';
$GLOBALS['wgDefaultUserOptions']['useeditwarning'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'useeditwarning';
$GLOBALS['wgDefaultUserOptions']['visualeditor-betatempdisable'] = 0;
$GLOBALS['wgHiddenPrefs'][] = 'visualeditor-betatempdisable';
# Section:Files
$GLOBALS['wgHiddenPrefs'][] = 'imagesize';
$GLOBALS['wgHiddenPrefs'][] = 'thumbsize';
$GLOBALS['wgHiddenPrefs'][] = 'multimediaviewer-enable';
# Section:Advanced options
$GLOBALS['wgHiddenPrefs'][] = 'showhiddencats';
$GLOBALS['wgHiddenPrefs'][] = 'forcesafemode';
# Section:Preview
$GLOBALS['wgHiddenPrefs'][] = 'previewonfirst';
$GLOBALS['wgHiddenPrefs'][] = 'previewontop';
$GLOBALS['wgHiddenPrefs'][] = 'uselivepreview';
## Extension:Math
$GLOBALS['wgHiddenPrefs'][] = 'math';
$GLOBALS['wgHiddenPrefs'][] = 'math-popups';
# Section:Section:Recent Changes
$GLOBALS['wgHiddenPrefs'][] = 'rcenhancedfilters-disable';
# Section:Watchlist
$GLOBALS['wgHiddenPrefs'][] = 'wlenhancedfilters-disable';
# Tab: SMW
# Section:General
$GLOBALS['wgHiddenPrefs'][] = 'smw-prefs-general-options-time-correction';
$GLOBALS['wgDefaultUserOptions']['smw-prefs-general-options-suggester-textinput'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'smw-prefs-general-options-suggester-textinput';
$GLOBALS['wgDefaultUserOptions']['smw-prefs-general-options-show-entity-issue-panel'] = 0;
$GLOBALS['wgHiddenPrefs'][] = 'smw-prefs-general-options-show-entity-issue-panel';
$GLOBALS['wgHiddenPrefs'][] = 'srf-prefs-intro';
# Section:Search
$GLOBALS['wgDefaultUserOptions']['smw-prefs-ask-options-tooltip-display'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'smw-prefs-ask-options-tooltip-display';
# Section:Event calendar
$GLOBALS['wgDefaultUserOptions']['srf-prefs-eventcalendar-options-update-default'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'srf-prefs-eventcalendar-options-update-default';
$GLOBALS['wgDefaultUserOptions']['srf-prefs-eventcalendar-options-paneview-default'] = 1;
$GLOBALS['wgHiddenPrefs'][] = 'srf-prefs-eventcalendar-options-paneview-default';

$GLOBALS['bsgEnabledExtensions']['EnhancedUpload'] = true;
$GLOBALS['wgEnhancedUploadOverrideStandardUpload'] = true;

$GLOBALS['bsgEnabledExtensions']['FilterSpecialPages'] = true;

$GLOBALS['bsgEnabledExtensions']['FlexiSkin'] = true;
if( !is_array( $GLOBALS['wgWhitelistRead'] ) ) { $GLOBALS['wgWhitelistRead'] = []; }
$GLOBALS['wgWhitelistRead'][] = 'Flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'File:Flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'Flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'File:Flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'File:flexiskin-images-favicon.png';
$GLOBALS['wgWhitelistRead'][] = 'flexiskin-images-logo.png';
$GLOBALS['wgWhitelistRead'][] = 'File:flexiskin-images-logo.png';

$GLOBALS['bsgEnabledExtensions']['FontAwesome'] = true;
$GLOBALS['bsgEnabledExtensions']['HitCounters'] = true;
$GLOBALS['bsgEnabledExtensions']['ImageMapEdit'] = true;

$GLOBALS['bsgEnabledExtensions']['InviteSignup'] = true;
$GLOBALS['wgGroupPermissions']['sysop']['invitesignup'] = true;
$GLOBALS['bsgPermissionConfig']['invitesignup'] = [
	'type' => 'global',
	'roles' => [ 'admin' ]
];

$GLOBALS['bsgEnabledExtensions']['Lingo'] = true;
$GLOBALS['bsgEnabledExtensions']['Math'] = true;

$GLOBALS['bsgEnabledExtensions']['MenuEditor'] = true;
$GLOBALS['wgHooks']['SetupAfterCache'][] = function() {
	$GLOBALS[ 'wgMenuEditorMediawikiSidebarAllowedKeywords' ] = [];
};
$GLOBALS['wgMenuEditorToolbarOffset'] = 123;

$GLOBALS['bsgEnabledExtensions']['NotifyMe'] = true;
// Use the shim from BlueSpiceDistributionConnector instead of `extensions/Echo/extension.json`
$GLOBALS['bsgEnabledExtensions']['Echo'] = $GLOBALS['IP'] . '/extensions/BlueSpiceDistributionConnector/extension-shims/Echo/extension.json';

$GLOBALS['bsgEnabledExtensions']['OOJSPlus'] = true;
$GLOBALS['wgOOJSPlusUseSplitTitleInputWidgetOptions'] = true;

$GLOBALS['bsgEnabledExtensions']['PageExcerpts'] = true;
$GLOBALS['bsgEnabledExtensions']['PageHeader'] = true;
$GLOBALS['bsgEnabledExtensions']['PageLayouts'] = true;
$GLOBALS['bsgEnabledExtensions']['PDFCreator'] = true;
$GLOBALS['bsgEnabledExtensions']['PdfHandler'] = true;
$GLOBALS['bsgEnabledExtensions']['RSS'] = true;
$GLOBALS['bsgEnabledExtensions']['StandardDialogs'] = true;
$GLOBALS['bsgEnabledExtensions']['TemplateData'] = true;
$GLOBALS['bsgEnabledExtensions']['TemplateStyles'] = true;
$GLOBALS['bsgEnabledExtensions']['TwoColConflict'] = true;
$GLOBALS['bsgEnabledExtensions']['UrlGetParameters'] = true;

$GLOBALS['bsgEnabledExtensions']['UserFunctions'] = true;
$GLOBALS['wgUFAllowedNamespaces'] = array_fill( 0, 5000, true );
$GLOBALS['wgUFEnabledPersonalDataFunctions'] = [ 'username' ];

$GLOBALS['bsgEnabledExtensions']['UserMerge'] = true;
$GLOBALS['wgUserMergeProtectedGroups'] = [];
$GLOBALS['wgUserMergeUnmergeable'] = [];

$GLOBALS['bsgEnabledExtensions']['UserProfile'] = true;

$GLOBALS['bsgEnabledExtensions']['VisualEditor'] = true;
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

$GLOBALS['bsgEnabledExtensions']['VisualEditorPlus'] = true;
$GLOBALS['bsgEnabledExtensions']['VueJsPlus'] = true;

$GLOBALS['bsgEnabledExtensions']['WikiEditor'] = true;
$GLOBALS['wgHiddenPrefs'][] = 'usebetatoolbar';
$GLOBALS['wgDefaultUserOptions']['usebetatoolbar'] = 1;
