-- Tạo và sử dụng cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS smartphone_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smartphone_store;

-- 1. Bảng quản lý người dùng (users)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm tài khoản mẫu
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '123456', 'admin'),
('user1', '123456', 'user');

-- 2. Bảng quản lý sản phẩm (products)
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `link` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm sản phẩm mẫu
INSERT INTO `products` (`name`, `price`, `image`, `category`, `link`) VALUES
('Samsung Galaxy A57 5G 12GB/256GB', 13290000, 'ss1.jpg', 'samsung', 'checkout.php?id=1'),
('Samsung Galaxy S26+ 5G 12GB/512GB', 29590000, 's26+.jpg', 'samsung', 'checkout.php?id=2'),
('Samsung Galaxy S26 5G 12GB/256GB', 21490000, 's26.jpg', 'samsung', 'checkout.php?id=3'),
('Samsung Galaxy S25 Ultra 5G 12GB/512GB', 31090000, 's25ul.jpg', 'samsung', 'checkout.php?id=4'),
('iPhone 17 Pro Max 1TB', 49090000, 'i17prm.jpg', 'iphone', 'checkout.php?id=5'),
('iPhone 16 Pro Max', 31590000, 'i16prm.jpg', 'iphone', 'checkout.php?id=6'),
('iPhone 15 Pro Max 1TB', 33440000, 'i15prm.jpg', 'iphone', 'checkout.php?id=7'),
('iPhone 14 Pro Max 1TB', 43090000, 'i14prm.jpg', 'iphone', 'checkout.php?id=8');

-- 3. Bảng lưu phản hồi hỗ trợ (supports)
CREATE TABLE IF NOT EXISTS `supports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Bảng quản lý đơn hàng đầy đủ thông tin (orders)
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `original_price` DECIMAL(12,2) NOT NULL,
  `discount_code` VARCHAR(50) DEFAULT NULL,
  `final_price` DECIMAL(12,2) NOT NULL,
  `payment_method` VARCHAR(100) NOT NULL,
  `installment_info` VARCHAR(255) DEFAULT 'Thanh toán thẳng',
  `status` VARCHAR(50) DEFAULT 'Chờ xử lý',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;