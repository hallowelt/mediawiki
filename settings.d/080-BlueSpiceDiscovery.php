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
