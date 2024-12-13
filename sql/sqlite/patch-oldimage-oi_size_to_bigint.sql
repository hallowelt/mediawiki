-- This file is automatically generated using maintenance/generateSchemaChangeSql.php.
-- Source: sql/abstractSchemaChanges/patch-oldimage-oi_size_to_bigint.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TEMPORARY TABLE /*_*/__temp__oldimage AS
SELECT
  oi_name,
  oi_archive_name,
  oi_size,
  oi_width,
  oi_height,
  oi_bits,
  oi_description_id,
  oi_actor,
  oi_timestamp,
  oi_metadata,
  oi_media_type,
  oi_major_mime,
  oi_minor_mime,
  oi_deleted,
  oi_sha1
FROM /*_*/oldimage;
DROP TABLE /*_*/oldimage;


CREATE TABLE /*_*/oldimage (
    oi_name BLOB DEFAULT '' NOT NULL, oi_archive_name BLOB DEFAULT '' NOT NULL,
    oi_size BIGINT UNSIGNED DEFAULT 0 NOT NULL,
    oi_width INTEGER DEFAULT 0 NOT NULL,
    oi_height INTEGER DEFAULT 0 NOT NULL,
    oi_bits INTEGER DEFAULT 0 NOT NULL,
    oi_description_id BIGINT UNSIGNED NOT NULL,
    oi_actor BIGINT UNSIGNED NOT NULL,
    oi_timestamp BLOB NOT NULL, oi_metadata BLOB NOT NULL,
    oi_media_type TEXT DEFAULT NULL, oi_major_mime TEXT DEFAULT 'unknown' NOT NULL,
    oi_minor_mime BLOB DEFAULT 'unknown' NOT NULL,
    oi_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
    oi_sha1 BLOB DEFAULT '' NOT NULL
  );
INSERT INTO /*_*/oldimage (
    oi_name, oi_archive_name, oi_size,
    oi_width, oi_height, oi_bits, oi_description_id,
    oi_actor, oi_timestamp, oi_metadata,
    oi_media_type, oi_major_mime, oi_minor_mime,
    oi_deleted, oi_sha1
  )
SELECT
  oi_name,
  oi_archive_name,
  oi_size,
  oi_width,
  oi_height,
  oi_bits,
  oi_description_id,
  oi_actor,
  oi_timestamp,
  oi_metadata,
  oi_media_type,
  oi_major_mime,
  oi_minor_mime,
  oi_deleted,
  oi_sha1
FROM
  /*_*/__temp__oldimage;
DROP TABLE /*_*/__temp__oldimage;

CREATE INDEX oi_actor_timestamp ON /*_*/oldimage (oi_actor, oi_timestamp);

CREATE INDEX oi_name_timestamp ON /*_*/oldimage (oi_name, oi_timestamp);

CREATE INDEX oi_name_archive_name ON /*_*/oldimage (oi_name, oi_archive_name);

CREATE INDEX oi_sha1 ON /*_*/oldimage (oi_sha1);

CREATE INDEX oi_timestamp ON /*_*/oldimage (oi_timestamp);
