<?php

$GLOBALS['bsgEnabledExtensions']['BlueSpiceFoundation'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceAbout'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceArticleInfo'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceAuthors'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceAvatars'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceChecklist'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceConfigManager'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceContextMenu'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceCountThings'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceCustomMenu'] = true;

$GLOBALS['bsgEnabledExtensions']['BlueSpiceExtendedSearch'] = true;
// Not respected by BlueSpiceExtendedSearch
$GLOBALS['wgHiddenPrefs'][] = 'searchlimit';

$GLOBALS['bsgEnabledExtensions']['BlueSpiceExtendedStatistics'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceHideTitle'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceInsertCategory'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceInstanceStatus'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceInterWikiLinks'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceNamespaceManager'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpicePageAccess'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpicePageAssignments'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpicePagesVisited'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpicePageTemplates'] = true;

$GLOBALS['bsgEnabledExtensions']['BlueSpicePermissionManager'] = true;
$GLOBALS['bsgPermissionManagerActivePreset'] = 'private';
$GLOBALS['bsgOverridePermissionManagerAllowedPresets'] = [ 'public', 'protected', 'private' ];

$GLOBALS['bsgEnabledExtensions']['BlueSpiceQrCode'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceReaders'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceRSSFeeder'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceSaferEdit'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceSmartList'] = true;

$GLOBALS['bsgEnabledExtensions']['BlueSpiceTagCloud'] = true;
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

$GLOBALS['bsgEnabledExtensions']['BlueSpiceUsageTracker'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceUserManager'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceUserSidebar'] = true;

$GLOBALS['bsgEnabledExtensions']['BlueSpiceVisualEditorConnector'] = true;
$GLOBALS['bsgVisualEditorConnectorUploadDialogType'] = 'simple';
$GLOBALS['wgUploadDialog']['fields']['categories'] = true;
$GLOBALS['wgUploadDialog']['format']['filepage'] = '$DESCRIPTION $CATEGORIES';

$GLOBALS['bsgEnabledExtensions']['BlueSpiceWatchList'] = true;
$GLOBALS['bsgEnabledExtensions']['BlueSpiceWhoIsOnline'] = true;
