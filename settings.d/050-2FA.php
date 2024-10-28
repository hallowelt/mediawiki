<?php

wfLoadExtension( 'OATHAuth' );
$GLOBALS['wgGroupPermissions']['user']['oathauth-enable'] = true;
$GLOBALS['bsgPermissionConfig']['oathauth-enable'] = [
	'type' => 'global',
	'roles' => [ 'reader' ]
];

wfLoadExtension( 'WebAuthn' );