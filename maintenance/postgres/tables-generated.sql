-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: maintenance/tables.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE site_identifiers (
  si_type TEXT NOT NULL,
  si_key TEXT NOT NULL,
  si_site INT NOT NULL,
  PRIMARY KEY(si_type, si_key)
);

CREATE INDEX si_site ON site_identifiers (si_site);

CREATE INDEX si_key ON site_identifiers (si_key);


CREATE TABLE updatelog (
  ul_key VARCHAR(255) NOT NULL,
  ul_value TEXT DEFAULT NULL,
  PRIMARY KEY(ul_key)
);


CREATE TABLE actor (
  actor_id BIGSERIAL NOT NULL,
  actor_user INT DEFAULT NULL,
  actor_name TEXT NOT NULL,
  PRIMARY KEY(actor_id)
);

CREATE UNIQUE INDEX actor_user ON actor (actor_user);

CREATE UNIQUE INDEX actor_name ON actor (actor_name);


CREATE TABLE user_former_groups (
  ufg_user INT DEFAULT 0 NOT NULL,
  ufg_group TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(ufg_user, ufg_group)
);


CREATE TABLE bot_passwords (
  bp_user INT NOT NULL,
  bp_app_id TEXT NOT NULL,
  bp_password TEXT NOT NULL,
  bp_token TEXT DEFAULT '' NOT NULL,
  bp_restrictions TEXT NOT NULL,
  bp_grants TEXT NOT NULL,
  PRIMARY KEY(bp_user, bp_app_id)
);


CREATE TABLE comment (
  comment_id BIGSERIAL NOT NULL,
  comment_hash INT NOT NULL,
  comment_text TEXT NOT NULL,
  comment_data TEXT DEFAULT NULL,
  PRIMARY KEY(comment_id)
);

CREATE INDEX comment_hash ON comment (comment_hash);


CREATE TABLE slots (
  slot_revision_id BIGINT NOT NULL,
  slot_role_id SMALLINT NOT NULL,
  slot_content_id BIGINT NOT NULL,
  slot_origin BIGINT NOT NULL,
  PRIMARY KEY(slot_revision_id, slot_role_id)
);

CREATE INDEX slot_revision_origin_role ON slots (
  slot_revision_id, slot_origin, slot_role_id
);


CREATE TABLE site_stats (
  ss_row_id INT NOT NULL,
  ss_total_edits BIGINT DEFAULT NULL,
  ss_good_articles BIGINT DEFAULT NULL,
  ss_total_pages BIGINT DEFAULT NULL,
  ss_users BIGINT DEFAULT NULL,
  ss_active_users BIGINT DEFAULT NULL,
  ss_images BIGINT DEFAULT NULL,
  PRIMARY KEY(ss_row_id)
);


CREATE TABLE user_properties (
  up_user INT NOT NULL,
  up_property TEXT NOT NULL,
  up_value TEXT DEFAULT NULL,
  PRIMARY KEY(up_user, up_property)
);

CREATE INDEX up_property ON user_properties (up_property);


CREATE TABLE log_search (
  ls_field TEXT NOT NULL,
  ls_value VARCHAR(255) NOT NULL,
  ls_log_id INT DEFAULT 0 NOT NULL,
  PRIMARY KEY(ls_field, ls_value, ls_log_id)
);

CREATE INDEX ls_log_id ON log_search (ls_log_id);


CREATE TABLE change_tag (
  ct_id SERIAL NOT NULL,
  ct_rc_id INT DEFAULT NULL,
  ct_log_id INT DEFAULT NULL,
  ct_rev_id INT DEFAULT NULL,
  ct_params TEXT DEFAULT NULL,
  ct_tag_id INT NOT NULL,
  PRIMARY KEY(ct_id)
);

CREATE UNIQUE INDEX ct_rc_tag_id ON change_tag (ct_rc_id, ct_tag_id);

CREATE UNIQUE INDEX ct_log_tag_id ON change_tag (ct_log_id, ct_tag_id);

CREATE UNIQUE INDEX ct_rev_tag_id ON change_tag (ct_rev_id, ct_tag_id);

CREATE INDEX ct_tag_id_id ON change_tag (
  ct_tag_id, ct_rc_id, ct_rev_id, ct_log_id
);


CREATE TABLE content (
  content_id BIGSERIAL NOT NULL,
  content_size INT NOT NULL,
  content_sha1 TEXT NOT NULL,
  content_model SMALLINT NOT NULL,
  content_address TEXT NOT NULL,
  PRIMARY KEY(content_id)
);


