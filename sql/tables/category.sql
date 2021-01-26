CREATE TABLE `category` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `image` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'name.png',
    `description` text COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `category` (`name`, `image`, `description`) VALUES
('Category 1', 'cat1.jpg', 'This is the Category #1'),
('Category 2', 'cat2.jpg', 'This is the Category #2'),
('Category 3', 'cat3.jpg', 'This is the Category #3'),
('Category 4', 'cat4.jpg', 'This is the Category #4'),
('Category 5', 'cat5.jpg', 'This is the Category #5');
