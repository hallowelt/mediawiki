<?php

//New constants
$sTMPUploadDir = empty( $GLOBALS['wgUploadDirectory'] ) ? $GLOBALS['IP'] . DIRECTORY_SEPARATOR . 'images' : $GLOBALS['wgUploadDirectory'];

$sTMPCacheDir = empty( $GLOBALS['wgFileCacheDirectory'] ) ? $sTMPUploadDir . DIRECTORY_SEPARATOR . 'cache' : $GLOBALS['wgFileCacheDirectory'];

$sTMPUploadPath = empty( $GLOBALS['wgUploadPath'] ) ? $GLOBALS['wgScriptPath'] . "/images" : $GLOBALS['wgUploadPath'];

if ( !defined( 'BS_DATA_DIR' ) ) {
	define( 'BS_DATA_DIR', $sTMPUploadDir . DIRECTORY_SEPARATOR . 'bluespice' ); //Future
}
if ( !defined( 'BS_CACHE_DIR' ) ) {
	define( 'BS_CACHE_DIR', $sTMPCacheDir . DIRECTORY_SEPARATOR . 'bluespice' ); //$wgCacheDirectory?
}
if ( !defined( 'BS_DATA_PATH' ) ) {
	define( 'BS_DATA_PATH', $sTMPUploadPath . '/bluespice' );
}

class BsCliInstaller extends CliInstaller {

	/**
	 *
	 * @var bool
	 */
	private $ignoreExistingLocalSettings = false;

	/**
	 *
	 * @param string $siteName
	 * @param string $admin
	 * @param array $option
	 */
	public function __construct( $siteName, $admin = null, array $option = array() ) {
		parent::__construct( $siteName, $admin, $option );

		if ( isset( $option['ignoreExistingLocalSettings'] ) ) {
			$this->ignoreExistingLocalSettings = true;
		}
	}

	/**
	 *
	 * @param \Status $status
	 * @return void
	 */
	public function showStatusMessage( \Status $status ) {
		$this->ignoreExistingLocalSettings;
		$errors = $status->getErrorsByType( 'error' );
		foreach( $errors as $error ) {
			if( $this->skipExistingLocalSettingsError( $error ) ) {
				return;
			}
		}
		return parent::showStatusMessage( $status );
	}

	private function skipExistingLocalSettingsError( $error ) {
		if( $error['message'] !== 'config-localsettings-cli-upgrade' ) {
			return false;
		}
		if( $this->ignoreExistingLocalSettings ) {
			return true;
		}
		return false;
	}

	protected function includeExtensions() {
		global $IP;
		$exts = $this->getVar( '_Extensions' );
		$IP = $this->getVar( 'IP' );
		/**
		 * We need to include DefaultSettings before including extensions to avoid
		 * warnings about unset variables. However, the only thing we really
		 * want here is $wgHooks['LoadExtensionSchemaUpdates']. This won't work
		 * if the extension has hidden hook registration in $wgExtensionFunctions,
		 * but we're not opening that can of worms
		 * @see https://phabricator.wikimedia.org/T28857
		 */
		global $wgAutoloadClasses, $wgVersion;
		$wgAutoloadClasses = [];
		$queue = [];
		require_once "$IP/includes/DefaultSettings.php";
		require_once __DIR__ . '/../../../extensions/BlueSpiceFoundation/includes/Defines.php';
		require_once __DIR__ . '/../../../LocalSettings.BlueSpice.php';
		foreach ( $exts as $e ) {
			if ( file_exists( "$IP/extensions/$e/extension.json" ) ) {
				$queue["$IP/extensions/$e/extension.json"] = 1;
			} else {
				require_once "$IP/extensions/$e/$e.php";
			}
		}
		$registry = new ExtensionRegistry();
		$data = $registry->readFromQueue( $queue );
		$wgAutoloadClasses += $data['autoload'];
		if ( isset( $data['autoloaderNS'] ) ) {
			AutoLoader::$psr4Namespaces += $data['autoloaderNS'];
		}
		$hooksWeWant = isset( $wgHooks['LoadExtensionSchemaUpdates'] ) ?
			/** @suppress PhanUndeclaredVariable $wgHooks is set by DefaultSettings */
			$wgHooks['LoadExtensionSchemaUpdates'] : [];
		if ( isset( $data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			$hooksWeWant = array_merge_recursive(
				$hooksWeWant, $data['globals']['wgHooks']['LoadExtensionSchemaUpdates']
			);
		}
		// Unset everyone else's hooks. Lord knows what someone might be doing
		// in ParserFirstCallInit (see T29171)
		$GLOBALS['wgHooks'] = [ 'LoadExtensionSchemaUpdates' => $hooksWeWant ];
		return Status::newGood();
	}

}
