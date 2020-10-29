-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 09, 2020 at 08:45 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `corporate-setup-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2020_05_02_014937_create_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `to_whom` bigint(20) UNSIGNED DEFAULT NULL,
  `title` char(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('SENT','NOT SENT','NOT APPLICABLE') NOT NULL DEFAULT 'NOT APPLICABLE',
  `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `file` char(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `status`, `description`, `requirements`, `file`, `updated_at`, `created_at`) VALUES
(1, 'Business Registration', 'ACTIVE', 'Business Registration (CAC) application', 'Fill the forms and submit for processing.', 'BUSINESS_REGISTRATION_CAC.zip', '2020-08-09 16:25:29', '2020-08-09 16:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `email` char(255) NOT NULL,
  `phone` char(50) NOT NULL,
  `location` char(255) NOT NULL,
  `service_id` bigint(20) NOT NULL,
  `service_name` int(10) UNSIGNED NOT NULL,
  `status` enum('PROCESSING','DONE') NOT NULL DEFAULT 'PROCESSING',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `description` text NOT NULL,
  `startDate` char(255) DEFAULT NULL,
  `startTime` char(255) DEFAULT 'NULL',
  `endDate` char(255) DEFAULT 'NULL',
  `endTime` char(255) DEFAULT 'NULL',
  `location` char(255) NOT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `training_image_path` char(255) NOT NULL,
  `accessKey` char(255) DEFAULT NULL,
  `type` enum('PAID','FREE') NOT NULL DEFAULT 'FREE',
  `currency` enum('$','&#8358;') NOT NULL DEFAULT '&#8358;',
  `price` char(50) NOT NULL DEFAULT '0.00',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`id`, `name`, `description`, `startDate`, `startTime`, `endDate`, `endTime`, `location`, `status`, `training_image_path`, `accessKey`, `type`, `currency`, `price`, `updated_at`, `created_at`) VALUES
