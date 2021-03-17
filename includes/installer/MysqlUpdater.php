<?php
/**
 * MySQL-specific updater.
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
 * @ingroup Installer
 */
use Wikimedia\Rdbms\MySQLField;

/**
 * Mysql update list and mysql-specific update functions.
 *
 * @ingroup Installer
 * @since 1.17
 * @property Wikimedia\Rdbms\DatabaseMysqlBase $db
 */
class MysqlUpdater extends DatabaseUpdater {
	protected function getCoreUpdateList() {
		return [
			// 1.2; T273080
			[ 'doInterwikiUpdate' ],

			// 1.28
			[ 'addIndex', 'recentchanges', 'rc_name_type_patrolled_timestamp',
				'patch-add-rc_name_type_patrolled_timestamp_index.sql' ],
			[ 'doRevisionPageRevIndexNonUnique' ],
			[ 'doNonUniquePlTlIl' ],
			[ 'addField', 'change_tag', 'ct_id', 'patch-change_tag-ct_id.sql' ],
			[ 'modifyField', 'recentchanges', 'rc_ip', 'patch-rc_ip_modify.sql' ],
			[ 'ifTableNotExists', 'actor', 'addIndex', 'archive', 'usertext_timestamp',
				'patch-rename-ar_usertext_timestamp.sql' ],

			// 1.29
			[ 'addField', 'externallinks', 'el_index_60', 'patch-externallinks-el_index_60.sql' ],
			[ 'dropIndex', 'user_groups', 'ug_user_group', 'patch-user_groups-primary-key.sql' ],
			[ 'addField', 'user_groups', 'ug_expiry', 'patch-user_groups-ug_expiry.sql' ],
			[ 'ifTableNotExists', 'actor',
				'addIndex', 'image', 'img_user_timestamp', 'patch-image-user-index-2.sql' ],

			// 1.30
			[ 'modifyField', 'image', 'img_media_type', 'patch-add-3d.sql' ],
			[ 'addTable', 'ip_changes', 'patch-ip_changes.sql' ],
			[ 'renameIndex', 'categorylinks', 'cl_from', 'PRIMARY', false,
				'patch-categorylinks-fix-pk.sql' ],
			[ 'renameIndex', 'templatelinks', 'tl_from', 'PRIMARY', false,
				'patch-templatelinks-fix-pk.sql' ],
			[ 'renameIndex', 'pagelinks', 'pl_from', 'PRIMARY', false, 'patch-pagelinks-fix-pk.sql' ],
			[ 'renameIndex', 'text', 'old_id', 'PRIMARY', false, 'patch-text-fix-pk.sql' ],
			[ 'renameIndex', 'imagelinks', 'il_from', 'PRIMARY', false, 'patch-imagelinks-fix-pk.sql' ],
			[ 'renameIndex', 'iwlinks', 'iwl_from', 'PRIMARY', false, 'patch-iwlinks-fix-pk.sql' ],
			[ 'renameIndex', 'langlinks', 'll_from', 'PRIMARY', false, 'patch-langlinks-fix-pk.sql' ],
			[ 'renameIndex', 'log_search', 'ls_field_val', 'PRIMARY', false, 'patch-log_search-fix-pk.sql' ],
			[ 'renameIndex', 'module_deps', 'md_module_skin', 'PRIMARY', false,
				'patch-module_deps-fix-pk.sql' ],
			[ 'renameIndex', 'objectcache', 'keyname', 'PRIMARY', false, 'patch-objectcache-fix-pk.sql' ],
			[ 'renameIndex', 'querycache_info', 'qci_type', 'PRIMARY', false,
				'patch-querycache_info-fix-pk.sql' ],
			[ 'renameIndex', 'site_stats', 'ss_row_id', 'PRIMARY', false, 'patch-site_stats-fix-pk.sql' ],
			[ 'renameIndex', 'user_former_groups', 'ufg_user_group', 'PRIMARY', false,
				'patch-user_former_groups-fix-pk.sql' ],
			[ 'renameIndex', 'user_properties', 'user_properties_user_property', 'PRIMARY', false,
				'patch-user_properties-fix-pk.sql' ],
			[ 'addTable', 'comment', 'patch-comment-table.sql' ],
			[ 'addTable', 'revision_comment_temp', 'patch-revision_comment_temp-table.sql' ],
			// image_comment_temp is no longer needed when upgrading to MW 1.31 or newer,
			// as it is dropped later in the update process as part of 'migrateImageCommentTemp'.
			// File kept on disk and the updater entry here for historical purposes.
			// [ 'addTable', 'image_comment_temp', 'patch-image_comment_temp-table.sql' ],
			[ 'addField', 'archive', 'ar_comment_id', 'patch-archive-ar_comment_id.sql' ],
			[ 'addField', 'filearchive', 'fa_description_id', 'patch-filearchive-fa_description_id.sql' ],
			[ 'modifyField', 'image', 'img_description', 'patch-image-img_description-default.sql' ],
			[ 'addField', 'ipblocks', 'ipb_reason_id', 'patch-ipblocks-ipb_reason_id.sql' ],
			[ 'addField', 'logging', 'log_comment_id', 'patch-logging-log_comment_id.sql' ],
			[ 'addField', 'oldimage', 'oi_description_id', 'patch-oldimage-oi_description_id.sql' ],
			[ 'addField', 'protected_titles', 'pt_reason_id', 'patch-protected_titles-pt_reason_id.sql' ],
			[ 'addField', 'recentchanges', 'rc_comment_id', 'patch-recentchanges-rc_comment_id.sql' ],
			[ 'setDefault', 'revision', 'rev_comment', '' ],

			// This field was added in 1.31, but is put here so it can be used by 'migrateComments'
			[ 'addField', 'image', 'img_description_id', 'patch-image-img_description_id.sql' ],

			[ 'migrateComments' ],
			[ 'renameIndex', 'l10n_cache', 'lc_lang_key', 'PRIMARY', false,
				'patch-l10n_cache-primary-key.sql' ],
			[ 'doUnsignedSyncronisation' ],

			// 1.31
			[ 'addTable', 'slots', 'patch-slots.sql' ],
			[ 'addField', 'slots', 'slot_origin', 'patch-slot-origin.sql' ],
			[ 'addTable', 'content', 'patch-content.sql' ],
			[ 'addTable', 'slot_roles', 'patch-slot_roles.sql' ],
			[ 'addTable', 'content_models', 'patch-content_models.sql' ],
			[ 'migrateArchiveText' ],
			[ 'addTable', 'actor', 'patch-actor-table.sql' ],
			[ 'addTable', 'revision_actor_temp', 'patch-revision_actor_temp-table.sql' ],
			[ 'addField', 'archive', 'ar_actor', 'patch-archive-ar_actor.sql' ],
			[ 'addField', 'ipblocks', 'ipb_by_actor', 'patch-ipblocks-ipb_by_actor.sql' ],
			[ 'addField', 'image', 'img_actor', 'patch-image-img_actor.sql' ],
			[ 'addField', 'oldimage', 'oi_actor', 'patch-oldimage-oi_actor.sql' ],
			[ 'addField', 'filearchive', 'fa_actor', 'patch-filearchive-fa_actor.sql' ],
			[ 'addField', 'recentchanges', 'rc_actor', 'patch-recentchanges-rc_actor.sql' ],
			[ 'addField', 'logging', 'log_actor', 'patch-logging-log_actor.sql' ],
			[ 'migrateActors' ],

			// Adds a default value to the rev_text_id field to allow Multi Content
			// Revisions migration to happen where rows will have to be added to the
			// revision table with no rev_text_id.
			[ 'setDefault', 'revision', 'rev_text_id', 0 ],
			[ 'modifyTable', 'site_stats', 'patch-site_stats-modify.sql' ],
			[ 'populateArchiveRevId' ],
			[ 'addIndex', 'recentchanges', 'rc_namespace_title_timestamp',
				'patch-recentchanges-nttindex.sql' ],

			// 1.32
			[ 'addTable', 'change_tag_def', 'patch-change_tag_def.sql' ],
			[ 'populateExternallinksIndex60' ],
			[ 'dropDefault', 'externallinks', 'el_index_60' ],
			[ 'runMaintenance', DeduplicateArchiveRevId::class, 'maintenance/deduplicateArchiveRevId.php' ],
			[ 'addField', 'change_tag', 'ct_tag_id', 'patch-change_tag-tag_id.sql' ],
			[ 'addIndex', 'archive', 'ar_revid_uniq', 'patch-archive-ar_rev_id-unique.sql' ],
			[ 'populateContentTables' ],
			[ 'addIndex', 'logging', 'log_type_action', 'patch-logging-log-type-action-index.sql' ],
			[ 'dropIndex', 'logging', 'type_action', 'patch-logging-drop-type-action-index.sql' ],
			[ 'renameIndex', 'interwiki', 'iw_prefix', 'PRIMARY', false, 'patch-interwiki-fix-pk.sql' ],
			[ 'renameIndex', 'page_props', 'pp_page_propname', 'PRIMARY', false,
				'patch-page_props-fix-pk.sql' ],
			[ 'renameIndex', 'protected_titles', 'pt_namespace_title', 'PRIMARY', false,
				'patch-protected_titles-fix-pk.sql' ],
			[ 'renameIndex', 'site_identifiers', 'site_ids_type', 'PRIMARY', false,
				'patch-site_identifiers-fix-pk.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_this_oldid', 'patch-recentchanges-rc_this_oldid-index.sql' ],
			[ 'dropTable', 'transcache' ],
			[ 'runMaintenance', PopulateChangeTagDef::class, 'maintenance/populateChangeTagDef.php' ],
			[ 'addIndex', 'change_tag', 'change_tag_rc_tag_id',
				'patch-change_tag-change_tag_rc_tag_id.sql' ],
			[ 'addField', 'ipblocks', 'ipb_sitewide', 'patch-ipb_sitewide.sql' ],
			[ 'addTable', 'ipblocks_restrictions', 'patch-ipblocks_restrictions-table.sql' ],
			[ 'migrateImageCommentTemp' ],

			// 1.33
			[ 'dropField', 'change_tag', 'ct_tag', 'patch-drop-ct_tag.sql' ],
			[ 'dropTable', 'valid_tag' ],
			[ 'dropTable', 'tag_summary' ],
			[ 'dropField', 'archive', 'ar_comment', 'patch-archive-drop-ar_comment.sql' ],
			[ 'dropField', 'ipblocks', 'ipb_reason', 'patch-ipblocks-drop-ipb_reason.sql' ],
			[ 'dropField', 'image', 'img_description', 'patch-image-drop-img_description.sql' ],
			[ 'dropField', 'oldimage', 'oi_description', 'patch-oldimage-drop-oi_description.sql' ],
			[ 'dropField', 'filearchive', 'fa_description', 'patch-filearchive-drop-fa_description.sql' ],
			[ 'dropField', 'recentchanges', 'rc_comment', 'patch-recentchanges-drop-rc_comment.sql' ],
			[ 'dropField', 'logging', 'log_comment', 'patch-logging-drop-log_comment.sql' ],
			[ 'dropField', 'protected_titles', 'pt_reason', 'patch-protected_titles-drop-pt_reason.sql' ],
			[ 'modifyTable', 'job', 'patch-job-params-mediumblob.sql' ],

			// 1.34
			[ 'dropIndex', 'archive', 'ar_usertext_timestamp',
				'patch-drop-archive-ar_usertext_timestamp.sql' ],
			[ 'dropIndex', 'archive', 'usertext_timestamp', 'patch-drop-archive-usertext_timestamp.sql' ],
			[ 'dropField', 'archive', 'ar_user', 'patch-drop-archive-user-fields.sql' ],
			[ 'dropField', 'ipblocks', 'ip_by', 'patch-drop-ipblocks-user-fields.sql' ],
			[ 'dropIndex', 'image', 'img_user_timestamp', 'patch-drop-image-img_user_timestamp.sql' ],
			[ 'dropField', 'image', 'img_user', 'patch-drop-image-user-fields.sql' ],
			[ 'dropField', 'oldimage', 'oi_user', 'patch-drop-oldimage-user-fields.sql' ],
			[ 'dropField', 'filearchive', 'fa_user', 'patch-drop-filearchive-user-fields.sql' ],
			[ 'dropField', 'recentchanges', 'rc_user', 'patch-drop-recentchanges-user-fields.sql' ],
			[ 'dropField', 'logging', 'log_user', 'patch-drop-logging-user-fields.sql' ],
			[ 'addIndex', 'user_newtalk', 'un_user_ip', 'patch-rename-mysql-user_newtalk-indexes.sql' ],

			// 1.35
			[ 'addTable', 'watchlist_expiry', 'patch-watchlist_expiry.sql' ],
			[ 'modifyField', 'page', 'page_restrictions', 'patch-page_restrictions-null.sql' ],
			[ 'renameIndex', 'ipblocks', 'ipb_address', 'ipb_address_unique', false,
				'patch-ipblocks-rename-ipb_address.sql' ],
			[ 'addField', 'revision', 'rev_actor', 'patch-revision-actor-comment-MCR.sql' ],
			[ 'dropField', 'archive', 'ar_text_id', 'patch-archive-MCR.sql' ],
			[ 'doLanguageLinksLengthSync' ],
			[ 'doFixIpbAddressUniqueIndex' ],
			[ 'modifyField', 'actor', 'actor_name', 'patch-actor-actor_name-varbinary.sql' ],
			[ 'modifyField', 'sites', 'site_global_key', 'patch-sites-site_global_key.sql' ],
			[ 'modifyField', 'iwlinks', 'iwl_prefix', 'patch-extend-iwlinks-iwl_prefix.sql' ],

			// 1.36
			[ 'modifyField', 'redirect', 'rd_title', 'patch-redirect-rd_title-varbinary.sql' ],
			[ 'modifyField', 'pagelinks', 'pl_title', 'patch-pagelinks-pl_title-varbinary.sql' ],
			[ 'modifyField', 'templatelinks', 'tl_title', 'patch-templatelinks-tl_title-varbinary.sql' ],
			[ 'modifyField', 'imagelinks', 'il_to', 'patch-imagelinks-il_to-varbinary.sql' ],
			[ 'modifyField', 'langlinks', 'll_title', 'patch-langlinks-ll_title-varbinary.sql' ],
			[ 'modifyField', 'iwlinks', 'iwl_title', 'patch-iwlinks-iwl_title-varbinary.sql' ],
			[ 'modifyField', 'category', 'cat_title', 'patch-category-cat_title-varbinary.sql' ],
			[ 'modifyField', 'querycache', 'qc_title', 'patch-querycache-qc_title-varbinary.sql' ],
			[ 'modifyField', 'querycachetwo', 'qcc_title', 'patch-querycachetwo-qcc_title-varbinary.sql' ],
			[ 'modifyField', 'watchlist', 'wl_title', 'patch-watchlist-wl_title-varbinary.sql' ],
			[ 'modifyField', 'user_newtalk', 'user_last_timestamp',
				'patch-user_newtalk-user_last_timestamp-binary.sql'
			],
			[ 'modifyField', 'protected_titles', 'pt_title', 'patch-protected_titles-pt_title-varbinary.sql' ],
			[ 'dropDefault', 'protected_titles', 'pt_expiry' ],
			[ 'dropDefault', 'ip_changes', 'ipc_rev_timestamp' ],
			[ 'dropDefault', 'revision_actor_temp', 'revactor_timestamp' ],
			[ 'modifyField', 'ipblocks_restrictions', 'ir_type', 'patch-ipblocks_restrictions-ir_type.sql' ],
			[ 'renameIndex', 'watchlist', 'namespace_title', 'wl_namespace_title', false,
				'patch-watchlist-namespace_title-rename-index.sql' ],
			[ 'modifyField', 'job', 'job_title', 'patch-job-job_title-varbinary.sql' ],
			[ 'modifyField', 'job', 'job_timestamp', 'patch-job_job_timestamp.sql' ],
			[ 'modifyField', 'job', 'job_token_timestamp', 'patch-job_job_token_timestamp.sql' ],
			[ 'modifyField', 'watchlist', 'wl_notificationtimestamp', 'patch-watchlist-wl_notificationtimestamp.sql' ],
			[ 'modifyField', 'slot_roles', 'role_id', 'patch-slot_roles-role_id.sql' ],
			[ 'modifyField', 'content_models', 'model_id', 'patch-content_models-model_id.sql' ],
			[ 'modifyField', 'categorylinks', 'cl_to', 'patch-categorylinks-cl_to-varbinary.sql' ],
			[ 'modifyField', 'logging', 'log_title', 'patch-logging-log_title-varbinary.sql' ],
			[ 'modifyField', 'uploadstash', 'us_timestamp', 'patch-uploadstash-us_timestamp.sql' ],
			[ 'renameIndex', 'user_properties', 'user_properties_property', 'up_property', false,
				'patch-user_properties-rename-index.sql' ],
			[ 'renameIndex', 'sites', 'sites_global_key', 'site_global_key', false, 'patch-sites-rename-indexes.sql' ],
			[ 'renameIndex', 'logging', 'type_time', 'log_type_time', false, 'patch-logging-rename-indexes.sql' ],
			[ 'modifyField', 'filearchive', 'fa_name', 'patch-filearchive-fa_name.sql' ],
			[ 'dropDefault', 'filearchive', 'fa_deleted_timestamp' ],
			[ 'dropDefault', 'filearchive', 'fa_timestamp' ],
			[ 'modifyField', 'oldimage', 'oi_name', 'patch-oldimage-oi_name-varbinary.sql' ],
			[ 'dropDefault', 'oldimage', 'oi_timestamp' ],
			[ 'modifyField', 'objectcache', 'exptime', 'patch-objectcache-exptime-notnull.sql' ],
			[ 'dropDefault', 'ipblocks', 'ipb_timestamp' ],
			[ 'dropDefault', 'ipblocks', 'ipb_expiry' ],
			[ 'renameIndex', 'archive', 'name_title_timestamp', 'ar_name_title_timestamp', false,
				'patch-archive-rename-name_title_timestamp-index.sql' ],
			[ 'modifyField', 'image', 'img_name', 'patch-image-img_name-varbinary.sql' ],
			[ 'dropDefault', 'image', 'img_timestamp' ],
			[ 'modifyField', 'image', 'img_timestamp', 'patch-image-img_timestamp.sql' ],
			[ 'renameIndex', 'site_identifiers', 'site_ids_key', 'si_key', false,
				'patch-site_identifiers-rename-indexes.sql' ],
			[ 'modifyField', 'recentchanges', 'rc_title', 'patch-recentchanges-rc_title-varbinary.sql' ],
			[ 'dropDefault', 'recentchanges', 'rc_timestamp' ],
			[ 'modifyField', 'recentchanges', 'rc_timestamp', 'patch-recentchanges-rc_timestamp.sql' ],
			[ 'modifyField', 'recentchanges', 'rc_id', 'patch-recentchanges-rc_id.sql' ],
			[ 'renameIndex', 'recentchanges', 'new_name_timestamp', 'rc_new_name_timestamp', false,
				'patch-recentchanges-rc_new_name_timestamp.sql' ],
			[ 'dropDefault', 'archive', 'ar_timestamp' ],
			[ 'modifyField', 'archive', 'ar_title', 'patch-archive-ar_title-varbinary.sql' ],
		];
	}

