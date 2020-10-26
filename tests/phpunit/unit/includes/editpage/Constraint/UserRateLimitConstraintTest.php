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
use MediaWiki\EditPage\Constraint\UserRateLimitConstraint;

/**
 * Tests the UserRateLimitConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\UserRateLimitConstraint
 */
class UserRateLimitConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		// Cannot assert that 0 parameters are passed, since PHP fills in the default
		// values before PHPUnit checks; first call uses both defaults, third call
		// uses the default of 1 for the second parameter
		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'pingLimiter' ] )
			->getMock();
		$user->expects( $this->exactly( 3 ) )
			->method( 'pingLimiter' )
			->withConsecutive(
				[
					$this->equalTo( 'edit' ),
					$this->equalTo( 1 ),
				],
				[
					$this->equalTo( 'linkpurge' ),
					$this->equalTo( 0 ),
				],
				[
					$this->equalTo( 'editcontentmodel' ),
					$this->equalTo( 1 )
				]
			)
			->will(
				$this->onConsecutiveCalls(
					false,
					false,
					false
				)
			);

		// true = also check for rate limit of editing content model
		$constraint = new UserRateLimitConstraint( $user, true );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		// Cannot assert that 0 parameters are passed, since PHP fills in the default
		// values before PHPUnit checks; first call uses both defaults, third call
		// uses the default of 1 for the second parameter
		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'pingLimiter' ] )
			->getMock();
		$user->expects( $this->exactly( 3 ) )
			->method( 'pingLimiter' )
			->withConsecutive(
				[
					$this->equalTo( 'edit' ),
					$this->equalTo( 1 ),
				],
				[
					$this->equalTo( 'linkpurge' ),
					$this->equalTo( 0 ),
				],
				[
					$this->equalTo( 'editcontentmodel' ),
					$this->equalTo( 1 )
				]
			)
			->will(
				$this->onConsecutiveCalls(
					false,
					false,
					true // Only die on the last check
				)
			);

		// true = also check for rate limit of editing content model
		$constraint = new UserRateLimitConstraint( $user, true );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_RATE_LIMITED );
	}

}
