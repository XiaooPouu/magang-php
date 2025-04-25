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

INSERT INTO `customers` (`id`, `ref_no`, `name`) VALUES
(11, 'A001', 'Andi'),
(12, 'B002', 'Budi'),
(13, 'C003', 'Citra'),
(14, 'D004', 'Dewi'),
(15, 'E005', 'Eko'),
(16, 'F006', 'Fajar'),
(17, 'G007', 'Gina'),
(18, 'H008', 'Hendra'),
(19, 'I009', 'Indah'),
(20, 'J010', 'Joko');

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `ref_no`, `name`) VALUES
(7, 'N001', 'Niko'),
(8, 'N002', 'Nevil'),
(10, 'S001', 'Safira'),
(11, 'T001', 'Tony'),
(12, 'R001', 'Rina'),
(13, 'A001', 'Andi'),
(14, 'M001', 'Maya'),
(15, 'B001', 'Budi'),
(16, 'C001', 'Citra'),
(17, 'D001', 'Dedi');

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `ref_no`, `name`, `price`) VALUES
(22, 'L003', 'Laptop', 7000000.00),
(24, 'P001', 'PS', 400000.00),
(25, 'L004', 'Laptop', 50000000.00),
(26, 'P002', 'PS', 4000000.00),
(27, 'L002', 'Laptop', 222222.00),
(28, 'N001', 'Nikel', 6000000.00),
(29, 'N003', 'Nikel', 100000.00),
(30, 'B001', 'Ben', 10000.00),
(31, 'H001', 'Headset', 150000.00),
(32, 'K001', 'Keyboard', 250000.00);

--
-- Dumping data for table `items_customers`
--

INSERT INTO `items_customers` (`id_ic`, `price`, `id_items`, `id_customers`) VALUES
(10, 22222.00, 26, 5),
(11, 500000.00, 24, 3),
(12, 7000000.00, 22, 7),
(13, 400000.00, 24, 9),
(14, 6000000.00, 28, 3),
(15, 100000.00, 29, 5),
(16, 10000.00, 30, 9),
(17, 150000.00, 31, 7),
(18, 250000.00, 32, 5),
(19, 222222.00, 27, 9);

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id_inv`, `kode_inv`, `tgl_inv`, `customers_id`) VALUES
(39, 'INV001', '2025-04-20', 5),
(40, 'INV002', '2025-04-21', 7),
(41, 'INV003', '2025-04-22', 9),
(42, 'INV004', '2025-04-22', 3),
(43, 'INV005', '2025-04-23', 5),
(44, 'INV006', '2025-04-24', 7),
(45, 'INV007', '2025-04-24', 9),
(46, 'INV008', '2025-04-25', 3),
(47, 'INV009', '2025-04-25', 5),
(48, 'INV010', '2025-04-26', 7);

--
-- Dumping data for table `inv_items`
--

INSERT INTO `inv_items` (`id`, `invoice_id`, `items_id`, `qty`, `price`, `total`) VALUES
(57, 37, 24, 1, 400000.00, 400000.00),
(58, 37, 24, 1, 400000.00, 400000.00),
(59, 38, 25, 2, 250000.00, 500000.00),
(60, 38, 26, 1, 300000.00, 300000.00),
(61, 39, 24, 3, 400000.00, 1200000.00),
(62, 40, 25, 1, 250000.00, 250000.00),
(63, 41, 26, 2, 300000.00, 600000.00),
(64, 42, 24, 1, 400000.00, 400000.00),
(65, 43, 25, 2, 250000.00, 500000.00),
(66, 44, 26, 1, 300000.00, 300000.00);

COMMIT;

-- Restore previous charset/collation settings
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
