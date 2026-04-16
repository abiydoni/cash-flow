-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Apr 2026 pada 11.55
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
(1, NULL, 'Gaji', 'income', 'cash-outline', '#10b981', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(2, NULL, 'Bonus', 'income', 'gift-outline', '#34d399', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(3, NULL, 'Investasi', 'income', 'trending-up-outline', '#6ee7b7', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(4, NULL, 'Freelance', 'income', 'laptop-outline', '#059669', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(5, NULL, 'Bisnis', 'income', 'business-outline', '#047857', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(6, NULL, 'Penjualan', 'income', 'cart-outline', '#065f46', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(7, NULL, 'Lain-lain', 'income', 'add-circle-outline', '#6b7280', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(8, NULL, 'Makan & Minum', 'expense', 'restaurant-outline', '#ef4444', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(9, NULL, 'Transportasi', 'expense', 'car-outline', '#f97316', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(10, NULL, 'Belanja', 'expense', 'bag-outline', '#eab308', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(11, NULL, 'Kesehatan', 'expense', 'medkit-outline', '#ec4899', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(12, NULL, 'Pendidikan', 'expense', 'school-outline', '#8b5cf6', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(13, NULL, 'Hiburan', 'expense', 'game-controller-outline', '#3b82f6', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(14, NULL, 'Tagihan', 'expense', 'receipt-outline', '#6366f1', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(15, NULL, 'Rumah', 'expense', 'home-outline', '#14b8a6', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(16, NULL, 'Asuransi', 'expense', 'shield-outline', '#0891b2', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(17, NULL, 'Tabungan', 'expense', 'save-outline', '#7c3aed', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(18, NULL, 'Lain-lain', 'expense', 'ellipsis-horizontal-outline', '#9ca3af', 1, '2026-04-16 09:36:42', '2026-04-16 09:36:42');

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
(1, '2026-04-15-043501', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1776306993, 1),
(2, '2026-04-15-043502', 'App\\Database\\Migrations\\CreateProfilesTable', 'default', 'App', 1776306994, 1),
(3, '2026-04-15-043503', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1776306994, 1),
(4, '2026-04-15-043504', 'App\\Database\\Migrations\\CreateTransactionsTable', 'default', 'App', 1776306995, 1),
(5, '2026-04-15-043505', 'App\\Database\\Migrations\\CreateBudgetsTable', 'default', 'App', 1776306996, 1),
(6, '2026-04-16-080000', 'App\\Database\\Migrations\\CreateSessionsTable', 'default', 'App', 1776306996, 1);

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
(1, 1, 'Administrator', '08100000000', 'Jl. Sudirman No. 1', 'Jakarta', 'DKI Jakarta', '10220', 'male', '1990-01-01', NULL, NULL, 'IDR', 20000000.00, 15000000.00, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(2, 2, 'John Doe', '08111111111', 'Jl. Thamrin No. 10', 'Bandung', 'Jawa Barat', '40111', 'male', '1995-06-15', NULL, NULL, 'IDR', 10000000.00, 7000000.00, '2026-04-16 09:36:42', '2026-04-16 09:36:42'),
(3, 3, 'Jane Doe', '08222222222', 'Jl. Gatot Subroto No. 5', 'Surabaya', 'Jawa Timur', '60100', 'female', '1998-03-22', NULL, NULL, 'IDR', 8000000.00, 5000000.00, '2026-04-16 09:36:42', '2026-04-16 09:36:42');

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
(1, 2, 1, 'income', 8500000.00, 'Gaji Bulan 2026-02', '2026-02-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(2, 2, 8, 'expense', 1500000.00, 'Makan bulan 2026-02', '2026-02-05', NULL, 'cash', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(3, 2, 9, 'expense', 500000.00, 'Bensin & Grabcar', '2026-02-10', NULL, 'e_wallet', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(4, 2, 14, 'expense', 700000.00, 'Listrik & Air', '2026-02-15', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(5, 2, 4, 'income', 2000000.00, 'Proyek Freelance', '2026-02-20', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(6, 2, 1, 'income', 8500000.00, 'Gaji Bulan 2026-03', '2026-03-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(7, 2, 8, 'expense', 1500000.00, 'Makan bulan 2026-03', '2026-03-05', NULL, 'cash', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(8, 2, 9, 'expense', 500000.00, 'Bensin & Grabcar', '2026-03-10', NULL, 'e_wallet', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(9, 2, 14, 'expense', 700000.00, 'Listrik & Air', '2026-03-15', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(10, 2, 4, 'income', 2000000.00, 'Proyek Freelance', '2026-03-20', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(11, 2, 1, 'income', 8500000.00, 'Gaji Bulan 2026-04', '2026-04-01', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(12, 2, 8, 'expense', 1500000.00, 'Makan bulan 2026-04', '2026-04-05', NULL, 'cash', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(13, 2, 9, 'expense', 500000.00, 'Bensin & Grabcar', '2026-04-10', NULL, 'e_wallet', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(14, 2, 14, 'expense', 700000.00, 'Listrik & Air', '2026-04-15', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL),
(15, 2, 4, 'income', 2000000.00, 'Proyek Freelance', '2026-04-20', NULL, 'bank_transfer', NULL, NULL, 0, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL);

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
(1, 'admin', 'admin@cashflow.com', '$2y$10$N6igVric2NJ.IgPi0iNeQ.g7eTLvJT.6lJilmX5Rkb1m0eBafh.82', 'admin', 1, '2026-04-16 15:25:13', '2026-04-16 09:36:42', '2026-04-16 15:25:13', NULL),
(2, 'john_doe', 'john@cashflow.com', '$2y$10$CAdT.Ow39wFN1hCHCdfLuuv3IT33fliZuq0wC6awiSGmrBDIgd3YO', 'user', 1, '2026-04-16 09:48:42', '2026-04-16 09:36:42', '2026-04-16 09:48:42', NULL),
(3, 'jane_doe', 'jane@cashflow.com', '$2y$10$8scFnu06xggZWEVHHy5x3OLh4sLsPyHn4.ZI5dX/cOSLII4vBGjC6', 'user', 1, NULL, '2026-04-16 09:36:42', '2026-04-16 09:36:42', NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
