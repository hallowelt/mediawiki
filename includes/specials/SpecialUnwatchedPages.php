<?php
/**
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\Linker;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of pages that are not on anyone's watchlist.
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */
class SpecialUnwatchedPages extends QueryPage {

	private LinkBatchFactory $linkBatchFactory;
	private ILanguageConverter $languageConverter;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Unwatchedpages', 'unwatchedpages' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->setDatabaseProvider( $dbProvider );
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
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
	 * Pre-cache page existence to speed up link generation
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$dbr = $this->getDatabaseProvider()->getReplicaDatabase();
		return [
			'tables' => [ 'page', 'watchlist' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_namespace'
			],
			'conds' => [
				'wl_title' => null,
				'page_is_redirect' => 0,
				$dbr->expr( 'page_namespace', '!=', NS_MEDIAWIKI ),
			],
			'join_conds' => [ 'watchlist' => [
				'LEFT JOIN', [ 'wl_title = page_title',
					'wl_namespace = page_namespace' ] ] ]
		];
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		return [ 'page_namespace', 'page_title' ];
	}

	/**
	 * Add the JS
	 * @param string|null $par
	 */
	public function execute( $par ) {
		parent::execute( $par );
		$this->getOutput()->addModules( 'mediawiki.special.unwatchedPages' );
		$this->addHelpLink( 'Help:Watchlist' );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$nt = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$nt ) {
			return Html::element( 'span', [ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription( $this->getContext(), $result->namespace, $result->title ) );
		}

		$text = $this->languageConverter->convertHtml( $nt->getPrefixedText() );

		$linkRenderer = $this->getLinkRenderer();

		$plink = $linkRenderer->makeKnownLink( $nt, new HtmlArmor( $text ) );
		$wlink = $linkRenderer->makeKnownLink(
			$nt,
			$this->msg( 'watch' )->text(),
			[ 'class' => 'mw-watch-link' ],
			[ 'action' => 'watch' ]
		);

		return $this->getLanguage()->specialList( $plink, $wlink );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnwatchedPages::class, 'SpecialUnwatchedPages' );
