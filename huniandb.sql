-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 28, 2025 at 04:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `huniandb`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `complaint_date` date DEFAULT NULL,
  `complaint_description` text DEFAULT NULL,
  `status` enum('Belum Ditangani','Diproses','Selesai') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `tenant_id`, `complaint_date`, `complaint_description`, `status`, `created_at`, `updated_at`) VALUES
(8, 11, '2025-07-19', 'Atap bocor', 'Belum Ditangani', '2025-07-18 09:25:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `expense_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `expense_total` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `expense_date`, `description`, `expense_total`, `created_at`, `updated_at`, `user_id`) VALUES
(1, '2025-07-18', 'Peralatan bersih2', 100000, '2025-07-17 19:37:19', '2025-07-18 07:02:50', 1),
(5, '2025-07-18', 'Perabotan 2', 500000, '2025-07-18 09:24:24', '2025-07-18 09:24:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_total` int(11) DEFAULT NULL,
  `amount_paid` int(11) DEFAULT NULL,
  `payment_due_date` date DEFAULT NULL,
  `status` enum('Lunas','Belum Lunas','Belum Bayar') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `payment_date`, `payment_total`, `amount_paid`, `payment_due_date`, `status`, `created_at`, `updated_at`) VALUES
(3, '2025-07-18', 13800000, 120000, '2025-07-21', 'Belum Lunas', '2025-07-17 19:18:42', NULL),
(4, '2025-07-19', 3600000, 220000, '2025-07-21', 'Belum Lunas', '2025-07-18 09:23:46', '2025-07-18 09:23:58');

-- --------------------------------------------------------

--
-- Table structure for table `rental_properties`
--

CREATE TABLE `rental_properties` (
  `rental_property_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_type` enum('Sewa','Kontrakan') NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `rental_price` int(11) NOT NULL,
  `rental_duration` enum('Bulanan','Tahunan') NOT NULL,
  `facilities` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_properties`
--

INSERT INTO `rental_properties` (`rental_property_id`, `user_id`, `property_type`, `property_name`, `rental_price`, `rental_duration`, `facilities`, `image`, `created_at`, `updated_at`, `location`) VALUES
(3, 1, 'Sewa', 'testpropertisewa333', 123000, 'Bulanan', 'yau', '1752679945_ERD kost ADSI.png', '2025-07-16 15:32:25', '2025-07-17 09:18:51', 'Yogyakarta'),
(4, 2, 'Sewa', 'Kos Jogja Bantul', 600000, 'Bulanan', 'Perabotan, Kamar mandi luar', '1752822571_kost1.jpg', '2025-07-18 07:09:31', '2025-07-18 07:09:31', 'Bantul, Yogyakarta'),
(5, 1, 'Sewa', 'Kos Jogja Amikom', 600000, 'Bulanan', 'Kamar mandi', '1752830463_kost2 - Copy.jpg', '2025-07-18 09:21:03', '2025-07-18 09:21:03', 'Condongcatur, Yogyakarta');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_no` char(10) NOT NULL,
  `status` enum('Tersedia','Disewa') DEFAULT NULL,
  `price_per_month` int(11) DEFAULT NULL,
  `rental_property_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_no`, `status`, `price_per_month`, `rental_property_id`, `created_at`, `updated_at`) VALUES
('A1', 'Tersedia', 100000, 3, '2025-07-17 07:18:17', '2025-07-18 08:26:40'),
('A2', 'Tersedia', 250000, 3, '2025-07-17 08:21:32', '2025-07-18 08:26:46'),
('A3', 'Tersedia', 1200000, 3, '2025-07-17 09:33:33', '2025-07-18 08:26:52'),
('A4', 'Tersedia', 2300000, 3, '2025-07-17 09:33:41', '2025-07-18 08:26:57'),
('B1', 'Disewa', 600000, 5, '2025-07-18 09:21:40', '2025-07-18 09:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `room_transactions`
--

CREATE TABLE `room_transactions` (
  `room_transaction_id` int(11) NOT NULL,
  `registration_date` date DEFAULT NULL,
  `rental_duration` varchar(20) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `room_no` char(10) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('Lunas','Belum Lunas','Belum Bayar') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_transactions`
--

INSERT INTO `room_transactions` (`room_transaction_id`, `registration_date`, `rental_duration`, `tenant_id`, `room_no`, `payment_id`, `created_at`, `updated_at`, `status`) VALUES
(6, '2025-07-18', '6 Bulan', 11, 'B1', 4, '2025-07-18 09:22:58', '2025-07-18 09:23:46', 'Belum Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `selling_properties`
--

CREATE TABLE `selling_properties` (
  `selling_property_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_name` varchar(255) NOT NULL,
  `sale_price` int(11) DEFAULT NULL,
  `price_per_month` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `land_area_size` int(11) DEFAULT NULL,
  `building_area_size` int(11) DEFAULT NULL,
  `certificate_type` varchar(100) DEFAULT NULL,
  `electricity_power` int(11) DEFAULT NULL,
  `floors` int(11) DEFAULT NULL,
  `garage` int(11) DEFAULT NULL,
  `property_condition` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `selling_properties`
--

INSERT INTO `selling_properties` (`selling_property_id`, `user_id`, `property_name`, `sale_price`, `price_per_month`, `image`, `bedrooms`, `bathrooms`, `land_area_size`, `building_area_size`, `certificate_type`, `electricity_power`, `floors`, `garage`, `property_condition`, `description`, `facilities`, `created_at`, `updated_at`, `location`) VALUES
(3, 1, 'testproperti3', 1200000, 500000, '1752661813_kost2.jpg', 4, 0, 0, 0, '', 0, 0, 0, '', '', '', '2025-07-16 10:30:13', '2025-07-16 10:30:13', ''),
(10, 1, 'testproperti35', 1200000, 400000, '1752681890_ERD kost ADSI.png', 2, 5, 100, 50, 'SHM', 20, 1, 2, 'bagus', '', 'Kamar Mandi', '2025-07-16 11:19:31', '2025-07-16 16:18:47', 'Sleman, Yogyakarta'),
(12, 1, 'Rumah Jogja', 500000000, 10000000, '1752822426_rumah3.jpg', 3, 5, 100, 100, 'SHM', 2200, 1, 1, 'Baru', 'Perumahan', 'Perabotan dan furniture', '2025-07-18 07:07:06', '2025-07-18 07:07:06', 'Bantul, Yogyakarta'),
(13, 16, 'Rumah Sleman', 700000000, 20000000, '1752823313_rumah2.jpg', 3, 2, 150, 150, 'SHM', 2200, 1, 1, 'Baru', 'Sudah tersedia furniture', 'Perabotan', '2025-07-18 07:21:53', '2025-07-18 07:21:53', 'Sleman, Yogyakarta');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `tenant_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`tenant_id`, `user_id`, `name`, `email`, `phone_number`, `address`) VALUES
(11, 1, 'Ganendra', 'ganendra@gmail.com', '081255967191', 'Yogyakarta'),
(12, 1, 'Husni', 'husni@gmail.com', '0812345', 'Yogyakarta'),
(13, 1, 'Alif', 'alif@gmail.com', '0845678', 'Yogyakarta'),
(14, 1, 'Dama', 'dama@gmail.com', '0867890', 'Yogyakarta'),
(15, 1, 'Alim', 'alim@gmail.com', '08123456', 'Sleman');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pemilik','pembeli') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `email`, `password`, `role`, `created_at`, `updated_at`, `phone_number`) VALUES
(1, 'testuser1', 'testemail1@gmail.com', 'sybau', 'pemilik', '2025-07-15 14:40:35', '2025-07-15 14:40:35', '08xxxx'),
(2, 'testuser2', 'testuser2@gmail.com', 'SYBAU', 'pemilik', '2025-07-15 15:01:27', '2025-07-15 15:01:27', '0812xxxx'),
(4, 'testuser3', 'testuser3@gmail.com', 'sybau', 'pemilik', '2025-07-15 15:26:34', '2025-07-15 15:26:34', '08123xxxx'),
(10, 'test', 'testuser6@gmail.com', 'sybau', 'pembeli', '2025-07-15 15:37:27', '2025-07-15 15:37:27', ''),
(15, 'testuser1pembeli', 'testemail1pembeli@gmail.com', 'sybau', 'pembeli', '2025-07-17 16:22:26', '2025-07-17 17:10:30', ''),
(16, 'ganendra', 'ganen@gmail.com', 'sybau', 'pemilik', '2025-07-18 07:20:33', '2025-07-18 07:20:33', '082246659669');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorite_rentals`
--

CREATE TABLE `user_favorite_rentals` (
  `user_id` int(11) NOT NULL,
  `rental_property_id` int(11) NOT NULL,
  `favorite_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_favorite_selling`
--

CREATE TABLE `user_favorite_selling` (
  `user_id` int(11) NOT NULL,
  `selling_property_id` int(11) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `complaints_ibfk_1` (`tenant_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `fk_expenses_user` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `rental_properties`
--
ALTER TABLE `rental_properties`
  ADD PRIMARY KEY (`rental_property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_no`),
  ADD KEY `fk_rental_property` (`rental_property_id`);

--
-- Indexes for table `room_transactions`
--
ALTER TABLE `room_transactions`
  ADD PRIMARY KEY (`room_transaction_id`),
  ADD KEY `room_transactions_ibfk_1` (`tenant_id`),
  ADD KEY `room_transactions_ibfk_2` (`room_no`),
  ADD KEY `room_transactions_ibfk_3` (`payment_id`);

--
-- Indexes for table `selling_properties`
--
ALTER TABLE `selling_properties`
  ADD PRIMARY KEY (`selling_property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`tenant_id`),
  ADD KEY `fk_tenants_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_favorite_rentals`
--
ALTER TABLE `user_favorite_rentals`
  ADD PRIMARY KEY (`user_id`,`rental_property_id`),
  ADD KEY `rental_property_id` (`rental_property_id`);

--
-- Indexes for table `user_favorite_selling`
--
ALTER TABLE `user_favorite_selling`
  ADD PRIMARY KEY (`user_id`,`selling_property_id`),
  ADD KEY `selling_property_id` (`selling_property_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rental_properties`
--
ALTER TABLE `rental_properties`
  MODIFY `rental_property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_transactions`
--
ALTER TABLE `room_transactions`
  MODIFY `room_transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `selling_properties`
--
ALTER TABLE `selling_properties`
  MODIFY `selling_property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `tenant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `fk_expenses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rental_properties`
--
ALTER TABLE `rental_properties`
  ADD CONSTRAINT `rental_properties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_rental_property` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_transactions`
--
ALTER TABLE `room_transactions`
  ADD CONSTRAINT `room_transactions_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_transactions_ibfk_2` FOREIGN KEY (`room_no`) REFERENCES `rooms` (`room_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_transactions_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selling_properties`
--
ALTER TABLE `selling_properties`
  ADD CONSTRAINT `selling_properties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `fk_tenants_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_favorite_rentals`
--
ALTER TABLE `user_favorite_rentals`
  ADD CONSTRAINT `user_favorite_rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_favorite_rentals_ibfk_2` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`);

--
-- Constraints for table `user_favorite_selling`
--
ALTER TABLE `user_favorite_selling`
  ADD CONSTRAINT `user_favorite_selling_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_favorite_selling_ibfk_2` FOREIGN KEY (`selling_property_id`) REFERENCES `selling_properties` (`selling_property_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
