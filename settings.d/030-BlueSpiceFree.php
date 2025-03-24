<?php

wfLoadExtension( 'BlueSpiceFoundation' );
wfLoadExtension( 'BlueSpiceAbout' );
wfLoadExtension( 'BlueSpiceArticleInfo' );
wfLoadExtension( 'BlueSpiceAuthors' );
wfLoadExtension( 'BlueSpiceAvatars' );
wfLoadExtension( 'BlueSpiceChecklist' );
wfLoadExtension( 'BlueSpiceConfigManager' );
wfLoadExtension( 'BlueSpiceContextMenu' );
wfLoadExtension( 'BlueSpiceCountThings' );
wfLoadExtension( 'BlueSpiceCustomMenu' );
wfLoadExtension( 'BlueSpiceEmoticons' );

wfLoadExtension( 'BlueSpiceExtendedSearch' );
// Not respected by BlueSpiceExtendedSearch
$GLOBALS['wgHiddenPrefs'][] = 'searchlimit';

wfLoadExtension( 'BlueSpiceExtendedStatistics' );
wfLoadExtension( 'BlueSpiceHideTitle' );
wfLoadExtension( 'BlueSpiceInsertCategory' );
wfLoadExtension( 'BlueSpiceInstanceStatus' );
wfLoadExtension( 'BlueSpiceInterWikiLinks' );
wfLoadExtension( 'BlueSpiceNamespaceManager' );
wfLoadExtension( 'BlueSpicePageAccess' );
wfLoadExtension( 'BlueSpicePageAssignments' );
wfLoadExtension( 'BlueSpicePagesVisited' );
wfLoadExtension( 'BlueSpicePageTemplates' );

wfLoadExtension( 'BlueSpicePermissionManager' );
$GLOBALS['bsgPermissionManagerActivePreset'] = 'private';
$GLOBALS['bsgOverridePermissionManagerAllowedPresets'] = [ 'public', 'protected', 'private' ];

wfLoadExtension( 'BlueSpiceQrCode' );
wfLoadExtension( 'BlueSpiceReaders' );
wfLoadExtension( 'BlueSpiceRSSFeeder' );
wfLoadExtension( 'BlueSpiceSaferEdit' );
wfLoadExtension( 'BlueSpiceSmartList' );

wfLoadExtension( 'BlueSpiceTagCloud' );
$GLOBALS['bsgTagCloudTypeCategoryExclude'] = [
	'BPMN Association',
	'BPMN DataInputAssociation',
	'BPMN DataStoreReference',
	'BPMN EndEvent',
	'BPMN ExclusiveGateway',
	'BPMN IntermediateThrowEvent',
	'BPMN Lane',
	'BPMN Participant',
	'BPMN SequenceFlow',
	'BPMN StartEvent',
	'BPMN SubProcess',
	'BPMN TextAnnotation',
	'Imported vocabulary',
	'Pages using Tabber parser tag',
	'Pages using TabberTransclude parser tag'
];

wfLoadExtension( 'BlueSpiceUsageTracker' );
wfLoadExtension( 'BlueSpiceUserManager' );
wfLoadExtension( 'BlueSpiceUserSidebar' );

wfLoadExtension( 'BlueSpiceVisualEditorConnector' );
$GLOBALS['bsgVisualEditorConnectorUploadDialogType'] = 'simple';
$GLOBALS['wgUploadDialog']['fields']['categories'] = true;
$GLOBALS['wgUploadDialog']['format']['filepage'] = '$DESCRIPTION $CATEGORIES';

wfLoadExtension( 'BlueSpiceWatchList' );
wfLoadExtension( 'BlueSpiceWhoIsOnline' );