CREATE TABLE l10n_cache (
  lc_lang TEXT NOT NULL,
  lc_key VARCHAR(255) NOT NULL,
  lc_value TEXT NOT NULL,
  PRIMARY KEY(lc_lang, lc_key)
);


CREATE TABLE module_deps (
  md_module TEXT NOT NULL,
  md_skin TEXT NOT NULL,
  md_deps TEXT NOT NULL,
  PRIMARY KEY(md_module, md_skin)
);


CREATE TABLE redirect (
  rd_from INT DEFAULT 0 NOT NULL,
  rd_namespace INT DEFAULT 0 NOT NULL,
  rd_title TEXT DEFAULT '' NOT NULL,
  rd_interwiki VARCHAR(32) DEFAULT NULL,
  rd_fragment TEXT DEFAULT NULL,
  PRIMARY KEY(rd_from)
);

CREATE INDEX rd_ns_title ON redirect (rd_namespace, rd_title, rd_from);


CREATE TABLE pagelinks (
  pl_from INT DEFAULT 0 NOT NULL,
  pl_namespace INT DEFAULT 0 NOT NULL,
  pl_title TEXT DEFAULT '' NOT NULL,
  pl_from_namespace INT DEFAULT 0 NOT NULL,
  pl_target_id BIGINT DEFAULT NULL,
  PRIMARY KEY(pl_from, pl_namespace, pl_title)
);

CREATE INDEX pl_namespace ON pagelinks (pl_namespace, pl_title, pl_from);

CREATE INDEX pl_backlinks_namespace ON pagelinks (
  pl_from_namespace, pl_namespace,
  pl_title, pl_from
);

CREATE INDEX pl_target_id ON pagelinks (pl_target_id, pl_from);

CREATE INDEX pl_backlinks_namespace_target_id ON pagelinks (
  pl_from_namespace, pl_target_id,
  pl_from
);


CREATE TABLE templatelinks (
  tl_from INT DEFAULT 0 NOT NULL,
  tl_target_id BIGINT NOT NULL,
  tl_from_namespace INT DEFAULT 0 NOT NULL,
  PRIMARY KEY(tl_from, tl_target_id)
);

CREATE INDEX tl_target_id ON templatelinks (tl_target_id, tl_from);

CREATE INDEX tl_backlinks_namespace_target_id ON templatelinks (
  tl_from_namespace, tl_target_id,
  tl_from
);


CREATE TABLE imagelinks (
  il_from INT DEFAULT 0 NOT NULL,
  il_to TEXT DEFAULT '' NOT NULL,
  il_from_namespace INT DEFAULT 0 NOT NULL,
  PRIMARY KEY(il_from, il_to)
);

CREATE INDEX il_to ON imagelinks (il_to, il_from);

CREATE INDEX il_backlinks_namespace ON imagelinks (
  il_from_namespace, il_to, il_from
);


CREATE TABLE langlinks (
  ll_from INT DEFAULT 0 NOT NULL,
  ll_lang TEXT DEFAULT '' NOT NULL,
  ll_title TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(ll_from, ll_lang)
);

CREATE INDEX ll_lang ON langlinks (ll_lang, ll_title);


CREATE TABLE iwlinks (
  iwl_from INT DEFAULT 0 NOT NULL,
  iwl_prefix TEXT DEFAULT '' NOT NULL,
  iwl_title TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(iwl_from, iwl_prefix, iwl_title)
);

CREATE INDEX iwl_prefix_title_from ON iwlinks (iwl_prefix, iwl_title, iwl_from);

CREATE INDEX iwl_prefix_from_title ON iwlinks (iwl_prefix, iwl_from, iwl_title);


CREATE TABLE category (
  cat_id SERIAL NOT NULL,
  cat_title TEXT NOT NULL,
  cat_pages INT DEFAULT 0 NOT NULL,
  cat_subcats INT DEFAULT 0 NOT NULL,
  cat_files INT DEFAULT 0 NOT NULL,
  PRIMARY KEY(cat_id)
);

CREATE UNIQUE INDEX cat_title ON category (cat_title);

CREATE INDEX cat_pages ON category (cat_pages);


CREATE TABLE watchlist_expiry (
  we_item INT NOT NULL,
  we_expiry TIMESTAMPTZ NOT NULL,
  PRIMARY KEY(we_item)
);

CREATE INDEX we_expiry ON watchlist_expiry (we_expiry);


CREATE TABLE change_tag_def (
  ctd_id SERIAL NOT NULL,
  ctd_name TEXT NOT NULL,
  ctd_user_defined SMALLINT NOT NULL,
  ctd_count BIGINT DEFAULT 0 NOT NULL,
  PRIMARY KEY(ctd_id)
);

