<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\HTMLForm\Field\HTMLSelectNamespace;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\ProtectedTitlesPager;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A special page that list protected titles from creation
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedTitles extends SpecialPage {

	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'Protectedtitles' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Protected_pages' );

		$request = $this->getRequest();
		$level = $request->getVal( 'level' );
		$NS = $request->getIntOrNull( 'namespace' );

		$pager = new ProtectedTitlesPager(
			$this->getContext(),
			$this->getLinkRenderer(),
			$this->linkBatchFactory,
			$this->dbProvider,
			$level,
			$NS
		);

		$this->getOutput()->addHTML( $this->showOptions() );

		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML(
				$pager->getNavigationBar() .
					'<ul>' . $pager->getBody() . '</ul>' .
					$pager->getNavigationBar()
			);
		} else {
			$this->getOutput()->addWikiMsg( 'protectedtitlesempty' );
		}
	}

	/**
	 * @return string
	 */
	private function showOptions() {
		$formDescriptor = [
			'namespace' => [
				'class' => HTMLSelectNamespace::class,
				'name' => 'namespace',
				'id' => 'namespace',
				'cssclass' => 'namespaceselector',
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			],
			'levelmenu' => $this->getLevelMenu()
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setMethod( 'get' )
			->setWrapperLegendMsg( 'protectedtitles' )
			->setSubmitTextMsg( 'protectedtitles-submit' );

		return $htmlForm->prepareForm()->getHTML( false );
	}

	/**
	 * @return string|array
	 */
	private function getLevelMenu() {
		$options = [ 'restriction-level-all' => 0 ];

		// Load the log names as options
		foreach ( $this->getConfig()->get( MainConfigNames::RestrictionLevels ) as $type ) {
			if ( $type != '' && $type != '*' ) {
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$options["restriction-level-$type"] = $type;
			}
		}

		// Is there only one level (aside from "all")?
		if ( count( $options ) <= 2 ) {
			return '';
		}

		return [
			'type' => 'select',
			'options-messages' => $options,
			'label-message' => 'restriction-level',
			'name' => 'level',
			'id' => 'level',
		];
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
class_alias( SpecialProtectedTitles::class, 'SpecialProtectedtitles' );
