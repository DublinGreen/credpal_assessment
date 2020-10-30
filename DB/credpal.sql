-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 30, 2020 at 05:15 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `credpal`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `account_number` char(255) NOT NULL,
  `account_type` enum('CURRENT','SAVINGS','CREDIT') NOT NULL DEFAULT 'CREDIT',
  `status` enum('ACTIVE','SUSPENDED','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `account_number`, `account_type`, `status`, `updated_at`, `created_at`) VALUES
(14, 22, '2139686956', 'CREDIT', 'ACTIVE', '2020-10-28 22:17:35', '2020-10-28 22:17:35'),
(15, 23, '4592661616', 'CREDIT', 'ACTIVE', '2020-10-28 23:29:12', '2020-10-28 23:29:12'),
(17, 25, '4456554024', 'CREDIT', 'ACTIVE', '2020-10-28 23:43:19', '2020-10-28 23:43:19'),
(24, 32, '1164383802', 'CREDIT', 'ACTIVE', '2020-10-29 04:55:05', '2020-10-29 04:55:05'),
(33, 42, '5843163454', 'CREDIT', 'ACTIVE', '2020-10-30 04:31:27', '2020-10-30 04:31:27'),
(34, 43, '9031595909', 'CREDIT', 'ACTIVE', '2020-10-30 04:34:12', '2020-10-30 04:34:12'),
(35, 44, '9755703113', 'CREDIT', 'ACTIVE', '2020-10-30 04:36:36', '2020-10-30 04:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `id` int(11) NOT NULL,
  `name` char(255) NOT NULL,
  `value` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `name`, `value`, `updated_at`, `created_at`) VALUES
(1, 'SEND_REGISTRATION_SMS', 'NO', '2020-10-28 20:30:24', '2020-10-28 20:30:24'),
(2, 'REFERRAL_BONUS', 'YES', '2020-10-29 16:05:21', '2020-10-29 16:05:21');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `handled_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` enum('PENDING','RESOLVED') DEFAULT 'PENDING',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `explain_what_happened` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

CREATE TABLE `ledger` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('SUCCESS','FAILED','PENDING') NOT NULL DEFAULT 'PENDING',
  `amount` bigint(20) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ledger`
--

INSERT INTO `ledger` (`id`, `sender_id`, `receiver_id`, `status`, `amount`, `description`, `updated_at`, `created_at`) VALUES
(7, 0, 42, 'SUCCESS', 10000000, NULL, '2020-10-30 04:31:27', '2020-10-30 04:31:27'),
(8, 42, 44, 'SUCCESS', 1000, NULL, '2020-10-30 04:36:36', '2020-10-30 04:36:36'),
(9, 42, 22, 'SUCCESS', 50000, NULL, '2020-10-30 07:22:30', '2020-10-30 07:22:30'),
(10, 22, 23, 'SUCCESS', 2000, 'test', '2020-10-30 14:57:22', '2020-10-30 14:57:22'),
(11, 22, 43, 'SUCCESS', 40495, 'rest', '2020-10-30 15:25:10', '2020-10-30 15:25:10'),
(12, 22, 43, 'SUCCESS', 50, 'Test', '2020-10-30 15:26:26', '2020-10-30 15:26:26'),
(13, 22, 43, 'SUCCESS', 30, 'test', '2020-10-30 15:28:15', '2020-10-30 15:28:15'),
(14, 22, 43, 'SUCCESS', 10, 'test', '2020-10-30 15:31:28', '2020-10-30 15:31:28'),
(15, 22, 43, 'SUCCESS', 1997, 'test', '2020-10-30 15:35:26', '2020-10-30 15:35:26'),
(16, 22, 43, 'SUCCESS', 494, 'test', '2020-10-30 15:38:00', '2020-10-30 15:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('DEFAULT','REGULAR') NOT NULL DEFAULT 'REGULAR',
  `first_name` char(255) NOT NULL,
  `middle_name` char(255) NOT NULL,
  `last_name` char(255) NOT NULL,
  `email` char(255) NOT NULL,
  `password` char(255) DEFAULT NULL,
  `email_validated` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `mobile` char(15) NOT NULL,
  `mobile_validated` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `mobile_verification_code` char(10) DEFAULT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `referral_codes` char(255) DEFAULT NULL,
  `referrer_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `email_validated`, `mobile`, `mobile_validated`, `mobile_verification_code`, `status`, `referral_codes`, `referrer_id`, `updated_at`, `created_at`) VALUES
