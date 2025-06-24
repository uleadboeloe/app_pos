/*
WARNING: This script will delete all the data in the tables dbo_header, dbo_detail, and dbo_payment.
*/

TRUNCATE TABLE dbo_header;
TRUNCATE TABLE dbo_detail;
TRUNCATE TABLE dbo_payment;
UPDATE dbo_noseries SET nomor_akhir = 1 WHERE kode_store = 'A101'