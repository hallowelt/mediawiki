<?php

$GLOBALS['bsgEnabledExtensions']['Cite'] = true;
$GLOBALS['wgCiteVisualEditorOtherGroup'] = true;

$GLOBALS['bsgEnabledExtensions']['CiteThisPage'] = true;
$GLOBALS['bsgEnabledExtensions']['CodeEditor'] = true;
$GLOBALS['bsgEnabledExtensions']['Gadgets'] = true;
$GLOBALS['bsgEnabledExtensions']['ImageMap'] = true;
$GLOBALS['bsgEnabledExtensions']['InputBox'] = true;
$GLOBALS['bsgEnabledExtensions']['Nuke'] = true;

$GLOBALS['bsgEnabledExtensions']['ParserFunctions'] = true;
$GLOBALS['wgPFEnableStringFunctions'] = true;

$GLOBALS['bsgEnabledExtensions']['Renameuser'] = true;
$GLOBALS['bsgEnabledExtensions']['SyntaxHighlight_GeSHi'] = true;
$GLOBALS['bsgEnabledExtensions']['WikiEditor'] = true;

// ERM35038 - MediaWiki core references Skin:Vector specific messages
// (e.g. `vector-view-view`) in `MessagesEn.php` which causes errors
//  in the L10N cache if Skin:Vector is not enabled
$GLOBALS['bsgEnabledSkins']['Vector'] = true;
$GLOBALS['wgSkipSkins'][] = 'vector';
$GLOBALS['wgSkipSkins'][] = 'vector-2022';

