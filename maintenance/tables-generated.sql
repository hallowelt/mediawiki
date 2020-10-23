-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: maintenance/tables.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE /*_*/site_identifiers (
  si_type VARBINARY(32) NOT NULL,
  si_key VARBINARY(32) NOT NULL,
  si_site INT UNSIGNED NOT NULL,
  INDEX site_ids_site (si_site),
  INDEX site_ids_key (si_key),
  PRIMARY KEY(si_type, si_key)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/updatelog (
  ul_key VARCHAR(255) NOT NULL,
  ul_value BLOB DEFAULT NULL,
  PRIMARY KEY(ul_key)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/actor (
  actor_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  actor_user INT UNSIGNED DEFAULT NULL,
  actor_name VARBINARY(255) NOT NULL,
  UNIQUE INDEX actor_user (actor_user),
  UNIQUE INDEX actor_name (actor_name),
  PRIMARY KEY(actor_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/user_former_groups (
  ufg_user INT UNSIGNED DEFAULT 0 NOT NULL,
  ufg_group VARBINARY(255) DEFAULT '' NOT NULL,
  PRIMARY KEY(ufg_user, ufg_group)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/bot_passwords (
  bp_user INT UNSIGNED NOT NULL,
  bp_app_id VARBINARY(32) NOT NULL,
  bp_password TINYBLOB NOT NULL,
  bp_token BINARY(32) DEFAULT '' NOT NULL,
  bp_restrictions BLOB NOT NULL,
  bp_grants BLOB NOT NULL,
  PRIMARY KEY(bp_user, bp_app_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/comment (
  comment_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  comment_hash INT NOT NULL,
  comment_text BLOB NOT NULL,
  comment_data BLOB DEFAULT NULL,
  INDEX comment_hash (comment_hash),
  PRIMARY KEY(comment_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/slots (
  slot_revision_id BIGINT UNSIGNED NOT NULL,
  slot_role_id SMALLINT UNSIGNED NOT NULL,
  slot_content_id BIGINT UNSIGNED NOT NULL,
  slot_origin BIGINT UNSIGNED NOT NULL,
  INDEX slot_revision_origin_role (
    slot_revision_id, slot_origin, slot_role_id
  ),
  PRIMARY KEY(slot_revision_id, slot_role_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/site_stats (
  ss_row_id INT UNSIGNED NOT NULL,
  ss_total_edits BIGINT UNSIGNED DEFAULT NULL,
  ss_good_articles BIGINT UNSIGNED DEFAULT NULL,
  ss_total_pages BIGINT UNSIGNED DEFAULT NULL,
  ss_users BIGINT UNSIGNED DEFAULT NULL,
  ss_active_users BIGINT UNSIGNED DEFAULT NULL,
  ss_images BIGINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY(ss_row_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/user_properties (
  up_user INT UNSIGNED NOT NULL,
  up_property VARBINARY(255) NOT NULL,
  up_value BLOB DEFAULT NULL,
  INDEX user_properties_property (up_property),
  PRIMARY KEY(up_user, up_property)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/log_search (
  ls_field VARBINARY(32) NOT NULL,
  ls_value VARCHAR(255) NOT NULL,
  ls_log_id INT UNSIGNED DEFAULT 0 NOT NULL,
  INDEX ls_log_id (ls_log_id),
  PRIMARY KEY(ls_field, ls_value, ls_log_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/change_tag (
  ct_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  ct_rc_id INT UNSIGNED DEFAULT NULL,
  ct_log_id INT UNSIGNED DEFAULT NULL,
  ct_rev_id INT UNSIGNED DEFAULT NULL,
  ct_params BLOB DEFAULT NULL,
  ct_tag_id INT UNSIGNED NOT NULL,
  UNIQUE INDEX change_tag_rc_tag_id (ct_rc_id, ct_tag_id),
  UNIQUE INDEX change_tag_log_tag_id (ct_log_id, ct_tag_id),
  UNIQUE INDEX change_tag_rev_tag_id (ct_rev_id, ct_tag_id),
  INDEX change_tag_tag_id_id (
    ct_tag_id, ct_rc_id, ct_rev_id, ct_log_id
  ),
  PRIMARY KEY(ct_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/content (
  content_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  content_size INT UNSIGNED NOT NULL,
  content_sha1 VARBINARY(32) NOT NULL,
  content_model SMALLINT UNSIGNED NOT NULL,
  content_address VARBINARY(255) NOT NULL,
  PRIMARY KEY(content_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/l10n_cache (
  lc_lang VARBINARY(35) NOT NULL,
  lc_key VARCHAR(255) NOT NULL,
  lc_value MEDIUMBLOB NOT NULL,
  PRIMARY KEY(lc_lang, lc_key)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/module_deps (
  md_module VARBINARY(255) NOT NULL,
  md_skin VARBINARY(32) NOT NULL,
  md_deps MEDIUMBLOB NOT NULL,
  PRIMARY KEY(md_module, md_skin)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/redirect (
  rd_from INT UNSIGNED DEFAULT 0 NOT NULL,
  rd_namespace INT DEFAULT 0 NOT NULL,
  rd_title VARBINARY(255) DEFAULT '' NOT NULL,
  rd_interwiki VARCHAR(32) DEFAULT NULL,
  rd_fragment VARBINARY(255) DEFAULT NULL,
  INDEX rd_ns_title (rd_namespace, rd_title, rd_from),
  PRIMARY KEY(rd_from)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/pagelinks (
  pl_from INT UNSIGNED DEFAULT 0 NOT NULL,
  pl_namespace INT DEFAULT 0 NOT NULL,
  pl_title VARBINARY(255) DEFAULT '' NOT NULL,
  pl_from_namespace INT DEFAULT 0 NOT NULL,
  INDEX pl_namespace (pl_namespace, pl_title, pl_from),
  INDEX pl_backlinks_namespace (
    pl_from_namespace, pl_namespace,
    pl_title, pl_from
  ),
  PRIMARY KEY(pl_from, pl_namespace, pl_title)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/templatelinks (
  tl_from INT UNSIGNED DEFAULT 0 NOT NULL,
  tl_namespace INT DEFAULT 0 NOT NULL,
  tl_title VARBINARY(255) DEFAULT '' NOT NULL,
  tl_from_namespace INT DEFAULT 0 NOT NULL,
  INDEX tl_namespace (tl_namespace, tl_title, tl_from),
  INDEX tl_backlinks_namespace (
    tl_from_namespace, tl_namespace,
    tl_title, tl_from
  ),
  PRIMARY KEY(tl_from, tl_namespace, tl_title)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/imagelinks (
  il_from INT UNSIGNED DEFAULT 0 NOT NULL,
  il_to VARBINARY(255) DEFAULT '' NOT NULL,
  il_from_namespace INT DEFAULT 0 NOT NULL,
  INDEX il_to (il_to, il_from),
  INDEX il_backlinks_namespace (
    il_from_namespace, il_to, il_from
  ),
  PRIMARY KEY(il_from, il_to)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/langlinks (
  ll_from INT UNSIGNED DEFAULT 0 NOT NULL,
  ll_lang VARBINARY(35) DEFAULT '' NOT NULL,
  ll_title VARBINARY(255) DEFAULT '' NOT NULL,
  INDEX ll_lang (ll_lang, ll_title),
  PRIMARY KEY(ll_from, ll_lang)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/iwlinks (
  iwl_from INT UNSIGNED DEFAULT 0 NOT NULL,
  iwl_prefix VARBINARY(20) DEFAULT '' NOT NULL,
  iwl_title VARBINARY(255) DEFAULT '' NOT NULL,
  INDEX iwl_prefix_title_from (iwl_prefix, iwl_title, iwl_from),
  INDEX iwl_prefix_from_title (iwl_prefix, iwl_from, iwl_title),
  PRIMARY KEY(iwl_from, iwl_prefix, iwl_title)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/category (
  cat_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  cat_title VARBINARY(255) NOT NULL,
  cat_pages INT DEFAULT 0 NOT NULL,
  cat_subcats INT DEFAULT 0 NOT NULL,
  cat_files INT DEFAULT 0 NOT NULL,
  UNIQUE INDEX cat_title (cat_title),
  INDEX cat_pages (cat_pages),
  PRIMARY KEY(cat_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/watchlist_expiry (
  we_item INT UNSIGNED NOT NULL,
  we_expiry BINARY(14) NOT NULL,
  INDEX we_expiry (we_expiry),
  PRIMARY KEY(we_item)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/change_tag_def (
  ctd_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  ctd_name VARBINARY(255) NOT NULL,
  ctd_user_defined TINYINT(1) NOT NULL,
  ctd_count BIGINT UNSIGNED DEFAULT 0 NOT NULL,
  UNIQUE INDEX ctd_name (ctd_name),
  INDEX ctd_count (ctd_count),
  INDEX ctd_user_defined (ctd_user_defined),
  PRIMARY KEY(ctd_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/ipblocks_restrictions (
  ir_ipb_id INT NOT NULL,
  ir_type TINYINT(1) NOT NULL,
  ir_value INT NOT NULL,
  INDEX ir_type_value (ir_type, ir_value),
  PRIMARY KEY(ir_ipb_id, ir_type, ir_value)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/querycache (
  qc_type VARBINARY(32) NOT NULL,
  qc_value INT UNSIGNED DEFAULT 0 NOT NULL,
  qc_namespace INT DEFAULT 0 NOT NULL,
  qc_title VARBINARY(255) DEFAULT '' NOT NULL,
  INDEX qc_type (qc_type, qc_value)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/querycachetwo (
  qcc_type VARBINARY(32) NOT NULL,
  qcc_value INT UNSIGNED DEFAULT 0 NOT NULL,
  qcc_namespace INT DEFAULT 0 NOT NULL,
  qcc_title VARBINARY(255) DEFAULT '' NOT NULL,
  qcc_namespacetwo INT DEFAULT 0 NOT NULL,
  qcc_titletwo VARBINARY(255) DEFAULT '' NOT NULL,
  INDEX qcc_type (qcc_type, qcc_value),
  INDEX qcc_title (
    qcc_type, qcc_namespace, qcc_title
  ),
  INDEX qcc_titletwo (
    qcc_type, qcc_namespacetwo, qcc_titletwo
  )
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/page_restrictions (
  pr_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  pr_page INT NOT NULL,
  pr_type VARBINARY(60) NOT NULL,
  pr_level VARBINARY(60) NOT NULL,
  pr_cascade TINYINT NOT NULL,
  pr_user INT UNSIGNED DEFAULT NULL,
  pr_expiry VARBINARY(14) DEFAULT NULL,
  UNIQUE INDEX pr_pagetype (pr_page, pr_type),
  INDEX pr_typelevel (pr_type, pr_level),
  INDEX pr_level (pr_level),
  INDEX pr_cascade (pr_cascade),
  PRIMARY KEY(pr_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/user_groups (
  ug_user INT UNSIGNED DEFAULT 0 NOT NULL,
  ug_group VARBINARY(255) DEFAULT '' NOT NULL,
  ug_expiry VARBINARY(14) DEFAULT NULL,
  INDEX ug_group (ug_group),
  INDEX ug_expiry (ug_expiry),
  PRIMARY KEY(ug_user, ug_group)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/querycache_info (
  qci_type VARBINARY(32) DEFAULT '' NOT NULL,
  qci_timestamp BINARY(14) DEFAULT '19700101000000' NOT NULL,
  PRIMARY KEY(qci_type)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/watchlist (
  wl_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  wl_user INT UNSIGNED NOT NULL,
  wl_namespace INT DEFAULT 0 NOT NULL,
  wl_title VARBINARY(255) DEFAULT '' NOT NULL,
  wl_notificationtimestamp BINARY(14) DEFAULT NULL,
  UNIQUE INDEX wl_user (wl_user, wl_namespace, wl_title),
  INDEX namespace_title (wl_namespace, wl_title),
  INDEX wl_user_notificationtimestamp (
    wl_user, wl_notificationtimestamp
  ),
  PRIMARY KEY(wl_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/sites (
  site_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  site_global_key VARBINARY(64) NOT NULL,
  site_type VARBINARY(32) NOT NULL,
  site_group VARBINARY(32) NOT NULL,
  site_source VARBINARY(32) NOT NULL,
  site_language VARBINARY(35) NOT NULL,
  site_protocol VARBINARY(32) NOT NULL,
  site_domain VARCHAR(255) NOT NULL,
  site_data BLOB NOT NULL,
  site_forward TINYINT(1) NOT NULL,
  site_config BLOB NOT NULL,
  UNIQUE INDEX sites_global_key (site_global_key),
  INDEX sites_type (site_type),
  INDEX sites_group (site_group),
  INDEX sites_source (site_source),
  INDEX sites_language (site_language),
  INDEX sites_protocol (site_protocol),
  INDEX sites_domain (site_domain),
  INDEX sites_forward (site_forward),
  PRIMARY KEY(site_id)
) /*$wgDBTableOptions*/;
