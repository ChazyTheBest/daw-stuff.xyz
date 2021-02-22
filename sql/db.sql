--
-- In order to execute this file you have to CD into the sql directory
-- and then run the mariadb (or mysql) client
-- MariaDB [(none)]> source db.sql
--
DROP DATABASE IF EXISTS `shop`;
CREATE DATABASE `shop` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shop`;

-- TABLE `user`
SOURCE tables/user.sql

-- TABLE `user_info`
SOURCE tables/user_info.sql

-- TABLE `category`
SOURCE tables/category.sql

-- TABLE `product`
SOURCE tables/product.sql

-- TABLE `user_cart`
SOURCE tables/user_cart.sql

-- TABLE `order`
SOURCE tables/order.sql

-- TABLE `order_line`
SOURCE tables/order_line.sql