CREATE UNIQUE INDEX ctd_name ON change_tag_def (ctd_name);

CREATE INDEX ctd_count ON change_tag_def (ctd_count);

CREATE INDEX ctd_user_defined ON change_tag_def (ctd_user_defined);


CREATE TABLE ipblocks_restrictions (
  ir_ipb_id INT NOT NULL,
  ir_type SMALLINT NOT NULL,
  ir_value INT NOT NULL,
  PRIMARY KEY(ir_ipb_id, ir_type, ir_value)
);

CREATE INDEX ir_type_value ON ipblocks_restrictions (ir_type, ir_value);


CREATE TABLE querycache (
  qc_type TEXT NOT NULL, qc_value INT DEFAULT 0 NOT NULL,
  qc_namespace INT DEFAULT 0 NOT NULL,
  qc_title TEXT DEFAULT '' NOT NULL
);

CREATE INDEX qc_type ON querycache (qc_type, qc_value);


CREATE TABLE querycachetwo (
  qcc_type TEXT NOT NULL, qcc_value INT DEFAULT 0 NOT NULL,
  qcc_namespace INT DEFAULT 0 NOT NULL,
  qcc_title TEXT DEFAULT '' NOT NULL,
  qcc_namespacetwo INT DEFAULT 0 NOT NULL,
  qcc_titletwo TEXT DEFAULT '' NOT NULL
);

CREATE INDEX qcc_type ON querycachetwo (qcc_type, qcc_value);

CREATE INDEX qcc_title ON querycachetwo (
  qcc_type, qcc_namespace, qcc_title
);

CREATE INDEX qcc_titletwo ON querycachetwo (
  qcc_type, qcc_namespacetwo, qcc_titletwo
);


CREATE TABLE page_restrictions (
  pr_id SERIAL NOT NULL,
  pr_page INT NOT NULL,
  pr_type TEXT NOT NULL,
  pr_level TEXT NOT NULL,
  pr_cascade SMALLINT NOT NULL,
  pr_expiry TIMESTAMPTZ DEFAULT NULL,
  PRIMARY KEY(pr_id)
);

CREATE UNIQUE INDEX pr_pagetype ON page_restrictions (pr_page, pr_type);

CREATE INDEX pr_typelevel ON page_restrictions (pr_type, pr_level);

CREATE INDEX pr_level ON page_restrictions (pr_level);

CREATE INDEX pr_cascade ON page_restrictions (pr_cascade);


CREATE TABLE user_groups (
  ug_user INT DEFAULT 0 NOT NULL,
  ug_group TEXT DEFAULT '' NOT NULL,
  ug_expiry TIMESTAMPTZ DEFAULT NULL,
  PRIMARY KEY(ug_user, ug_group)
);

CREATE INDEX ug_group ON user_groups (ug_group);

CREATE INDEX ug_expiry ON user_groups (ug_expiry);


CREATE TABLE querycache_info (
  qci_type TEXT DEFAULT '' NOT NULL,
  qci_timestamp TIMESTAMPTZ DEFAULT '1970-01-01 00:00:00+00' NOT NULL,
  PRIMARY KEY(qci_type)
);


CREATE TABLE watchlist (
  wl_id SERIAL NOT NULL,
  wl_user INT NOT NULL,
  wl_namespace INT DEFAULT 0 NOT NULL,
  wl_title TEXT DEFAULT '' NOT NULL,
  wl_notificationtimestamp TIMESTAMPTZ DEFAULT NULL,
  PRIMARY KEY(wl_id)
);

CREATE UNIQUE INDEX wl_user ON watchlist (wl_user, wl_namespace, wl_title);

CREATE INDEX wl_namespace_title ON watchlist (wl_namespace, wl_title);

CREATE INDEX wl_user_notificationtimestamp ON watchlist (
  wl_user, wl_notificationtimestamp
);


CREATE TABLE sites (
  site_id SERIAL NOT NULL,
  site_global_key TEXT NOT NULL,
  site_type TEXT NOT NULL,
  site_group TEXT NOT NULL,
  site_source TEXT NOT NULL,
  site_language TEXT NOT NULL,
  site_protocol TEXT NOT NULL,
  site_domain VARCHAR(255) NOT NULL,
  site_data TEXT NOT NULL,
  site_forward SMALLINT NOT NULL,
  site_config TEXT NOT NULL,
  PRIMARY KEY(site_id)
);

CREATE UNIQUE INDEX site_global_key ON sites (site_global_key);

CREATE INDEX site_type ON sites (site_type);

