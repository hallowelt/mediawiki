<?php
/**
 * Shortcut to construct a special page which is unlisted by default.
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
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

/**
 * Shortcut to construct a special page which is unlisted by default.
 *
 * @stable to extend
 *
 * @ingroup SpecialPage
 */
class UnlistedSpecialPage extends SpecialPage {

	/**
	 * @stable to call
	 *
	 * @param string $name
	 * @param string $restriction
	 * @param bool $function
	 * @param string $file
	 */
	public function __construct( $name, $restriction = '', $function = false, $file = 'default' ) {
		parent::__construct( $name, $restriction, false, $function, $file );
	}

	/** @inheritDoc */
	public function isListed() {
		return false;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( UnlistedSpecialPage::class, 'UnlistedSpecialPage' );
