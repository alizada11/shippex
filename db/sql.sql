-- create phone field for user
ALTER TABLE users
ADD COLUMN phone_number VARCHAR(20) AFTER email;
ADD COLUMN email_verified TINYINT(1) DEFAULT 0 AFTER password,
ADD COLUMN email_verification_token VARCHAR(255) NULL AFTER email_verified,
ADD COLUMN email_verified_at DATETIME NULL AFTER email_verification_token;
---packages
ALTER TABLE packages
ADD COLUMN archive TINYINT(1) NOT NULL DEFAULT 0
CHECK (archive IN (0, 1));
ALTER TABLE packages
ADD COLUMN combination_status TINYINT(1) NULL
CHECK (combination_status IN (0, 1));
ALTER TABLE packages
ADD COLUMN parent_package INT NULL DEFAULT NULL;
--virtual address 
ALTER TABLE virtual_addresses
ADD COLUMN easyship_wh TINYINT(1) NOT NULL DEFAULT 0;
--set rate shipping bookin
ALTER TABLE shipping_bookings
ADD COLUMN set_rate TINYINT(1) NOT NULL DEFAULT 0;
--add request_id 
ALTER TABLE `shipping_services`
ADD COLUMN `request_id` INT AFTER `id`;