CREATE INDEX site_group ON sites (site_group);

CREATE INDEX site_source ON sites (site_source);

CREATE INDEX site_language ON sites (site_language);

CREATE INDEX site_protocol ON sites (site_protocol);

CREATE INDEX site_domain ON sites (site_domain);

CREATE INDEX site_forward ON sites (site_forward);


CREATE TABLE user_newtalk (
  user_id INT DEFAULT 0 NOT NULL, user_ip TEXT DEFAULT '' NOT NULL,
  user_last_timestamp TIMESTAMPTZ DEFAULT NULL
);

CREATE INDEX un_user_id ON user_newtalk (user_id);

CREATE INDEX un_user_ip ON user_newtalk (user_ip);


CREATE TABLE interwiki (
  iw_prefix VARCHAR(32) NOT NULL,
  iw_url TEXT NOT NULL,
  iw_api TEXT NOT NULL,
  iw_wikiid VARCHAR(64) NOT NULL,
  iw_local SMALLINT NOT NULL,
  iw_trans SMALLINT DEFAULT 0 NOT NULL,
  PRIMARY KEY(iw_prefix)
);


CREATE TABLE protected_titles (
  pt_namespace INT NOT NULL,
  pt_title TEXT NOT NULL,
  pt_user INT NOT NULL,
  pt_reason_id BIGINT NOT NULL,
  pt_timestamp TIMESTAMPTZ NOT NULL,
  pt_expiry TIMESTAMPTZ NOT NULL,
  pt_create_perm TEXT NOT NULL,
  PRIMARY KEY(pt_namespace, pt_title)
);

CREATE INDEX pt_timestamp ON protected_titles (pt_timestamp);


CREATE TABLE externallinks (
  el_id SERIAL NOT NULL,
  el_from INT DEFAULT 0 NOT NULL,
  el_to_domain_index TEXT DEFAULT '' NOT NULL,
  el_to_path TEXT DEFAULT NULL,
  PRIMARY KEY(el_id)
);

CREATE INDEX el_from ON externallinks (el_from);

CREATE INDEX el_to_domain_index_to_path ON externallinks (el_to_domain_index, el_to_path);


CREATE TABLE ip_changes (
  ipc_rev_id INT DEFAULT 0 NOT NULL,
  ipc_rev_timestamp TIMESTAMPTZ NOT NULL,
  ipc_hex TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(ipc_rev_id)
);

CREATE INDEX ipc_rev_timestamp ON ip_changes (ipc_rev_timestamp);

CREATE INDEX ipc_hex_time ON ip_changes (ipc_hex, ipc_rev_timestamp);


CREATE TABLE page_props (
  pp_page INT NOT NULL,
  pp_propname TEXT NOT NULL,
  pp_value TEXT NOT NULL,
  pp_sortkey FLOAT DEFAULT NULL,
  PRIMARY KEY(pp_page, pp_propname)
);

CREATE UNIQUE INDEX pp_propname_page ON page_props (pp_propname, pp_page);

CREATE UNIQUE INDEX pp_propname_sortkey_page ON page_props (pp_propname, pp_sortkey, pp_page)
WHERE (pp_sortkey IS NOT NULL);


CREATE TABLE job (
  job_id SERIAL NOT NULL,
  job_cmd TEXT DEFAULT '' NOT NULL,
  job_namespace INT NOT NULL,
  job_title TEXT NOT NULL,
  job_timestamp TIMESTAMPTZ DEFAULT NULL,
  job_params TEXT NOT NULL,
  job_random INT DEFAULT 0 NOT NULL,
  job_attempts INT DEFAULT 0 NOT NULL,
  job_token TEXT DEFAULT '' NOT NULL,
  job_token_timestamp TIMESTAMPTZ DEFAULT NULL,
  job_sha1 TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(job_id)
);

CREATE INDEX job_sha1 ON job (job_sha1);

CREATE INDEX job_cmd_token ON job (job_cmd, job_token, job_random);

CREATE INDEX job_cmd_token_id ON job (job_cmd, job_token, job_id);

CREATE INDEX job_cmd ON job (
  job_cmd, job_namespace, job_title,
  job_params
);

CREATE INDEX job_timestamp ON job (job_timestamp);


CREATE TABLE slot_roles (
  role_id SERIAL NOT NULL,
  role_name TEXT NOT NULL,
  PRIMARY KEY(role_id)
);

CREATE UNIQUE INDEX role_name ON slot_roles (role_name);


CREATE TABLE content_models (
  model_id SERIAL NOT NULL,
  model_name TEXT NOT NULL,
  PRIMARY KEY(model_id)
);

