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
 */

use MediaWiki\Actions\FileDeleteAction;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArray;
use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * Special handling for representing file pages.
 *
 * @ingroup Media
 */
class WikiFilePage extends WikiPage {
	/** @var File|false */
	protected $mFile = false;
	/** @var LocalRepo|null */
	protected $mRepo = null;
	/** @var bool */
	protected $mFileLoaded = false;
	/** @var array|null */
	protected $mDupes = null;

	/**
	 * @param Title $title
	 */
	public function __construct( $title ) {
		parent::__construct( $title );
		$this->mDupes = null;
		$this->mRepo = null;
	}

	/**
	 * @param File $file
	 */
	public function setFile( File $file ) {
		$this->mFile = $file;
		$this->mFileLoaded = true;
	}

	/**
	 * @return bool
	 */
	protected function loadFile() {
		$services = MediaWikiServices::getInstance();
		if ( $this->mFileLoaded ) {
			return true;
		}

		$this->mFile = $services->getRepoGroup()->findFile( $this->mTitle );
		if ( !$this->mFile ) {
			$this->mFile = $services->getRepoGroup()->getLocalRepo()
				->newFile( $this->mTitle );
		}

		if ( !$this->mFile instanceof File ) {
			throw new RuntimeException( 'Expected to find file. See T250767' );
		}

		$this->mRepo = $this->mFile->getRepo();
		$this->mFileLoaded = true;
		return true;
	}

	/**
	 * @return mixed|null|Title
	 */
	public function getRedirectTarget() {
		$this->loadFile();
		if ( $this->mFile->isLocal() ) {
			return parent::getRedirectTarget();
		}
		// Foreign image page
		$from = $this->mFile->getRedirected();
		$to = $this->mFile->getName();
		if ( $from == $to ) {
			return null;
		}
		$this->mRedirectTarget = Title::makeTitle( NS_FILE, $to );
		return $this->mRedirectTarget;
	}

	/**
	 * @return bool|mixed|Title
	 */
	public function followRedirect() {
		$this->loadFile();
		if ( $this->mFile->isLocal() ) {
			return parent::followRedirect();
		}
		$from = $this->mFile->getRedirected();
		$to = $this->mFile->getName();
		if ( $from == $to ) {
			return false;
		}
		return Title::makeTitle( NS_FILE, $to );
	}

	/**
	 * @return bool
	 */
	public function isRedirect() {
		$this->loadFile();
		if ( $this->mFile->isLocal() ) {
			return parent::isRedirect();
		}

		return (bool)$this->mFile->getRedirected();
	}

	/**
	 * @return bool
	 */
	public function isLocal() {
		$this->loadFile();
		return $this->mFile->isLocal();
	}

	/**
	 * @return File
	 */
	public function getFile(): File {
		$this->loadFile();
		return $this->mFile;
	}

	/**
	 * @return File[]|null
	 */
	public function getDuplicates() {
		$this->loadFile();
		if ( $this->mDupes !== null ) {
			return $this->mDupes;
		}
		$hash = $this->mFile->getSha1();
		if ( !( $hash ) ) {
			$this->mDupes = [];
			return $this->mDupes;
		}
		$dupes = MediaWikiServices::getInstance()->getRepoGroup()->findBySha1( $hash );
		// Remove duplicates with self and non matching file sizes
		$self = $this->mFile->getRepoName() . ':' . $this->mFile->getName();
		$size = $this->mFile->getSize();

		/**
		 * @var File $file
		 */
		foreach ( $dupes as $index => $file ) {
			$key = $file->getRepoName() . ':' . $file->getName();
			if ( $key === $self || $file->getSize() != $size ) {
				unset( $dupes[$index] );
			}
		}
		$this->mDupes = $dupes;
		return $this->mDupes;
	}

	/**
	 * Override handling of action=purge
	 * @return bool
	 */
	public function doPurge() {
		$this->loadFile();

		if ( $this->mFile->exists() ) {
			wfDebug( 'ImagePage::doPurge purging ' . $this->mFile->getName() );
			$job = HTMLCacheUpdateJob::newForBacklinks(
				$this->mTitle,
				'imagelinks',
				[ 'causeAction' => 'file-purge' ]
			);
			MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush( $job );
		} else {
			wfDebug( 'ImagePage::doPurge no image for '
				. $this->mFile->getName() . "; limiting purge to cache only" );
		}

		// even if the file supposedly doesn't exist, force any cached information
		// to be updated (in case the cached information is wrong)

		// Purge current version and its thumbnails
		$this->mFile->purgeCache( [ 'forThumbRefresh' => true ] );

		// Purge the old versions and their thumbnails
		foreach ( $this->mFile->getHistory() as $oldFile ) {
			$oldFile->purgeCache( [ 'forThumbRefresh' => true ] );
		}

		if ( $this->mRepo ) {
			// Purge redirect cache
			$this->mRepo->invalidateImageRedirect( $this->mTitle );
		}

		return parent::doPurge();
	}

	/**
	 * Get the categories this file is a member of on the wiki where it was uploaded.
	 * For local files, this is the same as getCategories().
	 * For foreign API files (InstantCommons), this is not supported currently.
	 * Results will include hidden categories.
	 *
	 * @return TitleArray|Title[]
	 * @since 1.23
	 */
	public function getForeignCategories() {
		$this->loadFile();
		$title = $this->mTitle;
		$file = $this->mFile;

		if ( !$file instanceof LocalFile ) {
			wfDebug( __METHOD__ . " is not supported for this file" );
			return TitleArray::newFromResult( new FakeResultWrapper( [] ) );
		}

		/** @var LocalRepo $repo */
		$repo = $file->getRepo();
		$dbr = $repo->getReplicaDB();

		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'page_title' => 'cl_to', 'page_namespace' => (string)NS_CATEGORY ] )
			->from( 'page' )
			->join( 'categorylinks', null, 'page_id = cl_from' )
			->where( [ 'page_namespace' => $title->getNamespace(), 'page_title' => $title->getDBkey(), ] )
			->caller( __METHOD__ )->fetchResultSet();

		return TitleArray::newFromResult( $res );
	}

	/**
	 * @since 1.28
	 * @return string
	 */
	public function getWikiDisplayName() {
		return $this->getFile()->getRepo()->getDisplayName();
	}

	/**
	 * @since 1.28
	 * @return string
	 */
	public function getSourceURL() {
		return $this->getFile()->getDescriptionUrl();
	}

	/**
	 * @inheritDoc
	 */
	public function getActionOverrides() {
		$file = $this->getFile();
		if ( $file->exists() && $file->isLocal() && !$file->getRedirected() ) {
			// Would be an actual file deletion
			return [ 'delete' => FileDeleteAction::class ] + parent::getActionOverrides();
		}
		// It should use the normal article deletion interface
		return parent::getActionOverrides();
	}
}
