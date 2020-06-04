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

namespace MediaWiki\User;

use ConfiguredReadOnlyMode;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use Wikimedia\Rdbms\ILBFactory;

/**
 * Factory service for UserGroupManager instances. This allows UserGroupManager to be created for
 * cross-wiki access.
 *
 * @since 1.35
 */
class UserGroupManagerFactory {
	/** @var ServiceOptions */
	private $options;

	/** @var ConfiguredReadOnlyMode */
	private $configuredReadOnlyMode;

	/** @var ILBFactory */
	private $dbLoadBalancerFactory;

	/** @var callable[] */
	private $clearCacheCallbacks;

	/** @var HookContainer */
	private $hookContainer;

	/**
	 * @param ServiceOptions $options
	 * @param ConfiguredReadOnlyMode $configuredReadOnlyMode
	 * @param ILBFactory $dbLoadBalancerFactory
	 * @param HookContainer $hookContainer
	 * @param callable[] $clearCacheCallbacks
	 */
	public function __construct(
		ServiceOptions $options,
		ConfiguredReadOnlyMode $configuredReadOnlyMode,
		ILBFactory $dbLoadBalancerFactory,
		HookContainer $hookContainer,
		array $clearCacheCallbacks = []
	) {
		$this->options = $options;
		$this->configuredReadOnlyMode = $configuredReadOnlyMode;
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->hookContainer = $hookContainer;
		$this->clearCacheCallbacks = $clearCacheCallbacks;
	}

	/**
	 * @param string|bool $dbDomain
	 * @return UserGroupManager
	 */
	public function getUserGroupManager( $dbDomain = false ) : UserGroupManager {
		// TODO: Once UserRightsProxy is removed, cache the instance per domain.
		return new UserGroupManager(
			$this->options,
			$this->configuredReadOnlyMode,
			$this->dbLoadBalancerFactory,
			$this->hookContainer,
			$this->clearCacheCallbacks,
			$dbDomain
		);
	}
}