CREATE UNIQUE INDEX model_name ON content_models (model_name);


CREATE TABLE categorylinks (
  cl_from INT DEFAULT 0 NOT NULL,
  cl_to TEXT DEFAULT '' NOT NULL,
  cl_sortkey TEXT DEFAULT '' NOT NULL,
  cl_sortkey_prefix TEXT DEFAULT '' NOT NULL,
  cl_timestamp TIMESTAMPTZ NOT NULL,
  cl_collation TEXT DEFAULT '' NOT NULL,
  cl_type TEXT DEFAULT 'page' NOT NULL,
  PRIMARY KEY(cl_from, cl_to)
);

CREATE INDEX cl_sortkey ON categorylinks (
  cl_to, cl_type, cl_sortkey, cl_from
);

CREATE INDEX cl_timestamp ON categorylinks (cl_to, cl_timestamp);

CREATE INDEX cl_collation_ext ON categorylinks (
  cl_collation, cl_to, cl_type, cl_from
);


CREATE TABLE logging (
  log_id SERIAL NOT NULL,
  log_type TEXT DEFAULT '' NOT NULL,
  log_action TEXT DEFAULT '' NOT NULL,
  log_timestamp TIMESTAMPTZ DEFAULT '1970-01-01 00:00:00+00' NOT NULL,
  log_actor BIGINT NOT NULL,
  log_namespace INT DEFAULT 0 NOT NULL,
  log_title TEXT DEFAULT '' NOT NULL,
  log_page INT DEFAULT NULL,
  log_comment_id BIGINT NOT NULL,
  log_params TEXT NOT NULL,
  log_deleted SMALLINT DEFAULT 0 NOT NULL,
  PRIMARY KEY(log_id)
);

CREATE INDEX log_type_time ON logging (log_type, log_timestamp);

CREATE INDEX log_actor_time ON logging (log_actor, log_timestamp);

CREATE INDEX log_page_time ON logging (
  log_namespace, log_title, log_timestamp
);

CREATE INDEX log_times ON logging (log_timestamp);

CREATE INDEX log_actor_type_time ON logging (
  log_actor, log_type, log_timestamp
);

CREATE INDEX log_page_id_time ON logging (log_page, log_timestamp);

CREATE INDEX log_type_action ON logging (
  log_type, log_action, log_timestamp
);

CREATE TYPE US_MEDIA_TYPE_ENUM AS ENUM(
  'UNKNOWN', 'BITMAP', 'DRAWING', 'AUDIO',
  'VIDEO', 'MULTIMEDIA', 'OFFICE',
  'TEXT', 'EXECUTABLE', 'ARCHIVE',
  '3D'
);


CREATE TABLE uploadstash (
  us_id SERIAL NOT NULL,
  us_user INT NOT NULL,
  us_key VARCHAR(255) NOT NULL,
  us_orig_path VARCHAR(255) NOT NULL,
  us_path VARCHAR(255) NOT NULL,
  us_source_type VARCHAR(50) DEFAULT NULL,
  us_timestamp TIMESTAMPTZ NOT NULL,
  us_status VARCHAR(50) NOT NULL,
  us_chunk_inx INT DEFAULT NULL,
  us_props TEXT DEFAULT NULL,
  us_size BIGINT NOT NULL,
  us_sha1 VARCHAR(31) NOT NULL,
  us_mime VARCHAR(255) DEFAULT NULL,
  us_media_type US_MEDIA_TYPE_ENUM DEFAULT NULL,
  us_image_width INT DEFAULT NULL,
  us_image_height INT DEFAULT NULL,
  us_image_bits SMALLINT DEFAULT NULL,
  PRIMARY KEY(us_id)
);

CREATE INDEX us_user ON uploadstash (us_user);

CREATE UNIQUE INDEX us_key ON uploadstash (us_key);

CREATE INDEX us_timestamp ON uploadstash (us_timestamp);


CREATE TABLE filearchive (
  fa_id SERIAL NOT NULL,
  fa_name TEXT DEFAULT '' NOT NULL,
  fa_archive_name TEXT DEFAULT '',
  fa_storage_group TEXT DEFAULT NULL,
  fa_storage_key TEXT DEFAULT '',
  fa_deleted_user INT DEFAULT NULL,
  fa_deleted_timestamp TIMESTAMPTZ DEFAULT NULL,
  fa_deleted_reason_id BIGINT NOT NULL,
  fa_size BIGINT DEFAULT 0,
  fa_width INT DEFAULT 0,
  fa_height INT DEFAULT 0,
  fa_metadata TEXT DEFAULT NULL,
  fa_bits INT DEFAULT 0,
  fa_media_type TEXT DEFAULT NULL,
  fa_major_mime TEXT DEFAULT 'unknown',
  fa_minor_mime TEXT DEFAULT 'unknown',
  fa_description_id BIGINT NOT NULL,
  fa_actor BIGINT NOT NULL,
  fa_timestamp TIMESTAMPTZ DEFAULT NULL,
  fa_deleted SMALLINT DEFAULT 0 NOT NULL,
  fa_sha1 TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(fa_id)
);

