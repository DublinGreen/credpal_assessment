-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 29, 2020 at 08:49 PM
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
(14, 22, '2139686956', 'CREDIT', 'NOT ACTIVE', '2020-10-28 22:17:35', '2020-10-28 22:17:35'),
(15, 23, '4592661616', 'CREDIT', 'NOT ACTIVE', '2020-10-28 23:29:12', '2020-10-28 23:29:12'),
(17, 25, '4456554024', 'CREDIT', 'NOT ACTIVE', '2020-10-28 23:43:19', '2020-10-28 23:43:19'),
(24, 32, '1164383802', 'CREDIT', 'NOT ACTIVE', '2020-10-29 04:55:05', '2020-10-29 04:55:05');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(22, 'REGULAR', 'idisimagha', '', 'dublin-green', 'greendublin007@gmail.com', '$2y$10$ntnfv6FrlikQbCQ6hiIt..QTz/ZtBoCJbDDHy10VBqt1UOkB.KXqi', 'NO', '07032090809', 'NO', NULL, 'NOT ACTIVE', '3150a01a7b4fccbb22ffb357b146bf8c', 0, '2020-10-28 22:17:35', '2020-10-28 22:17:35'),
(23, 'REGULAR', 'juliet', '', 'wilcox', 'julietwilcox@gmail.com', '$2y$10$5t/nmMrtd9H5C.RqMakkyOCOiKInu/JtmGcgbi/SkpmmZNOB.fDzG', 'NO', '08023633856', 'NO', NULL, 'NOT ACTIVE', 'e91a0641daf34235d25f603f8f5d350e', 0, '2020-10-28 23:29:12', '2020-10-28 23:29:12'),
(25, 'REGULAR', 'idawari', '', 'dublin-green', 'idawari@gmail.com', '$2y$10$k6h1w0nhGEtqzE84Nh.ls.tzSnI1uvy2ujo92BZELKfsJoPPzGo76', 'NO', '08095060650', 'NO', NULL, 'NOT ACTIVE', '8ccf275d6a8291b51e4c843d7ba91ff4', 0, '2020-10-28 23:43:19', '2020-10-28 23:43:19'),
(32, 'REGULAR', 'sotonye', '', 'dublin-green', 'sotonye@gmail.com', '$2y$10$8BApPSdcMzZ/N.548wkh2eCiWWTEd75BGrWiBFqAST3NAnysKbGhq', 'NO', '07032010807', 'NO', NULL, 'NOT ACTIVE', '0dfeb289393e2c1df37da61a7d252d93', 0, '2020-10-29 04:55:04', '2020-10-29 04:55:04'),
(34, 'REGULAR', 'janet', '', 'partick', 'janetpatrick@gmail.com', '$2y$10$iLjSst1CfP3ETk/cNVQP1OUpG73DYCs8ap6c16iv4XvhfGr/IT10W', 'NO', '07032090746', 'NO', NULL, 'NOT ACTIVE', '0729c82284bb483753ed2c7a8bcd900f', 22, '2020-10-29 15:00:02', '2020-10-29 15:00:02');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
