<?php

namespace MediaWiki\Tests\Media;

use MediaWiki\Media\DjVuImage;
use MediaWikiMediaTestCase;

/**
 * @group Media
 * @covers \MediaWiki\Media\DjVuImage
 */
class DjVuImageTest extends MediaWikiMediaTestCase {

	private const string FILE_NAME = __DIR__ . '/../../data/media/LoremIpsum.djvu';

	public function testIsValid() {
		$this->assertTrue( ( new DjVuImage( self::FILE_NAME ) )->isValid() );
	}
}
