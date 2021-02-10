CREATE TABLE `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `role` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `auth_key` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 9,
    `created_at` int(11) NOT NULL DEFAULT 0,
    `updated_at` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `auth_key` (`auth_key`),
    UNIQUE KEY `verification_token` (`verification_token`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user` (`email`, `role`, `password_hash`, `status`, `created_at`) VALUES
('admin@admin.admin', 'admin', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 10, UNIX_TIMESTAMP()),
('staff1@a.a', 'staff', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 10, UNIX_TIMESTAMP()),
('staff2@a.a', 'staff', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 9, UNIX_TIMESTAMP()),
('staff3@a.a', 'staff', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 9, UNIX_TIMESTAMP()),
('staff4@a.a', 'staff', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 9, UNIX_TIMESTAMP()),
('staff5@a.a', 'staff', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 0, UNIX_TIMESTAMP()),
('customer1@a.a', 'customer', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 10, UNIX_TIMESTAMP()),
('customer2@a.a', 'customer', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 9, UNIX_TIMESTAMP()),
('customer3@a.a', 'customer', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 9, UNIX_TIMESTAMP()),
('customer4@a.a', 'customer', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 9, UNIX_TIMESTAMP()),
('customer5@a.a', 'customer', '$argon2id$v=19$m=65536,t=4,p=1$RDJUSEtoSGJuRmVERWRtaQ$AIhnJjS3z12kPEnZtHANT1Q4OHovGBBJ0JJh6em3YN8', 0, UNIX_TIMESTAMP());
