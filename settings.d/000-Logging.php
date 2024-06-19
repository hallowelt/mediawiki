<?php

// If $wgDebugToolbar is enabled the custom $wgMWLoggerDefaultSpi would eat up all the output
if ( !$GLOBALS['wgDebugToolbar'] && !$GLOBALS['wgDebugLogFile'] ) {
	$bsgDebugLogGroups['exception'] = $bsgDebugLogGroups['exception'] ?? true;
}

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

	$handler = [
		'class' => '\\Monolog\\Handler\\StreamHandler',
		'args' => [ $file ],
		'formatter' => 'line'
	];

	if ( $file === true ) {
		$handler = [
			'class' => '\\Monolog\\Handler\\ErrorLogHandler',
			'args' => [
				/* $messageType */       0, /* Monolog\Handler\ErrorLogHandler::OPERATING_SYSTEM */
				/* $level */           100, /* Monolog\Logger::DEBUG */
				/* $bubble */         true,
				/* $expandNewlines */ true
			]
		];
	}

	$handlers[$handlerName] = $handler;
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

