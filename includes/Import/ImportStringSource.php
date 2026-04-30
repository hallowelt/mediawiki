<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\Import;

/**
 * Used for importing XML dumps where the content of the dump is in a string.
 * This class is inefficient, and should only be used for small dumps.
 * For larger dumps, ImportStreamSource should be used instead.
 *
 * @ingroup SpecialPage
 */
class ImportStringSource implements ImportSource {
	private bool $mRead = false;

	public function __construct(
		private readonly string $string,
	) {
	}

	/**
	 * @return bool
	 */
	public function atEnd() {
		return $this->mRead;
	}

	/**
	 * @return bool|string
	 */
	public function readChunk() {
		if ( $this->atEnd() ) {
			return false;
		}
		$this->mRead = true;
		return $this->string;
	}

	/**
	 * @return bool
	 */
	public function isSeekable() {
		return true;
	}

	/**
	 * @param int $offset
	 * @return int
	 */
	public function seek( int $offset ) {
		$this->mRead = false;
		return 0;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( ImportStringSource::class, 'ImportStringSource' );
