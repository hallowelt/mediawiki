-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-sites-rename-indexes.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
DROP INDEX sites_global_key ON /*_*/sites;

CREATE UNIQUE INDEX site_global_key ON /*_*/sites (site_global_key);
DROP INDEX sites_type ON /*_*/sites;

CREATE INDEX site_type ON /*_*/sites (site_type);
DROP INDEX sites_group ON /*_*/sites;

CREATE INDEX site_group ON /*_*/sites (site_group);
DROP INDEX sites_source ON /*_*/sites;

CREATE INDEX site_source ON /*_*/sites (site_source);
DROP INDEX sites_language ON /*_*/sites;

CREATE INDEX site_language ON /*_*/sites (site_language);
DROP INDEX sites_protocol ON /*_*/sites;

CREATE INDEX site_protocol ON /*_*/sites (site_protocol);
DROP INDEX sites_domain ON /*_*/sites;

CREATE INDEX site_domain ON /*_*/sites (site_domain);
DROP INDEX sites_forward ON /*_*/sites;

CREATE INDEX site_forward ON /*_*/sites (site_forward);
