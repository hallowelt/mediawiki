<?php

if ( !isset( $bsgDebugLogGroups ) ) {
	return;
}

$loggers = [];
$handlers = [];
foreach( $bsgDebugLogGroups as $group => $file ) {
	$handlerName = "stream-$group";
	$loggers[$group] = [
		'processors' => [ 'wiki', 'psr' ],
		'handlers' => [ $handlerName ]
	];
	$handlers[$handlerName] = [
		'class' => '\\Monolog\\Handler\\StreamHandler',
		'args' => [ $file ],
		'formatter' => 'line'
	];
}

$GLOBALS['wgMWLoggerDefaultSpi'] = [
	'class' => '\\MediaWiki\\Logger\\MonologSpi',
	'args' => [ [
		'loggers' => $loggers,
		'processors' => [
			'wiki' => [ 'class' => '\\MediaWiki\\Logger\\Monolog\\WikiProcessor' ],
			'psr' => [ 'class' => '\\Monolog\\Processor\\PsrLogMessageProcessor' ],
		],
		'handlers' => $handlers,
		'formatters' => [
			'line' => [ 'class' => '\\Monolog\\Formatter\\LineFormatter' ],
		]
	] ]
];

unset( $loggers );
unset( $handlers );