	/**
	 * MW 1.4 betas were missing the 'binary' marker from logging.log_title,
	 * which caused a MySQL collation mismatch error.
	 *
	 * @param string $table Table name
	 * @param string $field Field name to check
	 * @param string $patchFile Path to the patch to correct the field
	 * @return bool
	 */
	protected function checkBin( $table, $field, $patchFile ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		/** @var MySQLField $fieldInfo */
		$fieldInfo = $this->db->fieldInfo( $table, $field );
		if ( $fieldInfo->isBinary() ) {
			$this->output( "...$table table has correct $field encoding.\n" );
		} else {
			$this->applyPatch( $patchFile, false, "Fixing $field encoding on $table table" );
		}
	}

	/**
	 * Check whether an index contain a field
	 *
	 * @param string $table Table name
	 * @param string $index Index name to check
	 * @param string $field Field that should be in the index
	 * @return bool
	 */
	protected function indexHasField( $table, $index, $field ) {
		$info = $this->db->indexInfo( $table, $index, __METHOD__ );
		if ( $info ) {
			foreach ( $info as $row ) {
				if ( $row->Column_name == $field ) {
					$this->output( "...index $index on table $table includes field $field.\n" );
					return true;
				}
			}
		}
		$this->output( "...index $index on table $table has no field $field; added.\n" );

		return false;
	}

