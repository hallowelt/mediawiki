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

$GLOBALS['wgHiddenPrefs'][] = 'skin';
// BlueSpice discovery is responsive by default
$GLOBALS['wgHiddenPrefs'][] = 'skin-responsive';
