<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

use PHPUnit\Framework\CodeCoverageException;
use PHPUnit\Util\Test;

/**
 * Trait that checks that covers tags are valid, since PHPUnit
 * won't do it unless you run it with coverage, which is super
 * slow.
 *
 * @since 1.31
 */
trait MediaWikiCoversValidator {

	/**
	 * Test that all methods in this class that begin
	 * with "test" have valid covers tags.
	 *
	 * @dataProvider providePHPUnitTestMethodNames
	 */
	public function testValidCovers( $class, $method ) {
		try {
			Test::getLinesToBeCovered( $class, $method );
		} catch ( CodeCoverageException $ex ) {
			$this->fail( "$class::$method: " . $ex->getMessage() );
		}

		$this->addToAssertionCount( 1 );
	}

	public function providePHPUnitTestMethodNames() {
		foreach ( get_class_methods( $this ) as $method ) {
			if ( strncmp( $method, 'test', 4 ) === 0 ) {
				yield [ static::class, $method ];
			}
		}
	}
}
