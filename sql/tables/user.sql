CREATE TABLE `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `role` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `auth_key` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` tinyint(3) NOT NULL DEFAULT 9,
    `created_at` int(11) NOT NULL DEFAULT 0,
    `updated_at` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `auth_key` (`auth_key`),
    UNIQUE KEY `verification_token` (`verification_token`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
