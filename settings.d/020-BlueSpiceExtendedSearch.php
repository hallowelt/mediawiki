<?php
return; // Disabled. Needs Tomcat

wfLoadExtension( 'BlueSpiceExtendedSearch' );

// Not respected by BlueSpiceExtendedSearch
$GLOBALS['wgHiddenPrefs'][] = 'searchlimit';
