<?php

// If $wgDebugToolbar is enabled the custom $wgMWLoggerDefaultSpi would eat up all the output
if ( !$GLOBALS['wgDebugToolbar'] && !$GLOBALS['wgDebugLogFile'] && !$GLOBALS['wgDebugLogGroups'] ) {
	$GLOBALS['bsgDebugLogGroups']['error'] = $GLOBALS['bsgDebugLogGroups']['error'] ?? true;
	$GLOBALS['bsgDebugLogGroups']['exception'] = $GLOBALS['bsgDebugLogGroups']['exception'] ?? true;
}

if ( !isset( $GLOBALS['bsgDebugLogGroups'] ) ) {
	return;
}

$loggers = [];
$handlers = [];
foreach( $GLOBALS['bsgDebugLogGroups'] as $group => $file ) {
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
				/* $messageType */       4, /* Monolog\Handler\ErrorLogHandler::SAPI */
				/* $level */           100, /* Monolog\Logger::DEBUG */
				/* $bubble */         true,
				/* $expandNewlines */ true
			]
		];

		if ( in_array( $group, [ 'error', 'exception'] ) ) {
			$handler['args'][1] = 400; /* Monolog\Logger::ERROR */
		}
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

