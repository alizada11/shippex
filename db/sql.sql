ALTER TABLE shipping_bookings
ADD COLUMN package_number VARCHAR(32) DEFAULT NULL;
ALTER TABLE packages
ADD COLUMN package_number VARCHAR(32) DEFAULT NULL;
ALTER TABLE packages
ADD COLUMN booking_id INT(11) DEFAULT NULL;
ALTER TABLE packages
ADD COLUMN booked_at DATETIME DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE packages
ADD overdue_fee_paid TINYINT(1) NOT NULL DEFAULT 0;

ALTER TABLE shipping_bookings
ADD COLUMN tracking_number VARCHAR(32) DEFAULT NULL;

ALTER TABLE packages
ADD COLUMN payment_info VARCHAR(5000) DEFAULT NULL;

-- import from db folder notiications table
-- changes the transit_days to VARCHAR(20) in shipping_services