	/**
	 * Check that interwiki table exists; if it doesn't source it
	 */
	protected function doInterwikiUpdate() {
		global $IP;

		if ( !$this->doTable( 'interwiki' ) ) {
			return;
		}

		if ( $this->db->tableExists( "interwiki", __METHOD__ ) ) {
			$this->output( "...already have interwiki table\n" );

			return;
		}

		$this->applyPatch( 'patch-interwiki.sql', false, 'Creating interwiki table' );
		$this->applyPatch(
			"$IP/maintenance/interwiki.sql",
			true,
			'Adding default interwiki definitions'
		);
	}

	protected function doOldLinksUpdate() {
		$cl = $this->maintenance->runChild( ConvertLinks::class );
		$cl->execute();
	}

	/**
	 * Check if we need to add talk page rows to the watchlist
	 */
	protected function doWatchlistUpdate() {
		global $wgUpdateRowsPerQuery;

		$sql = $this->db->unionQueries(
			[
				// Missing talk page rows (corresponding subject page row exists)
				$this->db->selectSQLText(
					[ 'wlsubject' => 'watchlist', 'wltalk' => 'watchlist' ],
					[
						'wl_user' => 'wlsubject.wl_user',
						'wl_namespace' => 'wlsubject.wl_namespace | 1',
						'wl_title' => 'wlsubject.wl_title',
						'wl_notificationtimestamp' => 'wlsubject.wl_notificationtimestamp'
					],
					[ 'NOT (wlsubject.wl_namespace & 1)', 'wltalk.wl_namespace IS NULL' ],
					__METHOD__,
					[],
					[
						'wltalk' => [ 'LEFT JOIN', [
							'wltalk.wl_user = wlsubject.wl_user',
							'wltalk.wl_namespace = (wlsubject.wl_namespace | 1)',
							'wltalk.wl_title = wlsubject.wl_title'
						] ]
					]
				),
				// Missing subject page rows (corresponding talk page row exists)
				$this->db->selectSQLText(
					[ 'wltalk' => 'watchlist', 'wlsubject' => 'watchlist' ],
					[
						'wl_user' => 'wltalk.wl_user',
						'wl_namespace' => 'wltalk.wl_namespace & ~1',
						'wl_title' => 'wltalk.wl_title',
						'wl_notificationtimestamp' => 'wltalk.wl_notificationtimestamp'
					],
					[ 'wltalk.wl_namespace & 1', 'wlsubject.wl_namespace IS NULL' ],
					__METHOD__,
					[],
					[
						'wlsubject' => [ 'LEFT JOIN', [
							'wlsubject.wl_user = wltalk.wl_user',
							'wlsubject.wl_namespace = (wltalk.wl_namespace & ~1)',
							'wlsubject.wl_title = wltalk.wl_title'
						] ]
					]
				)
			],
			true // use a non-distinct UNION to avoid overhead
		);

		$res = $this->db->query( $sql, __METHOD__ );

		if ( !$res->numRows() ) {
			$this->output( "...watchlist talk page rows already present.\n" );
			return;
		}

		$this->output( "Adding missing corresponding talk/subject watchlist page rows... " );

		$rowBatch = [];
		foreach ( $res as $row ) {
			$rowBatch[] = (array)$row;
			if ( count( $rowBatch ) >= $wgUpdateRowsPerQuery ) {
				$this->db->insert( 'watchlist', $rowBatch, __METHOD__, [ 'IGNORE' ] );
				$rowBatch = [];
			}
		}
		$this->db->insert( 'watchlist', $rowBatch, __METHOD__, [ 'IGNORE' ] );

		$this->output( "done.\n" );
	}

