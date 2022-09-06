-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: maintenance/abstractSchemaChanges/patch-templatelinks-drop-tl_title.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
DROP  INDEX tl_namespace;
DROP  INDEX tl_backlinks_namespace;
DROP  INDEX tl_target_id;
DROP  INDEX tl_backlinks_namespace_target_id;
CREATE TEMPORARY TABLE /*_*/__temp__templatelinks AS
SELECT  tl_from,  tl_target_id,  tl_from_namespace
FROM  /*_*/templatelinks;
DROP  TABLE  /*_*/templatelinks;
CREATE TABLE  /*_*/templatelinks (    tl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,    tl_target_id BIGINT UNSIGNED NOT NULL,    tl_from_namespace INTEGER DEFAULT 0 NOT NULL,    PRIMARY KEY(tl_from, tl_target_id)  );
INSERT INTO  /*_*/templatelinks (    tl_from, tl_target_id, tl_from_namespace  )
SELECT  tl_from,  tl_target_id,  tl_from_namespace
FROM  /*_*/__temp__templatelinks;
DROP  TABLE /*_*/__temp__templatelinks;
CREATE INDEX tl_target_id ON  /*_*/templatelinks (tl_target_id, tl_from);
CREATE INDEX tl_backlinks_namespace_target_id ON  /*_*/templatelinks (    tl_from_namespace, tl_target_id,    tl_from  );