<?php
/**
 * Block restriction interface.
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

namespace MediaWiki\Block;

use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\DAO\WikiAwareEntity;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IResultWrapper;

class BlockRestrictionStore {

	/**
	 * Map of all of the restriction types.
	 */
	private const TYPES_MAP = [
		PageRestriction::TYPE_ID => PageRestriction::class,
		NamespaceRestriction::TYPE_ID => NamespaceRestriction::class,
		ActionRestriction::TYPE_ID => ActionRestriction::class,
	];

	/**
	 * @var IConnectionProvider
	 */
	private $dbProvider;

	/**
	 * @var string|false
	 */
	private $wikiId;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param string|false $wikiId
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		$wikiId = WikiAwareEntity::LOCAL
	) {
		$this->dbProvider = $dbProvider;
		$this->wikiId = $wikiId;
	}

	/**
	 * Retrieve the restrictions from the database by block ID.
	 *
	 * @since 1.33
	 * @param int|int[] $blockId
	 * @return Restriction[]
	 */
	public function loadByBlockId( $blockId ) {
		if ( $blockId === null || $blockId === [] ) {
			return [];
		}

		$result = $this->dbProvider->getReplicaDatabase( $this->wikiId )
			->newSelectQueryBuilder()
			->select( [ 'ir_ipb_id', 'ir_type', 'ir_value', 'page_namespace', 'page_title' ] )
			->from( 'ipblocks_restrictions' )
			->leftJoin( 'page', null, [ 'ir_type' => PageRestriction::TYPE_ID, 'ir_value=page_id' ] )
			->where( [ 'ir_ipb_id' => $blockId ] )
			->caller( __METHOD__ )->fetchResultSet();

		return $this->resultToRestrictions( $result );
	}

	/**
	 * Insert the restrictions into the database.
	 *
	 * @since 1.33
	 * @param Restriction[] $restrictions
	 * @return bool
	 */
	public function insert( array $restrictions ) {
		if ( !$restrictions ) {
			return false;
		}

		$rows = [];
		foreach ( $restrictions as $restriction ) {
			if ( !$restriction instanceof Restriction ) {
				continue;
			}
			$rows[] = $restriction->toRow();
		}

		if ( !$rows ) {
			return false;
		}

		$dbw = $this->dbProvider->getPrimaryDatabase( $this->wikiId );

		$dbw->newInsertQueryBuilder()
			->insertInto( 'ipblocks_restrictions' )
			->ignore()
			->rows( $rows )
			->caller( __METHOD__ )->execute();

		return true;
	}

	/**
	 * Update the list of restrictions. This method does not allow removing all
	 * of the restrictions. To do that, use ::deleteByBlockId().
	 *
	 * @since 1.33
	 * @param Restriction[] $restrictions
	 * @return bool Whether all operations were successful
	 */
	public function update( array $restrictions ) {
		$dbw = $this->dbProvider->getPrimaryDatabase( $this->wikiId );

		$dbw->startAtomic( __METHOD__ );

		// Organize the restrictions by block ID.
		$restrictionList = $this->restrictionsByBlockId( $restrictions );

		// Load the existing restrictions and organize by block ID. Any block IDs
		// that were passed into this function will be used to load all of the
		// existing restrictions. This list might be the same, or may be completely
		// different.
		$existingList = [];
		$blockIds = array_keys( $restrictionList );
		if ( $blockIds ) {
			$result = $dbw->newSelectQueryBuilder()
				->select( [ 'ir_ipb_id', 'ir_type', 'ir_value' ] )
				->forUpdate()
				->from( 'ipblocks_restrictions' )
				->where( [ 'ir_ipb_id' => $blockIds ] )
				->caller( __METHOD__ )->fetchResultSet();

			$existingList = $this->restrictionsByBlockId(
				$this->resultToRestrictions( $result )
			);
		}

		$result = true;
		// Perform the actions on a per block-ID basis.
		foreach ( $restrictionList as $blockId => $blockRestrictions ) {
			// Insert all of the restrictions first, ignoring ones that already exist.
			$success = $this->insert( $blockRestrictions );

			$result = $success && $result;

			$restrictionsToRemove = $this->restrictionsToRemove(
				$existingList[$blockId] ?? [],
				$restrictions
			);

			if ( !$restrictionsToRemove ) {
				continue;
			}

			$success = $this->delete( $restrictionsToRemove );

			$result = $success && $result;
		}

		$dbw->endAtomic( __METHOD__ );

		return $result;
	}

	/**
	 * Updates the list of restrictions by parent ID.
	 *
	 * @since 1.33
	 * @param int $parentBlockId
	 * @param Restriction[] $restrictions
	 * @return bool Whether all updates were successful
	 */
	public function updateByParentBlockId( $parentBlockId, array $restrictions ) {
		$parentBlockId = (int)$parentBlockId;

		$db = $this->dbProvider->getPrimaryDatabase( $this->wikiId );

		$blockIds = $db->newSelectQueryBuilder()
			->select( 'ipb_id' )
			->forUpdate()
			->from( 'ipblocks' )
			->where( [ 'ipb_parent_block_id' => $parentBlockId ] )
			->caller( __METHOD__ )->fetchFieldValues();
		if ( !$blockIds ) {
			return true;
		}

		// If removing all of the restrictions, then just delete them all.
		if ( !$restrictions ) {
			$blockIds = array_map( 'intval', $blockIds );
			return $this->deleteByBlockId( $blockIds );
		}

		$db->startAtomic( __METHOD__ );

		$result = true;
		foreach ( $blockIds as $id ) {
			$success = $this->update( $this->setBlockId( $id, $restrictions ) );
			$result = $success && $result;
		}

		$db->endAtomic( __METHOD__ );

		return $result;
	}

	/**
	 * Delete the restrictions.
	 *
	 * @since 1.33
	 * @param Restriction[] $restrictions
	 * @return bool
	 */
	public function delete( array $restrictions ) {
		$dbw = $this->dbProvider->getPrimaryDatabase( $this->wikiId );
		foreach ( $restrictions as $restriction ) {
			if ( !$restriction instanceof Restriction ) {
				continue;
			}

			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'ipblocks_restrictions' )
				// The restriction row is made up of a compound primary key. Therefore,
				// the row and the delete conditions are the same.
				->where( $restriction->toRow() )
				->caller( __METHOD__ )->execute();
		}

		return true;
	}

	/**
	 * Delete the restrictions by block ID.
	 *
	 * @since 1.33
	 * @param int|int[] $blockId
	 * @return bool
	 */
	public function deleteByBlockId( $blockId ) {
		$this->dbProvider->getPrimaryDatabase( $this->wikiId )
			->newDeleteQueryBuilder()
			->deleteFrom( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => $blockId ] )
			->caller( __METHOD__ )->execute();
		return true;
	}

	/**
	 * Check if two arrays of Restrictions are effectively equal. This is a loose
	 * equality check as the restrictions do not have to contain the same block
	 * IDs.
	 *
	 * @since 1.33
	 * @param Restriction[] $a
	 * @param Restriction[] $b
	 * @return bool
	 */
	public function equals( array $a, array $b ) {
		$filter = static function ( $restriction ) {
			return $restriction instanceof Restriction;
		};

		// Ensure that every item in the array is a Restriction. This prevents a
		// fatal error from calling Restriction::getHash if something in the array
		// is not a restriction.
		$a = array_filter( $a, $filter );
		$b = array_filter( $b, $filter );

		$aCount = count( $a );
		$bCount = count( $b );

		// If the count is different, then they are obviously a different set.
		if ( $aCount !== $bCount ) {
			return false;
		}

		// If both sets contain no items, then they are the same set.
		if ( $aCount === 0 && $bCount === 0 ) {
			return true;
		}

		$hasher = static function ( $r ) {
			return $r->getHash();
		};

		$aHashes = array_map( $hasher, $a );
		$bHashes = array_map( $hasher, $b );

		sort( $aHashes );
		sort( $bHashes );

		return $aHashes === $bHashes;
	}

	/**
	 * Set the blockId on a set of restrictions and return a new set.
	 *
	 * @since 1.33
	 * @param int $blockId
	 * @param Restriction[] $restrictions
	 * @return Restriction[]
	 */
	public function setBlockId( $blockId, array $restrictions ) {
		$blockRestrictions = [];

		foreach ( $restrictions as $restriction ) {
			if ( !$restriction instanceof Restriction ) {
				continue;
			}

			// Clone the restriction so any references to the current restriction are
			// not suddenly changed to a different blockId.
			$restriction = clone $restriction;
			$restriction->setBlockId( $blockId );

			$blockRestrictions[] = $restriction;
		}

		return $blockRestrictions;
	}

	/**
	 * Get the restrictions that should be removed, which are existing
	 * restrictions that are not in the new list of restrictions.
	 *
	 * @param Restriction[] $existing
	 * @param Restriction[] $new
	 * @return array
	 */
	private function restrictionsToRemove( array $existing, array $new ) {
		return array_filter( $existing, static function ( $e ) use ( $new ) {
			foreach ( $new as $restriction ) {
				if ( !$restriction instanceof Restriction ) {
					continue;
				}

				if ( $restriction->equals( $e ) ) {
					return false;
				}
			}

			return true;
		} );
	}

	/**
	 * Converts an array of restrictions to an associative array of restrictions
	 * where the keys are the block IDs.
	 *
	 * @param Restriction[] $restrictions
	 * @return array
	 */
	private function restrictionsByBlockId( array $restrictions ) {
		$blockRestrictions = [];

		foreach ( $restrictions as $restriction ) {
			// Ensure that all of the items in the array are restrictions.
			if ( !$restriction instanceof Restriction ) {
				continue;
			}

			$blockRestrictions[$restriction->getBlockId()][] = $restriction;
		}

		return $blockRestrictions;
	}

	/**
	 * Convert a result wrapper to an array of restrictions.
	 *
	 * @param IResultWrapper $result
	 * @return Restriction[]
	 */
	private function resultToRestrictions( IResultWrapper $result ) {
		$restrictions = [];
		foreach ( $result as $row ) {
			$restriction = $this->rowToRestriction( $row );

			if ( !$restriction ) {
				continue;
			}

			$restrictions[] = $restriction;
		}

		return $restrictions;
	}

	/**
	 * Convert a result row from the database into a restriction object.
	 *
	 * @param stdClass $row
	 * @return Restriction|null
	 */
	private function rowToRestriction( stdClass $row ) {
		if ( array_key_exists( (int)$row->ir_type, self::TYPES_MAP ) ) {
			$class = self::TYPES_MAP[ (int)$row->ir_type ];
			return call_user_func( [ $class, 'newFromRow' ], $row );
		}

		return null;
	}
}
