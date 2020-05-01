<?php
/**
 * Holds list of deprecated hooks and methods for retrieval
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
 */

namespace MediaWiki\HookContainer;

use InvalidArgumentException;

class DeprecatedHooks {

	/**
	 * @var array[] List of deprecated hooks. Value arrays for each hook contain:
	 *  - deprecatedVersion: (string) Version in which the hook was deprecated,
	 *    to pass to wfDeprecated().
	 *  - component: (string, optional) $component to pass to wfDeprecated().
	 */
	private $deprecatedHooks = [
		'APIQueryInfoTokens' => [ 'deprecatedVersion' => '1.24' ],
		'APIQueryRecentChangesTokens' => [ 'deprecatedVersion' => '1.24' ],
		'APIQueryRevisionsTokens' => [ 'deprecatedVersion' => '1.24' ],
		'APIQueryUsersTokens' => [ 'deprecatedVersion' => '1.24' ],
		'ApiTokensGetTokenTypes' => [ 'deprecatedVersion' => '1.24' ],
		'ArticleEditUpdates' => [ 'deprecatedVersion' => '1.35' ],
		'ArticleEditUpdatesDeleteFromRecentchanges' => [ 'deprecatedVersion' => '1.35' ],
		'ArticleRevisionUndeleted' => [ 'deprecatedVersion' => '1.35' ],
		'BeforeParserrenderImageGallery' => [ 'deprecatedVersion' => '1.35' ],
		'InternalParseBeforeSanitize' => [ 'deprecatedVersion' => '1.35' ],
		'LinkBegin' => [ 'deprecatedVersion' => '1.28' ],
		'LinkEnd' => [ 'deprecatedVersion' => '1.28' ],
		'ParserBeforeTidy' => [ 'deprecatedVersion' => '1.35' ],
		'ParserFetchTemplate' => [ 'deprecatedVersion' => '1.35' ],
		'ParserGetVariableValueVarCache' => [ 'deprecatedVersion' => '1.35' ],
		'ParserPreSaveTransformComplete' => [ 'deprecatedVersion' => '1.35' ],
		'ParserSectionCreate' => [ 'deprecatedVersion' => '1.35' ],
		'RevisionInsertComplete' => [ 'deprecatedVersion' => '1.31' ],
		'UndeleteShowRevision' => [ 'deprecatedVersion' => '1.35' ],
	];

	/**
	 * @param array[] $deprecatedHooks List of hooks to mark as deprecated.
	 * Value arrays for each hook contain:
	 *  - deprecatedVersion: (string) Version in which the hook was deprecated,
	 *    to pass to wfDeprecated().
	 *  - component: (string, optional) $component to pass to wfDeprecated().
	 */
	public function __construct( array $deprecatedHooks = [] ) {
		foreach ( $deprecatedHooks as $hook => $info ) {
			$this->markDeprecated( $hook, $info['deprecatedVersion'], $info['component'] ?? false );
		}
	}

	/**
	 * For use by extensions, to add to list of deprecated hooks.
	 * Core-defined hooks should instead be added to $this->$deprecatedHooks directly.
	 * However, the preferred method of marking a hook deprecated is by adding it to
	 * the DeprecatedHooks attribute in extension.json
	 *
	 * @param string $hook
	 * @param string $version Version in which the hook was deprecated, to pass to wfDeprecated()
	 * @param string|null $component (optional) component to pass to wfDeprecated().
	 * @throws InvalidArgumentException Hook has already been marked deprecated
	 */
	public function markDeprecated( string $hook, string $version, ?string $component = null ) : void {
		if ( isset( $this->deprecatedHooks[$hook] ) ) {
			throw new InvalidArgumentException(
				"Cannot mark hook '$hook' deprecated with version $version. " .
				"It is already marked deprecated with version " .
				$this->deprecatedHooks[$hook]['deprecatedVersion']
			);
		}
		$hookInfo = [ 'deprecatedVersion' => $version ];
		if ( $component ) {
			$hookInfo['component'] = $component;
		}
		$this->deprecatedHooks[$hook] = $hookInfo;
	}

	/**
	 * Checks whether hook is marked deprecated
	 * @param string $hook Hook name
	 * @return bool
	 */
	public function isHookDeprecated( string $hook ) : bool {
		return isset( $this->deprecatedHooks[$hook] );
	}

	/**
	 * Gets deprecation info for a specific hook or all hooks if hook not specified
	 * @param string|null $hook (optional) Hook name
	 * @return array|null Value array from $this->deprecatedHooks for a specific hook or all hooks
	 */
	public function getDeprecationInfo( ?string $hook = null ) : ?array {
		if ( !$hook ) {
			return $this->deprecatedHooks;
		}
		return $this->deprecatedHooks[$hook] ?? null;
	}
}