	protected function doNamespaceSize() {
		$tables = [
			'page' => 'page',
			'archive' => 'ar',
			'recentchanges' => 'rc',
			'watchlist' => 'wl',
			'querycache' => 'qc',
			'logging' => 'log',
		];
		foreach ( $tables as $table => $prefix ) {
			$field = $prefix . '_namespace';

			$tablename = $this->db->tableName( $table );
			$result = $this->db->query( "SHOW COLUMNS FROM $tablename LIKE '$field'", __METHOD__ );
			$info = $this->db->fetchObject( $result );

			if ( substr( $info->Type, 0, 3 ) == 'int' ) {
				$this->output( "...$field is already a full int ($info->Type).\n" );
			} else {
				$this->output( "Promoting $field from $info->Type to int... " );
				$this->db->query( "ALTER TABLE $tablename MODIFY $field int NOT NULL", __METHOD__ );
				$this->output( "done.\n" );
			}
		}
	}

	/**
	 * Set page_random field to a random value where it is equals to 0.
	 *
	 * @see T5946
	 */
	protected function doPageRandomUpdate() {
		$page = $this->db->tableName( 'page' );
		$this->db->query( "UPDATE $page SET page_random = RAND() WHERE page_random = 0", __METHOD__ );
		$rows = $this->db->affectedRows();

		if ( $rows ) {
			$this->output( "Set page_random to a random value on $rows rows where it was set to 0\n" );
		} else {
			$this->output( "...no page_random rows needed to be set\n" );
		}
	}