CREATE INDEX fa_name ON filearchive (fa_name, fa_timestamp);

CREATE INDEX fa_storage_group ON filearchive (
  fa_storage_group, fa_storage_key
);

CREATE INDEX fa_deleted_timestamp ON filearchive (fa_deleted_timestamp);

CREATE INDEX fa_actor_timestamp ON filearchive (fa_actor, fa_timestamp);

CREATE INDEX fa_sha1 ON filearchive (fa_sha1);


CREATE TABLE text (
  old_id SERIAL NOT NULL,
  old_text TEXT NOT NULL,
  old_flags TEXT NOT NULL,
  PRIMARY KEY(old_id)
);


CREATE TABLE oldimage (
  oi_name TEXT DEFAULT '' NOT NULL, oi_archive_name TEXT DEFAULT '' NOT NULL,
  oi_size BIGINT DEFAULT 0 NOT NULL, oi_width INT DEFAULT 0 NOT NULL,
  oi_height INT DEFAULT 0 NOT NULL, oi_bits INT DEFAULT 0 NOT NULL,
  oi_description_id BIGINT NOT NULL,
  oi_actor BIGINT NOT NULL, oi_timestamp TIMESTAMPTZ NOT NULL,
  oi_metadata TEXT NOT NULL, oi_media_type TEXT DEFAULT NULL,
  oi_major_mime TEXT DEFAULT 'unknown' NOT NULL,
  oi_minor_mime TEXT DEFAULT 'unknown' NOT NULL,
  oi_deleted SMALLINT DEFAULT 0 NOT NULL,
  oi_sha1 TEXT DEFAULT '' NOT NULL
);

CREATE INDEX oi_actor_timestamp ON oldimage (oi_actor, oi_timestamp);

CREATE INDEX oi_name_timestamp ON oldimage (oi_name, oi_timestamp);

CREATE INDEX oi_name_archive_name ON oldimage (oi_name, oi_archive_name);

CREATE INDEX oi_sha1 ON oldimage (oi_sha1);

CREATE INDEX oi_timestamp ON oldimage (oi_timestamp);


CREATE TABLE objectcache (
  keyname TEXT DEFAULT '' NOT NULL,
  value TEXT DEFAULT NULL,
  exptime TIMESTAMPTZ NOT NULL,
  modtoken VARCHAR(17) DEFAULT '00000000000000000' NOT NULL,
  flags INT DEFAULT NULL,
  PRIMARY KEY(keyname)
);

CREATE INDEX exptime ON objectcache (exptime);


CREATE TABLE ipblocks (
  ipb_id SERIAL NOT NULL,
  ipb_address TEXT NOT NULL,
  ipb_user INT DEFAULT 0 NOT NULL,
  ipb_by_actor BIGINT NOT NULL,
  ipb_reason_id BIGINT NOT NULL,
  ipb_timestamp TIMESTAMPTZ NOT NULL,
  ipb_auto SMALLINT DEFAULT 0 NOT NULL,
  ipb_anon_only SMALLINT DEFAULT 0 NOT NULL,
  ipb_create_account SMALLINT DEFAULT 1 NOT NULL,
  ipb_enable_autoblock SMALLINT DEFAULT 1 NOT NULL,
  ipb_expiry TIMESTAMPTZ NOT NULL,
  ipb_range_start TEXT NOT NULL,
  ipb_range_end TEXT NOT NULL,
  ipb_deleted SMALLINT DEFAULT 0 NOT NULL,
  ipb_block_email SMALLINT DEFAULT 0 NOT NULL,
  ipb_allow_usertalk SMALLINT DEFAULT 0 NOT NULL,
  ipb_parent_block_id INT DEFAULT NULL,
  ipb_sitewide SMALLINT DEFAULT 1 NOT NULL,
  PRIMARY KEY(ipb_id)
);

CREATE UNIQUE INDEX ipb_address_unique ON ipblocks (ipb_address, ipb_user, ipb_auto);

CREATE INDEX ipb_user ON ipblocks (ipb_user);

CREATE INDEX ipb_range ON ipblocks (ipb_range_start, ipb_range_end);

