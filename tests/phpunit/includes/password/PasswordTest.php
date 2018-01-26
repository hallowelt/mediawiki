<?php
/**
 * Testing framework for the Password infrastructure
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
 */

/**
 * @covers InvalidPassword
 */
class PasswordTest extends MediaWikiTestCase {
	public function testInvalidUnequalInvalid() {
		$passwordFactory = new PasswordFactory();
		$invalid1 = $passwordFactory->newFromCiphertext( null );
		$invalid2 = $passwordFactory->newFromCiphertext( null );

		$this->assertFalse( $invalid1->equals( $invalid2 ) );
	}

	public function testInvalidPlaintext() {
		$passwordFactory = new PasswordFactory();
		$invalid = $passwordFactory->newFromPlaintext( null );

		$this->assertInstanceOf( InvalidPassword::class, $invalid );
	}
}