	protected function doCategoryPopulation() {
		if ( $this->updateRowExists( 'populate category' ) ) {
			$this->output( "...category table already populated.\n" );

			return;
		}

		$this->output(
			"Populating category table, printing progress markers. " .
			"For large databases, you\n" .
			"may want to hit Ctrl-C and do this manually with maintenance/\n" .
			"populateCategory.php.\n"
		);
		$task = $this->maintenance->runChild( PopulateCategory::class );
		$task->execute();
		$this->output( "Done populating category table.\n" );
	}

	protected function doPopulateParentId() {
		if ( !$this->updateRowExists( 'populate rev_parent_id' ) ) {
			$this->output(
				"Populating rev_parent_id fields, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/populateParentId.php.\n" );

			$task = $this->maintenance->runChild( PopulateParentId::class );
			$task->execute();
		}
	}

	protected function doNonUniquePlTlIl() {
		$info = $this->db->indexInfo( 'pagelinks', 'pl_namespace', __METHOD__ );
		if ( is_array( $info ) && $info[0]->Non_unique ) {
			$this->output( "...pl_namespace, tl_namespace, il_to indices are already non-UNIQUE.\n" );

			return true;
		}
		if ( $this->skipSchema ) {
			$this->output( "...skipping schema change (making pl_namespace, tl_namespace " .
				"and il_to indices non-UNIQUE).\n" );

			return false;
		}

		return $this->applyPatch(
			'patch-pl-tl-il-nonunique.sql',
			false,
			'Making pl_namespace, tl_namespace and il_to indices non-UNIQUE'
		);
	}

