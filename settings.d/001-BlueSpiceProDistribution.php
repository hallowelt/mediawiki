<?php
wfLoadExtension( 'BlueSpiceProDistributionConnector' );
if( empty( $wgFileExtensions ) ){
	$wgFileExtensions = [ 'png' ];
}
wfLoadExtension( 'DrawioEditor' );
$wgDrawioEditorImageType = 'png';
wfLoadExtension( 'HeaderTabs' );
wfLoadExtension( 'MultimediaViewer' );
wfLoadExtension( 'ReplaceText' );
wfLoadExtension( 'Scribunto' );
$wgScribuntoDefaultEngine = 'luastandalone';
wfLoadExtension( 'Widgets' );
#wfLoadExtension( 'NSFileRepo' );
#$GLOBALS['egNSFileRepoNamespaceThreshold'] = 3000;
#wfLoadExtension( 'BlueSpiceNSFileRepoConnector' );
wfLoadExtension( 'PageImages' );
wfLoadExtension( 'TextExtracts' );
wfLoadExtension( 'Popups' );
// ERM18546: Parse whole page for preview, not only section = 0
$wgPopupsTextExtractsIntroOnly = false;
$GLOBALS['wgPopupsOptInDefaultState'] = '1';
wfLoadExtension( 'CreateUserPage' );
$GLOBALS['wgCreateUserPage_PageContent'] = '{{Userpage standard content}}';
$GLOBALS['wgCreateUserPage_OnLogin'] = false;
wfLoadExtension( 'CognitiveProcessDesigner' );
wfLoadExtension( 'PageCheckout' );
wfLoadExtension( 'ImportOfficeFiles' );
wfLoadExtension( 'HeaderFooter' );
wfLoadExtension( 'SubPageList' );
wfLoadExtension( 'CodeMirror' );
$GLOBALS['wgDefaultUserOptions']['usecodemirror'] = 1;
$GLOBALS['wgCodeMirrorEnableBracketMatching'] = true;
$GLOBALS['wgCodeMirrorAccessibilityColors'] = true;
$GLOBALS['wgCodeMirrorLineNumberingNamespaces'] = [ NS_TEMPLATE ];
wfLoadExtension( 'ContentStabilization' );
wfLoadExtension( 'PDFembed' );
$GLOBALS['bsgPermissionConfig']['embed_pdf'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
wfLoadExtension( 'PreToClip' );
wfLoadExtension( 'ContainerFilter' );
wfLoadExtension( 'SectionAnchors' );

// Additional settings that only apply to PRO etc.

// Especially in SSO environments, it is expected that mails are send without additonal
// authentication. Mail addresses are usually set by the SSO provider.
$GLOBALS['wgEmailAuthentication'] = false;

wfLoadExtension( 'Checklists' );
wfLoadExtension( 'AtMentions' );
wfLoadExtension( 'DateTimeTools' );
wfLoadExtension( 'SimpleTasks' );
wfLoadExtension( 'TabberNeue' );
wfLoadExtension( 'EnhancedStandardUIs' );
$GLOBALS['wgEnhancedUIsVersionHistoryToolbarOffset'] = 153;
$GLOBALS['wgEnhancedUIsAllPagesPaginatorOffset'] = 64;
$GLOBALS['wgEnhancedUIsAllPagesOverride'] = true;
$GLOBALS['wgEnhancedUIsFilelistOverride'] = true;
$GLOBALS['wgEnhancedUIsExtendedFilelistOverride'] = true;
$GLOBALS['wgEnhancedUIsSpecialSpecialPagesOverride'] = true;
wfLoadExtension( 'ContentProvisioning' );
wfLoadExtension( 'AIEditingAssistant' );
$GLOBALS['wgAIEditingAssistantActiveProvider' ] = 'open-ai';
wfLoadExtension( 'CollabPads' );
$GLOBALS['wgCollabPadsBackendServiceURL'] = $GLOBALS['wgServer'] . '/_collabpads/';
wfLoadExtension( 'OAuth' );
// `$GLOBALS['wgOAuth2PrivateKey']` and `$GLOBALS['wgOAuth2PublicKey']` are being set through Special:ConfigManager
$GLOBALS['bsgPermissionConfig']['mwoauthproposeconsumer'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['mwoauthupdateownconsumer'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['mwoauthmanageconsumer'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['mwoauthsuppress'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['mwoauthviewsuppressed'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['mwoauthviewprivate'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['mwoauthmanagemygrants'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];

wfLoadExtension( 'TableTools' );
wfLoadExtension( 'VueJsPlus' );
wfLoadExtension( 'NumberHeadings' );
wfLoadExtension( 'CommentStreams' );
$GLOBALS['bsgPermissionConfig']['cs-comment'] = [
	'type' => 'namespace',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['cs-moderator-edit'] = [
	'type' => 'namespace',
	'roles' => [ 'admin' ]
];
$GLOBALS['bsgPermissionConfig']['cs-moderator-delete'] = [
	'type' => 'namespace',
	'roles' => [ 'admin' ]
];