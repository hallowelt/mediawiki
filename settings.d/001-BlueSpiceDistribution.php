<?php

require_once __DIR__ . "/../extensions/Arrays/Arrays.php";
require_once __DIR__ . "/../extensions/CategoryTree/CategoryTree.php";
wfLoadExtension( 'DynamicPageList' );
$GLOBALS['wgDplSettings']['functionalRichness'] = 0;
require_once __DIR__ . "/../extensions/HitCounters/HitCounters.php";
require_once __DIR__ . "/../extensions/ImageMapEdit/ImageMapEdit.php";
require_once __DIR__ . "/../extensions/Quiz/Quiz.php";
wfLoadExtension( 'RSS' );
wfLoadExtension( 'Echo');
require_once __DIR__ . "/../extensions/TitleKey/TitleKey.php";
require_once __DIR__ . "/../extensions/EmbedVideo/EmbedVideo.php";
wfLoadExtension( 'FilterSpecialPages' );
wfLoadExtension( 'UserMerge' );
$wgUserMergeProtectedGroups = [];
$wgUserMergeUnmergeable = [];
require_once __DIR__ . "/../extensions/Variables/Variables.php";
wfLoadExtension( 'BlueSpiceEchoConnector' );
wfLoadExtension( 'BlueSpiceDistributionConnector' );
require_once __DIR__ . "/../extensions/BlueSpiceUserMergeConnector/BlueSpiceUserMergeConnector.php";
require_once __DIR__ . "/../extensions/UserFunctions/UserFunctions.php";
$GLOBALS['wgUFAllowedNamespaces'] = array_fill( 0, 5000, true );
require_once __DIR__ . "/../extensions/UrlGetParameters/UrlGetParameters.php";
