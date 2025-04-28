-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- 
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 08:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table structure for table `customers`
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `invoice`
CREATE TABLE IF NOT EXISTS `invoice` (
  `id_inv` int(11) NOT NULL,
  `kode_inv` varchar(20) NOT NULL,
  `tgl_inv` date NOT NULL,
  `customers_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `inv_items`
CREATE TABLE IF NOT EXISTS `inv_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `items_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `items`
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `items_customers`
CREATE TABLE IF NOT EXISTS `items_customers` (
  `id_ic` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `id_items` int(11) NOT NULL,
  `id_customers` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `suppliers`
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Indexes for dumped tables
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_no_unique` (`ref_no`);

ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id_inv`),
  ADD UNIQUE KEY `kode_inv_unique` (`kode_inv`),
  ADD KEY `fk_customers_id` (`customers_id`);

ALTER TABLE `inv_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `items_id` (`items_id`);

ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_no_unique` (`ref_no`);

ALTER TABLE `items_customers`
  ADD PRIMARY KEY (`id_ic`),
  ADD KEY `id_items` (`id_items`),
  ADD KEY `id_customers` (`id_customers`);

ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ref_no_unique` (`ref_no`);

-- AUTO_INCREMENT for dumped tables
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `invoice`
  MODIFY `id_inv` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `inv_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `items_customers`
  MODIFY `id_ic` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Constraints for dumped tables
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_customers_id` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`);

ALTER TABLE `inv_items`
  ADD CONSTRAINT `invoice_id` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id_inv`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_id` FOREIGN KEY (`items_id`) REFERENCES `items` (`id`);

ALTER TABLE `items_customers`
  ADD CONSTRAINT `fk_items_customers_customers` FOREIGN KEY (`id_customers`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `fk_items_customers_items` FOREIGN KEY (`id_items`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `items_customers_ibfk_1` FOREIGN KEY (`id_items`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_customers_ibfk_2` FOREIGN KEY (`id_customers`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
