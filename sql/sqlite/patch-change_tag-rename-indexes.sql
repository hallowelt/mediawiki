-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-change_tag-rename-indexes.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TEMPORARY TABLE /*_*/__temp__change_tag AS
SELECT
  ct_id,
  ct_rc_id,
  ct_log_id,
  ct_rev_id,
  ct_params,
  ct_tag_id
FROM /*_*/change_tag;
DROP TABLE /*_*/change_tag;


CREATE TABLE /*_*/change_tag (
    ct_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    ct_rc_id INTEGER UNSIGNED DEFAULT NULL,
    ct_log_id INTEGER UNSIGNED DEFAULT NULL,
    ct_rev_id INTEGER UNSIGNED DEFAULT NULL,
    ct_params BLOB DEFAULT NULL, ct_tag_id INTEGER UNSIGNED NOT NULL
  );
INSERT INTO /*_*/change_tag (
    ct_id, ct_rc_id, ct_log_id, ct_rev_id,
    ct_params, ct_tag_id
  )
SELECT
  ct_id,
  ct_rc_id,
  ct_log_id,
  ct_rev_id,
  ct_params,
  ct_tag_id
FROM
  /*_*/__temp__change_tag;
DROP TABLE /*_*/__temp__change_tag;

CREATE UNIQUE INDEX ct_rc_tag_id ON /*_*/change_tag (ct_rc_id, ct_tag_id);

CREATE UNIQUE INDEX ct_log_tag_id ON /*_*/change_tag (ct_log_id, ct_tag_id);

CREATE UNIQUE INDEX ct_rev_tag_id ON /*_*/change_tag (ct_rev_id, ct_tag_id);

CREATE INDEX ct_tag_id_id ON /*_*/change_tag (
    ct_tag_id, ct_rc_id, ct_rev_id, ct_log_id
  );