	protected function doLanguageLinksLengthSync() {
		$sync = [
			[ 'table' => 'l10n_cache', 'field' => 'lc_lang', 'file' => 'patch-l10n_cache-lc_lang-35.sql' ],
			[ 'table' => 'langlinks', 'field' => 'll_lang', 'file' => 'patch-langlinks-ll_lang-35.sql' ],
			[ 'table' => 'sites', 'field' => 'site_language', 'file' => 'patch-sites-site_language-35.sql' ],
		];

		foreach ( $sync as $s ) {
			$table = $this->db->tableName( $s['table'] );
			$field = $s['field'];
			$res = $this->db->query( "SHOW COLUMNS FROM $table LIKE '$field'", __METHOD__ );
			$row = $this->db->fetchObject( $res );

			if ( $row && $row->Type !== "varbinary(35)" ) {
				$this->applyPatch(
					$s['file'],
					false,
					"Updating length of $field in $table"
				);
			} else {
				$this->output( "...$field is up-to-date.\n" );
			}
		}
	}

	protected function doFixIpbAddressUniqueIndex() {
		if ( !$this->doTable( 'ipblocks' ) ) {
			return;
		}

		if ( !$this->indexHasField( 'ipblocks', 'ipb_address_unique', 'ipb_anon_only' ) ) {
			$this->output( "...ipb_address_unique index up-to-date.\n" );
			return;
		}

		$this->applyPatch(
			'patch-ipblocks-fix-ipb_address_unique.sql',
			false,
			'Removing ipb_anon_only column from ipb_address_unique index'
		);
	}

