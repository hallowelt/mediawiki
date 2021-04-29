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

namespace MediaWiki\Block;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;

/**
 * Defines the actions that can be blocked by a partial block. They are
 * always blocked by a sitewide block.
 *
 * NB: The terms "right" and "action" are often used to describe the same
 * string, depending on the context. E.g. a querystring might contain
 * 'action=edit', but the PermissionManager will refer to the 'edit' right.
 *
 * Here, we use "action", since a user may be in a group that has a
 * certain right, while also being blocked from performing that action.
 *
 * @since 1.37
 */
class BlockActionInfo {
	/** @var HookRunner */
	private $hookRunner;

	/** @var int */
	private const ACTION_UPLOAD = 1;

	/** @var int */
	private const ACTION_MOVE = 2;

	/**
	 * Core block actions.
	 *
	 * Each key is an action string passed to PermissionManager::checkUserBlock
	 * Each value is a class constant for that action
	 *
	 * Each key has a corresponding message with key "ipb-actions-$key"
	 *
	 * Core messages:
	 * ipb-actions-upload
	 *
	 * @var int[]
	 */
	private const CORE_BLOCK_ACTIONS = [
		'upload' => self::ACTION_UPLOAD,
		'move' => self::ACTION_MOVE,
	];

	/**
	 * @param HookContainer $hookContainer
	 */
	public function __construct( HookContainer $hookContainer ) {
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Cache the array of actions
	 * @var int[]|null
	 */
	private $allBlockActions = null;

	/**
	 * @return int[]
	 */
	public function getAllBlockActions() : array {
		// Don't run the hook multiple times in the same request
		if ( !$this->allBlockActions ) {
			$this->allBlockActions = self::CORE_BLOCK_ACTIONS;
			$this->hookRunner->onGetAllBlockActions( $this->allBlockActions );
		}
		return $this->allBlockActions;
	}

}
