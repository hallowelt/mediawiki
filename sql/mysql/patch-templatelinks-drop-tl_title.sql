-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-templatelinks-drop-tl_title.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
DROP INDEX tl_namespace ON /*_*/templatelinks;
DROP INDEX tl_backlinks_namespace ON /*_*/templatelinks;
ALTER TABLE /*_*/templatelinks
  DROP tl_namespace,
  DROP tl_title;
