<?php
/**
 * Copyright © 2013 Brian Wolff
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List all files where the current version is a duplicate of the current
 * version of another file.
 *
 * @ingroup SpecialPage
 * @author Brian Wolff
 */
class SpecialListDuplicatedFiles extends QueryPage {
	private int $migrationStage;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'ListDuplicatedFiles' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/**
	 * Get all the duplicates by grouping on sha1s.
	 *
	 * A cheaper (but less useful) version of this
	 * query would be to not care how many duplicates a
	 * particular file has, and do a self-join on image or file table.
	 * However this version should be no more expensive then
	 * Special:MostLinked, which seems to get handled fine
	 * with however we are doing cached special pages.
	 * @return array
	 */
	public function getQueryInfo() {
		if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$tables = [ 'image' ];
			$nameField = 'img_name';
			$hashField = 'img_sha1';
			$conds = [];
			$joins = [];
		} else {
			$tables = [ 'file', 'filerevision' ];
			$nameField = 'file_name';
			$hashField = 'fr_sha1';
			$conds = [ 'file_deleted' => 0 ];
			$joins = [ 'filerevision' => [ 'JOIN', 'file_latest = fr_id' ] ];
		}
		return [
			'tables' => $tables,
			'fields' => [
				'namespace' => NS_FILE,
				'title' => "MIN($nameField)",
				'value' => 'count(*)'
			],
			'conds' => $conds,
			'join_conds' => $joins,
			'options' => [
				'GROUP BY' => $hashField,
				'HAVING' => 'count(*) > 1',
			],
		];
	}

	/**
	 * Pre-fill the link cache
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		// Future version might include a list of the first 5 duplicates
		// perhaps separated by an "↔".
		$image1 = Title::makeTitle( $result->namespace, $result->title );
		$dupeSearch = SpecialPage::getTitleFor( 'FileDuplicateSearch', $image1->getDBkey() );

		$msg = $this->msg( 'listduplicatedfiles-entry' )
			->params( $image1->getText() )
			->numParams( $result->value - 1 )
			->params( $dupeSearch->getPrefixedDBkey() );

		return $msg->parse();
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->addHelpLink( 'Help:Managing_files' );
		parent::execute( $par );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'media';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialListDuplicatedFiles::class, 'SpecialListDuplicatedFiles' );
