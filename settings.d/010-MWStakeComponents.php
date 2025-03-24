<?php

/**
 * This is required to ensure that the MWStakeComponents have a chance to register their services
 * via `$wgServiceWiringFiles`_before_ any other extension can instantiate the `MediaWikiServices`
 * service container.
 *
 * Background: Even though it should not be done, some extensions will make a call to
 * `MediaWikiServices::getInstance` at "manifest.callback" time ( = "on registration"
 * in `$IP/includes/Setup.php`)
 *
 * ERM23675
 */
mwsInitComponents();
