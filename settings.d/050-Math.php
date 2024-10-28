<?php

wfLoadExtension( 'Math' );

$GLOBALS['wgMathValidModes'] = [ 'mathml' ];
$GLOBALS['wgDefaultUserOptions']['math'] = 'mathml';
$GLOBALS['wgMaxShellMemory'] = 1228800;
$GLOBALS['wgHiddenPrefs'][] = 'math';

if ( wfIsWindows() ) {
	$GLOBALS['wgMathoidCli'] = [
		'node',
		'C:\bluespice\bin\mathoid\cli.js',
		'-c',
		'C:\bluespice\bin\mathoid\config.yaml'
	];
}
else {
	$GLOBALS['wgMathoidCli'] = [
		'/opt/mathoid/cli.js',
		'-c',
		'/opt/mathoid/config.yaml'
	];
}
