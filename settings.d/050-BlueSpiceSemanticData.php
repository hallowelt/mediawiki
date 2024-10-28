<?php

wfLoadExtension( 'SemanticMediaWiki' );
wfLoadExtension( 'BlueSpiceSMWConnector' );
wfLoadExtension( 'ExternalData' );
wfLoadExtension( 'OpenLayers' );
wfLoadExtension( 'SemanticCompoundQueries' );

wfLoadExtension( 'SemanticExtraSpecialProperties' );
$GLOBALS[ 'sespgUseFixedTables' ] = true;
$GLOBALS[ 'sespgExcludeBotEdits' ] = true;
$GLOBALS[ 'sespgEnabledPropertyList' ] = [
	'_EUSER', '_CUSER', '_REVID', '_PAGEID', '_VIEWS', '_NREV', '_TNREV',
	'_SUBP', '_USERREG', '_USEREDITCNT', '_EXIFDATA'
];

wfLoadExtension( 'SemanticScribunto' );

wfLoadExtension( 'SemanticResultFormats' );

enableSemantics( 'localhost' );
$GLOBALS[ 'smwgPageSpecialProperties' ] = array_merge(
	$GLOBALS[ 'smwgPageSpecialProperties' ],
	[ '_CDAT', '_LEDT', '_NEWP', '_MIME', '_MEDIA' ]
);
$GLOBALS[ 'smwgEnabledEditPageHelp' ] = false;
$GLOBALS['smwgQMaxSize'] = 100;
$GLOBALS['maxRecursionDepth'] = 4;

$GLOBALS[ 'smwgConfigFileDir' ] = "$IP/extensions/BlueSpiceFoundation/data";
if( defined( 'BSDATADIR' ) ) {
	$GLOBALS[ 'smwgConfigFileDir' ] = BSDATADIR;
}

if( defined( 'FARMER_CALLED_INSTANCE' ) && FARMER_CALLED_INSTANCE !== '' ) {
	$GLOBALS[ 'smwgConfigFileDir' ] =
		$GLOBALS[ 'wgSimpleFarmerConfig']->get( 'instanceDirectory' ) .
		'/' . FARMER_CALLED_INSTANCE . "/extensions/BlueSpiceFoundation/data";
}

$GLOBALS[ 'wgExtensionFunctions' ][] = function() {
	/**
	 * SMW's PHPUnit setup is not made to integrate into MW core's bootstrapper. This prevents MW
	 * core's PHPUnit script to break right away.
	*/
	if( defined( 'MW_PHPUNIT_TEST' ) ) {
		$GLOBALS['wgAutoloadClasses']['SMW\Tests\DataItemTest'] =
			$GLOBALS['IP']
			. '/extensions/SemanticMediaWiki/tests/phpunit/includes/dataitems/DataItemTest.php';
		require_once $GLOBALS['IP'] . '/extensions/SemanticMediaWiki/tests/autoloader.php';
	}
};


$GLOBALS['wgFooterIcons']['poweredby'] += [
	'semanticmediawiki' => [
		'src' => $wgScriptPath . '/extensions/BlueSpiceDistributionConnector/resources/images/footer/SemanticMediaWiki.png',
		'url' => 'https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki',
		'alt' => 'Powered by Semantic MediaWiki',
		'height' => '27',
		'width' => '149'
	]
];
