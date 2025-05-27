<?php

wfLoadSkin( 'BlueSpiceDiscovery' );
$GLOBALS['wgDefaultSkin'] = "bluespicediscovery";
$GLOBALS['wgSkipSkins'] = [
	'minerva',
	'monobook',
	'timeless',
	'vector',
	'vector-2022'
];

$GLOBALS['bsgDiscoveryMetaItemsHeader'] = [ "page-sentence" ];
$GLOBALS['bsgDiscoveryMetaItemsFooter'] = [ "categories", "rating", "recommendations" ];

# $GLOBALS['wgHiddenPrefs'][] = 'skin'; // Can not be hidden anymore due to https://github.com/wikimedia/mediawiki-skins-Vector/blob/master/includes/Hooks.php#L548C5-L548C65
// BlueSpice discovery is responsive by default
$GLOBALS['wgHiddenPrefs'][] = 'skin-responsive';

$bsImagesPath =
	$GLOBALS['wgScriptPath']
	. '/extensions/BlueSpiceDistributionConnector/resources/images';

/*
 * Use an other image for MediaWiki.org
 */
$GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] = [
	'src' => $bsImagesPath . '/footer/MediaWiki.png',
	'height' => '27',
	'width' => '149'
] + $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'];

/*
 * We want to use an other image for this extensions but the config files are processed to early.
 * So we have to set the complete items.
 */
$GLOBALS['wgFooterIcons']['poweredby'] += [
	'bluespice' => [
		'src' => $bsImagesPath . '/footer/BlueSpice.png',
		'url' => 'http://bluespice.com',
		'alt' => 'Powered by BlueSpice',
		'height' => '27',
		'width' => '149'
	]
];

if ( array_key_exists( 'semanticmediawiki', $GLOBALS['wgFooterIcons']['poweredby'] ) ) {
	$footerIcons = [
		'mediawiki' => $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'],
		'bluespice' => $GLOBALS['wgFooterIcons']['poweredby']['bluespice'],
		'semanticmediawiki' => $GLOBALS['wgFooterIcons']['poweredby']['semanticmediawiki']
	];

	unset( $GLOBALS['wgFooterIcons']['poweredby']['mediawiki'] );
	unset( $GLOBALS['wgFooterIcons']['poweredby']['bluespice'] );
	unset( $GLOBALS['wgFooterIcons']['poweredby']['semanticmediawiki'] );

	$footerIcons += $GLOBALS['wgFooterIcons']['poweredby'];

	$GLOBALS['wgFooterIcons']['poweredby'] = $footerIcons;
}

/**
 * The name of the default logo changed with REL1_39 from
 * wiki.png to change-your-logo.svg.
 * The old name may be set in LocalSetings.php. This caused an issue
 * when trying to set the BlueSpice logo.
 */
$assetsDir = $GLOBALS['wgResourceBasePath'] . '/resources/assets/';
$blueSpiceLogoPath = "$bsImagesPath/BlueSpice-5_Logo.svg";

if ( strpos( $GLOBALS['wgLogos']['1x'], $assetsDir ) === 0 ) {
	$GLOBALS['wgLogos'] = [ '1x' => $blueSpiceLogoPath ];
}

if ( $GLOBALS['wgFavicon'] == '/favicon.ico' ){
	$GLOBALS['wgFavicon'] = "$bsImagesPath/favicon.ico";
}

unset( $bsImagesPath );
unset( $assetsDir );
unset( $blueSpiceLogoPath );
