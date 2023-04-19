<?php
/**
 * Implements Special:Wantedfiles
 *
 * Copyright © 2008 Soxred93
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
 * @ingroup SpecialPage
 * @author Soxred93 <soxred93@gmail.com>
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use RepoGroup;
use WantedQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Querypage that lists the most wanted files
 *
 * @ingroup SpecialPage
 */
class SpecialWantedFiles extends WantedQueryPage {

	/** @var RepoGroup */
	private $repoGroup;

	/**
	 * @param RepoGroup $repoGroup
	 * @param IConnectionProvider $dbProvider
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		RepoGroup $repoGroup,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Wantedfiles' );
		$this->repoGroup = $repoGroup;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
	}

	protected function getPageHeader() {
		# Specifically setting to use "Wanted Files" (NS_MAIN) as title, so as to get what
		# category would be used on main namespace pages, for those tricky wikipedia
		# admins who like to do {{#ifeq:{{NAMESPACE}}|foo|bar|....}}.
		$catMessage = $this->msg( 'broken-file-category' )
			->page( PageReferenceValue::localReference( NS_MAIN, "Wanted Files" ) )
			->inContentLanguage();

		if ( !$catMessage->isDisabled() ) {
			$category = Title::makeTitleSafe( NS_CATEGORY, $catMessage->text() );
		} else {
			$category = false;
		}

		$noForeign = '';
		if ( !$this->likelyToHaveFalsePositives() ) {
			// Additional messages for grep:
			// wantedfiletext-cat-noforeign, wantedfiletext-nocat-noforeign
			$noForeign = '-noforeign';
		}

		if ( $category ) {
			return $this
				->msg( 'wantedfiletext-cat' . $noForeign )
				->params( $category->getFullText() )
				->parseAsBlock();
		} else {
			return $this
				->msg( 'wantedfiletext-nocat' . $noForeign )
				->parseAsBlock();
		}
	}

	/**
	 * Whether foreign repos are likely to cause false positives
	 *
	 * In its own function to allow subclasses to override.
	 * @see SpecialWantedFilesGUOverride in GlobalUsage extension.
	 * @since 1.24
	 * @return bool
	 */
	protected function likelyToHaveFalsePositives() {
		return $this->repoGroup->hasForeignRepos();
	}

	/**
	 * KLUGE: The results may contain false positives for files
	 * that exist e.g. in a shared repo.  Setting this at least
	 * keeps them from showing up as redlinks in the output, even
	 * if it doesn't fix the real problem (T8220).
	 *
	 * @note could also have existing links here from broken file
	 * redirects.
	 * @return bool
	 */
	protected function forceExistenceCheck() {
		return true;
	}

	/**
	 * Does the file exist?
	 *
	 * Use findFile() so we still think file namespace pages without files
	 * are missing, but valid file redirects and foreign files are ok.
	 *
	 * @param Title $title
	 * @return bool
	 */
	protected function existenceCheck( Title $title ) {
		return (bool)$this->repoGroup->findFile( $title );
	}

	public function getQueryInfo() {
		return [
			'tables' => [
				'imagelinks',
				'page',
				'redirect',
				'img1' => 'image',
				'img2' => 'image',
			],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'il_to',
				'value' => 'COUNT(*)'
			],
			'conds' => [
				'img1.img_name' => null,
				// We also need to exclude file redirects
				'img2.img_name' => null,
			],
			'options' => [ 'GROUP BY' => 'il_to' ],
			'join_conds' => [
				'img1' => [ 'LEFT JOIN',
					'il_to = img1.img_name'
				],
				'page' => [ 'LEFT JOIN', [
					'il_to = page_title',
					'page_namespace' => NS_FILE,
				] ],
				'redirect' => [ 'LEFT JOIN', [
					'page_id = rd_from',
					'rd_namespace' => NS_FILE,
					'rd_interwiki' => ''
				] ],
				'img2' => [ 'LEFT JOIN',
					'rd_title = img2.img_name'
				]
			]
		];
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialWantedFiles::class, 'WantedFilesPage' );
