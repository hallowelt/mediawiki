-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: maintenance/abstractSchemaChanges/patch-ipblocks_restrictions-ir_value.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
DROP  INDEX ir_type_value;
CREATE TEMPORARY TABLE /*_*/__temp__ipblocks_restrictions AS
SELECT  ir_ipb_id,  ir_type,  ir_value
FROM  /*_*/ipblocks_restrictions;
DROP  TABLE  /*_*/ipblocks_restrictions;
CREATE TABLE  /*_*/ipblocks_restrictions (    ir_ipb_id INTEGER NOT NULL,    ir_type SMALLINT NOT NULL,    ir_value INTEGER UNSIGNED NOT NULL,    PRIMARY KEY(ir_ipb_id, ir_type, ir_value)  );
INSERT INTO  /*_*/ipblocks_restrictions (ir_ipb_id, ir_type, ir_value)
SELECT  ir_ipb_id,  ir_type,  ir_value
FROM  /*_*/__temp__ipblocks_restrictions;
DROP  TABLE /*_*/__temp__ipblocks_restrictions;
CREATE INDEX ir_type_value ON  /*_*/ipblocks_restrictions (ir_type, ir_value);