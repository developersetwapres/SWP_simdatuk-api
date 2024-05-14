-- Migration data from old database to new database

-- Insert ASN user
INSERT INTO simdatuk.users (name, created_at)
SELECT nm_pegawai, CURRENT_TIMESTAMP
FROM simdatuk_dump.tbl_1pegawai_swp;