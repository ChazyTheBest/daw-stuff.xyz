CREATE TABLE `user_cart` (
    `product_id` int(11) NOT NULL DEFAULT 0,
    `quantity` tinyint(2) NOT NULL DEFAULT 0,
    `created_by` int(11) NOT NULL,
    PRIMARY KEY (`product_id`,`created_by`),
    KEY `idx-user_cart-created_by` (`created_by`),
    CONSTRAINT `fk-user_cart-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
