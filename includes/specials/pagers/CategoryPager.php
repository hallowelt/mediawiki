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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Pager\AlphabeticPager;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup Pager
 */
class CategoryPager extends AlphabeticPager {

	private LinkBatchFactory $linkBatchFactory;

	/**
	 * @param IContextSource $context
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinkRenderer $linkRenderer
	 * @param IConnectionProvider $dbProvider
	 * @param string $from
	 */
	public function __construct(
		IContextSource $context,
		LinkBatchFactory $linkBatchFactory,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		$from
	) {
		// Set database before parent constructor to avoid setting it there with wfGetDB
		$this->mDb = $dbProvider->getReplicaDatabase();
		parent::__construct( $context, $linkRenderer );
		$this->linkBatchFactory = $linkBatchFactory;
		$from = str_replace( ' ', '_', $from );
		if ( $from !== '' ) {
			$from = Title::capitalize( $from, NS_CATEGORY );
			$this->setOffset( $from );
			$this->setIncludeOffset( true );
		}
	}

	public function getQueryInfo() {
		return [
			'tables' => [ 'category' ],
			'fields' => [ 'cat_title', 'cat_pages' ],
			'options' => [ 'USE INDEX' => 'cat_title' ],
		];
	}

	public function getIndexField() {
		return 'cat_title';
	}

	public function getDefaultQuery() {
		parent::getDefaultQuery();
		unset( $this->mDefaultQuery['from'] );

		return $this->mDefaultQuery;
	}

	/* Override getBody to apply LinksBatch on resultset before actually outputting anything. */
	public function getBody() {
		$batch = $this->linkBatchFactory->newLinkBatch();

		$this->mResult->rewind();

		foreach ( $this->mResult as $row ) {
			$batch->add( NS_CATEGORY, $row->cat_title );
		}
		$batch->execute();
		$this->mResult->rewind();

		return parent::getBody();
	}

	public function formatRow( $result ) {
		$title = new TitleValue( NS_CATEGORY, $result->cat_title );
		$text = $title->getText();
		$link = $this->getLinkRenderer()->makeLink( $title, $text );

		$count = $this->msg( 'nmembers' )->numParams( $result->cat_pages )->escaped();
		return Html::rawElement( 'li', [], $this->getLanguage()->specialList( $link, $count ) ) . "\n";
	}

	public function getStartForm( $from ) {
		$formDescriptor = [
			'from' => [
				'type' => 'title',
				'namespace' => NS_CATEGORY,
				'relative' => true,
				'label-message' => 'categoriesfrom',
				'name' => 'from',
				'id' => 'from',
				'size' => 20,
				'default' => $from,
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setSubmitTextMsg( 'categories-submit' )
			->setWrapperLegendMsg( 'categories' )
			->setMethod( 'get' );
		return $htmlForm->prepareForm()->getHTML( false );
	}

}
