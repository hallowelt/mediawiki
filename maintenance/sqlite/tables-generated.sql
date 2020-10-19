-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: maintenance/tables.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE /*_*/site_identifiers (
  si_type BLOB NOT NULL,
  si_key BLOB NOT NULL,
  si_site INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(si_type, si_key)
);

CREATE INDEX site_ids_site ON /*_*/site_identifiers (si_site);

CREATE INDEX site_ids_key ON /*_*/site_identifiers (si_key);


CREATE TABLE /*_*/updatelog (
  ul_key VARCHAR(255) NOT NULL,
  ul_value BLOB DEFAULT NULL,
  PRIMARY KEY(ul_key)
);


CREATE TABLE /*_*/actor (
  actor_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  actor_user INTEGER UNSIGNED DEFAULT NULL,
  actor_name BLOB NOT NULL
);

CREATE UNIQUE INDEX actor_user ON /*_*/actor (actor_user);

CREATE UNIQUE INDEX actor_name ON /*_*/actor (actor_name);


CREATE TABLE /*_*/user_former_groups (
  ufg_user INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ufg_group BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ufg_user, ufg_group)
);


CREATE TABLE /*_*/bot_passwords (
  bp_user INTEGER UNSIGNED NOT NULL,
  bp_app_id BLOB NOT NULL,
  bp_password BLOB NOT NULL,
  bp_token BLOB DEFAULT '' NOT NULL,
  bp_restrictions BLOB NOT NULL,
  bp_grants BLOB NOT NULL,
  PRIMARY KEY(bp_user, bp_app_id)
);


CREATE TABLE /*_*/comment (
  comment_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  comment_hash INTEGER NOT NULL, comment_text BLOB NOT NULL,
  comment_data BLOB DEFAULT NULL
);

CREATE INDEX comment_hash ON /*_*/comment (comment_hash);


CREATE TABLE /*_*/slots (
  slot_revision_id BIGINT UNSIGNED NOT NULL,
  slot_role_id SMALLINT UNSIGNED NOT NULL,
  slot_content_id BIGINT UNSIGNED NOT NULL,
  slot_origin BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY(slot_revision_id, slot_role_id)
);

CREATE INDEX slot_revision_origin_role ON /*_*/slots (
  slot_revision_id, slot_origin, slot_role_id
);


CREATE TABLE /*_*/site_stats (
  ss_row_id INTEGER UNSIGNED NOT NULL,
  ss_total_edits BIGINT UNSIGNED DEFAULT NULL,
  ss_good_articles BIGINT UNSIGNED DEFAULT NULL,
  ss_total_pages BIGINT UNSIGNED DEFAULT NULL,
  ss_users BIGINT UNSIGNED DEFAULT NULL,
  ss_active_users BIGINT UNSIGNED DEFAULT NULL,
  ss_images BIGINT UNSIGNED DEFAULT NULL,
  PRIMARY KEY(ss_row_id)
);


CREATE TABLE /*_*/user_properties (
  up_user INTEGER UNSIGNED NOT NULL,
  up_property BLOB NOT NULL,
  up_value BLOB DEFAULT NULL,
  PRIMARY KEY(up_user, up_property)
);

CREATE INDEX user_properties_property ON /*_*/user_properties (up_property);


CREATE TABLE /*_*/log_search (
  ls_field BLOB NOT NULL,
  ls_value VARCHAR(255) NOT NULL,
  ls_log_id INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  PRIMARY KEY(ls_field, ls_value, ls_log_id)
);

CREATE INDEX ls_log_id ON /*_*/log_search (ls_log_id);


CREATE TABLE /*_*/change_tag (
  ct_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  ct_rc_id INTEGER UNSIGNED DEFAULT NULL,
  ct_log_id INTEGER UNSIGNED DEFAULT NULL,
  ct_rev_id INTEGER UNSIGNED DEFAULT NULL,
  ct_params BLOB DEFAULT NULL, ct_tag_id INTEGER UNSIGNED NOT NULL
);

CREATE UNIQUE INDEX change_tag_rc_tag_id ON /*_*/change_tag (ct_rc_id, ct_tag_id);

CREATE UNIQUE INDEX change_tag_log_tag_id ON /*_*/change_tag (ct_log_id, ct_tag_id);

CREATE UNIQUE INDEX change_tag_rev_tag_id ON /*_*/change_tag (ct_rev_id, ct_tag_id);

CREATE INDEX change_tag_tag_id_id ON /*_*/change_tag (
  ct_tag_id, ct_rc_id, ct_rev_id, ct_log_id
);


CREATE TABLE /*_*/content (
  content_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  content_size INTEGER UNSIGNED NOT NULL,
  content_sha1 BLOB NOT NULL, content_model SMALLINT UNSIGNED NOT NULL,
  content_address BLOB NOT NULL
);


CREATE TABLE /*_*/l10n_cache (
  lc_lang BLOB NOT NULL,
  lc_key VARCHAR(255) NOT NULL,
  lc_value BLOB NOT NULL,
  PRIMARY KEY(lc_lang, lc_key)
);


CREATE TABLE /*_*/module_deps (
  md_module BLOB NOT NULL,
  md_skin BLOB NOT NULL,
  md_deps BLOB NOT NULL,
  PRIMARY KEY(md_module, md_skin)
);


CREATE TABLE /*_*/redirect (
  rd_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  rd_namespace INTEGER DEFAULT 0 NOT NULL,
  rd_title BLOB DEFAULT '' NOT NULL,
  rd_interwiki VARCHAR(32) DEFAULT NULL,
  rd_fragment BLOB DEFAULT NULL,
  PRIMARY KEY(rd_from)
);

CREATE INDEX rd_ns_title ON /*_*/redirect (rd_namespace, rd_title, rd_from);


