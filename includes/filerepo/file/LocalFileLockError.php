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

namespace MediaWiki\FileRepo\File;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Status\Status;

/**
 * @newable
 * @stable to extend
 * @ingroup FileAbstraction
 */
class LocalFileLockError extends ErrorPageError {

	/**
	 * @stable to call
	 *
	 * @param Status $status
	 */
	public function __construct( Status $status ) {
		parent::__construct(
			'actionfailed',
			$status->getMessage()
		);
	}

	/** @inheritDoc */
	public function report( $action = self::SEND_OUTPUT ) {
		global $wgOut;
		$wgOut->setStatusCode( 429 );
		parent::report( $action );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LocalFileLockError::class, 'LocalFileLockError' );
