<?php

// Additional settings that only apply to PRO etc.

// Especially in SSO environments, it is expected that mails are send without additonal
// authentication. Mail addresses are usually set by the SSO provider.
$GLOBALS['wgEmailAuthentication'] = false;

wfLoadExtension( 'AdhocTranslation' );

wfLoadExtension( 'AIEditingAssistant' );
$GLOBALS[ 'wgAIEditingAssistantActiveProvider' ] = 'open-ai';

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
$GLOBALS[ 'bsgPermissionConfig' ][ 'cs-comment' ] = [
	'type' => 'namespace',
	'roles' => [ 'reader' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'cs-moderator-edit' ] = [
	'type' => 'namespace',
	'roles' => [ 'admin' ]
];
$GLOBALS[ 'bsgPermissionConfig' ][ 'cs-moderator-delete' ] = [
	'type' => 'namespace',
	'roles' => [ 'admin' ]
];

wfLoadExtension( 'ContainerFilter' );
wfLoadExtension( 'ContentProvisioning' );
wfLoadExtension( 'ContentStabilization' );

wfLoadExtension( 'CreateUserPage' );
$GLOBALS[ 'wgCreateUserPage_PageContent' ] = '{{Userpage standard content}}';
$GLOBALS[ 'wgCreateUserPage_OnLogin' ] = false;

wfLoadExtension( 'DataTransfer' );
$GLOBALS[ 'wgGroupPermissions' ][ 'user' ][ 'datatransferimport' ] = true;
$GLOBALS[ 'bsgPermissionConfig' ][ 'datatransferimport' ] = [
	'type' => 'global',
	'roles' => [ 'editor' ]
];
$GLOBALS[ 'wgDataTransferViewXMLParseFields' ] = true;

wfLoadExtension( 'DateTimeTools' );

wfLoadExtension( 'DrawioEditor' );
$GLOBALS[ 'wgDrawioEditorImageType' ] = 'png';

wfLoadExtension( 'EnhancedStandardUIs' );
$GLOBALS[ 'wgEnhancedUIsVersionHistoryToolbarOffset' ] = 123;
$GLOBALS[ 'wgEnhancedUIsAllPagesPaginatorOffset' ] = 64;
$GLOBALS[ 'wgEnhancedUIsAllPagesOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsFilelistOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsExtendedFilelistOverride' ] = true;
$GLOBALS[ 'wgEnhancedUIsSpecialSpecialPagesOverride' ] = true;

wfLoadExtension( 'EventBus' );
wfLoadExtension( 'ExternalData' );
wfLoadExtension( 'Forms' );
wfLoadExtension( 'HeaderFooter' );
wfLoadExtension( 'HeaderTabs' );
wfLoadExtension( 'ImportOfficeFiles' );

wfLoadExtension( 'InlineComments' );
$GLOBALS['bsgPermissionConfig']['inlinecomments-view'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];
$GLOBALS['bsgPermissionConfig']['inlinecomments-add'] = [
	'type' => 'global',
	'roles' => [ 'editor' ]
];
$GLOBALS['bsgPermissionConfig']['inlinecomments-edit-all'] = [
	'type' => 'global',
	'roles' => [ 'admin' ]
];

wfLoadExtension( 'Lingo' );

wfLoadExtension( 'LinkTitles' );
$GLOBALS['bsgPermissionConfig']['linktitles-batch'] = [
	'type' => 'global',
	'roles' => [ 'admin' ]
];

wfLoadExtension( 'Maps' );
wfLoadExtension( 'MultimediaViewer' );

wfLoadExtension( 'NSFileRepo' );
$GLOBALS[ 'egNSFileRepoNamespaceThreshold' ] = 3000;
$GLOBALS[ 'wgUploadPath' ] = $GLOBALS[ 'wgScriptPath' ] . '/nsfr_img_auth.php';

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

wfLoadExtension( 'OATHAuth' );
$GLOBALS['wgGroupPermissions']['user']['oathauth-enable'] = true;
$GLOBALS['bsgPermissionConfig']['oathauth-enable'] = [
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

wfLoadExtension( 'PageImages' );
// ERM21013
$GLOBALS['wgPageImagesLeadSectionOnly'] = false;

wfLoadExtension( 'PDFembed' );
$GLOBALS['bsgPermissionConfig']['embed_pdf'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];

wfLoadExtension( 'Popups' );
// ERM18546: Parse whole page for preview, not only section = 0
$GLOBALS[ 'wgPopupsTextExtractsIntroOnly' ] = false;
$GLOBALS[ 'wgPopupsOptInDefaultState' ] = '1';

wfLoadExtension( 'PreToClip' );
wfLoadExtension( 'ReplaceText' );

wfLoadExtension( 'RevisionSlider' );
$GLOBALS['wgVisualEditorEnableDiffPage'] = true;
$GLOBALS['wgVisualEditorEnableDiffPageBetaFeature'] = true;

wfLoadExtension( 'Scribunto' );
$GLOBALS[ 'wgScribuntoDefaultEngine' ] = 'luastandalone';

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

if(
	defined( 'FARMER_CALLED_INSTANCE' ) &&
	FARMER_CALLED_INSTANCE instanceof \BlueSpice\WikiFarm\InstanceEntity &&
	!( FARMER_CALLED_INSTANCE instanceof \BlueSpice\WikiFarm\RootInstanceEntity )
) {
	$GLOBALS[ 'smwgConfigFileDir' ] = FARMER_CALLED_INSTANCE->getVault( $GLOBALS['wgWikiFarmConfigInternal'] ) .
		"/extensions/BlueSpiceFoundation/data";
}

$GLOBALS[ 'wgFooterIcons' ][ 'poweredby' ] += [
	'semanticmediawiki' => [
		'src' => $GLOBALS['wgScriptPath'] . '/extensions/BlueSpiceDistributionConnector/resources/images/footer/SemanticMediaWiki.png',
		'url' => 'https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki',
		'alt' => 'Powered by Semantic MediaWiki',
		'height' => '27',
		'width' => '149'
	]
];

wfLoadExtension( 'SemanticResultFormats' );
wfLoadExtension( 'SemanticScribunto' );
wfLoadExtension( 'SimpleTasks' );

wfLoadExtension( 'SimpleBlogPage' );
$GLOBALS['bsgPermissionConfig']['createblogpost'] = [
	'type' => 'global',
	'roles' => [ 'editor' ]
];

wfLoadExtension( 'SubPageList' );
#wfLoadExtension( 'TabberNeue' );
wfLoadExtension( 'TableTools' );
wfLoadExtension( 'TextExtracts' );
wfLoadExtension( 'UnifiedTaskOverview' );
wfLoadExtension( 'VueJsPlus' );
wfLoadExtension( 'WebAuthn' );
wfLoadExtension( 'Widgets' );
$GLOBALS[ 'wgWidgetsCompileDir' ] = $GLOBALS[ 'wgCacheDirectory' ];
wfLoadExtension( 'Workflows');
wfLoadExtension( 'UserProfile' );