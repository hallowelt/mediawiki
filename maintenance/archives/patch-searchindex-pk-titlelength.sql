-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: maintenance/abstractSchemaChanges/patch-searchindex-innodb-pk-titlelength.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
DROP INDEX si_page ON /*_*/searchindex;
ALTER TABLE /*_*/searchindex
  CHANGE si_title si_title MEDIUMTEXT NOT NULL,
  ADD PRIMARY KEY (si_page);