(1, 'training 101', 'test training', 'NULL', 'NULL', 'NULL', 'NULL', 'Lagos, Nigeria', 'ACTIVE', 'uploads/trainings/card-2.jpg', '1373h3ur84hfhf8vh438hf', 'FREE', '&#8358;', '0.00', '2020-08-09 03:11:50', '2020-08-09 03:11:50'),
(2, 'testing 192', 'test training', 'NULL', 'NULL', 'NULL', 'NULL', 'Lagos, Nigeria', 'ACTIVE', 'uploads/trainings/card-2.jpg', '1373h3ur84hfhf8vh438hfeeee', 'FREE', '&#8358;', '0.00', '2020-08-09 13:42:26', '2020-08-09 13:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `training_attendees`
--

CREATE TABLE `training_attendees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` char(255) NOT NULL,
  `status` enum('ATTENDED','DID NOT ATTEND') NOT NULL DEFAULT 'DID NOT ATTEND',
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `training_files`
--

CREATE TABLE `training_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `path` char(255) NOT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `orderNumber` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `training_files`
--

INSERT INTO `training_files` (`id`, `training_id`, `name`, `path`, `status`, `orderNumber`, `created_at`) VALUES
(1, 1, 'testimonial_1.mp4', 'uploads/trainings/testimonial_1.mp4', 'ACTIVE', 1, '2020-08-09 03:13:38'),
(3, 2, 'testimonial_2.mp4', 'uploads/trainings/testimonial_2.mp4', 'ACTIVE', 1, '2020-08-09 14:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NOT ACTIVE',
  `confirmed_email` enum('YES','NO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO',
  `name` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `status`, `confirmed_email`, `name`, `email`, `website`, `about`, `password`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'NOT ACTIVE', 'NO', 'bernard', 'greendublin009@gmail.com', NULL, NULL, '$2y$10$x1W.NfwYhjCOjajjHq8bLeJTzSONj.973DBSvOD5DYVQdpoNYM0oS', NULL, '2020-04-30 23:00:00', '2020-05-08 23:00:00'),
(2, 'NOT ACTIVE', 'NO', 'babafemi.ibitolu', 'babafemi.ibitolu@hbng.com', NULL, NULL, NULL, NULL, '2020-05-02 03:47:34', '2020-05-02 03:47:34'),
(17, 'ACTIVE', 'YES', 'Cliquedom', 'greendublin007@gmail.com', 'www.cliquedom.com', 'about cliquedom technologies. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).\n\n', '$2y$10$T.r.itGRWMfBeXvcr.Et1uxTY.XOpB4MYKqf5LObYvKf5TJKO5cWC', '+2347032090809', '2020-07-19 13:58:36', '2020-07-30 01:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `users_documents`
--

CREATE TABLE `users_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_type` char(255) DEFAULT NULL,
  `expiration_interval` char(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `path` char(255) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `description` text NOT NULL,
  `uploaded_by` char(255) DEFAULT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `expiring_date` char(50) DEFAULT NULL,
  `document_key` char(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_documents`
--

INSERT INTO `users_documents` (`id`, `document_type`, `expiration_interval`, `user_id`, `path`, `name`, `description`, `uploaded_by`, `status`, `expiring_date`, `document_key`, `created_at`, `updated_at`) VALUES
(1, 'Corporate Affairs commission(CAC)', '6', 17, 'uploads/documents/0466f4c18f7357cf4b91c627268f210d1596358433.jpg', '0466f4c18f7357cf4b91c627268f210d1596358433.jpg', 'this is a test to update again', 'greendublin007@gmail.com', 'ACTIVE', '2020-08-17', '$2y$10$NPGTTGA5KVlLv6HQlOnm2O1J0IBI0CKWaDiRzXrv1BO71lpk/nwji', '2020-08-02 08:53:53', '2020-08-02 21:09:53'),
(2, 'National pension commission', '6', 17, 'uploads/documents/2e8f21de8f53d7c59a1eafc7ba22d1531596368733.pdf', '2e8f21de8f53d7c59a1eafc7ba22d1531596368733.pdf', 'test please ignore', 'greendublin007@gmail.com', 'ACTIVE', '2020-10-24', '$2y$10$H2MXCxzy0s74mwfQu41hCeTd9C9isG6g8WWT4F5bU/PRXkPme1Ixq', '2020-08-02 11:45:33', '2020-08-02 11:45:33'),
(3, 'Nigerian maritime Administration & Safety Agency(NIMASA)', '24', 17, 'uploads/documents/8ed05836b0bfd9fee6651a8b02b309f61596724829.png', '8ed05836b0bfd9fee6651a8b02b309f61596724829.png', 'test with document expiration interval', 'greendublin007@gmail.com', 'ACTIVE', '2020-08-31', '$2y$10$G.sASKB3aFszz0N17f8Stul8wgJpd3SWdnWJ3Q0Ww/Zrg/mz8wB42', '2020-08-06 14:40:29', '2020-08-06 14:40:29'),
(4, 'Department of petroleum resources(DPR)', '48', 17, 'uploads/documents/3dcaf761cb5a748bd984393c759a25ba1596724875.png', '3dcaf761cb5a748bd984393c759a25ba1596724875.png', 'test again', 'greendublin007@gmail.com', 'ACTIVE', '2020-08-29', '$2y$10$3XIWKpO5Np76ARNawxbGjeVCjgumfizZJf5qSBGEiIxSQa9gTpw.2', '2020-08-06 14:41:15', '2020-08-06 14:41:15');

-- --------------------------------------------------------

--
-- Table structure for table `users_document_expiration`
--

CREATE TABLE `users_document_expiration` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `name` char(255) NOT NULL,
  `value_in_months` int(11) NOT NULL,
  `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_document_expiration`
--

INSERT INTO `users_document_expiration` (`id`, `status`, `name`, `value_in_months`, `time_added`) VALUES
(1, 'ACTIVE', '6 months', 6, '2020-08-06 14:07:28'),
(2, 'ACTIVE', '1 year', 12, '2020-08-06 14:07:28'),
(3, 'ACTIVE', '2 years', 24, '2020-08-06 14:07:28'),
(4, 'NOT ACTIVE', '3 years', 36, '2020-08-06 14:07:28'),
(5, 'ACTIVE', '4 years', 48, '2020-08-06 14:07:28'),
(6, 'ACTIVE', '5 years', 60, '2020-08-06 14:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `users_document_send_history`
--

CREATE TABLE `users_document_send_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_owner` bigint(20) UNSIGNED NOT NULL,
  `status` enum('SENT','NOT SENT') NOT NULL DEFAULT 'NOT SENT',
  `email` char(255) NOT NULL,
  `document_key` char(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_document_send_history`
--

INSERT INTO `users_document_send_history` (`id`, `document_owner`, `status`, `email`, `document_key`, `created_at`, `updated_at`) VALUES
(1, 17, 'NOT SENT', 'greendublin007@gmail.com', '$2y$10$NPGTTGA5KVlLv6HQlOnm2O1J0IBI0CKWaDiRzXrv1BO71lpk/nwji', '2020-08-03 00:58:21', '2020-08-03 00:58:21'),
(2, 17, 'NOT SENT', 'greendublin@yahoo.com', '$2y$10$NPGTTGA5KVlLv6HQlOnm2O1J0IBI0CKWaDiRzXrv1BO71lpk/nwji', '2020-08-03 00:58:21', '2020-08-03 00:58:21'),
(3, 17, 'NOT SENT', 'greendublin007@gmail.com', '$2y$10$NPGTTGA5KVlLv6HQlOnm2O1J0IBI0CKWaDiRzXrv1BO71lpk/nwji', '2020-08-03 14:51:56', '2020-08-03 14:51:56'),
(4, 17, 'NOT SENT', 'greendublin007@gmail.com', '$2y$10$NPGTTGA5KVlLv6HQlOnm2O1J0IBI0CKWaDiRzXrv1BO71lpk/nwji', '2020-08-03 14:54:42', '2020-08-03 14:54:42'),
(5, 17, 'NOT SENT', 'greendublin@yahoo.com', '$2y$10$NPGTTGA5KVlLv6HQlOnm2O1J0IBI0CKWaDiRzXrv1BO71lpk/nwji', '2020-08-03 14:54:43', '2020-08-03 14:54:43');

-- --------------------------------------------------------

--
-- Table structure for table `users_document_type`
--

CREATE TABLE `users_document_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` char(255) NOT NULL,
  `status` enum('ACTIVE','NOT ACTIVE') NOT NULL DEFAULT 'NOT ACTIVE',
  `description` text NOT NULL,
  `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_document_type`
--

INSERT INTO `users_document_type` (`id`, `name`, `status`, `description`, `time_added`) VALUES
(1, 'Department of petroleum resources(DPR)', 'ACTIVE', 'Department of petroleum resources(DPR)', '2020-07-30 01:16:05'),
(2, 'Corporate Affairs commission(CAC)', 'ACTIVE', 'Corporate Affairs commission(CAC)', '2020-07-30 01:16:19'),
(3, 'Nigerian maritime Administration & Safety Agency(NIMASA)', 'ACTIVE', 'Nigerian maritime Administration & Safety Agency(NIMASA)', '2020-07-30 01:16:44'),
(4, 'State government Tax', 'ACTIVE', 'State government Tax', '2020-07-30 01:16:44'),
(5, 'National pension commission', 'ACTIVE', 'National pension commission', '2020-07-30 01:17:04'),
(6, 'National social insurance Trust Fund', 'ACTIVE', 'National social insurance Trust Fund', '2020-07-30 01:17:04'),
(7, 'Industrial Trust Fund', 'ACTIVE', 'Industrial Trust Fund', '2020-07-30 01:17:34'),
(8, 'NIPEX portal', 'ACTIVE', 'NIPEX portal', '2020-07-30 01:17:34'),
(9, 'Others', 'ACTIVE', 'Others non defined documents', '2020-07-30 01:17:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_file` (`file`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`accessKey`);

--
-- Indexes for table `training_attendees`
--
ALTER TABLE `training_attendees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training_files`
--
ALTER TABLE `training_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `path` (`path`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users_documents`
--
ALTER TABLE `users_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_document_to_company_id` (`user_id`);

--
-- Indexes for table `users_document_expiration`
--
ALTER TABLE `users_document_expiration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_document_send_history`
--
ALTER TABLE `users_document_send_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_document_type`
--
ALTER TABLE `users_document_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `training_attendees`
--
ALTER TABLE `training_attendees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_files`
--
ALTER TABLE `training_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users_documents`
--
ALTER TABLE `users_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users_document_expiration`
--
ALTER TABLE `users_document_expiration`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users_document_send_history`
--
ALTER TABLE `users_document_send_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_document_type`
--
ALTER TABLE `users_document_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
