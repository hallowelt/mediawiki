<?php

namespace MediaWiki\Permissions;

use DBAccessObjectUtils;
use IDBAccessObject;
use LinkCache;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageStore;
use stdClass;
use Title;
use TitleValue;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Class RestrictionStore
 *
 * @since 1.37
 */
class RestrictionStore {

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::NamespaceProtection,
		MainConfigNames::RestrictionLevels,
		MainConfigNames::RestrictionTypes,
		MainConfigNames::SemiprotectedRestrictionLevels,
	];

	/** @var ServiceOptions */
	private $options;

	/** @var WANObjectCache */
	private $wanCache;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var LinkCache */
	private $linkCache;

	/** @var LinksMigration */
	private $linksMigration;

	/** @var CommentStore */
	private $commentStore;

	/** @var HookContainer */
	private $hookContainer;

	/** @var HookRunner */
	private $hookRunner;

	/** @var PageStore */
	private $pageStore;

	/**
	 * @var array[] Caching various restrictions data in the following format:
	 * cache key => [
	 *   string[] `restrictions` => restrictions loaded for pages
	 *   ?string `expiry` => restrictions expiry data for pages
	 *   ?array `create_protection` => value for getCreateProtection
	 *   bool `cascade` => cascade restrictions on this page to included templates and images?
	 *   array[] `cascade_sources` => the results of getCascadeProtectionSources
	 *   bool `has_cascading` => Are cascading restrictions in effect on this page?
	 * ]
	 */
	private $cache = [];

	/**
	 * @param ServiceOptions $options
	 * @param WANObjectCache $wanCache
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkCache $linkCache
	 * @param LinksMigration $linksMigration
	 * @param CommentStore $commentStore
	 * @param HookContainer $hookContainer
	 * @param PageStore $pageStore
	 */
	public function __construct(
		ServiceOptions $options,
		WANObjectCache $wanCache,
		ILoadBalancer $loadBalancer,
		LinkCache $linkCache,
		LinksMigration $linksMigration,
		CommentStore $commentStore,
		HookContainer $hookContainer,
		PageStore $pageStore
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->wanCache = $wanCache;
		$this->loadBalancer = $loadBalancer;
		$this->linkCache = $linkCache;
		$this->linksMigration = $linksMigration;
		$this->commentStore = $commentStore;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->pageStore = $pageStore;
	}

	/**
	 * Returns list of restrictions for specified page
	 *
	 * @param PageIdentity $page Must be local
	 * @param string $action Action that restrictions need to be checked for
	 * @return string[] Restriction levels needed to take the action. All levels are required. Note
	 *   that restriction levels are normally user rights, but 'sysop' and 'autoconfirmed' are also
	 *   allowed for backwards compatibility. These should be mapped to 'editprotected' and
	 *   'editsemiprotected' respectively. Returns an empty array if there are no restrictions set
	 *   for this action (including for unrecognized actions).
	 */
	public function getRestrictions( PageIdentity $page, string $action ): array {
		$page->assertWiki( PageIdentity::LOCAL );

		$restrictions = $this->getAllRestrictions( $page );
		return $restrictions[$action] ?? [];
	}

	/**
	 * Returns the restricted actions and their restrictions for the specified page
	 *
	 * @param PageIdentity $page Must be local
	 * @return string[][] Keys are actions, values are arrays as returned by
	 *   RestrictionStore::getRestrictions(). Empty if no restrictions are in place.
	 */
	public function getAllRestrictions( PageIdentity $page ): array {
		$page->assertWiki( PageIdentity::LOCAL );

		if ( !$this->areRestrictionsLoaded( $page ) ) {
			$this->loadRestrictions( $page );
		}
		return $this->cache[CacheKeyHelper::getKeyForPage( $page )]['restrictions'] ?? [];
	}

	/**
	 * Get the expiry time for the restriction against a given action
	 *
	 * @param PageIdentity $page Must be local
	 * @param string $action
	 * @return ?string 14-char timestamp, or 'infinity' if the page is protected forever or not
	 *   protected at all, or null if the action is not recognized. NOTE: This returns null for
	 *   unrecognized actions, unlike Title::getRestrictionExpiry which returns false.
	 */
	public function getRestrictionExpiry( PageIdentity $page, string $action ): ?string {
		$page->assertWiki( PageIdentity::LOCAL );

		if ( !$this->areRestrictionsLoaded( $page ) ) {
			$this->loadRestrictions( $page );
		}
		return $this->cache[CacheKeyHelper::getKeyForPage( $page )]['expiry'][$action] ?? null;
	}

	/**
	 * Is this title subject to protection against creation?
	 *
	 * @param PageIdentity $page Must be local
	 * @return ?array Null if no restrictions. Otherwise an array with the following keys:
	 *     - user: user id
	 *     - expiry: 14-digit timestamp or 'infinity'
	 *     - permission: string (pt_create_perm)
	 *     - reason: string
	 * @internal Only to be called by Title::getTitleProtection. When that is discontinued, this
	 * will be too, in favor of getRestrictions( $page, 'create' ). If someone wants to know who
	 * protected it or the reason, there should be a method that exposes that for all restriction
	 * types.
	 */
	public function getCreateProtection( PageIdentity $page ): ?array {
		$page->assertWiki( PageIdentity::LOCAL );

		$protection = $this->getCreateProtectionInternal( $page );
		// TODO: the remapping below probably need to be migrated into other method one day
		if ( $protection ) {
			if ( $protection['permission'] == 'sysop' ) {
				$protection['permission'] = 'editprotected'; // B/C
			}
			if ( $protection['permission'] == 'autoconfirmed' ) {
				$protection['permission'] = 'editsemiprotected'; // B/C
			}
		}
		return $protection;
	}

	/**
	 * Remove any title creation protection due to page existing
	 *
	 * @param PageIdentity $page Must be local
	 * @internal Only to be called by WikiPage::onArticleCreate.
	 */
	public function deleteCreateProtection( PageIdentity $page ): void {
		$page->assertWiki( PageIdentity::LOCAL );

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$dbw->delete(
			'protected_titles',
			[ 'pt_namespace' => $page->getNamespace(), 'pt_title' => $page->getDBkey() ],
			__METHOD__
		);
		$this->cache[CacheKeyHelper::getKeyForPage( $page )]['create_protection'] = null;
	}

	/**
	 * Is this page "semi-protected" - the *only* protection levels are listed in
	 * $wgSemiprotectedRestrictionLevels?
	 *
	 * @param PageIdentity $page Must be local
	 * @param string $action Action to check (default: edit)
	 * @return bool
	 */
	public function isSemiProtected( PageIdentity $page, string $action = 'edit' ): bool {
		$page->assertWiki( PageIdentity::LOCAL );

		$restrictions = $this->getRestrictions( $page, $action );
		$semi = $this->options->get( MainConfigNames::SemiprotectedRestrictionLevels );
		if ( !$restrictions || !$semi ) {
			// Not protected, or all protection is full protection
			return false;
		}

		// Remap autoconfirmed to editsemiprotected for BC
		foreach ( array_keys( $semi, 'editsemiprotected' ) as $key ) {
			$semi[$key] = 'autoconfirmed';
		}
		foreach ( array_keys( $restrictions, 'editsemiprotected' ) as $key ) {
			$restrictions[$key] = 'autoconfirmed';
		}

		return !array_diff( $restrictions, $semi );
	}

	/**
	 * Does the title correspond to a protected article?
	 *
	 * @param PageIdentity $page Must be local
	 * @param string $action The action the page is protected from, by default checks all actions.
	 * @return bool
	 */
	public function isProtected( PageIdentity $page, string $action = '' ): bool {
		$page->assertWiki( PageIdentity::LOCAL );

		// Special pages have inherent protection (TODO: remove after switch to ProperPageIdentity)
		if ( $page->getNamespace() === NS_SPECIAL ) {
			return true;
		}

		// Check regular protection levels
		$applicableTypes = $this->listApplicableRestrictionTypes( $page );

		if ( $action === '' ) {
			foreach ( $applicableTypes as $type ) {
				if ( $this->isProtected( $page, $type ) ) {
					return true;
				}
			}
			return false;
		}

		if ( !in_array( $action, $applicableTypes ) ) {
			return false;
		}

		return (bool)array_diff(
			array_intersect(
				$this->getRestrictions( $page, $action ),
				$this->options->get( MainConfigNames::RestrictionLevels )
			),
			[ '' ]
		);
	}

	/**
	 * Cascading protection: Return true if cascading restrictions apply to this page, false if not.
	 *
	 * @param PageIdentity $page Must be local
	 * @return bool If the page is subject to cascading restrictions.
	 */
	public function isCascadeProtected( PageIdentity $page ): bool {
		$page->assertWiki( PageIdentity::LOCAL );

		return $this->getCascadeProtectionSourcesInternal( $page, true );
	}

	/**
	 * Returns restriction types for the current page
	 *
	 * @param PageIdentity $page Must be local
	 * @return string[] Applicable restriction types
	 */
	public function listApplicableRestrictionTypes( PageIdentity $page ): array {
		$page->assertWiki( PageIdentity::LOCAL );

		if ( !$page->canExist() ) {
			return [];
		}

		$types = $this->listAllRestrictionTypes( $page->exists() );

		if ( $page->getNamespace() !== NS_FILE ) {
			// Remove the upload restriction for non-file titles
			$types = array_values( array_diff( $types, [ 'upload' ] ) );
		}

		if ( $this->hookContainer->isRegistered( 'TitleGetRestrictionTypes' ) ) {
			$this->hookRunner->onTitleGetRestrictionTypes(
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable castFrom does not return null here
				Title::castFromPageIdentity( $page ), $types );
		}

		return $types;
	}

	/**
	 * Get a filtered list of all restriction types supported by this wiki.
	 *
	 * @param bool $exists True to get all restriction types that apply to titles that do exist,
	 *   false for all restriction types that apply to titles that do not exist
	 * @return string[]
	 */
	public function listAllRestrictionTypes( bool $exists = true ): array {
		$types = $this->options->get( MainConfigNames::RestrictionTypes );
		if ( $exists ) {
			// Remove the create restriction for existing titles
			return array_values( array_diff( $types, [ 'create' ] ) );
		}

		// Only the create restrictions apply to non-existing titles
		return array_values( array_intersect( $types, [ 'create' ] ) );
	}

	/**
	 * Load restrictions from page.page_restrictions and the page_restrictions table
	 *
	 * @param PageIdentity $page Must be local
	 * @param int $flags IDBAccessObject::READ_XXX constants (e.g., READ_LATEST to read from
	 *   primary DB)
	 * @internal Public for use in WikiPage only
	 */
	public function loadRestrictions(
		PageIdentity $page, int $flags = IDBAccessObject::READ_NORMAL
	): void {
		$page->assertWiki( PageIdentity::LOCAL );

		if ( !$page->canExist() ) {
			return;
		}

		$readLatest = DBAccessObjectUtils::hasFlags( $flags, IDBAccessObject::READ_LATEST );

		if ( $this->areRestrictionsLoaded( $page ) && !$readLatest ) {
			return;
		}

		$cacheEntry = &$this->cache[CacheKeyHelper::getKeyForPage( $page )];

		$cacheEntry['restrictions'] = [];

		// XXX Work around https://phabricator.wikimedia.org/T287575
		if ( $readLatest ) {
			$page = $this->pageStore->getPageByReference( $page, $flags ) ?? $page;
		}
		$id = $page->getId();
		if ( $id ) {
			$fname = __METHOD__;
			$loadRestrictionsFromDb = static function ( IDatabase $dbr ) use ( $fname, $id ) {
				return iterator_to_array(
					$dbr->newSelectQueryBuilder()
					->select( [ 'pr_type', 'pr_expiry', 'pr_level', 'pr_cascade' ] )
					->from( 'page_restrictions' )
					->where( [ 'pr_page' => $id ] )
					->caller( $fname )->fetchResultSet()
				);
			};

			if ( $readLatest ) {
				$dbr = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
				$rows = $loadRestrictionsFromDb( $dbr );
			} else {
				$this->linkCache->addLinkObj( $page );
				$latestRev = $this->linkCache->getGoodLinkFieldObj( $page, 'revision' );
				if ( !$latestRev ) {
					// This method can get called in the middle of page creation
					// (WikiPage::doUserEditContent) where a page might have an
					// id but no revisions, while checking the "autopatrol" permission.
					$rows = [];
				} else {
					$rows = $this->wanCache->getWithSetCallback(
						// Page protections always leave a new null revision
						$this->wanCache->makeKey( 'page-restrictions', 'v1', $id, $latestRev ),
						$this->wanCache::TTL_DAY,
						function ( $curValue, &$ttl, array &$setOpts ) use ( $loadRestrictionsFromDb ) {
							$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
							$setOpts += Database::getCacheSetOptions( $dbr );
							if ( $this->loadBalancer->hasOrMadeRecentPrimaryChanges() ) {
								// TODO: cleanup Title cache and caller assumption mess in general
								$ttl = WANObjectCache::TTL_UNCACHEABLE;
							}

							return $loadRestrictionsFromDb( $dbr );
						}
					);
				}
			}

			$this->loadRestrictionsFromRows( $page, $rows );
		} else {
			$titleProtection = $this->getCreateProtectionInternal( $page );

			if ( $titleProtection ) {
				$now = wfTimestampNow();
				$expiry = $titleProtection['expiry'];

				if ( !$expiry || $expiry > $now ) {
					// Apply the restrictions
					$cacheEntry['expiry']['create'] = $expiry ?: null;
					$cacheEntry['restrictions']['create'] =
						explode( ',', trim( $titleProtection['permission'] ) );
				} else {
					// Get rid of the old restrictions
					$cacheEntry['create_protection'] = null;
				}
			} else {
				$cacheEntry['expiry']['create'] = 'infinity';
			}
		}
	}

	/**
	 * Compiles list of active page restrictions for this existing page.
	 * Public for usage by LiquidThreads.
	 *
	 * @param PageIdentity $page Must be local
	 * @param stdClass[] $rows Array of db result objects
	 */
	public function loadRestrictionsFromRows(
		PageIdentity $page, array $rows
	): void {
		$page->assertWiki( PageIdentity::LOCAL );

		$cacheEntry = &$this->cache[CacheKeyHelper::getKeyForPage( $page )];

		$restrictionTypes = $this->listApplicableRestrictionTypes( $page );

		foreach ( $restrictionTypes as $type ) {
			$cacheEntry['restrictions'][$type] = [];
			$cacheEntry['expiry'][$type] = 'infinity';
		}

		$cacheEntry['cascade'] = false;

		if ( !$rows ) {
			return;
		}

		// New restriction format -- load second to make them override old-style restrictions.
		$now = wfTimestampNow();

		// Cycle through all the restrictions.
		foreach ( $rows as $row ) {
			// Don't take care of restrictions types that aren't allowed
			if ( !in_array( $row->pr_type, $restrictionTypes ) ) {
				continue;
			}

			$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
			$expiry = $dbr->decodeExpiry( $row->pr_expiry );

			// Only apply the restrictions if they haven't expired!
			// XXX Why would !$expiry ever be true? It should always be either 'infinity' or a
			// string consisting of 14 digits. Likewise for the ?: below.
			if ( !$expiry || $expiry > $now ) {
				$cacheEntry['expiry'][$row->pr_type] = $expiry ?: null;
				$cacheEntry['restrictions'][$row->pr_type]
					= explode( ',', trim( $row->pr_level ) );
				if ( $row->pr_cascade ) {
					$cacheEntry['cascade'] = true;
				}
			}
		}
	}

	/**
	 * Fetch title protection settings
	 *
	 * To work correctly, $this->loadRestrictions() needs to have access to the actual protections
	 * in the database without munging 'sysop' => 'editprotected' and 'autoconfirmed' =>
	 * 'editsemiprotected'.
	 *
	 * @param PageIdentity $page Must be local
	 * @return ?array Same format as getCreateProtection().
	 */
	private function getCreateProtectionInternal( PageIdentity $page ): ?array {
		// Can't protect pages in special namespaces
		if ( !$page->canExist() ) {
			return null;
		}

		// Can't apply this type of protection to pages that exist.
		if ( $page->exists() ) {
			return null;
		}

		$cacheEntry = &$this->cache[CacheKeyHelper::getKeyForPage( $page )];

		if ( !$cacheEntry || !array_key_exists( 'create_protection', $cacheEntry ) ) {
			$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
			$commentQuery = $this->commentStore->getJoin( 'pt_reason' );
			$row = $dbr->selectRow(
				[ 'protected_titles' ] + $commentQuery['tables'],
				[ 'pt_user', 'pt_expiry', 'pt_create_perm' ] + $commentQuery['fields'],
				[ 'pt_namespace' => $page->getNamespace(), 'pt_title' => $page->getDBkey() ],
				__METHOD__,
				[],
				$commentQuery['joins']
			);

			if ( $row ) {
				$cacheEntry['create_protection'] = [
					'user' => $row->pt_user,
					'expiry' => $dbr->decodeExpiry( $row->pt_expiry ),
					'permission' => $row->pt_create_perm,
					'reason' => $this->commentStore->getComment( 'pt_reason', $row )->text,
				];
			} else {
				$cacheEntry['create_protection'] = null;
			}

		}

		return $cacheEntry['create_protection'];
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param PageIdentity $page Must be local
	 * @return array[] Two elements: First is an array of PageIdentity objects of the pages from
	 *   which cascading restrictions have come, which may be empty. Second is an array like that
	 *   returned by getAllRestrictions(). NOTE: The first element of the return is always an
	 *   array, unlike Title::getCascadeProtectionSources where the first element is false if there
	 *   are no sources.
	 */
	public function getCascadeProtectionSources( PageIdentity $page ): array {
		$page->assertWiki( PageIdentity::LOCAL );

		return $this->getCascadeProtectionSourcesInternal( $page, false );
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param PageIdentity $page Must be local
	 * @param bool $shortCircuit If true, just return true or false instead of the actual lists.
	 * @return array|bool If $shortCircuit is true, return true if there is some cascading
	 *   protection and false otherwise. Otherwise, same as getCascadeProtectionSources().
	 */
	private function getCascadeProtectionSourcesInternal(
		PageIdentity $page, bool $shortCircuit = false
	) {
		if ( !$page->canExist() ) {
			return $shortCircuit ? false : [ [], [] ];
		}

		$cacheEntry = &$this->cache[CacheKeyHelper::getKeyForPage( $page )];

		if ( !$shortCircuit && isset( $cacheEntry['cascade_sources'] ) ) {
			return $cacheEntry['cascade_sources'];
		} elseif ( $shortCircuit && isset( $cacheEntry['has_cascading'] ) ) {
			return $cacheEntry['has_cascading'];
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$queryBuilder = $dbr->newSelectQueryBuilder();
		$queryBuilder->select( [ 'pr_expiry' ] )
			->from( 'page_restrictions' )
			->where( [ 'pr_cascade' => 1 ] );

		if ( $page->getNamespace() === NS_FILE ) {
			// Files transclusion may receive cascading protection in the future
			// see https://phabricator.wikimedia.org/T241453
			$queryBuilder->join( 'imagelinks', null, 'il_from=pr_page' );
			$queryBuilder->andWhere( [ 'il_to' => $page->getDBkey() ] );
		} else {
			$queryBuilder->join( 'templatelinks', null, 'tl_from=pr_page' );
			$queryBuilder->andWhere(
				$this->linksMigration->getLinksConditions(
					'templatelinks',
					TitleValue::newFromPage( $page )
				)
			);
		}

		if ( !$shortCircuit ) {
			$queryBuilder->fields( [ 'pr_page', 'page_namespace', 'page_title', 'pr_type', 'pr_level' ] );
			$queryBuilder->join( 'page', null, 'page_id=pr_page' );
		}

		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		$sources = [];
		$pageRestrictions = [];
		$now = wfTimestampNow();

		foreach ( $res as $row ) {
			$expiry = $dbr->decodeExpiry( $row->pr_expiry );
			if ( $expiry > $now ) {
				if ( $shortCircuit ) {
					$cacheEntry['has_cascading'] = true;
					return true;
				}

				$sources[$row->pr_page] = new PageIdentityValue( $row->pr_page,
						$row->page_namespace, $row->page_title, PageIdentity::LOCAL );
				// Add groups needed for each restriction type if its not already there
				// Make sure this restriction type still exists

				if ( !isset( $pageRestrictions[$row->pr_type] ) ) {
					$pageRestrictions[$row->pr_type] = [];
				}

				if ( !in_array( $row->pr_level, $pageRestrictions[$row->pr_type] ) ) {
					$pageRestrictions[$row->pr_type][] = $row->pr_level;
				}
			}
		}

		$cacheEntry['has_cascading'] = (bool)$sources;

		if ( $shortCircuit ) {
			return false;
		}

		$cacheEntry['cascade_sources'] = [ $sources, $pageRestrictions ];
		return [ $sources, $pageRestrictions ];
	}

	/**
	 * @param PageIdentity $page Must be local
	 * @return bool Whether or not the page's restrictions have already been loaded from the
	 *   database
	 */
	public function areRestrictionsLoaded( PageIdentity $page ): bool {
		$page->assertWiki( PageIdentity::LOCAL );

		return isset( $this->cache[CacheKeyHelper::getKeyForPage( $page )]['restrictions'] );
	}

	/**
	 * Determines whether cascading protection sources have already been loaded from the database.
	 *
	 * @param PageIdentity $page Must be local
	 * @return bool
	 */
	public function areCascadeProtectionSourcesLoaded( PageIdentity $page ): bool {
		$page->assertWiki( PageIdentity::LOCAL );

		return isset( $this->cache[CacheKeyHelper::getKeyForPage( $page )]['cascade_sources'] );
	}

	/**
	 * Checks if restrictions are cascading for the current page
	 *
	 * @param PageIdentity $page Must be local
	 * @return bool
	 */
	public function areRestrictionsCascading( PageIdentity $page ): bool {
		$page->assertWiki( PageIdentity::LOCAL );

		if ( !$this->areRestrictionsLoaded( $page ) ) {
			$this->loadRestrictions( $page );
		}
		return $this->cache[CacheKeyHelper::getKeyForPage( $page )]['cascade'] ?? false;
	}

	/**
	 * Flush the protection cache in this object and force reload from the database. This is used
	 * when updating protection from WikiPage::doUpdateRestrictions().
	 *
	 * @param PageIdentity $page Must be local
	 * @internal
	 */
	public function flushRestrictions( PageIdentity $page ): void {
		$page->assertWiki( PageIdentity::LOCAL );

		unset( $this->cache[CacheKeyHelper::getKeyForPage( $page )] );
	}

}
