<?php

wfLoadSkin( 'BlueSpiceDiscovery' );
$GLOBALS['wgDefaultSkin'] = "bluespicediscovery";

// TODO: Have logo in skin independent location
if ( $GLOBALS['wgLogos']['1x'] == $GLOBALS['wgResourceBasePath'] . '/resources/assets/wiki.png' ){
	$GLOBALS['wgLogos'] = [
		'1x' => $GLOBALS['wgResourceBasePath'] . '/skins/BlueSpiceDiscovery/resources/images/bs_logo.png'
	];
}