	protected function doUnsignedSyncronisation() {
		$sync = [
			[ 'table' => 'bot_passwords', 'field' => 'bp_user', 'file' => 'patch-bot_passwords-bp_user-unsigned.sql' ],
			[ 'table' => 'change_tag', 'field' => 'ct_log_id', 'file' => 'patch-change_tag-ct_log_id-unsigned.sql' ],
			[ 'table' => 'change_tag', 'field' => 'ct_rev_id', 'file' => 'patch-change_tag-ct_rev_id-unsigned.sql' ],
			[ 'table' => 'page_restrictions', 'field' => 'pr_user',
				'file' => 'patch-page_restrictions-pr_user-unsigned.sql' ],
			[ 'table' => 'user_newtalk', 'field' => 'user_id', 'file' => 'patch-user_newtalk-user_id-unsigned.sql' ],
			[ 'table' => 'user_properties', 'field' => 'up_user',
				'file' => 'patch-user_properties-up_user-unsigned.sql' ],
			[ 'table' => 'change_tag', 'field' => 'ct_rc_id', 'file' => 'patch-change_tag-ct_rc_id-unsigned.sql' ]
		];

		foreach ( $sync as $s ) {
			if ( !$this->doTable( $s['table'] ) ) {
				continue;
			}

			$info = $this->db->fieldInfo( $s['table'], $s['field'] );
			if ( $info === false ) {
				continue;
			}
			$fullName = "{$s['table']}.{$s['field']}";
			if ( $info->isUnsigned() ) {
				$this->output( "...$fullName is already unsigned int.\n" );

				continue;
			}

			$this->applyPatch(
				$s['file'],
				false,
				"Making $fullName into an unsigned int"
			);
		}

		return true;
	}

