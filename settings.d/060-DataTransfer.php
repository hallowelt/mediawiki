<?php

wfLoadExtension( 'DataTransfer' );
$GLOBALS['$wgGroupPermissions']['user']['datatransferimport'] = true;
$GLOBALS['bsgPermissionConfig']['datatransferimport'] = [
	'type' => 'global',
	'roles' => [ 'editor' ]
];
$GLOBALS['wgDataTransferViewXMLParseFields'] = true;
