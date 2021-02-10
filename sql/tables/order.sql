CREATE TABLE `order` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `reference` char(9) COLLATE utf8mb4_unicode_ci NOT NULL,
    `shipping_price` decimal(6,2) NOT NULL DEFAULT 0.00,
    `taxes` decimal(65,2) NOT NULL DEFAULT 0.00,
    `total` decimal(65,2) NOT NULL DEFAULT 0.00,
    `payment` tinyint(1) DEFAULT NULL,
    `paypal_order_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `paypal_transaction_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` int(11) NOT NULL DEFAULT 0,
    `created_by` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `reference` (`reference`),
    UNIQUE KEY `paypal_order_id` (`paypal_order_id`),
    UNIQUE KEY `paypal_transaction_id` (`paypal_transaction_id`),
    KEY `idx-order-created_by` (`created_by`),
    CONSTRAINT `fk-order-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order` (`reference`, `total`, `payment`, `status`, `created_at`, `created_by`) VALUES
('ACEFHLRVW', 617.25, 0, 2, UNIX_TIMESTAMP(), 7),
('BDGHLQRSV', 617.25, 1, 3, UNIX_TIMESTAMP(), 7),
('CADEHLOSZ', 617.25, 2, 4, UNIX_TIMESTAMP(), 7),
('DBRIWLOLZ', 617.25, 0, 5, UNIX_TIMESTAMP(), 7),
('ASMGOEIGE', 617.25, 1, 6, UNIX_TIMESTAMP(), 7),
('KIEJGIFEL', 617.25, 2, 7, UNIX_TIMESTAMP(), 7),
('OPFAEKBNI', 617.25, 0, 8, UNIX_TIMESTAMP(), 7),
('UJFEIJWFA', 617.25, 1, 0, UNIX_TIMESTAMP(), 7),
('ROKCJIGEG', 617.25, 2, 0, UNIX_TIMESTAMP(), 7);
