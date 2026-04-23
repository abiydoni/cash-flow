-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 23 Apr 2026 pada 13.10
-- Versi server: 10.6.21-MariaDB
-- Versi PHP: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appsbeem_cashflow`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `app_settings`
--

INSERT INTO `app_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'wa_gateway_url', '', NULL, NULL),
(2, 'wa_api_key', '', NULL, NULL),
(3, 'notif_enabled', '0', NULL, NULL),
(4, 'app_name', 'CashFlow', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `period_type` enum('monthly','yearly','custom') NOT NULL DEFAULT 'monthly',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'NULL = global category, user_id = personal category',
  `name` varchar(100) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `icon` varchar(100) NOT NULL DEFAULT 'wallet-outline',
  `color` varchar(20) NOT NULL DEFAULT '#6366f1',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `type`, `icon`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Gaji', 'income', 'cash-outline', '#10b981', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(2, NULL, 'Bonus', 'income', 'gift-outline', '#34d399', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(3, NULL, 'Investasi', 'income', 'trending-up-outline', '#6ee7b7', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(4, NULL, 'Freelance', 'income', 'laptop-outline', '#059669', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(5, NULL, 'Bisnis', 'income', 'business-outline', '#047857', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(6, NULL, 'Penjualan', 'income', 'cart-outline', '#065f46', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(7, NULL, 'Lain-lain', 'income', 'add-circle-outline', '#6b7280', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(8, NULL, 'Makan & Minum', 'expense', 'restaurant-outline', '#ef4444', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(9, NULL, 'Transportasi', 'expense', 'car-outline', '#f97316', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(10, NULL, 'Belanja', 'expense', 'bag-outline', '#eab308', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(11, NULL, 'Kesehatan', 'expense', 'medkit-outline', '#ec4899', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(12, NULL, 'Pendidikan', 'expense', 'school-outline', '#8b5cf6', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(13, NULL, 'Hiburan', 'expense', 'game-controller-outline', '#3b82f6', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(14, NULL, 'Tagihan', 'expense', 'receipt-outline', '#6366f1', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(15, NULL, 'Rumah', 'expense', 'home-outline', '#14b8a6', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(16, NULL, 'Asuransi', 'expense', 'shield-outline', '#0891b2', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(17, NULL, 'Tabungan', 'expense', 'save-outline', '#7c3aed', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(18, NULL, 'Lain-lain', 'expense', 'ellipsis-horizontal-outline', '#9ca3af', 1, '2026-04-17 18:12:21', '2026-04-17 18:12:21'),
(19, NULL, 'Iuran', 'income', 'calendar-outline', '#10b981', 1, '2026-04-18 23:50:07', '2026-04-18 23:50:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dues_payments`
--

CREATE TABLE `dues_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` int(11) UNSIGNED NOT NULL,
  `dues_type_id` int(11) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dues_payments`
--

INSERT INTO `dues_payments` (`id`, `member_id`, `dues_type_id`, `transaction_id`, `month`, `year`, `amount_paid`, `payment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 2026, 50000.00, '2026-01-01', '2026-04-18 23:50:07', '2026-04-18 23:50:07'),
(2, 1, 1, 2, 2, 2026, 50000.00, '2026-02-01', '2026-04-18 23:50:25', '2026-04-18 23:50:25'),
(3, 1, 1, 3, 3, 2026, 50000.00, '2026-03-01', '2026-04-18 23:50:39', '2026-04-18 23:50:39'),
(4, 1, 1, 4, 4, 2026, 50000.00, '2026-04-18', '2026-04-18 23:50:56', '2026-04-18 23:50:56'),
(5, 2, 1, 5, 1, 2026, 50000.00, '2026-01-01', '2026-04-18 23:51:11', '2026-04-18 23:51:11'),
(6, 2, 1, 6, 2, 2026, 50000.00, '2026-02-01', '2026-04-18 23:51:19', '2026-04-18 23:51:19'),
(7, 2, 1, 7, 3, 2026, 50000.00, '2026-03-01', '2026-04-18 23:51:26', '2026-04-18 23:51:26'),
(8, 2, 1, 8, 4, 2026, 50000.00, '2026-04-18', '2026-04-18 23:51:32', '2026-04-18 23:51:32'),
(9, 3, 1, 9, 1, 2026, 50000.00, '2026-01-01', '2026-04-18 23:51:59', '2026-04-18 23:51:59'),
(10, 3, 1, 10, 2, 2026, 50000.00, '2026-02-01', '2026-04-18 23:52:08', '2026-04-18 23:52:08'),
(11, 3, 1, 11, 3, 2026, 50000.00, '2026-03-01', '2026-04-18 23:52:15', '2026-04-18 23:52:15'),
(12, 3, 1, 12, 4, 2026, 50000.00, '2026-04-18', '2026-04-18 23:52:24', '2026-04-18 23:52:24'),
(13, 4, 1, 13, 1, 2026, 50000.00, '2026-01-01', '2026-04-18 23:53:33', '2026-04-18 23:53:33'),
(14, 4, 1, 14, 2, 2026, 50000.00, '2026-02-01', '2026-04-18 23:53:44', '2026-04-18 23:53:44'),
(15, 4, 1, 15, 3, 2026, 50000.00, '2026-03-01', '2026-04-18 23:53:55', '2026-04-18 23:53:55'),
(16, 4, 1, 16, 4, 2026, 50000.00, '2026-04-18', '2026-04-18 23:54:03', '2026-04-18 23:54:03'),
(17, 4, 1, 17, 5, 2026, 50000.00, '2026-04-18', '2026-04-18 23:54:11', '2026-04-18 23:54:11'),
(18, 4, 1, 18, 6, 2026, 50000.00, '2026-04-18', '2026-04-18 23:54:18', '2026-04-18 23:54:18'),
(19, 5, 1, 19, 4, 2026, 50000.00, '2026-04-18', '2026-04-18 23:54:44', '2026-04-18 23:54:44'),
(20, 2, 1, 20, 5, 2026, 50000.00, '2026-04-18', '2026-04-18 23:55:30', '2026-04-18 23:55:30'),
(25, 7, 2, 28, 1, 2026, 40000.00, '2026-04-23', '2026-04-23 11:41:26', '2026-04-23 11:41:26'),
(26, 7, 2, 29, 2, 2026, 40000.00, '2026-04-23', '2026-04-23 11:41:36', '2026-04-23 11:41:36'),
(27, 7, 2, 30, 3, 2026, 40000.00, '2026-04-23', '2026-04-23 11:41:42', '2026-04-23 11:41:42'),
(28, 7, 2, 31, 4, 2026, 40000.00, '2026-04-23', '2026-04-23 11:41:49', '2026-04-23 11:41:49'),
(29, 8, 2, 32, 1, 2026, 40000.00, '2026-04-23', '2026-04-23 11:42:15', '2026-04-23 11:42:15'),
(30, 8, 2, 33, 2, 2026, 40000.00, '2026-04-23', '2026-04-23 11:42:21', '2026-04-23 11:42:21'),
(31, 8, 2, 34, 3, 2026, 40000.00, '2026-04-23', '2026-04-23 11:42:27', '2026-04-23 11:42:27'),
(32, 8, 2, 35, 4, 2026, 40000.00, '2026-04-23', '2026-04-23 11:42:34', '2026-04-23 11:42:34'),
(33, 8, 2, 36, 5, 2026, 40000.00, '2026-04-23', '2026-04-23 11:42:47', '2026-04-23 11:42:47'),
(34, 8, 2, 37, 6, 2026, 40000.00, '2026-04-23', '2026-04-23 11:42:54', '2026-04-23 11:42:54'),
(35, 8, 2, 38, 7, 2026, 40000.00, '2026-04-23', '2026-04-23 11:43:03', '2026-04-23 11:43:03'),
(36, 9, 2, 39, 1, 2026, 40000.00, '2026-04-23', '2026-04-23 11:44:19', '2026-04-23 11:44:19'),
(37, 9, 2, 40, 2, 2026, 40000.00, '2026-04-23', '2026-04-23 11:44:25', '2026-04-23 11:44:25'),
(38, 9, 2, 41, 3, 2026, 40000.00, '2026-04-23', '2026-04-23 11:44:32', '2026-04-23 11:44:32'),
(39, 9, 2, 42, 4, 2026, 40000.00, '2026-04-23', '2026-04-23 11:44:42', '2026-04-23 11:44:42'),
(40, 9, 2, 43, 5, 2026, 40000.00, '2026-04-23', '2026-04-23 11:44:47', '2026-04-23 11:44:47'),
(41, 10, 2, 44, 1, 2026, 40000.00, '2026-04-23', '2026-04-23 11:45:07', '2026-04-23 11:45:07'),
(42, 10, 2, 45, 2, 2026, 40000.00, '2026-04-23', '2026-04-23 11:45:28', '2026-04-23 11:45:28'),
(43, 10, 2, 46, 3, 2026, 40000.00, '2026-04-23', '2026-04-23 11:45:37', '2026-04-23 11:45:37'),
(44, 10, 2, 47, 4, 2026, 20000.00, '2026-04-23', '2026-04-23 11:45:48', '2026-04-23 11:45:48'),
(45, 11, 2, 48, 1, 2026, 40000.00, '2026-04-23', '2026-04-23 11:46:22', '2026-04-23 11:46:22'),
(46, 11, 2, 49, 2, 2026, 40000.00, '2026-04-23', '2026-04-23 11:46:28', '2026-04-23 11:46:28'),
(47, 11, 2, 50, 3, 2026, 40000.00, '2026-04-23', '2026-04-23 11:46:34', '2026-04-23 11:46:34'),
(48, 11, 2, 51, 4, 2026, 40000.00, '2026-04-23', '2026-04-23 11:46:40', '2026-04-23 11:46:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dues_types`
--

CREATE TABLE `dues_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `period` enum('monthly','yearly','once') DEFAULT 'monthly',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dues_types`
--

INSERT INTO `dues_types` (`id`, `user_id`, `name`, `amount`, `period`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5, 'KAS HOD', 50000.00, 'monthly', 1, '2026-04-18 23:48:52', '2026-04-22 17:11:53'),
(2, 7, 'Kas Bulanan', 40000.00, 'monthly', 1, '2026-04-23 11:37:46', '2026-04-23 11:37:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `members`
--

CREATE TABLE `members` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `join_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `members`
--

INSERT INTO `members` (`id`, `user_id`, `name`, `join_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5, 'Doni Abiy', '2026-01-01', 1, '2026-04-18 23:44:06', '2026-04-22 17:24:49'),
(2, 5, 'Ardhiny', '2026-01-01', 1, '2026-04-18 23:44:38', '2026-04-18 23:44:38'),
(3, 5, 'Ota Setiawan', '2026-01-01', 1, '2026-04-18 23:45:20', '2026-04-18 23:45:20'),
(4, 5, 'Fajar Fahrodin', '2026-01-01', 1, '2026-04-18 23:47:01', '2026-04-18 23:47:01'),
(5, 5, 'Martinus', '2026-04-01', 1, '2026-04-18 23:47:41', '2026-04-18 23:47:41'),
(6, 7, 'Agus', '2025-12-01', 1, '2026-04-23 11:39:03', '2026-04-23 11:39:03'),
(7, 7, 'Doni', '2026-01-01', 1, '2026-04-23 11:39:27', '2026-04-23 11:39:27'),
(8, 7, 'Ayu', '2026-01-01', 1, '2026-04-23 11:39:45', '2026-04-23 11:39:45'),
(9, 7, 'Fajar', '2026-01-01', 1, '2026-04-23 11:39:59', '2026-04-23 11:39:59'),
(10, 7, 'Hendri', '2026-01-01', 1, '2026-04-23 11:40:14', '2026-04-23 11:40:14'),
(11, 7, 'Artono', '2026-01-01', 1, '2026-04-23 11:40:30', '2026-04-23 11:40:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-04-15-043501', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1776423773, 1),
(2, '2026-04-15-043502', 'App\\Database\\Migrations\\CreateProfilesTable', 'default', 'App', 1776423773, 1),
(3, '2026-04-15-043503', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1776423773, 1),
(4, '2026-04-15-043504', 'App\\Database\\Migrations\\CreateTransactionsTable', 'default', 'App', 1776423773, 1),
(5, '2026-04-15-043505', 'App\\Database\\Migrations\\CreateBudgetsTable', 'default', 'App', 1776423773, 1),
(6, '2026-04-16-080000', 'App\\Database\\Migrations\\CreateSessionsTable', 'default', 'App', 1776423773, 1),
(7, '2026-04-17-000000', 'App\\Database\\Migrations\\CreateDuesTables', 'default', 'App', 1776423773, 1),
(8, '2026-04-17-000001', 'App\\Database\\Migrations\\AddUserIdToDuesTables', 'default', 'App', 1776423773, 1),
(9, '2026-04-17-071957', 'App\\Database\\Migrations\\AddAppSettingsTable', 'default', 'App', 1776423773, 1),
(10, '2026-04-23-024519', 'App\\Database\\Migrations\\AddPeriodAndStatusToDuesTypes', 'default', 'App', 1776918551, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'IDR',
  `monthly_income_target` decimal(15,2) NOT NULL DEFAULT 0.00,
  `monthly_expense_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `full_name`, `phone_number`, `address`, `city`, `province`, `postal_code`, `gender`, `date_of_birth`, `avatar`, `bio`, `currency`, `monthly_income_target`, `monthly_expense_limit`, `created_at`, `updated_at`) VALUES
(1, 1, 'Administrator', '08100000000', 'Jl. Sudirman No. 1', 'Jakarta', 'DKI Jakarta', '10220', 'male', '1990-01-01', NULL, NULL, 'IDR', 20000000.00, 15000000.00, '2026-04-17 18:12:21', '2026-04-22 15:06:36'),
(4, 4, 'KAS HOD', '', '', '', '', '', '', NULL, 'uploads/avatars/1776426517_b9b3442b61a8503f7542.webp', '', 'IDR', 0.00, 0.00, '2026-04-17 18:45:42', '2026-04-17 18:48:37'),
(5, 5, 'Kas HOD', '085225106200', '', '', '', '', 'male', NULL, 'uploads/avatars/1776532634_af90a9dff7b58fa4d191.jpg', '', 'IDR', 0.00, 0.00, '2026-04-18 22:50:23', '2026-04-22 15:17:08'),
(6, 6, 'Mina Fiska', '087823766803', '', 'Semarang ', 'Jawa Tengah ', '50131', 'female', '1996-03-17', NULL, '', 'IDR', 0.00, 0.00, '2026-04-22 13:28:56', '2026-04-22 13:31:06'),
(7, 7, 'CASH FLOW ACOUNTING', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IDR', 0.00, 0.00, '2026-04-23 11:36:09', '2026-04-23 11:36:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `type` enum('income','expense','transfer') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','credit_card','debit_card','e_wallet','other') NOT NULL DEFAULT 'cash',
  `attachment` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recurring_type` enum('daily','weekly','monthly','yearly') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `category_id`, `type`, `amount`, `description`, `transaction_date`, `reference_no`, `payment_method`, `attachment`, `note`, `is_recurring`, `recurring_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Abiy Doni (Jan 2026)', '2026-01-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:50:07', '2026-04-18 23:50:07', NULL),
(2, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Abiy Doni (Feb 2026)', '2026-02-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:50:25', '2026-04-18 23:50:25', NULL),
(3, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Abiy Doni (Mar 2026)', '2026-03-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:50:39', '2026-04-18 23:50:39', NULL),
(4, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Abiy Doni (Apr 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:50:56', '2026-04-18 23:50:56', NULL),
(5, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ardhiny (Jan 2026)', '2026-01-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:51:11', '2026-04-18 23:51:11', NULL),
(6, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ardhiny (Feb 2026)', '2026-02-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:51:19', '2026-04-18 23:51:19', NULL),
(7, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ardhiny (Mar 2026)', '2026-03-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:51:26', '2026-04-18 23:51:26', NULL),
(8, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ardhiny (Apr 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:51:32', '2026-04-18 23:51:32', NULL),
(9, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ota Setiawan (Jan 2026)', '2026-01-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:51:59', '2026-04-18 23:51:59', NULL),
(10, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ota Setiawan (Feb 2026)', '2026-02-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:52:08', '2026-04-18 23:52:08', NULL),
(11, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ota Setiawan (Mar 2026)', '2026-03-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:52:15', '2026-04-18 23:52:15', NULL),
(12, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ota Setiawan (Apr 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:52:24', '2026-04-18 23:52:24', NULL),
(13, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Fajar Fahrodin (Jan 2026)', '2026-01-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:53:33', '2026-04-18 23:53:33', NULL),
(14, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Fajar Fahrodin (Feb 2026)', '2026-02-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:53:44', '2026-04-18 23:53:44', NULL),
(15, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Fajar Fahrodin (Mar 2026)', '2026-03-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:53:55', '2026-04-18 23:53:55', NULL),
(16, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Fajar Fahrodin (Apr 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:54:03', '2026-04-18 23:54:03', NULL),
(17, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Fajar Fahrodin (Mei 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:54:11', '2026-04-18 23:54:11', NULL),
(18, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Fajar Fahrodin (Jun 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:54:18', '2026-04-18 23:54:18', NULL),
(19, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Martinus (Apr 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:54:44', '2026-04-18 23:54:44', NULL),
(20, 5, 19, 'income', 50000.00, 'Iuran Kas HOD - Ardhiny (Mei 2026)', '2026-04-18', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-18 23:55:30', '2026-04-18 23:55:30', NULL),
(21, 5, 8, 'expense', 408500.00, 'Farwell Ryan', '2026-04-18', '', 'bank_transfer', NULL, 'Sate Kambing', 0, NULL, '2026-04-19 00:08:47', '2026-04-19 00:08:47', NULL),
(22, 5, 19, 'income', 1026111.00, 'Saldo akhir bulan Desmber 2025', '2025-12-31', '', 'bank_transfer', NULL, '', 0, NULL, '2026-04-22 11:14:23', '2026-04-22 11:14:23', NULL),
(23, 5, 10, 'expense', 176113.00, 'hadiah farewell pak ryan', '2026-04-22', '', 'bank_transfer', NULL, '', 0, NULL, '2026-04-22 13:29:11', '2026-04-22 13:29:11', NULL),
(28, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Doni (Jan 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:41:26', '2026-04-23 11:41:26', NULL),
(29, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Doni (Feb 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:41:36', '2026-04-23 11:41:36', NULL),
(30, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Doni (Mar 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:41:42', '2026-04-23 11:41:42', NULL),
(31, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Doni (Apr 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:41:49', '2026-04-23 11:41:49', NULL),
(32, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Ayu (Jan 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:42:15', '2026-04-23 11:42:15', NULL),
(33, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Ayu (Feb 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:42:21', '2026-04-23 11:42:21', NULL),
(34, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Ayu (Mar 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:42:27', '2026-04-23 11:42:27', NULL),
(35, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Ayu (Apr 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:42:34', '2026-04-23 11:42:34', NULL),
(36, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Ayu (Mei 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:42:47', '2026-04-23 11:42:47', NULL),
(37, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Ayu (Jun 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:42:54', '2026-04-23 11:42:54', NULL),
(38, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Ayu (Jul 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:43:03', '2026-04-23 11:43:03', NULL),
(39, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Fajar (Jan 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:44:19', '2026-04-23 11:44:19', NULL),
(40, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Fajar (Feb 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:44:25', '2026-04-23 11:44:25', NULL),
(41, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Fajar (Mar 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:44:32', '2026-04-23 11:44:32', NULL),
(42, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Fajar (Apr 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:44:42', '2026-04-23 11:44:42', NULL),
(43, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Fajar (Mei 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:44:47', '2026-04-23 11:44:47', NULL),
(44, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Hendri (Jan 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:45:07', '2026-04-23 11:45:07', NULL),
(45, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Hendri (Feb 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:45:28', '2026-04-23 11:45:28', NULL),
(46, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Hendri (Mar 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:45:37', '2026-04-23 11:45:37', NULL),
(47, 7, 19, 'income', 20000.00, 'Iuran Kas Bulanan - Hendri (Apr 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:45:48', '2026-04-23 11:45:48', NULL),
(48, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Artono (Jan 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:46:22', '2026-04-23 11:46:22', NULL),
(49, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Artono (Feb 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:46:28', '2026-04-23 11:46:28', NULL),
(50, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Artono (Mar 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:46:34', '2026-04-23 11:46:34', NULL),
(51, 7, 19, 'income', 40000.00, 'Iuran Kas Bulanan - Artono (Apr 2026)', '2026-04-23', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-23 11:46:40', '2026-04-23 11:46:40', NULL),
(52, 7, 18, 'expense', 680950.00, 'pengeluaran sebelumnya', '2026-04-23', NULL, 'cash', NULL, NULL, 0, NULL, '2026-04-23 11:48:27', '2026-04-23 11:48:27', NULL),
(53, 7, 18, 'expense', 680950.00, 'pengeluaran sebelumnya', '2026-04-23', '', 'cash', NULL, '', 0, NULL, '2026-04-23 12:31:34', '2026-04-23 12:31:47', '2026-04-23 12:31:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@cashflow.com', '$2y$10$deefQPiAqY9vBls6NBQkPeXgBTHDoUCquds17H1oZ.yb0ibHyCH7.', 'admin', 1, '2026-04-22 13:51:43', '2026-04-17 18:12:21', '2026-04-22 15:06:36', NULL),
(4, 'abiydoni', 'abiydoni@gmail.com', '$2y$10$SgPUddZu2mcDK.qihcRLC..lr3OQ/s5UX0mP2UgxXPyC.voN1.sl.', 'user', 1, '2026-04-18 22:01:01', '2026-04-17 18:45:42', '2026-04-18 22:48:38', '2026-04-18 22:48:38'),
(5, 'kashod', 'ca@dafamsemarang.com', '$2y$10$qbgCBpjbqbgZyOecB69wSOq76MjTo4OjBCF2ya/MfJeNAKScXSuWO', 'user', 1, '2026-04-23 11:56:20', '2026-04-18 22:50:22', '2026-04-23 11:56:20', NULL),
(6, 'Mbanina', 'minafiska@gmail.com', '$2y$10$m0g817oMyApt/u4X.CFz2.rRvnW2.844QAP5jhSmNim1NqW7gwiZy', 'user', 1, '2026-04-22 13:29:00', '2026-04-22 13:28:56', '2026-04-22 13:29:00', NULL),
(7, 'accounting', 'cc@dafamsemarang.com', '$2y$10$3TrkjNU.I0T55ENEcuG0DO5Qlx1ios7ce0s2BNlvRve.OCVcp0OcS', 'user', 1, '2026-04-23 12:05:33', '2026-04-23 11:36:09', '2026-04-23 12:05:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indeks untuk tabel `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`ip_address`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indeks untuk tabel `dues_payments`
--
ALTER TABLE `dues_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dues_payments_member_id_foreign` (`member_id`),
  ADD KEY `dues_payments_dues_type_id_foreign` (`dues_type_id`),
  ADD KEY `dues_payments_transaction_id_foreign` (`transaction_id`);

--
-- Indeks untuk tabel `dues_types`
--
ALTER TABLE `dues_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profiles_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `dues_payments`
--
ALTER TABLE `dues_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `dues_types`
--
ALTER TABLE `dues_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dues_payments`
--
ALTER TABLE `dues_payments`
  ADD CONSTRAINT `dues_payments_dues_type_id_foreign` FOREIGN KEY (`dues_type_id`) REFERENCES `dues_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dues_payments_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dues_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Ketidakleluasaan untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
