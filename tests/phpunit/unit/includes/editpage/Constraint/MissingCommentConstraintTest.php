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

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\MissingCommentConstraint;

/**
 * Tests the MissingCommentConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\MissingCommentConstraint
 */
class MissingCommentConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new MissingCommentConstraint( 'new', 'Comment' );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$constraint = new MissingCommentConstraint( 'new', '' );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_TEXTBOX_EMPTY );
	}

	public function testNonNew() {
		$constraint = new MissingCommentConstraint( 'notnew', '' );
		$this->assertConstraintPassed( $constraint );
	}

}
