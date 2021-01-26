CREATE TABLE `order_line` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL DEFAULT 0,
    `quantity` tinyint(2) NOT NULL DEFAULT 0,
    `price` decimal(7,2) NOT NULL DEFAULT 0.00,
    `order_id` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx-order_line-product_id` (`product_id`),
    KEY `idx-order_line-order_id` (`order_id`),
    CONSTRAINT `fk-order_line-product_id` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION,
    CONSTRAINT `fk-order_line-order_id` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
