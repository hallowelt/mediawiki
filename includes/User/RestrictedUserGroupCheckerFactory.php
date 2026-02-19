<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\WikiMap\WikiMap;

/**
 * A service that helps to create RestrictedUserGroupChecker instances, either for the local or remote wikis.
 *
 * @since 1.46
 */
class RestrictedUserGroupCheckerFactory {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RestrictedGroups,
	];

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly UserRequirementsConditionChecker $userRequirementsConditionChecker,
	) {
	}

	/**
	 * Creates an instance of RestrictedUserGroupChecker for the specified wiki, reading the groups restrictions from
	 * the ServiceOptions provided to the constructor or global $wgConf variable.
	 * @param false|string $wiki Wiki ID for which the checker should be created. `false` means the current wiki.
	 */
	public function getRestrictedUserGroupChecker( false|string $wiki = false ): RestrictedUserGroupChecker {
		$isLocal = $wiki === false || $wiki === WikiMap::getCurrentWikiId();
		if ( $isLocal ) {
			return $this->getLocalRestrictedUserGroupChecker();
		}
		return $this->getRestrictedUserGroupCheckerForWiki( $wiki );
	}

	private function getLocalRestrictedUserGroupChecker(): RestrictedUserGroupChecker {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$restrictedGroups = $this->options->get( MainConfigNames::RestrictedGroups );

		return new RestrictedUserGroupChecker(
			$restrictedGroups,
			$this->userRequirementsConditionChecker,
		);
	}

	private function getRestrictedUserGroupCheckerForWiki( string $wiki ): RestrictedUserGroupChecker {
		global $wgConf;
		'@phan-var \MediaWiki\Config\SiteConfiguration $wgConf';

		$remoteRestrictedGroups = $wgConf->get( 'wgRestrictedGroups', $wiki ) ?? [];
		return new RestrictedUserGroupChecker(
			$remoteRestrictedGroups,
			$this->userRequirementsConditionChecker,
		);
	}
}
