-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-ipblocks-ipb_id.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TEMPORARY TABLE /*_*/__temp__ipblocks AS
SELECT
  ipb_id,
  ipb_address,
  ipb_user,
  ipb_by_actor,
  ipb_reason_id,
  ipb_timestamp,
  ipb_auto,
  ipb_anon_only,
  ipb_create_account,
  ipb_enable_autoblock,
  ipb_expiry,
  ipb_range_start,
  ipb_range_end,
  ipb_deleted,
  ipb_block_email,
  ipb_allow_usertalk,
  ipb_parent_block_id,
  ipb_sitewide
FROM /*_*/ipblocks;
DROP TABLE /*_*/ipblocks;


CREATE TABLE /*_*/ipblocks (
    ipb_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    ipb_address BLOB NOT NULL, ipb_user INTEGER UNSIGNED DEFAULT 0 NOT NULL,
    ipb_by_actor BIGINT UNSIGNED NOT NULL,
    ipb_reason_id BIGINT UNSIGNED NOT NULL,
    ipb_timestamp BLOB NOT NULL, ipb_auto SMALLINT DEFAULT 0 NOT NULL,
    ipb_anon_only SMALLINT DEFAULT 0 NOT NULL,
    ipb_create_account SMALLINT DEFAULT 1 NOT NULL,
    ipb_enable_autoblock SMALLINT DEFAULT 1 NOT NULL,
    ipb_expiry BLOB NOT NULL, ipb_range_start BLOB NOT NULL,
    ipb_range_end BLOB NOT NULL, ipb_deleted SMALLINT DEFAULT 0 NOT NULL,
    ipb_block_email SMALLINT DEFAULT 0 NOT NULL,
    ipb_allow_usertalk SMALLINT DEFAULT 0 NOT NULL,
    ipb_parent_block_id INTEGER UNSIGNED DEFAULT NULL,
    ipb_sitewide SMALLINT DEFAULT 1 NOT NULL
  );
INSERT INTO /*_*/ipblocks (
    ipb_id, ipb_address, ipb_user, ipb_by_actor,
    ipb_reason_id, ipb_timestamp, ipb_auto,
    ipb_anon_only, ipb_create_account,
    ipb_enable_autoblock, ipb_expiry,
    ipb_range_start, ipb_range_end,
    ipb_deleted, ipb_block_email, ipb_allow_usertalk,
    ipb_parent_block_id, ipb_sitewide
  )
SELECT
  ipb_id,
  ipb_address,
  ipb_user,
  ipb_by_actor,
  ipb_reason_id,
  ipb_timestamp,
  ipb_auto,
  ipb_anon_only,
  ipb_create_account,
  ipb_enable_autoblock,
  ipb_expiry,
  ipb_range_start,
  ipb_range_end,
  ipb_deleted,
  ipb_block_email,
  ipb_allow_usertalk,
  ipb_parent_block_id,
  ipb_sitewide
FROM
  /*_*/__temp__ipblocks;
DROP TABLE /*_*/__temp__ipblocks;

CREATE UNIQUE INDEX ipb_address_unique ON /*_*/ipblocks (ipb_address, ipb_user, ipb_auto);

CREATE INDEX ipb_user ON /*_*/ipblocks (ipb_user);

CREATE INDEX ipb_range ON /*_*/ipblocks (ipb_range_start, ipb_range_end);

CREATE INDEX ipb_timestamp ON /*_*/ipblocks (ipb_timestamp);

CREATE INDEX ipb_expiry ON /*_*/ipblocks (ipb_expiry);

CREATE INDEX ipb_parent_block_id ON /*_*/ipblocks (ipb_parent_block_id);
