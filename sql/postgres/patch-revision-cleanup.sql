-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-revision-cleanup.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
ALTER TABLE revision
  ALTER rev_id TYPE BIGINT;

ALTER TABLE revision
  ALTER rev_comment_id
  DROP DEFAULT;

ALTER TABLE revision
  ALTER rev_actor
  DROP DEFAULT;

ALTER TABLE revision
  ALTER rev_parent_id TYPE BIGINT;
