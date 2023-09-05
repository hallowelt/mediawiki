-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: maintenance/abstractSchemaChanges/patch-externallinks-drop-el_to.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TEMPORARY TABLE /*_*/__temp__externallinks AS
SELECT  el_id,  el_from,  el_to_domain_index,  el_to_path
FROM  /*_*/externallinks;
DROP  TABLE  /*_*/externallinks;
CREATE TABLE  /*_*/externallinks (    el_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,    el_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,    el_to_domain_index BLOB DEFAULT '' NOT NULL,    el_to_path BLOB DEFAULT NULL  );
INSERT INTO  /*_*/externallinks (    el_id, el_from, el_to_domain_index,    el_to_path  )
SELECT  el_id,  el_from,  el_to_domain_index,  el_to_path
FROM  /*_*/__temp__externallinks;
DROP  TABLE /*_*/__temp__externallinks;
CREATE INDEX el_from ON  /*_*/externallinks (el_from);
CREATE INDEX el_to_domain_index_to_path ON  /*_*/externallinks (el_to_domain_index, el_to_path);