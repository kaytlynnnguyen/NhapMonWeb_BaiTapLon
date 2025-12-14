-- SQL dump for jourlestours (generated from project JSON and functions.php)
-- Contains CREATE TABLE and INSERT statements for users, categories, comments, orders, order_items

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `categories` (
  `id` INT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price_range` VARCHAR(255),
  `created_at` DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `comments` (
  `id` INT PRIMARY KEY,
  `user_id` INT,
  `user_email` VARCHAR(255),
  `product_id` INT,
  `content` TEXT,
  `rating` INT DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'pending',
  `created_at` DATETIME,
  `updated_at` DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `orders` (
  `id` INT PRIMARY KEY,
  `user_id` INT,
  `user_email` VARCHAR(255),
  `total` INT,
  `status` VARCHAR(50),
  `created_at` DATETIME,
  `updated_at` DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert users (from users.json)
INSERT INTO `users` (`id`,`name`,`email`,`password_hash`,`created_at`) VALUES
  (1,'Nguyen Gia Han','Nguyengiahan@gmail.com','$2y$12$buPSBZueZ1c.9DmB8Zwl3uY8E1R0xpUOPymkVJSCp9z6K3JO5yVmq','2025-11-27 13:38:35'),
  (2,'hangia','gaheun4@gmail.com','$2y$10$x.owteThseVD.2wpXNF25OcPgTRy0od.88RRFpQb/.zzKsMCKqPDC','2025-12-03 03:40:52'),
  (3,'admin','admin@jourlestours.com','$2y$10$BeSbq1HL75w6VY/rm8jzDusedOczi5TgnNK.vkJEPhSUjliF7Kf5u','2025-12-03 06:40:58'),
  (4,'han','nguyenhan20048@gmail.com','$2y$10$W/9LeS9E2.8WdaYrOwACL.NOac6nMfxAIZ15ff8U6Agwviie8hfdq','2025-12-13 07:28:55');

-- Insert categories (from data/categories.json)
INSERT INTO `categories` (`id`,`name`,`price_range`,`created_at`) VALUES
  (1,'BÁNH KEM','300000-500000','2025-12-03 06:41:23'),
  (2,'MACARON','200000-400000','2025-12-03 06:41:23'),
  (3,'MOUSSE & CAKE','250000-450000','2025-12-03 06:41:23');

-- No existing comments to import (data/comments.json is empty)

-- Insert orders (from data/orders.json) and order_items
INSERT INTO `orders` (`id`,`user_id`,`user_email`,`total`,`status`,`created_at`,`updated_at`) VALUES
  (1,2,'nguyenhan20048@gmail.com',560000,'completed','2025-12-03 06:53:50','2025-12-13 08:09:44'),
  (2,4,'nguyenhan20048@gmail.com',280000,'cancelled','2025-12-13 08:15:51','2025-12-13 08:17:30');

INSERT INTO `order_items` (`order_id`,`product_id`,`quantity`) VALUES
  (1,2,2),
  (2,2,1);

SET FOREIGN_KEY_CHECKS = 1;

-- End of dump
