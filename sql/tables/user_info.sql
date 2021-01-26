CREATE TABLE `user_info` (
    `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `surname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `address_1` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `address_2` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `nin` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`user_id`),
    KEY `idx-user_info-user_id` (`user_id`),
    CONSTRAINT `fk-user_info-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
