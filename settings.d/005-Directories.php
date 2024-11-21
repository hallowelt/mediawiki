<?php

// Cache directory.
// If this is not set database will be used.
// Must be writable by webserver.
if( !$wgCacheDirectory ) {
	$wgCacheDirectory = __DIR__ . "/../cache";
}
