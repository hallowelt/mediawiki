-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-ipblocks_restrictions-ir_ipb_id.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
ALTER TABLE /*_*/ipblocks_restrictions
  CHANGE ir_ipb_id ir_ipb_id INT UNSIGNED NOT NULL;