(22, 'REGULAR', 'idisimagha', 'bernard', 'dublin-green', 'greendublin007@gmail.com', '$2y$10$ntnfv6FrlikQbCQ6hiIt..QTz/ZtBoCJbDDHy10VBqt1UOkB.KXqi', 'NO', '07032090809', 'NO', NULL, 'ACTIVE', '3150a01a7b4fccbb22ffb357b146bf8c', 0, '2020-10-30 04:14:46', '2020-10-28 22:17:35'),
(23, 'REGULAR', 'juliet', '', 'wilcox', 'julietwilcox@gmail.com', '$2y$10$5t/nmMrtd9H5C.RqMakkyOCOiKInu/JtmGcgbi/SkpmmZNOB.fDzG', 'NO', '08023633856', 'NO', NULL, 'ACTIVE', 'e91a0641daf34235d25f603f8f5d350e', 0, '2020-10-28 23:29:12', '2020-10-28 23:29:12'),
(25, 'REGULAR', 'idawari', '', 'dublin-green', 'idawari@gmail.com', '$2y$10$k6h1w0nhGEtqzE84Nh.ls.tzSnI1uvy2ujo92BZELKfsJoPPzGo76', 'NO', '08095060650', 'NO', NULL, 'ACTIVE', '8ccf275d6a8291b51e4c843d7ba91ff4', 0, '2020-10-28 23:43:19', '2020-10-28 23:43:19'),
(32, 'REGULAR', 'sotonye', '', 'dublin-green', 'sotonye@gmail.com', '$2y$10$8BApPSdcMzZ/N.548wkh2eCiWWTEd75BGrWiBFqAST3NAnysKbGhq', 'NO', '07032010807', 'NO', NULL, 'ACTIVE', '0dfeb289393e2c1df37da61a7d252d93', 0, '2020-10-29 04:55:04', '2020-10-29 04:55:04'),
(34, 'REGULAR', 'janet', '', 'partick', 'janetpatrick@gmail.com', '$2y$10$iLjSst1CfP3ETk/cNVQP1OUpG73DYCs8ap6c16iv4XvhfGr/IT10W', 'NO', '07032090746', 'NO', NULL, 'ACTIVE', '0729c82284bb483753ed2c7a8bcd900f', 22, '2020-10-29 15:00:02', '2020-10-29 15:00:02'),
(42, 'DEFAULT', 'CredPal', 'CredPal', 'CredPal', 'credpa@credPal.com', '$2y$10$vujQiCTY/rOQNSoxE4dYrO7d6Wgf5bmL92/DoO4n/j1HYpK2OeUGG', 'YES', '07011112222', 'YES', '1234567890', 'ACTIVE', '000001111122222', 0, '2020-10-30 04:31:27', '2020-10-30 04:31:27'),
(43, 'REGULAR', 'tosin', 'fine girl', 'dublin-green', 'tosin@gmail.com', '$2y$10$OxL/UQRRfPiFZrrFizeFH.SJdJbbtDGl6X6GFl4ftsVV54bwtj/gC', 'NO', '07032097357', 'NO', NULL, 'ACTIVE', '32e6ce064abd3c3be912a3dac7b235b9', 0, '2020-10-30 04:34:54', '2020-10-30 04:34:12'),
(44, 'REGULAR', 'tosinmother', '', 'dublin-green', 'tosinmother@gmail.com', '$2y$10$a5Unldyk6PdArU90ZM8.A.4pOMrD.WKlP28zJI7z1poHbZMIjr8Hi', 'NO', '07032097528', 'NO', NULL, 'ACTIVE', '2348873a6bb337fd51a201a130a81c39', 43, '2020-10-30 04:36:36', '2020-10-30 04:36:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`account_number`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ledger`
--
ALTER TABLE `ledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ledger_receiver_id_to_users_id` (`receiver_id`),
  ADD KEY `ledger_sender_id_to_users_id` (`sender_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `referral_codes` (`referral_codes`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ledger`
--
ALTER TABLE `ledger`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_to_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `ledger`
--
ALTER TABLE `ledger`
  ADD CONSTRAINT `ledger_receiver_id_to_users_id` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
