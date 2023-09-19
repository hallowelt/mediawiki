<?php
/**
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
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\FormOptions;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Specials\SpecialNewPages;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use RecentChange;

/**
 * @internal For use by SpecialNewPages
 * @ingroup Pager
 */
class NewPagesPager extends ReverseChronologicalPager {

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @var SpecialNewPages
	 */
	protected $mForm;

	private GroupPermissionsLookup $groupPermissionsLookup;
	private HookRunner $hookRunner;
	private LinkBatchFactory $linkBatchFactory;
	private NamespaceInfo $namespaceInfo;
	private ChangeTagsStore $changeTagsStore;

	/**
	 * @param SpecialNewPages $form
	 * @param GroupPermissionsLookup $groupPermissionsLookup
	 * @param HookContainer $hookContainer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param NamespaceInfo $namespaceInfo
	 * @param FormOptions $opts
	 */
	public function __construct(
		SpecialNewPages $form,
		GroupPermissionsLookup $groupPermissionsLookup,
		HookContainer $hookContainer,
		LinkBatchFactory $linkBatchFactory,
		NamespaceInfo $namespaceInfo,
		FormOptions $opts,
		ChangeTagsStore $changeTagsStore
	) {
		parent::__construct( $form->getContext() );
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->mForm = $form;
		$this->opts = $opts;
		$this->changeTagsStore = $changeTagsStore;
	}

	public function getQueryInfo() {
		$rcQuery = RecentChange::getQueryInfo();

		$conds = [];
		$conds['rc_new'] = 1;

		$username = $this->opts->getValue( 'username' );
		$user = Title::makeTitleSafe( NS_USER, $username );

		$size = abs( intval( $this->opts->getValue( 'size' ) ) );
		if ( $size > 0 ) {
			if ( $this->opts->getValue( 'size-mode' ) === 'max' ) {
				$conds[] = 'page_len <= ' . $size;
			} else {
				$conds[] = 'page_len >= ' . $size;
			}
		}

		if ( $user ) {
			$conds['actor_name'] = $user->getText();
		} elseif ( $this->canAnonymousUsersCreatePages() && $this->opts->getValue( 'hideliu' ) ) {
			# If anons cannot make new pages, don't "exclude logged in users"!
			$conds['actor_user'] = null;
		}

		$conds = array_merge( $conds, $this->getNamespaceCond() );

		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if ( $this->opts->getValue( 'hidepatrolled' ) && $this->getUser()->useNPPatrol() ) {
			$conds['rc_patrolled'] = RecentChange::PRC_UNPATROLLED;
		}

		if ( $this->opts->getValue( 'hidebots' ) ) {
			$conds['rc_bot'] = 0;
		}

		if ( $this->opts->getValue( 'hideredirs' ) ) {
			$conds['page_is_redirect'] = 0;
		}

		// Allow changes to the New Pages query
		$tables = array_merge( $rcQuery['tables'], [ 'page' ] );
		$fields = array_merge( $rcQuery['fields'], [
			'length' => 'page_len', 'rev_id' => 'page_latest', 'page_namespace', 'page_title',
			'page_content_model',
		] );
		$join_conds = [ 'page' => [ 'JOIN', 'page_id=rc_cur_id' ] ] + $rcQuery['joins'];

		$this->hookRunner->onSpecialNewpagesConditions(
			$this, $this->opts, $conds, $tables, $fields, $join_conds );

		$info = [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => $conds,
			'options' => [],
			'join_conds' => $join_conds
		];

		// Modify query for tags
		$this->changeTagsStore->modifyDisplayQuery(
			$info['tables'],
			$info['fields'],
			$info['conds'],
			$info['join_conds'],
			$info['options'],
			$this->opts['tagfilter'],
			$this->opts['tagInvert']
		);

		return $info;
	}

	private function canAnonymousUsersCreatePages() {
		return $this->groupPermissionsLookup->groupHasPermission( '*', 'createpage' ) ||
			$this->groupPermissionsLookup->groupHasPermission( '*', 'createtalk' );
	}

	// Based on ContribsPager.php
	private function getNamespaceCond() {
		$namespace = $this->opts->getValue( 'namespace' );
		if ( $namespace === 'all' || $namespace === '' ) {
			return [];
		}

		$namespace = intval( $namespace );
		if ( $namespace < NS_MAIN ) {
			// Negative namespaces are invalid
			return [];
		}

		$invert = $this->opts->getValue( 'invert' );
		$associated = $this->opts->getValue( 'associated' );

		$eq_op = $invert ? '!=' : '=';
		$bool_op = $invert ? 'AND' : 'OR';

		$dbr = $this->getDatabase();
		$selectedNS = $dbr->addQuotes( $namespace );
		if ( !$associated ) {
			return [ "rc_namespace $eq_op $selectedNS" ];
		}

		$associatedNS = $dbr->addQuotes(
			$this->namespaceInfo->getAssociated( $namespace )
		);
		return [
			"rc_namespace $eq_op $selectedNS " .
			$bool_op .
			" rc_namespace $eq_op $associatedNS"
		];
	}

	public function getIndexField() {
		return [ [ 'rc_timestamp', 'rc_id' ] ];
	}

	public function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	protected function doBatchLookups() {
		$linkBatch = $this->linkBatchFactory->newLinkBatch();
		foreach ( $this->mResult as $row ) {
			$linkBatch->add( NS_USER, $row->rc_user_text );
			$linkBatch->add( NS_USER_TALK, $row->rc_user_text );
			$linkBatch->add( $row->page_namespace, $row->page_title );
		}
		$linkBatch->execute();
	}

	protected function getStartBody() {
		return '<ul>';
	}

	protected function getEndBody() {
		return '</ul>';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( NewPagesPager::class, 'NewPagesPager' );
