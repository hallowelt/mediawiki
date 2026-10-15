<?php

// Slightly increase session expiry to avoid logout issues caused by timezone misconfiguration
$GLOBALS['wgObjectCacheSessionExpiry'] = 3 * 60 * 60;

// Avoid `MWUnknownContentModelException` on legacy databases
$GLOBALS['wgContentHandlers']['BSSocial'] = 'FallbackContentHandler';
$GLOBALS['wgContentHandlers']['BSSocialDiscussion'] = 'FallbackContentHandler';
$GLOBALS['wgContentHandlers']['BSSocialProfile'] = 'FallbackContentHandler';
