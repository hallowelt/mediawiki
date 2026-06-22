<?php

wfLoadExtension( 'Cite' );
$GLOBALS['wgCiteVisualEditorOtherGroup'] = true;

wfLoadExtension( 'CiteThisPage' );
wfLoadExtension( 'Gadgets' );
wfLoadExtension( 'ImageMap' );
wfLoadExtension( 'InputBox' );
wfLoadExtension( 'Nuke' );

wfLoadExtension( 'ParserFunctions' );
$GLOBALS['wgPFEnableStringFunctions'] = true;

wfLoadExtension( 'Renameuser' );
wfLoadExtension( 'SyntaxHighlight_GeSHi' );
wfLoadExtension( 'WikiEditor' );
wfLoadExtension( 'CodeEditor' );

// ERM35038 - MediaWiki core references Skin:Vector specific messages
// (e.g. `vector-view-view`) in `MessagesEn.php` which causes errors
//  in the L10N cache if Skin:Vector is not enabled
wfLoadSkin( 'Vector' );
$GLOBALS['wgSkipSkins'][] = 'vector';
$GLOBALS['wgSkipSkins'][] = 'vector-2022';

