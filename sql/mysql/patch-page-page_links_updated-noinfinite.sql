-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-page-page_links_updated-noinfinite.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
ALTER TABLE /*_*/page
  CHANGE page_links_updated page_links_updated BINARY(14) DEFAULT NULL;
