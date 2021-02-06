CREATE TABLE `order` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `reference` char(9) COLLATE utf8mb4_unicode_ci NOT NULL,
    `shipping_price` decimal(6,2) NOT NULL DEFAULT 0.00,
    `taxes` decimal(65,2) NOT NULL DEFAULT 0.00,
    `total` decimal(65,2) NOT NULL DEFAULT 0.00,
    `payment` tinyint(1) DEFAULT NULL,
    `paypal_order_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `paypal_transaction_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` int(11) NOT NULL DEFAULT 0,
    `created_by` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `reference` (`reference`),
    UNIQUE KEY `paypal_order_id` (`paypal_order_id`),
    UNIQUE KEY `paypal_transaction_id` (`paypal_transaction_id`),
    KEY `idx-order-created_by` (`created_by`),
    CONSTRAINT `fk-order-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
