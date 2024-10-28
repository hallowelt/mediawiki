<?php

wfLoadExtension( 'WebDAV' );
#wfLoadExtension( 'BlueSpiceWebDAVMinorSave' );  Experimental, disabled by default, enabled only on request
wfLoadExtension( 'BlueSpiceWebDAVClientIntegration' );

$wgWebDAVAuthType = 'token';
