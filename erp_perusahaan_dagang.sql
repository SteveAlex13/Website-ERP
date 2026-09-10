-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 10, 2026 at 02:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_perusahaan_dagang`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acquired_at` date NOT NULL,
  `acquisition_cost` decimal(15,2) NOT NULL,
  `residual_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `useful_life_months` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `code`, `name`, `acquired_at`, `acquisition_cost`, `residual_value`, `useful_life_months`, `created_at`, `updated_at`) VALUES
(1, 'AST-DEMO-001', 'Kendaraan Operasional', '2025-08-26', 180000000.00, 30000000.00, 60, '2026-08-26 14:18:30', '2026-08-26 14:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `name`, `account_number`, `opening_balance`, `created_at`, `updated_at`) VALUES
(1, 'Tunai', NULL, 100000000.00, '2026-08-26 13:25:44', '2026-09-02 21:47:25'),
(2, 'Bank BCA', '10000001', 100000000.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(3, 'Bank Mandiri', '10000002', 100000000.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(4, 'Bank BRI', '10000003', 100000000.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(6, 'Bank BNI', '10000004', 100000000.00, '2026-09-02 21:46:06', '2026-09-02 21:46:06');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transactions`
--

CREATE TABLE `bank_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_transactions`
--

INSERT INTO `bank_transactions` (`id`, `bank_account_id`, `sale_id`, `transaction_at`, `type`, `payment_method`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2026-09-03 05:25:21', 'credit', 'cash', 'Pembayaran penjualan POS-20260903052513', 65000.00, '2026-09-02 22:25:21', '2026-09-02 22:25:21'),
(2, 1, 2, '2026-09-08 02:07:59', 'debit', 'cash', 'Refund POS-20260903052513: Refund', 65000.00, '2026-09-07 19:07:59', '2026-09-07 19:07:59'),
(3, 2, 4, '2026-09-08 02:11:26', 'credit', 'bank_transfer', 'Pembayaran penjualan POS-20260908021049', 65000.00, '2026-09-07 19:11:26', '2026-09-07 19:11:26'),
(4, 2, 5, '2026-09-08 02:39:50', 'credit', 'bank_transfer', 'Pembayaran invoice WEB-20260908022245 (Quotation)', 845000.00, '2026-09-07 19:39:50', '2026-09-07 19:39:50'),
(5, 2, 2, '2026-09-08 02:40:53', 'credit', 'bank_transfer', 'Pembayaran invoice POS-20260903052513 (Pos)', 65000.00, '2026-09-07 19:40:53', '2026-09-07 19:40:53'),
(6, 3, 7, '2026-09-08 02:56:04', 'credit', 'bank_transfer', 'Pembayaran invoice POS-20260908024111 (Pos)', 390000.00, '2026-09-07 19:56:04', '2026-09-07 19:56:04'),
(7, 2, 6, '2026-09-08 03:02:16', 'credit', 'bank_transfer', 'Pembayaran invoice WEB-20260908022551 (Order)', 585000.00, '2026-09-07 20:02:16', '2026-09-07 20:02:16'),
(8, 2, 8, '2026-09-08 03:08:40', 'credit', 'bank_transfer', 'Pembayaran invoice WEB-20260908025701 (Order)', 650000.00, '2026-09-07 20:08:40', '2026-09-07 20:08:40'),
(9, 1, 9, '2026-09-08 03:09:05', 'credit', 'cash', 'Pembayaran invoice POS-20260908030845 (Pos)', 65000.00, '2026-09-07 20:09:05', '2026-09-07 20:09:05'),
(10, 2, 10, '2026-09-09 17:39:17', 'credit', 'bank_transfer', 'Pembayaran invoice POS-20260908031247 (Pos)', 390000.00, '2026-09-09 10:39:17', '2026-09-09 10:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_expenses`
--

CREATE TABLE `cash_expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_expenses`
--

INSERT INTO `cash_expenses` (`id`, `bank_account_id`, `date`, `category`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-08-26', 'Operasional', 'Biaya operasional gudang', 250000.00, '2026-08-26 14:18:30', '2026-08-26 14:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `type`, `name`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'supplier', 'PT Sumber Niaga', NULL, NULL, NULL, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(2, 'customer', 'Toko Sejahtera', NULL, NULL, NULL, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(3, 'customer', 'Samuel', '08312719232', NULL, NULL, '2026-09-07 19:22:38', '2026-09-07 19:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_orders`
--

CREATE TABLE `delivery_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_orders`
--

INSERT INTO `delivery_orders` (`id`, `sale_id`, `number`, `date`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 'DO-20260908022605', '2026-09-08', 'confirmed', '2026-09-07 19:26:24', '2026-09-07 19:26:24'),
(2, 8, 'DO-20260908030749', '2026-09-08', 'confirmed', '2026-09-07 20:07:57', '2026-09-07 20:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_order_items`
--

CREATE TABLE `delivery_order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `delivery_order_id` bigint UNSIGNED NOT NULL,
  `sale_item_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_order_items`
--

INSERT INTO `delivery_order_items` (`id`, `delivery_order_id`, `sale_item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 1.00, '2026-09-07 19:26:24', '2026-09-07 19:26:24'),
(2, 2, 9, 1.00, '2026-09-07 20:07:57', '2026-09-07 20:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `expense_purchases`
--

CREATE TABLE `expense_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `purchase_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kebutuhan',
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'invoice',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_allocations`
--

CREATE TABLE `inventory_allocations` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_item_id` bigint UNSIGNED NOT NULL,
  `inventory_batch_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_allocations`
--

INSERT INTO `inventory_allocations` (`id`, `sale_item_id`, `inventory_batch_id`, `quantity`, `unit_cost`, `total_cost`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1.00, 500000.00, 500000.00, '2026-09-02 22:25:21', '2026-09-02 22:25:21'),
(2, 4, 1, 1.00, 500000.00, 500000.00, '2026-09-07 19:11:26', '2026-09-07 19:11:26'),
(3, 7, 2, 1.00, 450000.00, 450000.00, '2026-09-07 19:26:24', '2026-09-07 19:26:24'),
(4, 5, 3, 1.00, 350000.00, 350000.00, '2026-09-07 19:39:50', '2026-09-07 19:39:50'),
(5, 6, 4, 1.00, 300000.00, 300000.00, '2026-09-07 19:39:50', '2026-09-07 19:39:50'),
(6, 2, 1, 1.00, 500000.00, 500000.00, '2026-09-07 19:40:53', '2026-09-07 19:40:53'),
(7, 8, 4, 1.00, 300000.00, 300000.00, '2026-09-07 19:56:04', '2026-09-07 19:56:04'),
(8, 9, 5, 1.00, 500000.00, 500000.00, '2026-09-07 20:07:57', '2026-09-07 20:07:57'),
(9, 10, 1, 1.00, 500000.00, 500000.00, '2026-09-07 20:09:05', '2026-09-07 20:09:05'),
(10, 11, 4, 1.00, 300000.00, 300000.00, '2026-09-09 10:39:17', '2026-09-09 10:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_batches`
--

CREATE TABLE `inventory_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `purchase_item_id` bigint UNSIGNED DEFAULT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `received_at` date NOT NULL,
  `initial_quantity` decimal(15,2) NOT NULL,
  `remaining_quantity` decimal(15,2) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_batches`
--

INSERT INTO `inventory_batches` (`id`, `product_id`, `purchase_item_id`, `supplier_id`, `received_at`, `initial_quantity`, `remaining_quantity`, `unit_cost`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-08-31', 10.00, 7.00, 500000.00, '2026-08-31 13:58:33', '2026-09-07 20:09:05'),
(2, 9, NULL, NULL, '2026-08-26', 20.00, 19.00, 450000.00, '2026-09-07 19:26:24', '2026-09-07 19:26:24'),
(3, 7, NULL, NULL, '2026-08-26', 20.00, 19.00, 350000.00, '2026-09-07 19:39:50', '2026-09-07 19:39:50'),
(4, 6, NULL, NULL, '2026-08-26', 20.00, 17.00, 300000.00, '2026-09-07 19:39:50', '2026-09-09 10:39:17'),
(5, 10, NULL, NULL, '2026-08-26', 20.00, 19.00, 500000.00, '2026-09-07 20:07:57', '2026-09-07 20:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_26_195006_add_role_and_sales_target_to_users_table', 1),
(5, '2026_08_26_195007_create_assets_table', 1),
(6, '2026_08_26_195010_create_cash_expenses_table', 1),
(7, '2026_08_26_195011_create_bank_accounts_table', 1),
(8, '2026_08_26_195012_create_product_returns_table', 1),
(9, '2026_08_26_195013_create_delivery_order_items_table', 1),
(10, '2026_08_26_195014_create_delivery_orders_table', 1),
(11, '2026_08_26_195015_create_sale_items_table', 1),
(12, '2026_08_26_195016_create_sales_table', 1),
(13, '2026_08_26_195017_create_purchase_items_table', 1),
(14, '2026_08_26_195032_create_purchases_table', 1),
(15, '2026_08_26_195033_create_products_table', 1),
(16, '2026_08_26_195034_create_contacts_table', 1),
(17, '2026_08_26_195844_add_erp_foreign_keys', 1),
(18, '2026_08_26_202410_create_personal_access_tokens_table', 2),
(19, '2026_08_26_211204_create_product_return_items_table', 3),
(20, '2026_08_26_211205_add_commission_settings_to_users_table', 3),
(21, '2026_08_31_202955_create_fifo_inventory_tables', 4),
(22, '2026_08_31_205758_add_payment_fields_to_sales_and_create_bank_transactions_table', 5),
(23, '2026_08_31_213351_create_expense_purchases_table', 6),
(24, '2026_09_03_041003_add_purchase_type_and_document_type_to_expense_purchases_table', 7),
(25, '2026_09_03_044008_make_customer_id_nullable_on_sales_table', 8),
(26, '2026_09_03_050812_add_quotation_delete_key_to_users_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock` decimal(15,2) NOT NULL DEFAULT '0.00',
  `minimum_stock` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `unit`, `purchase_price`, `selling_price`, `stock`, `minimum_stock`, `created_at`, `updated_at`) VALUES
(1, 'PRD-001', 'Produk 1', 'pcs', 500000.00, 65000.00, 7.00, 5.00, '2026-08-26 13:25:44', '2026-09-07 20:09:05'),
(2, 'PRD-002', 'Produk 2', 'pcs', 100000.00, 130000.00, 20.00, 5.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(3, 'PRD-003', 'Produk 3', 'pcs', 150000.00, 195000.00, 20.00, 5.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(4, 'PRD-004', 'Produk 4', 'pcs', 200000.00, 260000.00, 20.00, 5.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(5, 'PRD-005', 'Produk 5', 'pcs', 250000.00, 325000.00, 20.00, 5.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(6, 'PRD-006', 'Produk 6', 'pcs', 300000.00, 390000.00, 17.00, 5.00, '2026-08-26 13:25:44', '2026-09-09 10:39:17'),
(7, 'PRD-007', 'Produk 7', 'pcs', 350000.00, 455000.00, 19.00, 5.00, '2026-08-26 13:25:44', '2026-09-07 19:39:50'),
(8, 'PRD-008', 'Produk 8', 'pcs', 400000.00, 520000.00, 20.00, 5.00, '2026-08-26 13:25:44', '2026-08-26 13:25:44'),
(9, 'PRD-009', 'Produk 9', 'pcs', 450000.00, 585000.00, 19.00, 5.00, '2026-08-26 13:25:44', '2026-09-07 19:26:24'),
(10, 'PRD-010', 'Produk 10', 'pcs', 500000.00, 650000.00, 19.00, 5.00, '2026-08-26 13:25:44', '2026-09-07 20:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `product_returns`
--

CREATE TABLE `product_returns` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_return_items`
--

CREATE TABLE `product_return_items` (
  `id` bigint UNSIGNED NOT NULL,
  `product_return_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `shipping_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `number`, `supplier_id`, `date`, `status`, `shipping_cost`, `shipping_status`, `total`, `paid_amount`, `created_at`, `updated_at`) VALUES
(1, 'PO-DEMO-001', 1, '2026-08-26', 'completed', 100000.00, 'unpaid', 5000000.00, 0.00, '2026-08-26 14:18:30', '2026-08-31 13:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `received_quantity` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `price`, `subtotal`, `received_quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10.00, 500000.00, 5000000.00, 10.00, '2026-08-26 14:18:30', '2026-08-31 13:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'order',
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `sales_user_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `number`, `bill_name`, `document_type`, `customer_id`, `sales_user_id`, `date`, `status`, `discount_type`, `discount_value`, `total`, `paid_amount`, `payment_method`, `bank_account_id`, `created_at`, `updated_at`) VALUES
(1, 'SO-DEMO-001', NULL, 'order', 2, 1, '2026-08-26', 'open', NULL, 0.00, 1300000.00, 0.00, NULL, NULL, '2026-08-26 14:18:30', '2026-08-26 14:18:30'),
(2, 'POS-20260903052513', NULL, 'pos', NULL, 7, '2026-09-03', 'completed', 'fixed', 0.00, 65000.00, 65000.00, 'bank_transfer', 2, '2026-09-02 22:25:21', '2026-09-07 19:40:53'),
(4, 'POS-20260908021049', NULL, 'pos', NULL, 7, '2026-09-08', 'completed', 'fixed', 0.00, 65000.00, 65000.00, 'bank_transfer', 2, '2026-09-07 19:11:26', '2026-09-07 19:11:26'),
(5, 'WEB-20260908022245', NULL, 'quotation', 3, 7, '2026-09-08', 'completed', 'fixed', 0.00, 845000.00, 845000.00, 'bank_transfer', 2, '2026-09-07 19:22:54', '2026-09-07 19:39:50'),
(6, 'WEB-20260908022551', NULL, 'order', 3, 7, '2026-09-08', 'completed', 'fixed', 0.00, 585000.00, 585000.00, 'bank_transfer', 2, '2026-09-07 19:26:05', '2026-09-07 20:02:16'),
(7, 'POS-20260908024111', '1', 'pos', NULL, 7, '2026-09-08', 'completed', 'fixed', 0.00, 390000.00, 390000.00, 'bank_transfer', 3, '2026-09-07 19:41:21', '2026-09-07 19:56:04'),
(8, 'WEB-20260908025701', NULL, 'order', 2, 7, '2026-09-08', 'completed', 'fixed', 0.00, 650000.00, 650000.00, 'bank_transfer', 2, '2026-09-07 19:57:17', '2026-09-07 20:08:40'),
(9, 'POS-20260908030845', '1', 'pos', NULL, 7, '2026-09-08', 'completed', 'fixed', 0.00, 65000.00, 65000.00, 'cash', 1, '2026-09-07 20:09:05', '2026-09-07 20:09:05'),
(10, 'POS-20260908031247', '5', 'pos', NULL, 7, '2026-09-08', 'completed', 'fixed', 0.00, 390000.00, 390000.00, 'bank_transfer', 2, '2026-09-07 20:13:18', '2026-09-09 10:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cost_of_goods_sold` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `price`, `discount`, `subtotal`, `created_at`, `updated_at`, `cost_of_goods_sold`) VALUES
(1, 1, 1, 2.00, 650000.00, 0.00, 1300000.00, '2026-08-26 14:18:30', '2026-08-26 14:18:30', 0.00),
(2, 2, 1, 1.00, 65000.00, 0.00, 65000.00, '2026-09-02 22:25:21', '2026-09-07 19:40:53', 1000000.00),
(4, 4, 1, 1.00, 65000.00, 0.00, 65000.00, '2026-09-07 19:11:26', '2026-09-07 19:11:26', 500000.00),
(5, 5, 7, 1.00, 455000.00, 0.00, 455000.00, '2026-09-07 19:22:54', '2026-09-07 19:39:50', 350000.00),
(6, 5, 6, 1.00, 390000.00, 0.00, 390000.00, '2026-09-07 19:22:54', '2026-09-07 19:39:50', 300000.00),
(7, 6, 9, 1.00, 585000.00, 0.00, 585000.00, '2026-09-07 19:26:05', '2026-09-07 19:26:24', 450000.00),
(8, 7, 6, 1.00, 390000.00, 0.00, 390000.00, '2026-09-07 19:41:21', '2026-09-07 19:56:04', 300000.00),
(9, 8, 10, 1.00, 650000.00, 0.00, 650000.00, '2026-09-07 19:57:17', '2026-09-07 20:07:57', 500000.00),
(10, 9, 1, 1.00, 65000.00, 0.00, 65000.00, '2026-09-07 20:09:05', '2026-09-07 20:09:05', 500000.00),
(11, 10, 6, 1.00, 390000.00, 0.00, 390000.00, '2026-09-07 20:13:18', '2026-09-09 10:39:17', 300000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0GqS89G8fZNVSakH8rLc3RPNrx8XGRNzvV2ssCNR', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJSS0YweTByeUZ0ZkhEUEVaaFJjS2JxRFFyMkZuQ3l5N2dzbXJBZ1lNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo3fQ==', 1789006733),
('4LpqCU0nKDyYeIAiexzXtcMamktDxICFTatTCRYy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWbURpSkJEekN6OXBLeGUycDVoZDM4RWdrRlZIUTBRaVEyNHpRVkpkIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6bnVsbH19', 1788978335);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quotation_delete_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sales',
  `sales_target` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commission_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage_of_sale',
  `commission_value` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `quotation_delete_key`, `remember_token`, `created_at`, `updated_at`, `role`, `sales_target`, `commission_type`, `commission_value`) VALUES
(1, 'Steve', 'steve@gmail.com', NULL, '$2y$12$z/Um0C9/WfyBlEZ9pdBKveLe5Sdm3NM2c5cHSLWutchuH4VYpSS4y', NULL, NULL, '2026-08-26 13:04:54', '2026-08-26 14:18:30', 'sales', 1000000.00, 'percentage_of_sale', 2.50),
(2, 'Administrator', 'admin@erp.test', '2026-08-31 13:26:14', '$2y$12$rNeoiZx0xgqLWqPYHjdI5OvLWtXq9Jl/LGgWSX8a6NRi0bzGzdMYa', NULL, NULL, '2026-08-26 13:25:42', '2026-08-31 13:26:14', 'admin', 0.00, 'percentage_of_sale', 0.00),
(3, 'Purchasing', 'purchasing@erp.test', '2026-08-31 13:26:14', '$2y$12$yIDSEjsso.gSY1RVfvS3se63S7ta92YbFReaZBCepru4nUVUtqlfi', NULL, NULL, '2026-08-26 13:25:43', '2026-08-31 13:26:14', 'purchasing', 0.00, 'percentage_of_sale', 0.00),
(4, 'Sales', 'sales@erp.test', '2026-08-31 13:26:14', '$2y$12$Te7a/62ETNZltKLv2MjJz.h7g11yTM6i33t70drOwIhhh8qBk2JHa', NULL, NULL, '2026-08-26 13:25:43', '2026-08-31 13:26:14', 'sales', 0.00, 'percentage_of_sale', 0.00),
(5, 'Finance', 'finance@erp.test', '2026-08-31 13:26:14', '$2y$12$7/2pJsDO1QSHkGROKP29Kuj07ynizcWM0DoFyNvLiSSx3SF0mBJTe', NULL, NULL, '2026-08-26 13:25:44', '2026-08-31 13:26:15', 'finance', 0.00, 'percentage_of_sale', 0.00),
(6, 'Warehouse', 'warehouse@erp.test', '2026-08-31 13:26:15', '$2y$12$Mq7yVLuCiKhGHjQEeBE8ruUWYmLJODDC8YEdjJdyQ7IyPpqmdC3Iu', NULL, NULL, '2026-08-26 13:25:44', '2026-08-31 13:26:15', 'warehouse', 0.00, 'percentage_of_sale', 0.00),
(7, 'Super Administrator', 'superadmin@erp.test', '2026-08-31 13:26:13', '$2y$12$2P9hWdfbFfv8hIaUZ7wLMeDEzegh5QKUbjp73Clkqyaqqk/rvNrkK', '$2y$12$9tYg2PSVuok8Gm3jdbzdj.Z/D8.uAaspz03waSqshBqECuTcW.akW', NULL, '2026-08-31 13:26:14', '2026-09-07 19:07:24', 'superadmin', 0.00, 'percentage_of_sale', 0.00),
(8, 'Kasir', 'kasir@erp.test', '2026-08-31 13:26:14', '$2y$12$ZIOm4z44y5qAnCnrVo4mq..I1k2SSNBog5pX5w3coqMWyU4rZo09e', NULL, NULL, '2026-08-31 13:26:14', '2026-08-31 13:26:14', 'cashier', 0.00, 'percentage_of_sale', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assets_code_unique` (`code`),
  ADD KEY `assets_acquired_at_index` (`acquired_at`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_accounts_name_unique` (`name`),
  ADD UNIQUE KEY `bank_accounts_account_number_unique` (`account_number`);

--
-- Indexes for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_transactions_sale_id_foreign` (`sale_id`),
  ADD KEY `bank_transactions_bank_account_id_transaction_at_index` (`bank_account_id`,`transaction_at`),
  ADD KEY `bank_transactions_transaction_at_index` (`transaction_at`),
  ADD KEY `bank_transactions_type_index` (`type`),
  ADD KEY `bank_transactions_payment_method_index` (`payment_method`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `cash_expenses`
--
ALTER TABLE `cash_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_expenses_bank_account_id_index` (`bank_account_id`),
  ADD KEY `cash_expenses_date_index` (`date`),
  ADD KEY `cash_expenses_category_index` (`category`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_type_index` (`type`),
  ADD KEY `contacts_name_index` (`name`);

--
-- Indexes for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_orders_number_unique` (`number`),
  ADD KEY `delivery_orders_sale_id_index` (`sale_id`),
  ADD KEY `delivery_orders_date_index` (`date`),
  ADD KEY `delivery_orders_status_index` (`status`);

--
-- Indexes for table `delivery_order_items`
--
ALTER TABLE `delivery_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_order_items_delivery_order_id_index` (`delivery_order_id`),
  ADD KEY `delivery_order_items_sale_item_id_index` (`sale_item_id`);

--
-- Indexes for table `expense_purchases`
--
ALTER TABLE `expense_purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_purchases_number_unique` (`number`),
  ADD KEY `expense_purchases_supplier_id_foreign` (`supplier_id`),
  ADD KEY `expense_purchases_date_index` (`date`),
  ADD KEY `expense_purchases_payment_status_index` (`payment_status`),
  ADD KEY `expense_purchases_purchase_type_index` (`purchase_type`),
  ADD KEY `expense_purchases_document_type_index` (`document_type`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `inventory_allocations`
--
ALTER TABLE `inventory_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_allocations_sale_item_id_foreign` (`sale_item_id`),
  ADD KEY `inventory_allocations_inventory_batch_id_foreign` (`inventory_batch_id`);

--
-- Indexes for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_batches_purchase_item_id_foreign` (`purchase_item_id`),
  ADD KEY `inventory_batches_supplier_id_foreign` (`supplier_id`),
  ADD KEY `inventory_batches_product_id_received_at_id_index` (`product_id`,`received_at`,`id`),
  ADD KEY `inventory_batches_product_id_remaining_quantity_index` (`product_id`,`remaining_quantity`),
  ADD KEY `inventory_batches_received_at_index` (`received_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_name_index` (`name`);

--
-- Indexes for table `product_returns`
--
ALTER TABLE `product_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_returns_number_unique` (`number`),
  ADD KEY `product_returns_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  ADD KEY `product_returns_type_index` (`type`),
  ADD KEY `product_returns_date_index` (`date`),
  ADD KEY `product_returns_status_index` (`status`);

--
-- Indexes for table `product_return_items`
--
ALTER TABLE `product_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_return_items_product_return_id_foreign` (`product_return_id`),
  ADD KEY `product_return_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchases_number_unique` (`number`),
  ADD KEY `purchases_supplier_id_index` (`supplier_id`),
  ADD KEY `purchases_date_index` (`date`),
  ADD KEY `purchases_status_index` (`status`),
  ADD KEY `purchases_shipping_status_index` (`shipping_status`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_index` (`purchase_id`),
  ADD KEY `purchase_items_product_id_index` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_number_unique` (`number`),
  ADD KEY `sales_document_type_index` (`document_type`),
  ADD KEY `sales_customer_id_index` (`customer_id`),
  ADD KEY `sales_sales_user_id_index` (`sales_user_id`),
  ADD KEY `sales_date_index` (`date`),
  ADD KEY `sales_status_index` (`status`),
  ADD KEY `sales_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `sales_payment_method_index` (`payment_method`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_index` (`sale_id`),
  ADD KEY `sale_items_product_id_index` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cash_expenses`
--
ALTER TABLE `cash_expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_order_items`
--
ALTER TABLE `delivery_order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expense_purchases`
--
ALTER TABLE `expense_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_allocations`
--
ALTER TABLE `inventory_allocations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_returns`
--
ALTER TABLE `product_returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_return_items`
--
ALTER TABLE `product_return_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD CONSTRAINT `bank_transactions_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_transactions_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_expenses`
--
ALTER TABLE `cash_expenses`
  ADD CONSTRAINT `cash_expenses_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  ADD CONSTRAINT `delivery_orders_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_order_items`
--
ALTER TABLE `delivery_order_items`
  ADD CONSTRAINT `delivery_order_items_delivery_order_id_foreign` FOREIGN KEY (`delivery_order_id`) REFERENCES `delivery_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_order_items_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `expense_purchases`
--
ALTER TABLE `expense_purchases`
  ADD CONSTRAINT `expense_purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_allocations`
--
ALTER TABLE `inventory_allocations`
  ADD CONSTRAINT `inventory_allocations_inventory_batch_id_foreign` FOREIGN KEY (`inventory_batch_id`) REFERENCES `inventory_batches` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `inventory_allocations_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  ADD CONSTRAINT `inventory_batches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `inventory_batches_purchase_item_id_foreign` FOREIGN KEY (`purchase_item_id`) REFERENCES `purchase_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_batches_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `contacts` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `product_return_items`
--
ALTER TABLE `product_return_items`
  ADD CONSTRAINT `product_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `product_return_items_product_return_id_foreign` FOREIGN KEY (`product_return_id`) REFERENCES `product_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `contacts` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `contacts` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `sales_sales_user_id_foreign` FOREIGN KEY (`sales_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
