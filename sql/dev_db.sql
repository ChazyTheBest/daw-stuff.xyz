DROP DATABASE IF EXISTS `shop`;
CREATE DATABASE `shop` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shop`;

-- TABLE `user`
SOURCE /srv/http/daw-stuff.xyz/sql/tables/user.sql

-- TABLE `user_info`
SOURCE /srv/http/daw-stuff.xyz/sql/tables/user_info.sql

-- TABLE `category`
SOURCE /srv/http/daw-stuff.xyz/sql/tables/category.sql

-- TABLE `product`
SOURCE /srv/http/daw-stuff.xyz/sql/tables/product.sql

-- TABLE `user_cart`
SOURCE /srv/http/daw-stuff.xyz/sql/tables/user_cart.sql

-- TABLE `order`
SOURCE /srv/http/daw-stuff.xyz/sql/tables/order.sql

-- TABLE `order_line`
SOURCE /srv/http/daw-stuff.xyz/sql/tables/order_line.sql
