-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2024 at 08:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `staff_sync`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attached_by` int(11) DEFAULT NULL,
  `model_name` varchar(125) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `file` varchar(125) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `attached_by`, `model_name`, `model_id`, `file`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(16, 1, '\\App\\Models\\Estimate', 3, 'ESTIMATE--d58Jak1714601838.jpg', 1, '2024-05-01 17:17:18', '2024-05-01 17:17:18', NULL),
(15, 1, '\\App\\Models\\Estimate', 3, 'ESTIMATE--XlkbJM1714601838.png', 1, '2024-05-01 17:17:18', '2024-05-01 17:17:18', NULL),
(14, 1, '\\App\\Models\\Estimate', 3, 'ESTIMATE--EuK1RK1714601838.jpg', 1, '2024-05-01 17:17:18', '2024-05-01 17:17:18', NULL),
(13, 1, '\\App\\Models\\Estimate', 3, 'ESTIMATE--61t3Xw1714601838.jpg', 1, '2024-05-01 17:17:18', '2024-05-01 17:17:18', NULL),
(12, 1, '\\App\\Models\\Estimate', 2, 'ESTIMATE--XoYLRM1714601438.png', 1, '2024-05-01 17:10:38', '2024-05-01 17:10:38', NULL),
(11, 1, '\\App\\Models\\Estimate', 2, 'ESTIMATE--5MySL61714601438.jpg', 1, '2024-05-01 17:10:38', '2024-05-01 17:10:38', NULL),
(10, 1, '\\App\\Models\\Estimate', 2, 'ESTIMATE--VivQow1714601438.jpg', 1, '2024-05-01 17:10:38', '2024-05-01 17:10:38', NULL),
(9, 1, '\\App\\Models\\Estimate', 2, 'ESTIMATE--bSugsr1714601438.jpg', 1, '2024-05-01 17:10:38', '2024-05-01 17:10:38', NULL),
(17, 1, '\\App\\Models\\Estimate', 4, 'ESTIMATE--7SHWLq1714602462.jpg', 1, '2024-05-01 17:27:42', '2024-05-01 17:27:42', NULL),
(18, 1, '\\App\\Models\\Estimate', 4, 'ESTIMATE--gEX1bz1714602462.jpg', 1, '2024-05-01 17:27:42', '2024-05-01 17:27:42', NULL),
(19, 1, '\\App\\Models\\Estimate', 4, 'ESTIMATE--YMfPlk1714602462.png', 1, '2024-05-01 17:27:42', '2024-05-01 17:27:42', NULL),
(20, 1, '\\App\\Models\\Estimate', 4, 'ESTIMATE--Edy1zT1714602462.png', 1, '2024-05-01 17:27:42', '2024-05-01 17:27:42', NULL),
(21, 1, '\\App\\Models\\Estimate', 4, 'ESTIMATE--fhxaox1714602462.png', 1, '2024-05-01 17:27:42', '2024-05-01 17:27:42', NULL),
(22, 1, '\\App\\Models\\Estimate', 5, 'ESTIMATE--9Ipqt51714607999.png', 1, '2024-05-01 18:59:59', '2024-05-01 18:59:59', NULL),
(23, 1, '\\App\\Models\\Estimate', 5, 'ESTIMATE--uX9CU81714607999.jpg', 1, '2024-05-01 18:59:59', '2024-05-01 18:59:59', NULL),
(24, 1, '\\App\\Models\\Estimate', 6, 'ESTIMATE--k4kvSv1714608025.png', 1, '2024-05-01 19:00:25', '2024-05-01 19:00:25', NULL),
(25, 1, '\\App\\Models\\Estimate', 6, 'ESTIMATE--1pEmgt1714608025.png', 1, '2024-05-01 19:00:25', '2024-05-01 19:00:25', NULL),
(26, 1, '\\App\\Models\\Estimate', 6, 'ESTIMATE--oYgu041714608025.png', 1, '2024-05-01 19:00:25', '2024-05-01 19:00:25', NULL),
(27, 1, '\\App\\Models\\Estimate', 7, 'ESTIMATE--liIUnC1714608050.png', 1, '2024-05-01 19:00:50', '2024-05-01 19:00:50', NULL),
(28, 1, '\\App\\Models\\Estimate', 7, 'ESTIMATE--xnGagg1714608050.jpg', 1, '2024-05-01 19:00:50', '2024-05-01 19:00:50', NULL),
(29, 1, '\\App\\Models\\Estimate', 7, 'ESTIMATE--s8EfsX1714608050.png', 1, '2024-05-01 19:00:50', '2024-05-01 19:00:50', NULL),
(30, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--hfjTRY1714673471.png', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(31, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--BLIvlV1714673471.jpg', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(32, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--6v6kf31714673471.png', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(33, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--0KaE8X1714673471.png', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(34, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--ElXzDH1714673471.png', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(35, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--mZ4lZ21714673471.png', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(36, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--Vkyz5l1714673471.jpg', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(37, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--wtacwt1714673471.png', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(38, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--AuLNvs1714673471.png', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(39, 5, '\\App\\Models\\Estimate', 9, 'ESTIMATE--y7GK6g1714673471.jpg', 1, '2024-05-02 13:11:11', '2024-05-02 13:11:11', NULL),
(40, 1, '\\App\\Models\\Estimate', 20, 'ESTIMATE--ZP6kDP1714766493.png', 1, '2024-05-03 15:01:33', '2024-05-03 15:01:33', NULL),
(41, 1, '\\App\\Models\\Estimate', 20, 'ESTIMATE--qqhGOL1714766493.png', 1, '2024-05-03 15:01:33', '2024-05-03 15:01:33', NULL),
(42, 1, '\\App\\Models\\Estimate', 25, 'ESTIMATE-1714767261.jpeg', 1, '2024-05-03 15:14:21', '2024-05-03 15:14:21', NULL),
(43, 1, '\\App\\Models\\Estimate', 26, 'ESTIMATE--dew0uy1714767382.png', 1, '2024-05-03 15:16:22', '2024-05-03 15:16:22', NULL),
(44, 1, '\\App\\Models\\Estimate', 26, 'ESTIMATE--zlBh4T1714767382.png', 1, '2024-05-03 15:16:22', '2024-05-03 15:16:22', NULL),
(45, 1, '\\App\\Models\\Estimate', 26, 'ESTIMATE--1pDfC31714767382.png', 1, '2024-05-03 15:16:22', '2024-05-03 15:16:22', NULL),
(46, 1, '\\App\\Models\\Estimate', 26, 'ESTIMATE--ZJKOM61714767382.png', 1, '2024-05-03 15:16:22', '2024-05-03 15:16:22', NULL),
(47, 1, '\\App\\Models\\Estimate', 29, 'ESTIMATE--I516Td1715287044.jpg', 1, '2024-05-09 15:37:24', '2024-05-09 15:37:24', NULL),
(48, 1, '\\App\\Models\\Estimate', 29, 'ESTIMATE--OT2uwV1715287044.png', 1, '2024-05-09 15:37:24', '2024-05-09 15:37:24', NULL),
(49, 1, '\\App\\Models\\Estimate', 29, 'ESTIMATE--ix5upC1715287044.jpg', 1, '2024-05-09 15:37:24', '2024-05-09 15:37:24', NULL),
(50, 1, '\\App\\Models\\Estimate', 30, 'ESTIMATE--NuffhY1715287099.png', 1, '2024-05-09 15:38:19', '2024-05-09 15:38:19', NULL),
(51, 1, '\\App\\Models\\Estimate', 30, 'ESTIMATE--BveWq61715287099.jpg', 1, '2024-05-09 15:38:19', '2024-05-09 15:38:19', NULL),
(52, 1, '\\App\\Models\\Estimate', 31, 'ESTIMATE--532LVP1715287251.png', 1, '2024-05-09 15:40:51', '2024-05-09 15:40:51', NULL),
(53, 1, '\\App\\Models\\Estimate', 31, 'ESTIMATE--OobxrZ1715287251.png', 1, '2024-05-09 15:40:51', '2024-05-09 15:40:51', NULL),
(54, 1, '\\App\\Models\\Estimate', 32, 'ESTIMATE--GyX1qk1715287306.png', 1, '2024-05-09 15:41:46', '2024-05-09 15:41:46', NULL),
(55, 1, '\\App\\Models\\Estimate', 32, 'ESTIMATE--Zvf1n61715287306.png', 1, '2024-05-09 15:41:46', '2024-05-09 15:41:46', NULL),
(56, 1, '\\App\\Models\\Estimate', 33, 'ESTIMATE--aGLgdc1716333116.jpg', 1, '2024-05-21 18:11:56', '2024-05-21 18:11:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `base_url` varchar(125) DEFAULT NULL,
  `name` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_id`, `base_url`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, 'Cyberonix Consulting Limited', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(2, 2, NULL, 'Vertical Edge', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(3, 3, NULL, 'Braincell  Technology', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(4, 4, NULL, 'C-Level', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(5, 5, NULL, 'DELVE12', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(6, 6, NULL, 'HORIZONTAL', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(7, 7, NULL, 'MERCURY', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(8, 8, NULL, 'MOMYOM', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(9, 9, NULL, 'SOFTNOVA', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(10, 10, NULL, 'SOFTFELLOW', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(11, 11, NULL, 'SWYFTCUBE', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(12, 12, NULL, 'SWYFTZONE', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(13, 13, NULL, 'TECHCOMRADE', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL),
(14, 14, NULL, 'ROCKET-FLARE-LABS', '2024-04-24 16:01:16', '2024-04-24 16:01:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `manager_id` bigint(20) DEFAULT NULL,
  `parent_department_id` bigint(20) DEFAULT NULL,
  `name` varchar(125) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `manager_id`, `parent_department_id`, `name`, `description`, `location`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Main Department', NULL, NULL, 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `department_users`
--

CREATE TABLE `department_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_users`
--

INSERT INTO `department_users` (`id`, `department_id`, `user_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-04-22', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(125) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `title`, `description`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Vice President - Business Unit Head', 'Vice President - Business Unit Head', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(2, 'Director', 'Director', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(3, 'N/A', 'N/A', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(4, 'Manager - Account & Finance', 'Manager - Account & Finance', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(5, 'CEO', 'CEO', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(6, 'Senior Vice President (SVP) - Management Committee', 'Senior Vice President (SVP) - Management Committee', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(7, 'Manager - Business Development', 'Manager - Business Development', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(8, 'Assistant Executive - Customer Support', 'Assistant Executive - Customer Support', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(9, 'Senior Manager - Business Development', 'Senior Manager - Business Development', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(10, 'Senior Manager - Customer Support', 'Senior Manager - Customer Support', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(11, '3D Animator', '3D Animator', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(12, 'Sr. Executive Vice President - SEVP', 'Sr. Executive Vice President - SEVP', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(13, 'Assistant Vice President - Customer Support', 'Assistant Vice President - Customer Support', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(14, 'Sr.Manager', 'Sr.Manager', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(15, 'Senior Executive - UI/UX Developer', 'Senior Executive - UI/UX Developer', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(16, 'Sales Executive', 'Sales Executive', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(17, 'Sales Executive - Customer Support', 'Sales Executive - Customer Support', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(18, 'Intern - Graphic Designer', 'Intern - Graphic Designer', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(19, 'IT Support', 'IT Support', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(20, 'Senior Executive - Customer Support', 'Senior Executive - Customer Support', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(21, 'Business Development Executive', 'Business Development Executive', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(22, 'Senior Business Development Executive', 'Senior Business Development Executive', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(23, 'Assistant Manager - Customer Support', 'Assistant Manager - Customer Support', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(24, 'Sweeper', 'Sweeper', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(25, 'Cleaning boys', 'Cleaning boys', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(26, 'Tea boys', 'Tea boys', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(27, 'Business Development  - Executive', 'Business Development  - Executive', 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `employee_requisitions`
--

CREATE TABLE `employee_requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `createdBy` bigint(20) NOT NULL,
  `manager_id` bigint(20) DEFAULT NULL,
  `shift_id` bigint(20) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `title` varchar(125) DEFAULT NULL,
  `max_salary` varchar(125) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL,
  `min_education` varchar(125) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-deactive',
  `deleted_at` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employment_statuses`
--

CREATE TABLE `employment_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) NOT NULL,
  `class` varchar(125) NOT NULL,
  `description` varchar(125) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 1,
  `alias` varchar(125) DEFAULT NULL,
  `deleted_at` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employment_statuses`
--

INSERT INTO `employment_statuses` (`id`, `name`, `class`, `description`, `is_default`, `alias`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Probation', 'warning', 'Probation', 1, 'Probation', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(2, 'Permanent', 'success', 'Permanent', 1, 'Permanent', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(3, 'Terminated', 'danger', 'Terminated', 1, 'Terminated', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(4, 'Full Time', 'info', 'Full Time', 1, 'Full Time', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(5, 'Contract', 'warning', 'Contract', 1, 'Contract', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(6, 'Voluntary', 'info', 'Voluntary', 1, 'Voluntary', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(7, 'Layoffs', 'warning', 'Layoffs', 1, 'Layoffs', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07'),
(8, 'Retirements', 'info', 'Retirements', 1, 'Retirements', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `title` varchar(125) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `price` varchar(125) DEFAULT NULL,
  `raw_data` longtext DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remarks` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estimates`
--

INSERT INTO `estimates` (`id`, `creator_id`, `company_id`, `request_id`, `title`, `description`, `price`, `raw_data`, `status`, `created_at`, `updated_at`, `deleted_at`, `remarks`) VALUES
(6, 1, 3, 4, 'New Estimate Laptoop second time', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', '35000', NULL, 3, '2024-05-01 19:00:25', '2024-05-21 17:33:46', NULL, 'Rejected'),
(7, 1, 3, 4, 'HP Laptop', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.', '76000', NULL, 3, '2024-05-01 19:00:50', '2024-05-21 17:33:46', NULL, 'Rejected'),
(5, 1, 3, 4, 'New Laptop Estimate', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '25000', NULL, 3, '2024-05-01 18:59:59', '2024-05-21 17:33:46', NULL, 'Rejected'),
(8, 5, 1, 5, 'Testing from Api', 'Testing From Api Description', '120', NULL, 2, '2024-05-02 13:10:45', '2024-05-02 13:31:02', NULL, 'Approved'),
(9, 5, 1, 5, 'Testing from Api', 'Testing From Api Description', '120', NULL, 3, '2024-05-02 13:11:11', '2024-05-02 13:31:02', NULL, 'Rejected'),
(10, 1, 3, 4, 'Test from api', 'Testing From Api Description', '100', NULL, 2, '2024-05-03 14:44:05', '2024-05-21 17:32:07', NULL, 'Approved'),
(11, 1, 3, 4, 'Test from api', 'Testing From Api Description', '100', NULL, 3, '2024-05-03 14:49:49', '2024-05-21 17:33:46', NULL, 'Rejected'),
(12, 1, 3, 4, 'Test from api', 'Testing From Api Description', '100', NULL, 3, '2024-05-03 14:51:58', '2024-05-21 17:33:46', NULL, 'Rejected'),
(13, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 14:53:19', '2024-05-21 17:33:46', NULL, 'Rejected'),
(14, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 14:55:03', '2024-05-21 17:33:46', NULL, 'Rejected'),
(15, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 14:58:38', '2024-05-21 17:33:46', NULL, 'Rejected'),
(16, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 15:00:24', '2024-05-21 17:33:46', NULL, 'Rejected'),
(17, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 15:00:47', '2024-05-21 17:33:46', NULL, 'Rejected'),
(18, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 15:01:09', '2024-05-21 17:33:46', NULL, 'Rejected'),
(19, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 15:01:23', '2024-05-21 17:33:46', NULL, 'Rejected'),
(20, 1, 3, 4, 'Test from api', 'Testing From Api Description', '1004', NULL, 3, '2024-05-03 15:01:33', '2024-05-21 17:33:46', NULL, 'Rejected'),
(21, 1, 3, 4, 'Test Base64 Image Upload', 'Testing From Api Description', '322', NULL, 3, '2024-05-03 15:09:14', '2024-05-21 17:33:46', NULL, 'Rejected'),
(22, 1, 3, 4, 'Test Base64 Image Upload', 'Testing From Api Description', '322', NULL, 3, '2024-05-03 15:09:40', '2024-05-21 17:33:46', NULL, 'Rejected'),
(23, 1, 3, 4, 'Test Base64 Image Upload', 'Testing From Api Description', '322', NULL, 3, '2024-05-03 15:11:56', '2024-05-21 17:33:46', NULL, 'Rejected'),
(24, 1, 3, 4, 'Test Base64 Image Upload', 'Testing From Api Description', '322', NULL, 3, '2024-05-03 15:12:22', '2024-05-21 17:33:46', NULL, 'Rejected'),
(25, 1, 3, 4, 'Test Base64 Image Upload', 'Testing From Api Description', '322', NULL, 3, '2024-05-03 15:14:21', '2024-05-21 17:33:46', NULL, 'Rejected'),
(26, 1, 3, 4, 'Test Array Image Upload', 'Testing From Api Description', '4', NULL, 3, '2024-05-03 15:16:22', '2024-05-21 17:33:46', NULL, 'Rejected'),
(27, 1, 3, 4, 'dghfj', 'sd;ajf;', '564', NULL, 3, '2024-05-06 13:59:25', '2024-05-21 17:33:46', NULL, 'Rejected'),
(28, 1, 3, 4, 'Abc', 'Hello', '90', NULL, 3, '2024-05-06 13:59:54', '2024-05-21 17:33:46', NULL, 'Rejected'),
(29, 1, 1, 25, 'Hello', 'Checking the', '200', NULL, 2, '2024-05-09 15:37:24', '2024-05-09 15:39:16', NULL, 'Please approved id'),
(30, 1, 1, 25, 'Chair', 'Chair description hello', '600', NULL, 3, '2024-05-09 15:38:19', '2024-05-09 15:39:16', NULL, 'Rejected'),
(31, 1, 2, 24, 'Add title', 'add description', '100', NULL, 2, '2024-05-09 15:40:51', '2024-05-09 15:52:33', NULL, 'Approved'),
(32, 1, 2, 24, 'add title two', 'add description two', '200', NULL, 3, '2024-05-09 15:41:46', '2024-05-09 15:52:33', NULL, 'Rejected'),
(33, 1, 1, 28, 'Testing', '<p>testing</p>', '120', NULL, 3, '2024-05-21 18:11:56', '2024-05-21 18:20:30', NULL, 'Rejected'),
(34, 1, 1, 28, 'Testing Again', '<p>Testing Again</p>', '150', NULL, 3, '2024-05-21 18:14:41', '2024-05-21 18:20:30', NULL, 'Rejected'),
(35, 1, 1, 28, 'Send the Notification', '<p>dfsdfdsf</p>', '123', NULL, 2, '2024-05-21 18:18:27', '2024-05-21 18:18:50', NULL, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `estimate_statuses`
--

CREATE TABLE `estimate_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) DEFAULT NULL,
  `class` varchar(125) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estimate_statuses`
--

INSERT INTO `estimate_statuses` (`id`, `name`, `class`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pending', 'warning', 1, '2024-05-01 19:02:29', '2024-05-01 19:02:29', NULL),
(2, 'Approved', 'success', 1, '2024-05-01 19:02:29', '2024-05-01 19:02:29', NULL),
(3, 'Rejected', 'danger', 1, '2024-05-01 19:02:29', '2024-05-01 19:02:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(125) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_histories`
--

CREATE TABLE `job_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `parent_designation_id` bigint(20) DEFAULT NULL,
  `designation_id` bigint(20) DEFAULT NULL,
  `employment_status_id` bigint(20) NOT NULL,
  `joining_date` date NOT NULL,
  `vehicle_name` varchar(125) DEFAULT NULL,
  `vehicle_cc` varchar(125) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `deleted_at` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_histories`
--

INSERT INTO `job_histories` (`id`, `created_by`, `user_id`, `parent_designation_id`, `designation_id`, `employment_status_id`, `joining_date`, `vehicle_name`, `vehicle_cc`, `end_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, 2, '2024-04-22', NULL, NULL, NULL, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `log_activities`
--

CREATE TABLE `log_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(125) NOT NULL,
  `url` varchar(125) NOT NULL,
  `method` varchar(125) NOT NULL,
  `ip` varchar(125) NOT NULL,
  `agent` varchar(125) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_activities`
--

INSERT INTO `log_activities` (`id`, `subject`, `url`, `method`, `ip`, `agent`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Profile Updated', 'http://localhost/staff-sync-hub/profile/update/1', 'PATCH', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 1, '2024-05-24 20:05:13', '2024-05-24 20:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(125) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_05_03_185209_create_profiles_table', 1),
(6, '2023_05_03_200801_create_designations_table', 1),
(7, '2023_05_03_200908_create_work_shifts_table', 1),
(8, '2023_05_03_200924_create_departments_table', 1),
(9, '2023_05_04_193435_create_employment_statuses_table', 1),
(10, '2023_05_04_224452_create_job_histories_table', 1),
(11, '2023_05_04_230534_create_salary_histories_table', 1),
(12, '2023_05_05_210039_create_log_activities_table', 1),
(13, '2023_05_09_211150_create_department_users_table', 1),
(14, '2023_05_12_233009_create_user_employment_statuses_table', 1),
(15, '2023_05_18_004538_create_settings_table', 1),
(16, '2023_05_23_205031_create_working_shift_users_table', 1),
(17, '2024_01_30_002507_create_permission_tables', 1),
(18, '2024_02_02_195654_create_employee_requisitions_table', 2),
(19, '2024_04_23_000431_add_columns_to_settings_table', 3),
(20, '2024_04_24_184403_add_column_in_users', 4),
(22, '2024_04_24_194137_create_companies_table', 5),
(23, '2024_04_24_205436_create_stocks_table', 6),
(24, '2024_04_24_205957_create_stock_images_table', 6),
(25, '2024_04_25_010836_add_column_to_status', 6),
(26, '2024_04_25_011302_add_column_to_remarks', 6),
(27, '2024_04_25_203815_create_user_player_ids_table', 7),
(28, '2024_04_24_203709_create_otps_table', 8),
(29, '2024_04_25_195959_add_column_in_permissions', 8),
(30, '2024_04_25_200126_add_column_in_roles', 8),
(31, '2024_04_29_180436_add_column_to_type', 8),
(32, '2024_05_01_162403_create_purchase_requests_table', 8),
(34, '2024_05_01_183232_create_purchase_request_statuses_table', 9),
(36, '2024_05_01_185243_create_estimates_table', 10),
(37, '2024_05_01_192020_create_estimate_statuses_table', 11),
(38, '2024_05_01_192203_create_attachments_table', 12),
(39, '2024_04_29_225714_add_column_to_request_type', 13),
(40, '2024_05_01_163053_add_column_in_users', 13),
(41, '2024_05_01_195950_add_column_in_purchase_requests', 13),
(42, '2024_05_01_234814_add_column_to_estimates_table', 13),
(43, '2024_05_01_230533_add_column_estimateid_in_stocks', 14),
(44, '2024_05_09_200253_add_column_creator_id_in_purchase_requests', 15),
(45, '2024_05_08_222348_add_columns_to_purchase_requests_table', 16),
(46, '2024_05_17_185500_create_valide_i_p_addresses_table', 17);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(125) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(125) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 9);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `otp` varchar(125) DEFAULT NULL,
  `otp_expires` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `user_id`, `otp`, `otp_expires`, `created_at`, `updated_at`) VALUES
(1, 5, '70155', '2024-05-02 20:07:11', '2024-05-02 19:49:58', '2024-05-02 20:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(125) NOT NULL,
  `token` varchar(125) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(125) NOT NULL,
  `name` varchar(125) NOT NULL,
  `guard_name` varchar(125) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `display_name` varchar(125) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `label`, `name`, `guard_name`, `created_at`, `updated_at`, `display_name`) VALUES
(1, 'Dashboard', 'dashboards-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(2, 'User', 'users-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(3, 'User', 'users-profile', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Profile'),
(4, 'User', 'users-create', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Create'),
(5, 'User', 'users-view', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'View'),
(6, 'User', 'users-edit', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Edit'),
(7, 'User', 'users-delete', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Delete'),
(8, 'User', 'users-direct-permission', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Delete'),
(9, 'Permission', 'permissions-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(10, 'Permission', 'permissions-create', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Create'),
(11, 'Permission', 'permissions-view', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'View'),
(12, 'Permission', 'permissions-edit', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Edit'),
(13, 'Permission', 'permissions-delete', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Delete'),
(14, 'Role', 'roles-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(15, 'Role', 'roles-create', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Create'),
(16, 'Role', 'roles-view', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'View'),
(17, 'Role', 'roles-edit', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Edit'),
(18, 'Role', 'roles-delete', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Delete'),
(19, 'Role', 'roles-all-user', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'User'),
(20, 'Employee', 'employees-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Employee'),
(21, 'Employee', 'employees-terminated-current-month', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Terminated current month'),
(22, 'Employee', 'employees-new-hired-employee', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'New hired employee'),
(23, 'Purchase', 'purchases-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(24, 'Purchase', 'purchases-request', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Request'),
(25, 'Offices', 'offices-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(26, 'Estimates', 'estimates-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(27, 'Estimates', 'estimates-create', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Create'),
(28, 'Receipts', 'receipts-list', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'List'),
(29, 'Settings', 'settings-create', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'Create'),
(30, 'Employees', 'employees-terminated', 'web', '2024-05-01 19:41:14', '2024-05-01 19:41:14', 'terminated'),
(31, 'Grievances', 'grievances-list', 'web', '2024-05-15 17:36:45', '2024-05-15 17:36:45', 'list'),
(32, 'Grievances', 'grievances-create', 'web', '2024-05-15 17:36:45', '2024-05-15 17:36:45', 'create'),
(33, 'Grievances', 'grievances-edit', 'web', '2024-05-15 17:36:45', '2024-05-15 17:36:45', 'edit'),
(34, 'Grievances', 'grievances-delete', 'web', '2024-05-15 17:36:45', '2024-05-15 17:36:45', 'delete'),
(35, 'Grievances', 'grievances-status', 'web', '2024-05-15 17:36:45', '2024-05-15 17:36:45', 'status'),
(36, 'Leave types', 'leave-types-list', 'web', '2024-05-16 18:27:09', '2024-05-16 18:27:09', 'list'),
(37, 'Leave types', 'leave-types-create', 'web', '2024-05-16 18:27:09', '2024-05-16 18:27:09', 'create'),
(38, 'Leave types', 'leave-types-edit', 'web', '2024-05-16 18:27:09', '2024-05-16 18:27:09', 'edit'),
(39, 'Leave types', 'leave-types-delete', 'web', '2024-05-16 18:27:09', '2024-05-16 18:27:09', 'delete'),
(40, 'Leave types', 'leave-types-status', 'web', '2024-05-16 18:27:09', '2024-05-16 18:27:09', 'status'),
(41, 'Ip addresses', 'ip-addresses-list', 'web', '2024-05-17 14:05:47', '2024-05-17 14:05:47', 'list'),
(42, 'Ip addresses', 'ip-addresses-create', 'web', '2024-05-17 14:05:47', '2024-05-17 14:05:47', 'create'),
(43, 'Ip addresses', 'ip-addresses-edit', 'web', '2024-05-17 14:05:47', '2024-05-17 14:05:47', 'edit'),
(44, 'Ip addresses', 'ip-addresses-delete', 'web', '2024-05-17 14:05:47', '2024-05-17 14:05:47', 'delete'),
(45, 'Ip addresses', 'ip-addresses-status', 'web', '2024-05-17 14:05:47', '2024-05-17 14:05:47', 'status'),
(53, 'Letter templates', 'letter-templates-edit', 'web', '2024-05-20 17:34:54', '2024-05-20 17:34:54', 'edit'),
(52, 'Letter templates', 'letter-templates-create', 'web', '2024-05-20 17:34:54', '2024-05-20 17:34:54', 'create'),
(51, 'Letter templates', 'letter-templates-list', 'web', '2024-05-20 17:34:54', '2024-05-20 17:34:54', 'list'),
(54, 'Letter templates', 'letter-templates-delete', 'web', '2024-05-20 17:34:54', '2024-05-20 17:34:54', 'delete'),
(55, 'Letter templates', 'letter-templates-status', 'web', '2024-05-20 17:34:54', '2024-05-20 17:34:54', 'status'),
(56, 'Employee letters', 'employee-letters-list', 'web', '2024-05-20 17:51:18', '2024-05-20 17:51:18', 'list'),
(57, 'Employee letters', 'employee-letters-create', 'web', '2024-05-20 17:51:18', '2024-05-20 17:51:18', 'create'),
(58, 'Employee letters', 'employee-letters-edit', 'web', '2024-05-20 17:51:18', '2024-05-20 17:51:18', 'edit'),
(59, 'Employee letters', 'employee-letters-delete', 'web', '2024-05-20 17:51:18', '2024-05-20 17:51:18', 'delete'),
(60, 'Employee letters', 'employee-letters-status', 'web', '2024-05-20 17:51:18', '2024-05-20 17:51:18', 'status'),
(61, 'Purchase requests', 'purchase-requests-list', 'web', '2024-05-21 17:27:51', '2024-05-21 17:27:51', 'list'),
(62, 'Purchase requests', 'purchase-requests-create', 'web', '2024-05-21 17:27:51', '2024-05-21 17:27:51', 'create'),
(63, 'Purchase requests', 'purchase-requests-edit', 'web', '2024-05-21 17:27:51', '2024-05-21 17:27:51', 'edit'),
(64, 'Purchase requests', 'purchase-requests-delete', 'web', '2024-05-21 17:27:51', '2024-05-21 17:27:51', 'delete'),
(65, 'Purchase requests', 'purchase-requests-status', 'web', '2024-05-21 17:27:51', '2024-05-21 17:27:51', 'status'),
(66, 'User leaves', 'user-leaves-list', 'web', '2024-05-21 17:36:07', '2024-05-21 17:36:07', 'list'),
(67, 'User leaves', 'user-leaves-create', 'web', '2024-05-21 17:36:07', '2024-05-21 17:36:07', 'create'),
(68, 'User leaves', 'user-leaves-edit', 'web', '2024-05-21 17:36:07', '2024-05-21 17:36:07', 'edit'),
(69, 'User leaves', 'user-leaves-delete', 'web', '2024-05-21 17:36:07', '2024-05-21 17:36:07', 'delete'),
(70, 'User leaves', 'user-leaves-status', 'web', '2024-05-21 17:36:07', '2024-05-21 17:36:07', 'status'),
(71, 'Attendance-adjustments', 'attendance-adjustments-list', 'web', '2024-05-24 13:04:02', '2024-05-24 13:04:02', 'list'),
(72, 'Attendance-adjustments', 'attendance-adjustments-create', 'web', '2024-05-24 13:04:02', '2024-05-24 13:04:02', 'create'),
(73, 'Attendance-adjustments', 'attendance-adjustments-edit', 'web', '2024-05-24 13:04:02', '2024-05-24 13:04:02', 'edit'),
(74, 'Attendance-adjustments', 'attendance-adjustments-delete', 'web', '2024-05-24 13:04:02', '2024-05-24 13:04:02', 'delete'),
(75, 'Attendance-adjustments', 'attendance-adjustments-status', 'web', '2024-05-24 13:04:02', '2024-05-24 13:04:02', 'status'),
(76, 'Discrepencies', 'discrepencies-list', 'web', '2024-05-24 14:59:42', '2024-05-24 14:59:42', 'list'),
(77, 'Discrepencies', 'discrepencies-create', 'web', '2024-05-24 14:59:42', '2024-05-24 14:59:42', 'create'),
(78, 'Discrepencies', 'discrepencies-edit', 'web', '2024-05-24 14:59:42', '2024-05-24 14:59:42', 'edit'),
(79, 'Discrepencies', 'discrepencies-delete', 'web', '2024-05-24 14:59:42', '2024-05-24 14:59:42', 'delete'),
(80, 'Discrepencies', 'discrepencies-status', 'web', '2024-05-24 14:59:42', '2024-05-24 14:59:42', 'status'),
(81, 'Designations', 'designations-list', 'web', '2024-05-24 18:54:35', '2024-05-24 18:54:35', 'list'),
(82, 'Designations', 'designations-create', 'web', '2024-05-24 18:54:35', '2024-05-24 18:54:35', 'create'),
(83, 'Designations', 'designations-edit', 'web', '2024-05-24 18:54:35', '2024-05-24 18:54:35', 'edit'),
(84, 'Designations', 'designations-delete', 'web', '2024-05-24 18:54:35', '2024-05-24 18:54:35', 'delete'),
(85, 'Designations', 'designations-status', 'web', '2024-05-24 18:54:35', '2024-05-24 18:54:35', 'status'),
(86, 'Salaries', 'salaries-list', 'web', '2024-05-24 18:55:23', '2024-05-24 18:55:23', 'list'),
(87, 'Salaries', 'salaries-create', 'web', '2024-05-24 18:55:23', '2024-05-24 18:55:23', 'create'),
(88, 'Salaries', 'salaries-edit', 'web', '2024-05-24 18:55:23', '2024-05-24 18:55:23', 'edit'),
(89, 'Salaries', 'salaries-delete', 'web', '2024-05-24 18:55:23', '2024-05-24 18:55:23', 'delete'),
(90, 'Salaries', 'salaries-status', 'web', '2024-05-24 18:55:23', '2024-05-24 18:55:23', 'status'),
(91, 'Salary-reports', 'salary-reports-list', 'web', '2024-05-24 18:55:38', '2024-05-24 18:55:38', 'list'),
(92, 'Salary-reports', 'salary-reports-create', 'web', '2024-05-24 18:55:38', '2024-05-24 18:55:38', 'create'),
(93, 'Salary-reports', 'salary-reports-edit', 'web', '2024-05-24 18:55:38', '2024-05-24 18:55:38', 'edit'),
(94, 'Salary-reports', 'salary-reports-delete', 'web', '2024-05-24 18:55:38', '2024-05-24 18:55:38', 'delete'),
(95, 'Salary-reports', 'salary-reports-status', 'web', '2024-05-24 18:55:38', '2024-05-24 18:55:38', 'status'),
(96, 'Work_shifts', 'work_shifts-list', 'web', '2024-05-24 18:56:12', '2024-05-24 18:56:12', 'list'),
(97, 'Work_shifts', 'work_shifts-create', 'web', '2024-05-24 18:56:12', '2024-05-24 18:56:12', 'create'),
(98, 'Work_shifts', 'work_shifts-edit', 'web', '2024-05-24 18:56:12', '2024-05-24 18:56:12', 'edit'),
(99, 'Work_shifts', 'work_shifts-delete', 'web', '2024-05-24 18:56:12', '2024-05-24 18:56:12', 'delete'),
(100, 'Work_shifts', 'work_shifts-status', 'web', '2024-05-24 18:56:12', '2024-05-24 18:56:12', 'status'),
(101, 'Vahicles', 'vahicles-list', 'web', '2024-05-24 18:59:16', '2024-05-24 18:59:16', 'list'),
(102, 'Vahicles', 'vahicles-create', 'web', '2024-05-24 18:59:16', '2024-05-24 18:59:16', 'create'),
(103, 'Vahicles', 'vahicles-edit', 'web', '2024-05-24 18:59:16', '2024-05-24 18:59:16', 'edit'),
(104, 'Vahicles', 'vahicles-delete', 'web', '2024-05-24 18:59:16', '2024-05-24 18:59:16', 'delete'),
(105, 'Vahicles', 'vahicles-status', 'web', '2024-05-24 18:59:16', '2024-05-24 18:59:16', 'status');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(125) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(17, 'App\\Models\\User', 1, 'token', '443aaeec8b3311d6e46f874f72d9b4815661def0b2f02f376c256cb22326d4a9', '[\"*\"]', NULL, '2024-05-20 16:29:04', '2024-04-29 13:33:16', '2024-05-20 16:29:04'),
(16, 'App\\Models\\User', 1, 'token', 'da3c3bce8220c6a7f6f3aac44c31d6e06c820fa66a334738618bda963e7ffadb', '[\"*\"]', NULL, '2024-05-20 16:29:04', '2024-04-25 14:38:31', '2024-05-20 16:29:04'),
(14, 'App\\Models\\User', 7, 'token', '0ee5f0108a0ffa4b705d3d5dff276b6e322f326aa4aa1e903869edda455d0a5a', '[\"*\"]', NULL, NULL, '2024-04-24 13:29:31', '2024-04-24 13:29:31'),
(13, 'App\\Models\\User', 6, 'token', '7882f00443783cc85fe92a7d93fb79012555bb768c98614ff35012395a918d48', '[\"*\"]', NULL, NULL, '2024-04-24 13:25:15', '2024-04-24 13:25:15'),
(8, 'App\\Models\\User', 4, 'token', '1ced71332da083d93038b8485cd0acdb3895b51a02a5e17805e80738ba20265a', '[\"*\"]', NULL, NULL, '2024-04-24 12:43:20', '2024-04-24 12:43:20'),
(7, 'App\\Models\\User', 4, 'token', '0646a0aa5f53ca84ed8ca46ffdff0aa200d5d9b5a5225eb189d6554185a18416', '[\"*\"]', NULL, '2024-04-24 12:43:20', '2024-04-24 12:43:13', '2024-04-24 12:43:20'),
(18, 'App\\Models\\User', 1, 'token', '7d24c7b2d26a18c33d75e79eeeccde1422d85e1a1356c7f075f4b6757a841dbe', '[\"*\"]', NULL, '2024-05-20 16:29:04', '2024-04-29 19:08:45', '2024-05-20 16:29:04'),
(19, 'App\\Models\\User', 9, 'token', '737185844f8c5173bbc13faf2fa72385eef359dbb115a30493ec6e4a723b973b', '[\"*\"]', NULL, NULL, '2024-05-01 19:54:03', '2024-05-01 19:54:03'),
(20, 'App\\Models\\User', 5, 'token', '95604455c0d40f8b31e5b8c617407fdc85280389306a48b9d9d1046ce6d5296c', '[\"*\"]', NULL, '2024-05-02 20:17:29', '2024-05-02 11:53:51', '2024-05-02 20:17:29'),
(21, 'App\\Models\\User', 5, 'token', '8917385b3ceadde6e3c620091b5b051452d4c0606d9ac8812b7f4ed287333821', '[\"*\"]', NULL, '2024-05-02 20:17:29', '2024-05-02 17:14:14', '2024-05-02 20:17:29'),
(22, 'App\\Models\\User', 5, 'token', 'd6fe9ff016184deec52fa44bd1141a57c108ede47d1bd04b7d61d88254955182', '[\"*\"]', NULL, '2024-05-02 20:17:29', '2024-05-02 18:37:04', '2024-05-02 20:17:29'),
(23, 'App\\Models\\User', 5, 'token', 'ce054ffc728db8a7132f8f2048264f934651e127123a84955adcf84135d83abb', '[\"*\"]', NULL, NULL, '2024-05-02 20:17:29', '2024-05-02 20:17:29'),
(24, 'App\\Models\\User', 1, 'token', 'dbc4f17829786cc0f9205339adef4b2d5af9c3e07d9e1f4ef47ac19615bc9718', '[\"*\"]', NULL, '2024-05-20 16:29:04', '2024-05-03 14:42:33', '2024-05-20 16:29:04'),
(25, 'App\\Models\\User', 1, 'token', '3ee5815879febd09be2681fba7e860f085240a035073b8876ae6ed2723f1a61d', '[\"*\"]', NULL, '2024-05-20 16:29:04', '2024-05-09 15:43:54', '2024-05-20 16:29:04'),
(26, 'App\\Models\\User', 1, 'token', 'ece3965cbc9a8f83669db2106bec17cad204f2349d129c54cacc4e9b7f9f9ec4', '[\"*\"]', NULL, NULL, '2024-05-20 16:29:04', '2024-05-20 16:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `employment_id` bigint(20) DEFAULT NULL,
  `cover_image_id` bigint(20) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `marital_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=married, 0=single',
  `social_security_number` varchar(125) DEFAULT NULL,
  `phone_number` varchar(125) DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `address` varchar(125) DEFAULT NULL,
  `profile` varchar(125) DEFAULT NULL,
  `cnic` varchar(125) DEFAULT NULL,
  `cnic_front` varchar(125) DEFAULT NULL,
  `cnic_back` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `employment_id`, `cover_image_id`, `joining_date`, `date_of_birth`, `gender`, `marital_status`, `social_security_number`, `phone_number`, `about_me`, `address`, `profile`, `cnic`, `cnic_front`, `cnic_back`, `created_at`, `updated_at`) VALUES
(1, 1, 1145, NULL, '2024-04-22', NULL, 'male', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-22 13:01:07', '2024-05-24 20:05:12');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator` varchar(125) DEFAULT NULL COMMENT 'can be integer or direct User email',
  `creator_id` bigint(20) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `subject` varchar(125) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `modified_by` varchar(125) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `raw_data` longtext DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `portal_request_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `creator`, `creator_id`, `company_id`, `subject`, `description`, `modified_by`, `modified_at`, `raw_data`, `remarks`, `status`, `created_at`, `updated_at`, `deleted_at`, `portal_request_id`) VALUES
(4, 'kamran@braincell.com', 0, 3, 'New Laptop', 'I need a new laptop', '1', '2024-05-21 22:33:46', NULL, 'Approved', 2, '2024-05-01 18:59:09', '2024-05-21 17:33:46', NULL, NULL),
(5, 'test@test.com', 0, 1, 'To Purchase a new Chair for Manager', 'This is a request to please arrange or purchase a new chair as the old one was wrecked', '5', '2024-05-02 18:31:02', NULL, 'Approved', 2, '2024-05-02 12:49:01', '2024-05-02 13:31:02', NULL, NULL),
(25, 'super@admin.com', 1, 1, 'Testing', 'Description', '1', '2024-05-09 20:39:16', NULL, 'Please approved id', 2, '2024-05-09 15:35:55', '2024-05-09 15:39:16', NULL, NULL),
(24, 'super@admin.com', 1, 2, 'Add subject the testing', 'Description subject the testing', '1', '2024-05-09 20:52:33', NULL, 'Approved', 2, '2024-05-09 15:34:35', '2024-05-09 15:52:33', NULL, NULL),
(26, 'super@admin.com', 1, 1, 'Testing For the One Signal Message', '<p>Hello</p>', NULL, NULL, NULL, 'New Purchase Requsest Created', 1, '2024-05-21 17:53:17', '2024-05-21 17:53:17', '2024-05-21 23:08:19', NULL),
(27, 'super@admin.com', 1, 1, 'Testing For the One Signal Message', '<p>Testing 2</p>', NULL, NULL, NULL, 'New Purchase Requsest Created', 1, '2024-05-21 17:54:04', '2024-05-21 17:54:04', '2024-05-21 23:08:15', NULL),
(28, 'super@admin.com', 1, 1, 'Testing For the One Signal Message', '<p>Testing For the One Signal Message</p>', '1', '2024-05-21 23:20:30', NULL, 'Approved', 2, '2024-05-21 18:09:38', '2024-05-21 18:20:30', NULL, NULL),
(29, 'super@admin.com', 1, 1, 'Add New Purchase Request', '<p>Add Purchase Request for One Signal notification Checking</p>', NULL, NULL, NULL, 'New Purchase Requsest Created', 1, '2024-05-21 18:43:05', '2024-05-21 18:43:05', NULL, NULL),
(30, 'super@admin.com', 1, 2, 'Add New Purchase Request', '<p>Checking</p>', NULL, NULL, NULL, 'New Purchase Requsest Created', 1, '2024-05-21 18:51:37', '2024-05-21 18:51:37', NULL, NULL),
(31, 'super@admin.com', 1, 1, 'Add New Purchase Request', '<p>Testing For the One Signal Message</p>', NULL, NULL, NULL, 'New Purchase Requsest Created', 1, '2024-05-21 18:57:27', '2024-05-21 18:57:27', NULL, NULL),
(32, 'super@admin.com', 1, 1, 'Add New Purchase Request', '<p>Add New Purchase request</p>', NULL, NULL, NULL, 'New Purchase Requsest Created', 1, '2024-05-21 18:58:27', '2024-05-21 18:58:27', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_statuses`
--

CREATE TABLE `purchase_request_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) DEFAULT NULL,
  `class` varchar(125) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_request_statuses`
--

INSERT INTO `purchase_request_statuses` (`id`, `name`, `class`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pending', 'warning', 1, '2024-05-01 19:02:29', '2024-05-01 19:02:29', NULL),
(2, 'Approved', 'success', 1, '2024-05-01 19:02:29', '2024-05-01 19:02:29', NULL),
(3, 'Rejected', 'danger', 1, '2024-05-01 19:02:29', '2024-05-01 19:02:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resignations`
--

CREATE TABLE `resignations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `is_manager_approved` timestamp NULL DEFAULT NULL,
  `is_concerned_approved` timestamp NULL DEFAULT NULL,
  `employment_status_id` bigint(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `resignation_date` date NOT NULL,
  `reason_for_resignation` text DEFAULT NULL COMMENT 'Write here reason if want.',
  `notice_period` varchar(100) DEFAULT NULL,
  `last_working_date` date NOT NULL,
  `comment` varchar(100) DEFAULT NULL,
  `rehire_eligibility` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'A boolean field indicating whether the employee is eligible for rehire in the future.',
  `is_rehired` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'If a user re-hired it will set log',
  `resignation_letter` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=pending, 1=approved, 2-rejected',
  `deleted_at` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resignations`
--

INSERT INTO `resignations` (`id`, `created_by`, `employee_id`, `is_manager_approved`, `is_concerned_approved`, `employment_status_id`, `subject`, `resignation_date`, `reason_for_resignation`, `notice_period`, `last_working_date`, `comment`, `rehire_eligibility`, `is_rehired`, `resignation_letter`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(30, 1, 199, '2023-10-27 03:53:03', '2023-10-27 03:53:03', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:53:03', '2023-10-27 03:53:03'),
(31, 1, 198, '2023-10-27 03:56:16', '2023-10-27 03:56:16', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:56:16', '2023-10-27 03:56:16'),
(32, 1, 197, '2023-10-27 03:56:50', '2023-10-27 03:56:50', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:56:50', '2023-10-27 03:56:50'),
(33, 1, 196, '2023-10-27 03:57:15', '2023-10-27 03:57:15', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:57:15', '2023-10-27 03:57:15'),
(34, 1, 194, '2023-10-27 03:57:39', '2023-10-27 03:57:39', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:57:39', '2023-10-27 03:57:39'),
(35, 1, 193, '2023-10-27 03:58:01', '2023-10-27 03:58:01', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:58:01', '2023-10-27 03:58:01'),
(36, 1, 192, '2023-10-27 03:58:22', '2023-10-27 03:58:22', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:58:22', '2023-10-27 03:58:22'),
(37, 1, 191, '2023-10-27 03:58:51', '2023-10-27 03:58:51', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:58:51', '2023-10-27 03:58:51'),
(38, 1, 190, '2023-10-27 03:59:15', '2023-10-27 03:59:15', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:59:15', '2023-10-27 03:59:15'),
(39, 1, 189, '2023-10-27 03:59:43', '2023-10-27 03:59:43', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 03:59:43', '2023-10-27 03:59:43'),
(40, 1, 188, '2023-10-27 04:00:09', '2023-10-27 04:00:09', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:00:09', '2023-10-27 04:00:09'),
(41, 1, 187, '2023-10-27 04:00:33', '2023-10-27 04:00:33', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:00:33', '2023-10-27 04:00:33'),
(42, 1, 186, '2023-10-27 04:00:52', '2023-10-27 04:00:52', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:00:52', '2023-10-27 04:00:52'),
(43, 1, 185, '2023-10-27 04:01:16', '2023-10-27 04:01:16', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:01:16', '2023-10-27 04:01:16'),
(44, 1, 184, '2023-10-27 04:01:38', '2023-10-27 04:01:38', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:01:38', '2023-10-27 04:01:38'),
(45, 1, 183, '2023-10-27 04:02:03', '2023-10-27 04:02:03', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:02:03', '2023-10-27 04:02:03'),
(46, 1, 182, '2023-10-27 04:02:24', '2023-10-27 04:02:24', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:02:24', '2023-10-27 04:02:24'),
(47, 1, 181, '2023-10-27 04:02:43', '2023-10-27 04:02:43', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:02:43', '2023-10-27 04:02:43'),
(48, 1, 180, '2023-10-27 04:03:02', '2023-10-27 04:03:02', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:03:02', '2023-10-27 04:03:02'),
(49, 1, 179, '2023-10-27 04:03:24', '2023-10-27 04:03:24', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:03:24', '2023-10-27 04:03:24'),
(50, 1, 178, '2023-10-27 04:03:45', '2023-10-27 04:03:45', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:03:45', '2023-10-27 04:03:45'),
(51, 1, 177, '2023-10-27 04:04:04', '2023-10-27 04:04:04', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:04:04', '2023-10-27 04:04:04'),
(52, 1, 176, '2023-10-27 04:04:23', '2023-10-27 04:04:23', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:04:23', '2023-10-27 04:04:23'),
(53, 1, 175, '2023-10-27 04:04:52', '2023-10-27 04:04:52', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:04:52', '2023-10-27 04:04:52'),
(54, 1, 174, '2023-10-27 04:05:16', '2023-10-27 04:05:16', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:05:16', '2023-10-27 04:05:16'),
(55, 1, 173, '2023-10-27 04:05:35', '2023-10-27 04:05:35', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:05:35', '2023-10-27 04:05:35'),
(56, 1, 172, '2023-10-27 04:06:02', '2023-10-27 04:06:02', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:06:02', '2023-10-27 04:06:02'),
(57, 1, 171, '2023-10-27 04:06:23', '2023-10-27 04:06:23', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:06:23', '2023-10-27 04:06:23'),
(58, 1, 170, '2023-10-27 04:06:53', '2023-10-27 04:06:53', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:06:53', '2023-10-27 04:06:53'),
(59, 1, 167, '2023-10-27 04:07:15', '2023-10-27 04:07:15', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:07:15', '2023-10-27 04:07:15'),
(60, 1, 166, '2023-10-27 04:07:50', '2023-10-27 04:07:50', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:07:50', '2023-10-27 04:07:50'),
(61, 1, 165, '2023-10-27 04:08:13', '2023-10-27 04:08:13', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:08:13', '2023-10-27 04:08:13'),
(62, 1, 164, '2023-10-27 04:08:32', '2023-10-27 04:08:32', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:08:32', '2023-10-27 04:08:32'),
(63, 1, 163, '2023-10-27 04:08:57', '2023-10-27 04:08:57', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:08:57', '2023-10-27 04:08:57'),
(64, 1, 162, '2023-10-27 04:09:18', '2023-10-27 04:09:18', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:09:18', '2023-10-27 04:09:18'),
(65, 1, 161, '2023-10-27 04:09:40', '2023-10-27 04:09:40', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:09:40', '2023-10-27 04:09:40'),
(66, 1, 160, '2023-10-27 04:10:09', '2023-10-27 04:10:09', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:10:09', '2023-10-27 04:10:09'),
(67, 1, 159, '2023-10-27 04:11:48', '2023-10-27 04:11:48', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:11:48', '2023-10-27 04:11:48'),
(68, 1, 158, '2023-10-27 04:12:05', '2023-10-27 04:12:05', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:12:05', '2023-10-27 04:12:05'),
(69, 1, 157, '2023-10-27 04:12:24', '2023-10-27 04:12:24', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:12:24', '2023-10-27 04:12:24'),
(70, 1, 156, '2023-10-27 04:12:43', '2023-10-27 04:12:43', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:12:43', '2023-10-27 04:12:43'),
(71, 1, 155, '2023-10-27 04:13:03', '2023-10-27 04:13:03', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:13:03', '2023-10-27 04:13:03'),
(72, 1, 154, '2023-10-27 04:13:31', '2023-10-27 04:13:31', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:13:31', '2023-10-27 04:13:31'),
(73, 1, 153, '2023-10-27 04:13:50', '2023-10-27 04:13:50', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:13:50', '2023-10-27 04:13:50'),
(74, 1, 152, '2023-10-27 04:14:12', '2023-10-27 04:14:12', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:14:12', '2023-10-27 04:14:12'),
(75, 1, 151, '2023-10-27 04:14:30', '2023-10-27 04:14:30', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:14:30', '2023-10-27 04:14:30'),
(76, 1, 150, '2023-10-27 04:14:50', '2023-10-27 04:14:50', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:14:50', '2023-10-27 04:14:50'),
(77, 1, 149, '2023-10-27 04:15:11', '2023-10-27 04:15:11', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:15:11', '2023-10-27 04:15:11'),
(78, 1, 148, '2023-10-27 04:15:33', '2023-10-27 04:15:33', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:15:33', '2023-10-27 04:15:33'),
(79, 1, 147, '2023-10-27 04:16:01', '2023-10-27 04:16:01', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:16:01', '2023-10-27 04:16:01'),
(80, 1, 145, '2023-10-27 04:16:21', '2023-10-27 04:16:21', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:16:21', '2023-10-27 04:16:21'),
(81, 1, 144, '2023-10-27 04:16:42', '2023-10-27 04:16:42', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:16:42', '2023-10-27 04:16:42'),
(82, 1, 143, '2023-10-27 04:17:02', '2023-10-27 04:17:02', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:17:02', '2023-10-27 04:17:02'),
(83, 1, 142, '2023-10-27 04:17:20', '2023-10-27 04:17:20', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:17:20', '2023-10-27 04:17:20'),
(84, 1, 141, '2023-10-27 04:17:39', '2023-10-27 04:17:39', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:17:39', '2023-10-27 04:17:39'),
(85, 1, 140, '2023-10-27 04:18:01', '2023-10-27 04:18:01', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:18:01', '2023-10-27 04:18:01'),
(86, 1, 139, '2023-10-27 04:18:21', '2023-10-27 04:18:21', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:18:21', '2023-10-27 04:18:21'),
(87, 1, 138, '2023-10-27 04:18:59', '2023-10-27 04:18:59', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:18:59', '2023-10-27 04:18:59'),
(88, 1, 137, '2023-10-27 04:19:33', '2023-10-27 04:19:33', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:19:33', '2023-10-27 04:19:33'),
(89, 1, 136, '2023-10-27 04:19:51', '2023-10-27 04:19:51', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:19:51', '2023-10-27 04:19:51'),
(90, 1, 135, '2023-10-27 04:20:07', '2023-10-27 04:20:07', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:20:07', '2023-10-27 04:20:07'),
(91, 1, 134, '2023-10-27 04:20:29', '2023-10-27 04:20:29', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:20:29', '2023-10-27 04:20:29'),
(92, 1, 133, '2023-10-27 04:20:46', '2023-10-27 04:20:46', 2, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 1, NULL, 2, NULL, '2023-10-27 04:20:46', '2023-10-31 08:54:55'),
(93, 1, 132, '2023-10-27 04:21:03', '2023-10-27 04:21:03', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:21:03', '2023-10-27 04:21:03'),
(94, 1, 131, '2023-10-27 04:21:30', '2023-10-27 04:21:30', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:21:30', '2023-10-27 04:21:30'),
(95, 1, 130, '2023-10-27 04:21:45', '2023-10-27 04:21:45', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:21:45', '2023-10-27 04:21:45'),
(96, 1, 129, '2023-10-27 04:22:03', '2023-10-27 04:22:03', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:22:03', '2023-10-27 04:22:03'),
(97, 1, 75, '2023-10-27 04:23:31', '2023-10-27 04:23:31', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:23:31', '2023-10-27 04:23:31'),
(98, 1, 70, '2023-10-27 04:23:47', '2023-10-27 04:23:47', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:23:47', '2023-10-27 04:23:47'),
(99, 1, 47, '2023-10-27 04:24:38', '2023-10-27 04:24:38', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:24:38', '2023-10-27 04:24:38'),
(100, 1, 12, '2023-10-27 04:25:42', '2023-10-27 04:25:42', 3, '', '2023-10-27', 'Terminated', 'Immediately', '2023-10-27', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-27 04:25:42', '2023-10-27 04:25:42'),
(102, 1, 206, '2023-10-28 07:36:19', '2023-10-28 07:36:19', 3, '', '2023-10-28', 'Terminated', 'Immediately', '2023-10-28', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-28 07:36:19', '2023-10-28 07:36:19'),
(103, 1, 125, '2023-10-31 09:29:09', '2023-10-31 09:29:09', 3, '', '2023-10-17', 'Last Day 17/10/2023', 'Immediately', '2023-10-17', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-10-31 09:29:09', '2023-10-31 09:29:09'),
(145, 1, 169, '2023-11-01 02:54:01', '2023-11-01 02:54:01', 3, '', '2023-10-31', 'Last Date 31/10/2023', 'Immediately', '2023-10-31', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-11-01 02:54:01', '2023-11-01 02:54:01'),
(146, 1, 117, '2023-11-09 04:33:15', '2023-11-09 04:33:15', 3, '', '2023-10-30', NULL, 'Immediately', '2023-10-30', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-11-09 04:33:15', '2023-11-09 04:33:15'),
(147, 1, 96, '2023-11-09 04:34:18', '2023-11-09 04:34:18', 3, '', '2023-11-02', NULL, 'Immediately', '2023-11-02', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-11-09 04:34:18', '2023-11-09 04:34:18'),
(164, 1, 108, '2023-11-14 08:07:05', '2023-11-14 08:07:05', 3, '', '2023-11-13', 'Medical reasons, not willing to serve notice!', 'Immediately', '2023-11-13', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-11-14 08:07:05', '2023-11-14 08:07:05'),
(165, 1, 123, '2023-11-22 05:03:15', '2023-11-22 05:03:15', 3, '', '2023-11-17', 'last working day was 17th Novemeber.', 'Immediately', '2023-11-17', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-11-22 05:03:15', '2023-11-22 05:03:15'),
(166, 1, 210, '2023-11-28 09:47:10', '2023-11-28 09:47:10', 3, '', '2023-11-17', '17-11-2023', 'Immediately', '2023-11-17', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-11-28 09:47:10', '2023-11-28 09:47:10'),
(167, 1, 67, '2023-12-05 10:25:59', '2023-12-05 10:25:59', 3, '', '2023-12-01', 'Last working day Dec 1, 2023', 'Immediately', '2023-12-01', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-12-05 10:25:59', '2023-12-05 10:25:59'),
(168, 1, 118, '2023-12-07 11:07:15', '2023-12-07 11:07:15', 3, '', '2023-11-30', 'last working day 30-11-2023', 'Immediately', '2023-11-30', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-12-07 11:07:15', '2023-12-07 11:07:15'),
(174, 1, 122, '2023-12-16 05:15:47', '2023-12-16 05:15:47', 3, '', '2023-11-30', 'Last working day Nov 30, 2023', 'Immediately', '2023-11-30', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-12-16 05:15:47', '2023-12-16 05:15:47'),
(175, 1, 119, '2023-12-16 05:16:44', '2023-12-16 05:16:44', 3, '', '2023-11-30', 'Last working day Nov 30, 2023', 'Immediately', '2023-11-30', 'Terminated by admin', 1, 0, NULL, 2, NULL, '2023-12-16 05:16:44', '2023-12-16 05:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) NOT NULL,
  `guard_name` varchar(125) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `display_name` varchar(125) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `display_name`) VALUES
(1, 'Super Admin', 'web', '2024-04-22 13:01:07', '2024-04-22 13:01:07', NULL),
(2, 'Admin', 'web', '2024-04-22 13:01:07', '2024-04-22 13:01:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1);

-- --------------------------------------------------------

--
-- Table structure for table `salary_histories`
--

CREATE TABLE `salary_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `job_history_id` bigint(20) NOT NULL,
  `raise_salary` bigint(20) DEFAULT NULL,
  `salary` bigint(20) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) DEFAULT NULL,
  `base_url` varchar(125) DEFAULT NULL,
  `logo` varchar(125) DEFAULT NULL,
  `black_logo` varchar(125) DEFAULT NULL,
  `phone_number` varchar(125) DEFAULT NULL,
  `email` varchar(125) DEFAULT NULL,
  `website_url` text DEFAULT NULL,
  `favicon` varchar(125) DEFAULT NULL,
  `banner` varchar(125) DEFAULT NULL,
  `language` varchar(125) DEFAULT NULL,
  `country` varchar(125) DEFAULT NULL,
  `area` varchar(125) DEFAULT NULL,
  `city` varchar(125) DEFAULT NULL,
  `state` varchar(125) DEFAULT NULL,
  `zip_code` varchar(125) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `facebook_link` text DEFAULT NULL,
  `instagram_link` text DEFAULT NULL,
  `linked_in_link` text DEFAULT NULL,
  `twitter_link` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `base_url`, `logo`, `black_logo`, `phone_number`, `email`, `website_url`, `favicon`, `banner`, `language`, `country`, `area`, `city`, `state`, `zip_code`, `address`, `facebook_link`, `instagram_link`, `linked_in_link`, `twitter_link`, `created_at`, `updated_at`) VALUES
(1, 'Demo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1684433621.png', 'English', 'Pakistan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `estimate_id` int(11) DEFAULT NULL,
  `title` varchar(125) NOT NULL,
  `description` longtext NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Pending, 2 = Approve, 3=Rejected',
  `remarks` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `user_id`, `company_id`, `estimate_id`, `title`, `description`, `quantity`, `status`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 3, NULL, 'Testing Tiile', 'Testing2 Description', 7, 3, 'test', '2024-04-25 14:42:12', '2024-04-29 11:27:11', NULL),
(2, 1, 3, NULL, 'Testing Tiile', 'Testing2 Description', 7, 2, 'Approved', '2024-04-25 14:42:26', '2024-04-29 11:26:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_images`
--

CREATE TABLE `stock_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) NOT NULL,
  `image` varchar(125) NOT NULL,
  `type` varchar(125) DEFAULT NULL,
  `request_type` bigint(20) NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_images`
--

INSERT INTO `stock_images` (`id`, `stock_id`, `image`, `type`, `request_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '1468634642_logo (1).png', NULL, 2, '2024-04-25 14:42:26', '2024-04-25 14:42:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(125) NOT NULL,
  `first_name` varchar(125) NOT NULL,
  `last_name` varchar(125) DEFAULT NULL,
  `email` varchar(125) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_employee` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` varchar(125) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp` varchar(125) DEFAULT NULL,
  `otp_expires` timestamp NULL DEFAULT NULL,
  `user_for_api` int(11) DEFAULT NULL,
  `user_for_portal` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `slug`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `status`, `is_employee`, `deleted_at`, `remember_token`, `created_at`, `updated_at`, `otp`, `otp_expires`, `user_for_api`, `user_for_portal`) VALUES
(1, 'super-admin-1', 'Super', 'Admin', 'super@admin.com', NULL, '$2y$10$uwXX/.NWh3g85WsqOGMMQ.1jKzulFuGzrlnXGCGV1jVSj8MbWQpLu', 1, 1, NULL, NULL, '2024-04-22 13:01:07', '2024-05-07 15:54:54', NULL, NULL, 1, 1),
(3, 'aGFzZWViMzQ1', 'haseeb345', 'haseeb35', 'hasee44sdfb@gmail.com', NULL, '$2y$10$e6tkbss9fC8s8V.ALkp89OrTv6D6M7qBWERUWwqRoXlA1RPeF3R96', 1, 1, NULL, NULL, '2024-04-24 12:41:29', '2024-04-24 12:41:29', NULL, NULL, NULL, NULL),
(4, 'aGFzZWViMzQyNQ==', 'haseeb3425', 'haseeb325', 'hasee44sdf2b@gmail.com', NULL, '$2y$10$e6tkbss9fC8s8V.ALkp89OrTv6D6M7qBWERUWwqRoXlA1RPeF3R96', 1, 1, NULL, NULL, '2024-04-24 12:42:10', '2024-04-24 12:42:10', NULL, NULL, NULL, NULL),
(5, 'dGVzdA==', 'test', 'user', 'test@user.com', NULL, '$2y$10$e6tkbss9fC8s8V.ALkp89OrTv6D6M7qBWERUWwqRoXlA1RPeF3R96', 1, 1, NULL, NULL, '2024-04-24 13:19:20', '2024-04-24 13:19:20', NULL, NULL, 1, NULL),
(6, 'dGUyc3Q=', 'te2st', 'u2ser', 'test2@user.com', NULL, '$2y$10$e6tkbss9fC8s8V.ALkp89OrTv6D6M7qBWERUWwqRoXlA1RPeF3R96', 1, 1, NULL, NULL, '2024-04-24 13:25:15', '2024-04-24 13:25:15', NULL, NULL, NULL, NULL),
(7, 'dGUyc3N0', 'te2sst', 'u2sser', 'tsest2@user.com', NULL, '$2y$12$1LGHMRj.AMzGUvv2GByXVuVVPC9WUqItG.MoURN0s0uqQP0QvM1IW$2y$10$e6tkbss9fC8s8V.ALkp89OrTv6D6M7qBWERUWwqRoXlA1RPeF3R96', 1, 1, NULL, NULL, '2024-04-24 13:29:31', '2024-04-24 13:31:45', NULL, NULL, NULL, NULL),
(8, 'a2Fzb29y', 'kasoor', 'lahor', 'kasoor@lahore.com', NULL, '$2y$10$e6tkbss9fC8s8V.ALkp89OrTv6D6M7qBWERUWwqRoXlA1RPeF3R96', 1, 1, NULL, NULL, '2024-04-24 14:44:47', '2024-04-24 14:44:47', NULL, NULL, 1, NULL),
(9, 'xwZg78VFbLKqQAxAAYWcf0ZLulVnQb-1714611184-lEobOdd4eWSpj8gXPliOJHfCAwwp8m', 'imran', 'ali', 'imran@gmail.com', NULL, '$2y$10$e6tkbss9fC8s8V.ALkp89OrTv6D6M7qBWERUWwqRoXlA1RPeF3R96', 1, 1, NULL, NULL, '2024-05-01 19:53:04', '2024-05-01 19:53:04', NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_employment_statuses`
--

CREATE TABLE `user_employment_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employment_status_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_employment_statuses`
--

INSERT INTO `user_employment_statuses` (`id`, `user_id`, `employment_status_id`, `start_date`, `end_date`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2024-04-22', NULL, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_player_ids`
--

CREATE TABLE `user_player_ids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `player_id` varchar(125) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_player_ids`
--

INSERT INTO `user_player_ids` (`id`, `user_id`, `player_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '22117be2-5ea8-4a4a-94b5-266989d1f10b', '2024-05-20 16:30:36', '2024-05-20 16:30:36', NULL),
(2, 1, '99167aae-19df-4c8f-98e7-b9718489cfa3', '2024-05-21 23:15:54', '2024-05-21 23:15:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `valide_i_p_addresses`
--

CREATE TABLE `valide_i_p_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_id` bigint(20) NOT NULL,
  `ip_address` varchar(125) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=Active , 2=De-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `valide_i_p_addresses`
--

INSERT INTO `valide_i_p_addresses` (`id`, `creator_id`, `ip_address`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '::1', 1, '2024-05-17 15:17:51', '2024-05-17 16:50:12', '2024-05-17 16:50:12'),
(2, 1, '::1', 1, '2024-05-17 15:18:51', '2024-05-17 16:29:48', '2024-05-17 16:29:48'),
(3, 1, '::1', 1, '2024-05-17 15:20:41', '2024-05-17 16:29:45', '2024-05-17 16:29:45'),
(4, 1, 'sada', 1, '2024-05-17 15:28:41', '2024-05-17 16:29:39', '2024-05-17 16:29:39'),
(5, 1, 'dasdasdasd', 1, '2024-05-17 15:29:17', '2024-05-17 16:29:35', '2024-05-17 16:29:35'),
(6, 1, 'dasdasdasd', 1, '2024-05-17 15:29:39', '2024-05-17 16:29:32', '2024-05-17 16:29:32'),
(7, 1, 'dasdasdasd', 1, '2024-05-17 15:29:47', '2024-05-17 16:29:28', '2024-05-17 16:29:28'),
(8, 1, 'saaS', 1, '2024-05-17 15:29:54', '2024-05-17 16:29:24', '2024-05-17 16:29:24'),
(9, 1, 'ASDSADDA', 1, '2024-05-17 15:30:31', '2024-05-17 16:29:21', '2024-05-17 16:29:21'),
(10, 1, 'sdfsfsd', 1, '2024-05-17 15:30:50', '2024-05-17 16:29:06', '2024-05-17 16:29:06'),
(11, 1, 'dasasdas', 1, '2024-05-17 15:31:34', '2024-05-17 16:29:09', '2024-05-17 16:29:09'),
(12, 1, '::1', 1, '2024-05-17 16:50:08', '2024-05-20 16:33:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `working_shift_users`
--

CREATE TABLE `working_shift_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `working_shift_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `working_shift_users`
--

INSERT INTO `working_shift_users` (`id`, `working_shift_id`, `user_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-04-22', NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `work_shifts`
--

CREATE TABLE `work_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `type` enum('regular','scheduled') NOT NULL DEFAULT 'regular',
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_shifts`
--

INSERT INTO `work_shifts` (`id`, `name`, `start_date`, `end_date`, `start_time`, `end_time`, `type`, `description`, `is_default`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Night Shift (9 to 6)', '2024-04-22', NULL, NULL, NULL, 'regular', NULL, 1, 1, NULL, '2024-04-22 13:01:07', '2024-04-22 13:01:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_users`
--
ALTER TABLE `department_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_requisitions`
--
ALTER TABLE `employee_requisitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employment_statuses`
--
ALTER TABLE `employment_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_statuses`
--
ALTER TABLE `estimate_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `job_histories`
--
ALTER TABLE `job_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_activities`
--
ALTER TABLE `log_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_request_statuses`
--
ALTER TABLE `purchase_request_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resignations`
--
ALTER TABLE `resignations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `salary_histories`
--
ALTER TABLE `salary_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_images`
--
ALTER TABLE `stock_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_employment_statuses`
--
ALTER TABLE `user_employment_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_employment_statuses_user_id_foreign` (`user_id`),
  ADD KEY `user_employment_statuses_employment_status_id_foreign` (`employment_status_id`);

--
-- Indexes for table `user_player_ids`
--
ALTER TABLE `user_player_ids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `valide_i_p_addresses`
--
ALTER TABLE `valide_i_p_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `working_shift_users`
--
ALTER TABLE `working_shift_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_shifts`
--
ALTER TABLE `work_shifts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `department_users`
--
ALTER TABLE `department_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `employee_requisitions`
--
ALTER TABLE `employee_requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employment_statuses`
--
ALTER TABLE `employment_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `estimate_statuses`
--
ALTER TABLE `estimate_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_histories`
--
ALTER TABLE `job_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `log_activities`
--
ALTER TABLE `log_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `purchase_request_statuses`
--
ALTER TABLE `purchase_request_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resignations`
--
ALTER TABLE `resignations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `salary_histories`
--
ALTER TABLE `salary_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_images`
--
ALTER TABLE `stock_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_employment_statuses`
--
ALTER TABLE `user_employment_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_player_ids`
--
ALTER TABLE `user_player_ids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `valide_i_p_addresses`
--
ALTER TABLE `valide_i_p_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `working_shift_users`
--
ALTER TABLE `working_shift_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `work_shifts`
--
ALTER TABLE `work_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
