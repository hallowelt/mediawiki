<?php

/**
 * Convert a JSON abstract schema to a schema file in the given DBMS type
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Settings\Source\FileSource;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to generate doc/configuration.md from settings-schema.yaml
 *
 * @ingroup Maintenance
 */
class GenerateConfigDoc extends Maintenance {
	/** @var string */
	private const DEFAULT_INPUT_PATH = 'includes/config-schema.yaml';

	/** @var string */
	private const DEFAULT_OUTPUT_PATH = 'docs/Configuration.md';

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Build Configuration.md file from config-schema.yaml' );

		$this->addOption(
			'schema',
			'Path to the config schema file relative to $IP. Default: ' . self::DEFAULT_INPUT_PATH,
			false,
			true
		);
		$this->addOption(
			'output',
			'Path to output relative to $IP. Default: ' . self::DEFAULT_OUTPUT_PATH,
			false,
			true
		);
	}

	public function execute() {
		$input = ( new FileSource( $this->getInputPath() ) )->load();

		$result = "<!-- This file is automatically generated using maintenance/generateConfigDoc.php. -->\n";
		$result .= "<!-- Do not edit this file directly. -->\n";
		$result .= "<!-- See maintenance/generateConfigDoc.php for instructions. -->\n";

		$result .= "This is a list of configuration variables that can be set in LocalSettings.php.\n\n";

		// Table of contents
		$result .= "[TOC]\n\n";

		// Details about each config variable
		foreach ( $input['config-schema'] as $configKey => $configSchema ) {
			$result .= "\section $configKey\n";
			if ( array_key_exists( 'description', $configSchema ) ) {
				$result .= $configSchema['description'];
			}
			$result .= "\n\n";
		}

		file_put_contents( $this->getOutputPath(), $result );
	}

	private function getInputPath(): string {
		global $IP;
		$inputPath = $this->getOption( 'schema', self::DEFAULT_INPUT_PATH );
		return $IP . DIRECTORY_SEPARATOR . $inputPath;
	}

	private function getOutputPath(): string {
		global $IP;
		$outputPath = $this->getOption( 'output', self::DEFAULT_OUTPUT_PATH );
		if ( $outputPath === '-' || $outputPath === 'php://stdout' ) {
			return 'php://stdout';
		}
		return $IP . DIRECTORY_SEPARATOR . $outputPath;
	}

}

$maintClass = GenerateConfigDoc::class;
require_once RUN_MAINTENANCE_IF_MAIN;
