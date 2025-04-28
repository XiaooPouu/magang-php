-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 11:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

--
-- Database: `magang_php`
--

--
-- Dumping data for table `customers`
--

-- Data Dummy untuk tabel customers
INSERT INTO `customers` (`id`, `ref_no`, `name`) VALUES
(1, 'CUST001', 'Andi Setiawan'),
(2, 'CUST002', 'Budi Santoso'),
(3, 'CUST003', 'Citra Wijaya'),
(4, 'CUST004', 'Dewi Saraswati'),
(5, 'CUST005', 'Eko Prabowo'),
(6, 'CUST006', 'Fajar Ahmad'),
(7, 'CUST007', 'Gina Rahayu'),
(8, 'CUST008', 'Hendra Kusuma'),
(9, 'CUST009', 'Indah Sari'),
(10, 'CUST010', 'Joko Susanto');

-- Data Dummy untuk tabel suppliers
INSERT INTO `suppliers` (`id`, `ref_no`, `name`) VALUES
(1, 'SUP001', 'Niko Electronics'),
(2, 'SUP002', 'Nevil Industries'),
(3, 'SUP003', 'Safira Gadgets'),
(4, 'SUP004', 'Tony Supplies'),
(5, 'SUP005', 'Rina Tech'),
(6, 'SUP006', 'Maya Store'),
(7, 'SUP007', 'Budi Mart'),
(8, 'SUP008', 'Citra Wholesale'),
(9, 'SUP009', 'Dedi Electronics');

-- Data Dummy untuk tabel items
INSERT INTO `items` (`id`, `ref_no`, `name`, `price`) VALUES
(1, 'ITEM001', 'Laptop Dell', 7000000.00),
(2, 'ITEM002', 'PS5', 8000000.00),
(3, 'ITEM003', 'Smartphone Samsung', 5000000.00),
(4, 'ITEM004', 'Headset Sony', 1500000.00),
(5, 'ITEM005', 'Keyboard Logitech', 600000.00),
(6, 'ITEM006', 'Mouse Razer', 450000.00),
(7, 'ITEM007', 'Monitor LG', 3500000.00),
(8, 'ITEM008', 'External Hard Drive', 1200000.00),
(9, 'ITEM009', 'Smartwatch Apple', 4500000.00),
(10, 'ITEM010', 'Wireless Earbuds', 700000.00);

-- Data Dummy untuk tabel items_customers
INSERT INTO `items_customers` (`id_ic`, `price`, `id_items`, `id_customers`) VALUES
(1, 7000000.00, 1, 1),
(2, 8000000.00, 2, 2),
(3, 5000000.00, 3, 3),
(4, 1500000.00, 4, 4),
(5, 600000.00, 5, 5),
(6, 450000.00, 6, 6),
(7, 3500000.00, 7, 7),
(8, 1200000.00, 8, 8),
(9, 4500000.00, 9, 9),
(10, 700000.00, 10, 10);

-- Data Dummy untuk tabel invoice
INSERT INTO `invoice` (`id_inv`, `kode_inv`, `tgl_inv`, `customers_id`) VALUES
(1, 'INV001', '2025-04-01', 1),
(2, 'INV002', '2025-04-02', 2),
(3, 'INV003', '2025-04-03', 3),
(4, 'INV004', '2025-04-04', 4),
(5, 'INV005', '2025-04-05', 5),
(6, 'INV006', '2025-04-06', 6),
(7, 'INV007', '2025-04-07', 7),
(8, 'INV008', '2025-04-08', 8),
(9, 'INV009', '2025-04-09', 9),
(10, 'INV010', '2025-04-10', 10);

-- Data Dummy untuk tabel inv_items
INSERT INTO `inv_items` (`id`, `invoice_id`, `items_id`, `qty`, `price`, `total`) VALUES
(1, 1, 1, 1, 7000000.00, 7000000.00),
(2, 2, 2, 1, 8000000.00, 8000000.00),
(3, 3, 3, 2, 5000000.00, 10000000.00),
(4, 4, 4, 1, 1500000.00, 1500000.00),
(5, 5, 5, 3, 600000.00, 1800000.00),
(6, 6, 6, 2, 450000.00, 900000.00),
(7, 7, 7, 1, 3500000.00, 3500000.00),
(8, 8, 8, 1, 1200000.00, 1200000.00),
(9, 9, 9, 1, 4500000.00, 4500000.00),
(10, 10, 10, 5, 700000.00, 3500000.00);


COMMIT;

-- Restore previous charset/collation settings
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