CREATE TABLE /*_*/pagelinks (
  pl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  pl_namespace INTEGER DEFAULT 0 NOT NULL,
  pl_title BLOB DEFAULT '' NOT NULL,
  pl_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(pl_from, pl_namespace, pl_title)
);

CREATE INDEX pl_namespace ON /*_*/pagelinks (pl_namespace, pl_title, pl_from);

CREATE INDEX pl_backlinks_namespace ON /*_*/pagelinks (
  pl_from_namespace, pl_namespace,
  pl_title, pl_from
);


CREATE TABLE /*_*/templatelinks (
  tl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  tl_namespace INTEGER DEFAULT 0 NOT NULL,
  tl_title BLOB DEFAULT '' NOT NULL,
  tl_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(tl_from, tl_namespace, tl_title)
);

CREATE INDEX tl_namespace ON /*_*/templatelinks (tl_namespace, tl_title, tl_from);

CREATE INDEX tl_backlinks_namespace ON /*_*/templatelinks (
  tl_from_namespace, tl_namespace,
  tl_title, tl_from
);


CREATE TABLE /*_*/imagelinks (
  il_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  il_to BLOB DEFAULT '' NOT NULL,
  il_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(il_from, il_to)
);

CREATE INDEX il_to ON /*_*/imagelinks (il_to, il_from);

CREATE INDEX il_backlinks_namespace ON /*_*/imagelinks (
  il_from_namespace, il_to, il_from
);


CREATE TABLE /*_*/langlinks (
  ll_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ll_lang BLOB DEFAULT '' NOT NULL,
  ll_title BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ll_from, ll_lang)
);

CREATE INDEX ll_lang ON /*_*/langlinks (ll_lang, ll_title);


CREATE TABLE /*_*/iwlinks (
  iwl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  iwl_prefix BLOB DEFAULT '' NOT NULL,
  iwl_title BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(iwl_from, iwl_prefix, iwl_title)
);

CREATE INDEX iwl_prefix_title_from ON /*_*/iwlinks (iwl_prefix, iwl_title, iwl_from);

CREATE INDEX iwl_prefix_from_title ON /*_*/iwlinks (iwl_prefix, iwl_from, iwl_title);


CREATE TABLE /*_*/category (
  cat_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  cat_title BLOB NOT NULL, cat_pages INTEGER DEFAULT 0 NOT NULL,
  cat_subcats INTEGER DEFAULT 0 NOT NULL,
  cat_files INTEGER DEFAULT 0 NOT NULL
);

CREATE UNIQUE INDEX cat_title ON /*_*/category (cat_title);

CREATE INDEX cat_pages ON /*_*/category (cat_pages);


CREATE TABLE /*_*/watchlist_expiry (
  we_item INTEGER UNSIGNED NOT NULL,
  we_expiry BLOB NOT NULL,
  PRIMARY KEY(we_item)
);

CREATE INDEX we_expiry ON /*_*/watchlist_expiry (we_expiry);


CREATE TABLE /*_*/change_tag_def (
  ctd_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  ctd_name BLOB NOT NULL, ctd_user_defined SMALLINT NOT NULL,
  ctd_count BIGINT UNSIGNED DEFAULT 0 NOT NULL
);

CREATE UNIQUE INDEX ctd_name ON /*_*/change_tag_def (ctd_name);

CREATE INDEX ctd_count ON /*_*/change_tag_def (ctd_count);

CREATE INDEX ctd_user_defined ON /*_*/change_tag_def (ctd_user_defined);


CREATE TABLE /*_*/ipblocks_restrictions (
  ir_ipb_id INTEGER NOT NULL,
  ir_type SMALLINT NOT NULL,
  ir_value INTEGER NOT NULL,
  PRIMARY KEY(ir_ipb_id, ir_type, ir_value)
);

CREATE INDEX ir_type_value ON /*_*/ipblocks_restrictions (ir_type, ir_value);


CREATE TABLE /*_*/querycache (
  qc_type BLOB NOT NULL, qc_value INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  qc_namespace INTEGER DEFAULT 0 NOT NULL,
  qc_title BLOB DEFAULT '' NOT NULL
);

CREATE INDEX qc_type ON /*_*/querycache (qc_type, qc_value);


CREATE TABLE /*_*/querycachetwo (
  qcc_type BLOB NOT NULL, qcc_value INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  qcc_namespace INTEGER DEFAULT 0 NOT NULL,
  qcc_title BLOB DEFAULT '' NOT NULL,
  qcc_namespacetwo INTEGER DEFAULT 0 NOT NULL,
  qcc_titletwo BLOB DEFAULT '' NOT NULL
);

CREATE INDEX qcc_type ON /*_*/querycachetwo (qcc_type, qcc_value);

CREATE INDEX qcc_title ON /*_*/querycachetwo (
  qcc_type, qcc_namespace, qcc_title
);

CREATE INDEX qcc_titletwo ON /*_*/querycachetwo (
  qcc_type, qcc_namespacetwo, qcc_titletwo
);


CREATE TABLE /*_*/page_restrictions (
  pr_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  pr_page INTEGER NOT NULL, pr_type BLOB NOT NULL,
  pr_level BLOB NOT NULL, pr_cascade SMALLINT NOT NULL,
  pr_user INTEGER UNSIGNED DEFAULT NULL,
  pr_expiry BLOB DEFAULT NULL
);

CREATE UNIQUE INDEX pr_pagetype ON /*_*/page_restrictions (pr_page, pr_type);

CREATE INDEX pr_typelevel ON /*_*/page_restrictions (pr_type, pr_level);

CREATE INDEX pr_level ON /*_*/page_restrictions (pr_level);

CREATE INDEX pr_cascade ON /*_*/page_restrictions (pr_cascade);