CREATE INDEX ipb_timestamp ON ipblocks (ipb_timestamp);

CREATE INDEX ipb_expiry ON ipblocks (ipb_expiry);

CREATE INDEX ipb_parent_block_id ON ipblocks (ipb_parent_block_id);


CREATE TABLE image (
  img_name TEXT DEFAULT '' NOT NULL,
  img_size BIGINT DEFAULT 0 NOT NULL,
  img_width INT DEFAULT 0 NOT NULL,
  img_height INT DEFAULT 0 NOT NULL,
  img_metadata TEXT NOT NULL,
  img_bits INT DEFAULT 0 NOT NULL,
  img_media_type TEXT DEFAULT NULL,
  img_major_mime TEXT DEFAULT 'unknown' NOT NULL,
  img_minor_mime TEXT DEFAULT 'unknown' NOT NULL,
  img_description_id BIGINT NOT NULL,
  img_actor BIGINT NOT NULL,
  img_timestamp TIMESTAMPTZ NOT NULL,
  img_sha1 TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(img_name)
);

CREATE INDEX img_actor_timestamp ON image (img_actor, img_timestamp);

CREATE INDEX img_size ON image (img_size);

CREATE INDEX img_timestamp ON image (img_timestamp);

CREATE INDEX img_sha1 ON image (img_sha1);

CREATE INDEX img_media_mime ON image (
  img_media_type, img_major_mime, img_minor_mime
);


CREATE TABLE recentchanges (
  rc_id SERIAL NOT NULL,
  rc_timestamp TIMESTAMPTZ NOT NULL,
  rc_actor BIGINT NOT NULL,
  rc_namespace INT DEFAULT 0 NOT NULL,
  rc_title TEXT DEFAULT '' NOT NULL,
  rc_comment_id BIGINT NOT NULL,
  rc_minor SMALLINT DEFAULT 0 NOT NULL,
  rc_bot SMALLINT DEFAULT 0 NOT NULL,
  rc_new SMALLINT DEFAULT 0 NOT NULL,
  rc_cur_id INT DEFAULT 0 NOT NULL,
  rc_this_oldid INT DEFAULT 0 NOT NULL,
  rc_last_oldid INT DEFAULT 0 NOT NULL,
  rc_type SMALLINT DEFAULT 0 NOT NULL,
  rc_source TEXT DEFAULT '' NOT NULL,
  rc_patrolled SMALLINT DEFAULT 0 NOT NULL,
  rc_ip TEXT DEFAULT '' NOT NULL,
  rc_old_len INT DEFAULT NULL,
  rc_new_len INT DEFAULT NULL,
  rc_deleted SMALLINT DEFAULT 0 NOT NULL,
  rc_logid INT DEFAULT 0 NOT NULL,
  rc_log_type TEXT DEFAULT NULL,
  rc_log_action TEXT DEFAULT NULL,
  rc_params TEXT DEFAULT NULL,
  PRIMARY KEY(rc_id)
);

CREATE INDEX rc_timestamp ON recentchanges (rc_timestamp);

CREATE INDEX rc_namespace_title_timestamp ON recentchanges (
  rc_namespace, rc_title, rc_timestamp
);

CREATE INDEX rc_cur_id ON recentchanges (rc_cur_id);

CREATE INDEX rc_new_name_timestamp ON recentchanges (
  rc_new, rc_namespace, rc_timestamp
);

CREATE INDEX rc_ip ON recentchanges (rc_ip);

CREATE INDEX rc_ns_actor ON recentchanges (rc_namespace, rc_actor);

CREATE INDEX rc_actor ON recentchanges (rc_actor, rc_timestamp);

CREATE INDEX rc_name_type_patrolled_timestamp ON recentchanges (
  rc_namespace, rc_type, rc_patrolled,
  rc_timestamp
);

CREATE INDEX rc_this_oldid ON recentchanges (rc_this_oldid);


CREATE TABLE archive (
  ar_id SERIAL NOT NULL,
  ar_namespace INT DEFAULT 0 NOT NULL,
  ar_title TEXT DEFAULT '' NOT NULL,
  ar_comment_id BIGINT NOT NULL,
  ar_actor BIGINT NOT NULL,
  ar_timestamp TIMESTAMPTZ NOT NULL,
  ar_minor_edit SMALLINT DEFAULT 0 NOT NULL,
  ar_rev_id INT NOT NULL,
  ar_deleted SMALLINT DEFAULT 0 NOT NULL,
  ar_len INT DEFAULT NULL,
  ar_page_id INT DEFAULT NULL,
  ar_parent_id INT DEFAULT NULL,
  ar_sha1 TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(ar_id)
);

