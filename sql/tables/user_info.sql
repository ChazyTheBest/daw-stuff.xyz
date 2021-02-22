CREATE TABLE `user_info` (
    `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `surname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `address_1` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `address_2` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `nin` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `user_id` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`user_id`),
    KEY `idx-user_info-user_id` (`user_id`),
    CONSTRAINT `fk-user_info-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_info` (`name`, `user_id`) VALUES
('admin', 1),
('staff 1', 2),
('staff 2', 3),
('staff 3', 4),
('staff 4', 5),
('staff 5', 6),
('customer 1', 7),
('customer 2', 8),
('customer 3', 9),
('customer 4', 10),
('customer 5', 11);
