<?php

wfLoadExtension( 'PageForms');
$GLOBALS['bsgPermissionConfig']['multipageedit'] = [
	'type' => 'global',
	'roles' => [ 'editor' ]
];
$GLOBALS['bsgPermissionConfig']['editrestrictedfields'] = [
	'type' => 'global',
	'roles' => [ 'admin' ]
];

wfLoadExtension( 'BlueSpicePageFormsConnector');
