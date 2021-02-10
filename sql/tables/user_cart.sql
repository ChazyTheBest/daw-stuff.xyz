CREATE TABLE `user_cart` (
    `product_id` int(11) NOT NULL DEFAULT 0,
    `quantity` smallint(3) NOT NULL DEFAULT 0,
    `created_by` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`product_id`,`created_by`),
    KEY `idx-user_cart-product_id` (`product_id`),
    KEY `idx-user_cart-created_by` (`created_by`),
    CONSTRAINT `fk-user_cart-product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION,
    CONSTRAINT `fk-user_cart-created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