CREATE INDEX ar_name_title_timestamp ON archive (
  ar_namespace, ar_title, ar_timestamp
);

CREATE INDEX ar_actor_timestamp ON archive (ar_actor, ar_timestamp);

CREATE UNIQUE INDEX ar_revid_uniq ON archive (ar_rev_id);


CREATE TABLE page (
  page_id SERIAL NOT NULL,
  page_namespace INT NOT NULL,
  page_title TEXT NOT NULL,
  page_is_redirect SMALLINT DEFAULT 0 NOT NULL,
  page_is_new SMALLINT DEFAULT 0 NOT NULL,
  page_random FLOAT NOT NULL,
  page_touched TIMESTAMPTZ NOT NULL,
  page_links_updated TIMESTAMPTZ DEFAULT NULL,
  page_latest INT NOT NULL,
  page_len INT NOT NULL,
  page_content_model TEXT DEFAULT NULL,
  page_lang TEXT DEFAULT NULL,
  PRIMARY KEY(page_id)
);

CREATE UNIQUE INDEX page_name_title ON page (page_namespace, page_title);

CREATE INDEX page_random ON page (page_random);

CREATE INDEX page_len ON page (page_len);

CREATE INDEX page_redirect_namespace_len ON page (
  page_is_redirect, page_namespace,
  page_len
);


CREATE TABLE "user" (
  user_id SERIAL NOT NULL,
  user_name TEXT DEFAULT '' NOT NULL,
  user_real_name TEXT DEFAULT '' NOT NULL,
  user_password TEXT NOT NULL,
  user_newpassword TEXT NOT NULL,
  user_newpass_time TIMESTAMPTZ DEFAULT NULL,
  user_email TEXT NOT NULL,
  user_touched TIMESTAMPTZ NOT NULL,
  user_token TEXT DEFAULT '' NOT NULL,
  user_email_authenticated TIMESTAMPTZ DEFAULT NULL,
  user_email_token TEXT DEFAULT NULL,
  user_email_token_expires TIMESTAMPTZ DEFAULT NULL,
  user_registration TIMESTAMPTZ DEFAULT NULL,
  user_editcount INT DEFAULT NULL,
  user_password_expires TIMESTAMPTZ DEFAULT NULL,
  user_is_temp SMALLINT DEFAULT 0 NOT NULL,
  PRIMARY KEY(user_id)
);

CREATE UNIQUE INDEX user_name ON "user" (user_name);

CREATE INDEX user_email_token ON "user" (user_email_token);

CREATE INDEX user_email ON "user" (user_email);


CREATE TABLE user_autocreate_serial (
  uas_shard INT NOT NULL,
  uas_value INT NOT NULL,
  PRIMARY KEY(uas_shard)
);


CREATE TABLE revision (
  rev_id SERIAL NOT NULL,
  rev_page INT NOT NULL,
  rev_comment_id BIGINT DEFAULT 0 NOT NULL,
  rev_actor BIGINT DEFAULT 0 NOT NULL,
  rev_timestamp TIMESTAMPTZ NOT NULL,
  rev_minor_edit SMALLINT DEFAULT 0 NOT NULL,
  rev_deleted SMALLINT DEFAULT 0 NOT NULL,
  rev_len INT DEFAULT NULL,
  rev_parent_id INT DEFAULT NULL,
  rev_sha1 TEXT DEFAULT '' NOT NULL,
  PRIMARY KEY(rev_id)
);

CREATE INDEX rev_timestamp ON revision (rev_timestamp);

CREATE INDEX rev_page_timestamp ON revision (rev_page, rev_timestamp);

CREATE INDEX rev_actor_timestamp ON revision (rev_actor, rev_timestamp, rev_id);

CREATE INDEX rev_page_actor_timestamp ON revision (
  rev_page, rev_actor, rev_timestamp
);


CREATE TABLE searchindex (
  si_page INT NOT NULL,
  si_title VARCHAR(255) DEFAULT '' NOT NULL,
  si_text TEXT NOT NULL
);

CREATE UNIQUE INDEX si_page ON searchindex (si_page);

CREATE INDEX si_title ON searchindex (si_title);

CREATE INDEX si_text ON searchindex (si_text);


CREATE TABLE linktarget (
  lt_id BIGSERIAL NOT NULL,
  lt_namespace INT NOT NULL,
  lt_title TEXT NOT NULL,
  PRIMARY KEY(lt_id)
);

CREATE UNIQUE INDEX lt_namespace_title ON linktarget (lt_namespace, lt_title);
