<?php

wfLoadExtension( 'BlueSpiceExtendedSearch' );

// Not respected by BlueSpiceExtendedSearch
$GLOBALS['wgHiddenPrefs'][] = 'searchlimit';
