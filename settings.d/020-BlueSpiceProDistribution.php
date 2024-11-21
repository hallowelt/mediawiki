<?php

wfLoadExtension( 'AdhocTranslation' );
wfLoadExtension( 'AIEditingAssistant' );
wfLoadExtension( 'AtMentions' );
wfLoadExtension( 'Checklists' );

wfLoadExtension( 'CodeMirror' );
$GLOBALS[ 'wgDefaultUserOptions' ][ 'usecodemirror' ] = 1;
$GLOBALS[ 'wgCodeMirrorEnableBracketMatching' ] = true;
$GLOBALS[ 'wgCodeMirrorAccessibilityColors' ] = true;
$GLOBALS[ 'wgCodeMirrorLineNumberingNamespaces' ] = [ NS_TEMPLATE ];

wfLoadExtension( 'CognitiveProcessDesigner' );
wfLoadExtension( 'CollabPads' );
$GLOBALS[ 'wgCollabPadsBackendServiceURL' ] = $GLOBALS[ 'wgServer' ] . '/_collabpads/';

wfLoadExtension( 'CommentStreams' );
wfLoadExtension( 'ContainerFilter' );
wfLoadExtension( 'ContentProvisioning' );
wfLoadExtension( 'ContentStabilization' );

wfLoadExtension( 'CreateUserPage' );
$GLOBALS[ 'wgCreateUserPage_PageContent' ] = '{{Userpage standard content}}';
$GLOBALS[ 'wgCreateUserPage_OnLogin' ] = false;

wfLoadExtension( 'DataTransfer' );
$GLOBALS[ '$wgGroupPermissions' ][ 'user' ][ 'datatransferimport' ] = true;
$GLOBALS[ 'bsgPermissionConfig' ][ 'datatransferimport' ] = [
	'type' => 'global',
	'roles' => [ 'editor' ]
];
$GLOBALS[ 'wgDataTransferViewXMLParseFields' ] = true;

wfLoadExtension( 'DateTimeTools' );

wfLoadExtension( 'DrawioEditor' );
$GLOBALS[ 'wgDrawioEditorImageType' ] = 'png';

wfLoadExtension( 'EnhancedStandardUIs' );
wfLoadExtension( 'EventBus' );
wfLoadExtension( 'ExternalData' );
wfLoadExtension( 'Forms' );
wfLoadExtension( 'HeaderFooter' );
wfLoadExtension( 'HeaderTabs' );
wfLoadExtension( 'ImportOfficeFiles' );
wfLoadExtension( 'Maps' );
wfLoadExtension( 'MultimediaViewer' );

#wfLoadExtension( 'NSFileRepo' );
#$GLOBALS[ 'egNSFileRepoNamespaceThreshold' ] = 3000;

wfLoadExtension( 'NumberHeadings' );

wfLoadExtension( 'OAuth' );
// `$GLOBALS[ 'wgOAuth2PrivateKey' ]` and `$GLOBALS[ 'wgOAuth2PublicKey' ]` are being set through Special:ConfigManager
$GLOBALS[ 'bsgPermissionConfig' ][ 'mwoauthproposeconsumer' ] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'mwoauthupdateownconsumer' ] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'mwoauthmanageconsumer' ] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'mwoauthsuppress' ] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'mwoauthviewsuppressed' ] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'mwoauthviewprivate' ] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'mwoauthmanagemygrants' ] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];

wfLoadExtension( 'OpenLayers' );
wfLoadExtension( 'PageCheckout' );

wfLoadExtension( 'PageForms' );
$GLOBALS[ 'bsgPermissionConfig' ][ 'multipageedit' ] = [
	'type' => 'global',
	'roles' => [ 'editor' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'editrestrictedfields' ] = [
	'type' => 'global',
	'roles' => [ 'admin' ]
];

wfLoadExtension( 'PDFembed' );

wfLoadExtension( 'Popups' );
// ERM18546: Parse whole page for preview, not only section = 0
$GLOBALS[ 'wgPopupsTextExtractsIntroOnly' ] = false;
$GLOBALS[ 'wgPopupsOptInDefaultState' ] = '1';

wfLoadExtension( 'PreToClip' );
wfLoadExtension( 'RevisionSlider' );
wfLoadExtension( 'SectionAnchors' );
wfLoadExtension( 'SemanticCompoundQueries' );
wfLoadExtension( 'SemanticExtraSpecialProperties' );
$GLOBALS[ 'sespgUseFixedTables' ] = true;
$GLOBALS[ 'sespgExcludeBotEdits' ] = true;
$GLOBALS[ 'sespgEnabledPropertyList' ] = [
	'_EUSER', '_CUSER', '_REVID', '_PAGEID', '_VIEWS', '_NREV', '_TNREV',
	'_SUBP', '_USERREG', '_USEREDITCNT', '_EXIFDATA'
];
wfLoadExtension( 'SemanticMediaWiki' );
enableSemantics( 'localhost' );
// ERM23160
$GLOBALS[ 'smwgChangePropagationProtection' ] = false;
$GLOBALS[ 'smwgPageSpecialProperties' ] = array_merge(
	$GLOBALS[ 'smwgPageSpecialProperties' ],
	[ '_CDAT', '_LEDT', '_NEWP', '_MIME', '_MEDIA' ]
);
$GLOBALS[ 'smwgEnabledEditPageHelp' ] = false;
$GLOBALS[ 'smwgQMaxSize' ] = 100;
$GLOBALS[ 'maxRecursionDepth' ] = 4;

$GLOBALS[ 'smwgConfigFileDir' ] = "$IP/extensions/BlueSpiceFoundation/data";
if( defined( 'BSDATADIR' ) ) {
	$GLOBALS[ 'smwgConfigFileDir' ] = BSDATADIR;
}

if( defined( 'FARMER_CALLED_INSTANCE' ) && FARMER_CALLED_INSTANCE !== '' ) {
	$GLOBALS[ 'smwgConfigFileDir' ] =
		$GLOBALS[ 'wgSimpleFarmerConfig' ]->get( 'instanceDirectory' ) .
		'/' . FARMER_CALLED_INSTANCE . "/extensions/BlueSpiceFoundation/data";
}

$GLOBALS[ 'wgFooterIcons' ][ 'poweredby' ] += [
	'semanticmediawiki' => [
		'src' => $wgScriptPath . '/extensions/BlueSpiceDistributionConnector/resources/images/footer/SemanticMediaWiki.png',
		'url' => 'https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki',
		'alt' => 'Powered by Semantic MediaWiki',
		'height' => '27',
		'width' => '149'
	]
];

wfLoadExtension( 'SemanticResultFormats' );
wfLoadExtension( 'SemanticScribunto' );
wfLoadExtension( 'SimpleTasks' );
wfLoadExtension( 'SubPageList' );
wfLoadExtension( 'TableTools' );
wfLoadExtension( 'UnifiedTaskOverview' );
wfLoadExtension( 'VueJsPlus' );
wfLoadExtension( 'WebAuthn' );
wfLoadExtension( 'Widgets' );
$GLOBALS[ 'wgWidgetsCompileDir' ] = $GLOBALS[ 'wgCacheDirectory' ];
wfLoadExtension( 'Workflows');