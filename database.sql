-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 02, 2019 at 03:42 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iyzico`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `name`, `city`, `country`, `address`, `zipcode`) VALUES
(1, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(2, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(3, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(4, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(5, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(6, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(7, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(8, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(9, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(10, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(11, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732'),
(12, 'Mehmet Baz', 'Istanbul', 'Turkey', 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1', '34732');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'Ödemeyi yapan kişi',
  `shipping_id` int(10) UNSIGNED NOT NULL COMMENT 'Kargo adresi',
  `billing_id` int(10) UNSIGNED NOT NULL COMMENT 'Fatura adresi',
  `price` decimal(10,0) NOT NULL COMMENT 'Toplam tutar',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ödemenin durumu',
  `order_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_at` timestamp NULL DEFAULT NULL,
  `order_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IYZICO''daki ÖDEME NO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `shipping_id`, `billing_id`, `price`, `status`, `order_at`, `payment_at`, `order_ip`, `payment_id`) VALUES
(1, 1, 1, 2, '1', 2, '2019-01-02 15:33:09', NULL, '56.43.12.122', ''),
(2, 2, 3, 4, '2', 1, '2019-01-02 15:33:45', NULL, '56.43.12.122', '11174850'),
(3, 3, 5, 6, '1', 1, '2019-01-02 15:34:21', NULL, '56.43.12.122', '11174852'),
(4, 4, 7, 8, '2', 2, '2019-01-02 15:34:59', NULL, '56.43.12.122', '11174863'),
(5, 5, 9, 10, '3', 2, '2019-01-02 15:40:32', NULL, '56.43.12.122', '11174884'),
(6, 6, 11, 12, '100', 2, '2019-01-02 15:41:34', NULL, '56.43.12.122', '11174888');

-- --------------------------------------------------------

--
-- Table structure for table `order_errors`
--

CREATE TABLE `order_errors` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_errors`
--

INSERT INTO `order_errors` (`id`, `order_id`, `code`, `message`) VALUES
(1, 4, '5115', 'Bir Hata Oluştu! Hata Kodu: 5115 Hata Mesajı: Bu ödemenin durumu 3dsecure için geçerli değil'),
(2, 5, '10051', 'Bir Hata Oluştu! Hata Kodu: 10051 Hata Mesajı: Kart limiti yetersiz, yetersiz bakiye'),
(3, 6, '10012', 'Bir Hata Oluştu! Hata Kodu: 10012 Hata Mesajı: Geçersiz işlem');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `phone`, `email`, `identity`, `city`, `country`) VALUES
(1, 'Mehmet', 'Baz', '+905071234567', 'mehmet@baz.com', '11417654675', 'Konya', 'Turkey'),
(2, 'Mehmet', 'Baz', '+905071234567', 'mehmet@baz.com', '11417654675', 'Konya', 'Turkey'),
(3, 'Mehmet', 'Baz', '+905071234567', 'mehmet@baz.com', '11417654675', 'Konya', 'Turkey'),
(4, 'Mehmet', 'Baz', '+905071234567', 'mehmet@baz.com', '11417654675', 'Konya', 'Turkey'),
(5, 'Mehmet', 'Baz', '+905071234567', 'mehmet@baz.com', '11417654675', 'Konya', 'Turkey'),
(6, 'Mehmet', 'Baz', '+905071234567', 'mehmet@baz.com', '11417654675', 'Konya', 'Turkey');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_errors`
--
ALTER TABLE `order_errors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_errors`
--
ALTER TABLE `order_errors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
