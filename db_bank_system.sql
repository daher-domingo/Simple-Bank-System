-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2026 at 10:24 AM
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
-- Database: `db_bank_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profile`
--

CREATE TABLE `tbl_profile` (
  `id` int(11) NOT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_profile`
--

INSERT INTO `tbl_profile` (`id`, `account_number`, `lastname`, `firstname`, `middlename`, `birthdate`, `gender`, `created_at`) VALUES
(1, '85594', 'Domingo', 'Dacer', 'Vergara', '2002-05-25', 'Male', '2026-04-10 16:07:21'),
(2, '1234', 'Domingo', 'Khim juzz', 'Vergara', '2010-01-23', 'Male', '2026-04-10 16:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `trans_id` int(11) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `trans_type` enum('Deposit','Withdrawal') NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_transaction`
--

INSERT INTO `tbl_transaction` (`trans_id`, `trans_date`, `profile_id`, `account_number`, `amount`, `trans_type`, `remarks`, `created_at`) VALUES
(1, '2026-04-10', 2, '1234', 1500.00, 'Deposit', '', '2026-04-10 16:09:08'),
(2, '2026-04-10', 1, '85594', 2500.00, 'Deposit', '', '2026-04-10 16:09:37'),
(3, '2026-04-10', 1, '85594', 500.00, 'Withdrawal', '', '2026-04-10 16:10:26'),
(4, '2026-04-11', 2, '1234', 9000.00, 'Deposit', '-', '2026-04-11 03:20:56'),
(5, '2026-04-11', 1, '85594', 2500.00, 'Deposit', '', '2026-04-11 03:22:30'),
(6, '2026-04-11', 1, '85594', 6500.00, 'Deposit', '', '2026-04-11 03:25:43'),
(7, '2026-04-11', 2, '1234', 350.00, 'Deposit', '', '2026-04-11 03:27:46'),
(8, '2026-04-11', 1, '85594', 2400.00, 'Deposit', '', '2026-04-11 03:30:09'),
(9, '2026-04-11', 2, '1234', 9850.00, 'Withdrawal', '', '2026-04-11 03:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(2, 'admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_profile`
--
ALTER TABLE `tbl_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `profile_id` (`profile_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_profile`
--
ALTER TABLE `tbl_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD CONSTRAINT `tbl_transaction_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `tbl_profile` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