	protected function doRevisionPageRevIndexNonUnique() {
		if ( !$this->doTable( 'revision' ) ) {
			return true;
		} elseif ( !$this->db->indexExists( 'revision', 'rev_page_id', __METHOD__ ) ) {
			$this->output( "...rev_page_id index not found on revision.\n" );
			return true;
		}

		if ( !$this->db->indexUnique( 'revision', 'rev_page_id', __METHOD__ ) ) {
			$this->output( "...rev_page_id index already non-unique.\n" );
			return true;
		}

		return $this->applyPatch(
			'patch-revision-page-rev-index-nonunique.sql',
			false,
			'Making rev_page_id index non-unique'
		);
	}

	public function getSchemaVars() {
		global $wgDBTableOptions;

		$vars = [];
		$vars['wgDBTableOptions'] = str_replace( 'TYPE', 'ENGINE', $wgDBTableOptions );
		$vars['wgDBTableOptions'] = str_replace(
			'CHARSET=mysql4',
			'CHARSET=binary',
			$vars['wgDBTableOptions']
		);

		return $vars;
	}

	/**
	 * Drop a default value from a field
	 *
	 * @since 1.36
	 * @param string $table
	 * @param string $field
	 */
	protected function dropDefault( $table, $field ) {
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info && $info->defaultValue() !== false ) {
			$this->output( "Removing '$table.$field' default value\n" );
			$table = $this->db->tableName( $table );
			$this->db->query( "ALTER TABLE $table ALTER COLUMN $field DROP DEFAULT", __METHOD__ );
		}
	}

	/**
	 * Set a default value for a field
	 *
	 * @since 1.36
	 * @param string $table
	 * @param string $field
	 * @param mixed $default
	 */
	protected function setDefault( $table, $field, $default ) {
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info && $info->defaultValue() !== $default ) {
			$this->output( "Changing '$table.$field' default value\n" );
			$table = $this->db->tableName( $table );
			$this->db->query(
				"ALTER TABLE $table ALTER COLUMN $field SET DEFAULT "
				. $this->db->addQuotes( $default ), __METHOD__
			);
		}
	}

}
