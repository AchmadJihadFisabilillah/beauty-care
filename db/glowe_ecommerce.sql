-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2026 at 06:56 AM
-- Server version: 8.0.45
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glowe_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `recipient_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address_line` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `recipient_name`, `phone`, `address_line`, `city`, `province`, `postal_code`, `notes`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', '', 1, '2026-04-06 07:15:14', '2026-04-06 07:15:14'),
(2, 3, 'kipli anjay', '0812345678', 'kemana mana hatiku senang', 'madura', 'JAWA TIMUR', '47899', '', 1, '2026-04-07 02:19:26', '2026-04-07 02:19:26'),
(3, 4, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', '', 1, '2026-04-07 02:42:20', '2026-04-07 02:42:20'),
(4, 1, 'Zulfikar Fauzi', '0812345678', 'telang', 'bangkalan', 'jawa timur', '1234', '', 1, '2026-04-20 15:26:59', '2026-04-20 15:26:59'),
(5, 5, 'Zulfikar Fauzi', '0812345678', 'telang', 'bangkalan', 'jawa timur', '1234', '', 1, '2026-04-20 15:39:32', '2026-04-20 15:39:32'),
(6, 6, '24-054 ZULFIKAR FAUZI', '085735514152', '-Campor', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', '', 1, '2026-05-04 19:04:25', '2026-05-04 19:04:25'),
(7, 7, 'jihad', '0987654321', 'sj', 'sj', 'sj', '1', 'q', 0, '2026-05-11 01:16:59', '2026-05-11 01:16:59'),
(8, 8, 'k', '0987654321', 'asdfghj', 'asdfgh', 'asdfg', '1234', 'asdfgh', 1, '2026-06-02 01:02:35', '2026-06-02 01:02:35'),
(9, 10, 'Rendi', '08974879695', 'Jl. Tambak Wedi', 'Surabaya', 'Jatim', '0987', 'da', 1, '2026-06-11 15:06:30', '2026-06-11 15:06:30'),
(10, 11, 'Renday', '56127631827387', 'Surabaya', 'Surabaya', 'Jawa Timur', '62197', 'Aku Jawa', 1, '2026-06-12 06:47:52', '2026-06-12 06:47:52');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `activity` varchar(255) NOT NULL,
  `context` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `activity`, `context`, `created_at`) VALUES
(1, 1, 'Menambahkan brand', '{\"name\":\"Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++\",\"description\":\"Biore UV Aqua Rich Watery Essence SPF 50+ PA++++ adalah tabir surya dengan tekstur berbasis air yang sangat ringan dan memberikan sensasi dingin saat menyentuh kulit. Produk ini menggunakan teknologi Micro Defense untuk melindungi kulit hingga ke celah terkecil tanpa meninggalkan rasa lengket atau lapisan putih (white cast). Selain melindungi dari sinar UV, formulanya yang diperkaya dengan Hyaluronic Acid menjaga kulit tetap lembap dan terhidrasi, sehingga sangat nyaman digunakan sebagai dasar riasan sehari-hari.\"}', '2026-04-07 07:27:39'),
(2, 1, 'Mengupdate brand', '{\"id\":8,\"name\":\"Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++\",\"description\":\"Biore UV Aqua Rich Watery Essence SPF 50+ PA++++ adalah tabir surya dengan tekstur berbasis air yang sangat ringan dan memberikan sensasi dingin saat menyentuh kulit. Produk ini menggunakan teknologi Micro Defense untuk melindungi kulit hingga ke celah terkecil tanpa meninggalkan rasa lengket atau lapisan putih (white cast). Selain melindungi dari sinar UV, formulanya yang diperkaya dengan Hyaluronic Acid menjaga kulit tetap lembap dan terhidrasi, sehingga sangat nyaman digunakan sebagai dasar riasan sehari-hari.\"}', '2026-04-07 07:44:00'),
(3, 1, 'Pergerakan stok produk', '{\"product_id\":8,\"product_name\":\"Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++\",\"movement_type\":\"in\",\"qty\":18,\"note\":\"\"}', '2026-04-07 07:51:32'),
(4, 1, 'Upload gallery product', '{\"product_id\":8,\"count\":1}', '2026-04-07 07:52:37'),
(5, 1, 'Menghapus brand', '{\"brand_id\":4}', '2026-04-07 08:00:41'),
(6, 1, 'Menambahkan brand', '{\"name\":\"Scarlett Whitening\",\"description\":\"Reveal Your Beauty\"}', '2026-04-07 08:01:09'),
(7, 1, 'Mengubah brand', '{\"brand_id\":5,\"name\":\"Scarlett Whitening\",\"description\":\"Reveal Your Beautyy\"}', '2026-04-07 08:01:32'),
(8, 1, 'Menambahkan kategori', '{\"name\":\"Lip-care\"}', '2026-04-07 08:02:28'),
(9, 1, 'Mengupdate kategori', '{\"id\":5,\"name\":\"Lip-care\"}', '2026-04-07 08:02:43'),
(10, 1, 'Menghapus kategori', '{\"id\":5}', '2026-04-07 08:02:59'),
(11, 1, 'Update status pesanan', '{\"order_id\":20,\"status\":\"cancelled\"}', '2026-04-07 08:03:32'),
(12, 1, 'Menambahkan ongkir', '{\"label\":\"3\",\"city\":\"sumenep\",\"cost\":30000}', '2026-04-07 08:04:13'),
(13, 1, 'Mengubah ongkir', '{\"id\":3,\"label\":\"\",\"city\":\"\",\"cost\":0}', '2026-04-07 08:04:29'),
(14, 1, 'Menambahkan ongkir', '{\"label\":\"3\",\"city\":\"sumenep\",\"cost\":30000}', '2026-04-07 08:05:48'),
(15, 1, 'Menghapus ongkir', '{\"id\":4,\"label\":\"\",\"city\":\"\",\"cost\":0}', '2026-04-07 08:05:54'),
(16, 1, 'Menambahkan ongkir', '{\"label\":\"3\",\"city\":\"sumenep\",\"cost\":30000}', '2026-04-07 08:07:10'),
(17, 1, 'Menghapus ongkir', '{\"id\":5}', '2026-04-07 08:07:12'),
(18, 1, 'Export laporan bulanan', '{\"total_bulan\":0}', '2026-04-07 08:07:22'),
(19, 1, 'Update status pesanan', '{\"order_id\":21,\"status\":\"shipped\"}', '2026-04-20 15:35:27'),
(20, 1, 'Verifikasi pembayaran', '{\"payment_id\":2,\"action\":\"verify\"}', '2026-04-20 15:43:45'),
(21, 1, 'Verifikasi pembayaran', '{\"payment_id\":2,\"action\":\"verify\"}', '2026-04-20 15:43:54'),
(22, 1, 'Update status pesanan', '{\"order_id\":23,\"status\":\"shipped\"}', '2026-04-20 15:44:08'),
(23, 1, 'Verifikasi pembayaran', '{\"payment_id\":3,\"action\":\"verify\"}', '2026-04-20 16:32:27'),
(24, 1, 'Update status pesanan', '{\"order_id\":24,\"status\":\"shipped\"}', '2026-04-20 16:32:37'),
(25, 1, 'Update status pesanan', '{\"order_id\":25,\"status\":\"shipped\"}', '2026-05-04 19:05:40'),
(26, 1, 'Menambahkan brand', '{\"name\":\"yahuuu test\",\"description\":\"yahu test dooang\"}', '2026-05-04 19:37:03'),
(27, 1, 'Mengupdate brand', '{\"id\":9,\"name\":\"yahuuu test\",\"description\":\"yahu test dooang\"}', '2026-05-04 19:44:17'),
(28, 1, 'Mengupdate brand', '{\"id\":9,\"name\":\"yahuuu test\",\"description\":\"yahu test dooang\"}', '2026-05-04 19:44:45'),
(29, 1, 'Mengupdate brand', '{\"id\":9,\"name\":\"yahuuu test\",\"description\":\"yahu test dooang\"}', '2026-05-04 19:46:02'),
(30, 1, 'Menambahkan brand', '{\"name\":\"jihad\",\"description\":\"satu\"}', '2026-05-11 01:16:07'),
(31, 1, 'Update status pesanan', '{\"order_id\":26,\"status\":\"new\"}', '2026-05-11 01:19:05'),
(32, 1, 'Update status pesanan', '{\"order_id\":26,\"status\":\"completed\"}', '2026-05-11 01:19:10'),
(33, 1, 'Update status pesanan', '{\"order_id\":26,\"status\":\"shipped\"}', '2026-05-11 01:19:23'),
(34, 1, 'Export laporan bulanan', '{\"total_bulan\":1}', '2026-05-18 13:13:46'),
(35, 1, 'Menambahkan kategori', '{\"name\":\"scakjk\"}', '2026-05-18 14:35:31'),
(36, 1, 'Menghapus kategori', '{\"id\":5}', '2026-05-18 14:35:34'),
(37, 1, 'Upload gallery product', '{\"product_id\":1,\"count\":1}', '2026-05-19 00:06:56'),
(38, 1, 'Set primary gallery image', '{\"product_id\":1,\"image_id\":4}', '2026-05-19 00:06:59'),
(39, 1, 'Delete product', '{\"product_id\":10}', '2026-06-02 01:29:36'),
(40, 1, 'Verifikasi pembayaran', '{\"payment_id\":4,\"action\":\"verify\"}', '2026-06-02 01:32:18'),
(41, 1, 'Update status pesanan', '{\"order_id\":28,\"status\":\"shipped\"}', '2026-06-02 01:32:32'),
(42, 1, 'Delete product', '{\"product_id\":9}', '2026-06-02 01:36:27'),
(43, 1, 'Upload gallery product', '{\"product_id\":7,\"count\":1}', '2026-06-02 01:36:39'),
(44, 1, 'Set primary gallery image', '{\"product_id\":7,\"image_id\":5}', '2026-06-02 01:36:43'),
(45, 1, 'Set primary gallery image', '{\"product_id\":7,\"image_id\":5}', '2026-06-02 01:37:08'),
(46, 1, 'Set primary gallery image', '{\"product_id\":7,\"image_id\":5}', '2026-06-02 01:37:15'),
(47, 1, 'Mengupdate brand', '{\"id\":8,\"name\":\"Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++\",\"description\":\"Biore UV Aqua Rich Watery Essence SPF 50+ PA++++ adalah tabir surya dengan tekstur berbasis air yang sangat ringan dan memberikan sensasi dingin saat menyentuh kulit. Produk ini menggunakan teknologi Micro Defense untuk melindungi kulit hingga ke celah terkecil tanpa meninggalkan rasa lengket atau lapisan putih (white cast). Selain melindungi dari sinar UV, formulanya yang diperkaya dengan Hyaluronic Acid menjaga kulit tetap lembap dan terhidrasi, sehingga sangat nyaman digunakan sebagai dasar riasan sehari-hari.\"}', '2026-06-02 01:37:35'),
(48, 1, 'Mengupdate brand', '{\"id\":7,\"name\":\"hadalabo\",\"description\":\"Hada Labo Gokujyun Ultimate Moisturizing (seri garis oranye\\/putih) adalah rangkaian skincare Jepang yang berfokus pada hidrasi intensif. Mengandung beberapa jenis Hyaluronic Acid untuk melembapkan, menghaluskan, dan menjaga elastisitas kulit tanpa alkohol, pewangi, atau pewarna. Teksturnya ringan, cepat meresap, dan aman untuk kulit sensitif.\"}', '2026-06-02 01:39:44'),
(49, 1, 'Mengupdate brand', '{\"id\":6,\"name\":\"Overnight Renewal Cream\",\"description\":\"Night cream lembut untuk pemakaian malam hari.\"}', '2026-06-02 01:40:03'),
(50, 1, 'Mengupdate brand', '{\"id\":5,\"name\":\"Barrier Repair Serum\",\"description\":\"Serum untuk membantu memperkuat skin barrier.\"}', '2026-06-02 01:41:26'),
(51, 1, 'Mengupdate brand', '{\"id\":4,\"name\":\"Daily UV Shield SPF 50\",\"description\":\"Sunscreen nyaman untuk perlindungan harian dari sinar UV.\"}', '2026-06-02 01:41:47'),
(52, 1, 'Mengupdate brand', '{\"id\":3,\"name\":\"Brightening Day Cream\",\"description\":\"Krim siang dengan niacinamide untuk membantu kulit tampak cerah.\"}', '2026-06-02 01:42:14'),
(53, 1, 'Verifikasi pembayaran', '{\"payment_id\":4,\"action\":\"verify\"}', '2026-06-02 01:46:55'),
(54, 1, 'Export laporan penjualan per order', '{\"total_penjualan\":3}', '2026-06-02 01:49:43'),
(55, 1, 'Mengupdate brand', '{\"id\":2,\"name\":\"Gentle Facial Cleanser\",\"description\":\"Cleanser lembut untuk mengangkat kotoran tanpa membuat kulit kering.\"}', '2026-06-02 01:58:20'),
(56, 1, 'Mengupdate brand', '{\"id\":1,\"name\":\"Hydrating Glow Serum\",\"description\":\"Serum ringan dengan hyaluronic acid untuk menjaga kelembapan kulit.\"}', '2026-06-02 01:58:52'),
(57, 1, 'Verifikasi pembayaran', '{\"payment_id\":5,\"action\":\"verify\"}', '2026-06-02 02:05:13'),
(58, 1, 'Update status pesanan', '{\"order_id\":30,\"status\":\"shipped\"}', '2026-06-02 02:05:48'),
(59, 1, 'Tolak pembayaran', '{\"payment_id\":6,\"action\":\"reject\"}', '2026-06-11 16:05:03'),
(60, 1, 'Verifikasi pembayaran', '{\"payment_id\":6,\"action\":\"verify\"}', '2026-06-11 16:05:05'),
(61, 1, 'Verifikasi pembayaran', '{\"payment_id\":6,\"action\":\"verify\"}', '2026-06-11 16:05:07'),
(62, 1, 'Update status, resi, kurir & ongkir pesanan', '{\"order_id\":32,\"status\":\"in_transit\",\"tracking_number\":\"\",\"courier\":\"\",\"shipping_cost\":25000}', '2026-06-12 06:46:05'),
(63, 1, 'Menambahkan ongkir', '{\"label\":\"1\",\"city\":\"Surabaya\",\"cost\":20000}', '2026-06-12 06:49:24'),
(64, 1, 'Mengupdate ongkir', '{\"id\":1,\"label\":\"3\",\"city\":\"bangkalan\",\"cost\":25000}', '2026-06-12 06:49:39'),
(65, 1, 'Verifikasi pembayaran', '{\"payment_id\":7,\"action\":\"verify\"}', '2026-06-12 06:51:21'),
(66, 1, 'Update status, resi, kurir & ongkir pesanan', '{\"order_id\":33,\"status\":\"shipped\",\"tracking_number\":\"JNE127132718\",\"courier\":\"JNE\",\"shipping_cost\":20000}', '2026-06-12 06:51:56'),
(67, 1, 'Update status, resi, kurir & ongkir pesanan', '{\"order_id\":33,\"status\":\"in_transit\",\"tracking_number\":\"JNE127132718\",\"courier\":\"JNE\",\"shipping_cost\":20000}', '2026-06-12 06:52:22'),
(68, 1, 'Update status, resi, kurir & ongkir pesanan', '{\"order_id\":33,\"status\":\"completed\",\"tracking_number\":\"JNE127132718\",\"courier\":\"JNE\",\"shipping_cost\":20000}', '2026-06-12 06:52:41');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Glowé', 'Brand utama Glowe', '2026-04-05 05:26:38'),
(2, 'SkinMuse', 'Partner brand skincare', '2026-04-05 05:26:38'),
(3, 'Pureveil', 'Brand perawatan kulit premium', '2026-04-05 05:26:38'),
(5, 'Scarlett Whitening', 'Reveal Your Beautyy', '2026-04-07 08:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-04-06 08:05:11', '2026-04-06 08:05:11'),
(2, 3, '2026-04-07 02:19:40', '2026-04-07 02:19:40'),
(3, 4, '2026-04-07 02:42:27', '2026-04-07 02:42:27'),
(4, 1, '2026-04-20 15:26:18', '2026-04-20 15:26:18'),
(5, 5, '2026-04-20 15:39:45', '2026-04-20 15:39:45'),
(6, 6, '2026-05-04 19:04:15', '2026-05-04 19:04:15'),
(7, 7, '2026-05-11 01:16:37', '2026-05-11 01:16:37'),
(8, 8, '2026-06-02 01:01:01', '2026-06-02 01:01:01'),
(9, 10, '2026-06-11 15:05:50', '2026-06-11 15:05:50'),
(10, 11, '2026-06-12 06:48:07', '2026-06-12 06:48:07');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `price_at_added` decimal(12,2) NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `qty`, `price_at_added`, `line_total`, `created_at`, `updated_at`) VALUES
(13, 2, 5, 1, 205000.00, 205000.00, '2026-04-07 02:40:13', '2026-04-07 02:40:13');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Serum', '2026-04-05 05:26:39'),
(2, 'Cleanser', '2026-04-05 05:26:39'),
(3, 'Moisturizer', '2026-04-05 05:26:39'),
(4, 'Sunscreen', '2026-04-05 05:26:39');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(12,2) NOT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `order_id`, `invoice_number`, `invoice_date`, `total_amount`, `pdf_file`, `created_at`) VALUES
(1, 21, 'INV/20260420/000021', '2026-04-20 22:31:57', 85000.00, NULL, '2026-04-20 15:31:57'),
(2, 31, 'INV/20260611/000031', '2026-06-11 22:14:36', 145000.00, NULL, '2026-06-11 15:14:36');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','replied','closed') NOT NULL DEFAULT 'open',
  `replied_by` int DEFAULT NULL,
  `reply_message` text,
  `replied_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `address_id` int NOT NULL,
  `recipient_name_snapshot` varchar(100) NOT NULL,
  `phone_snapshot` varchar(30) NOT NULL,
  `address_line_snapshot` text NOT NULL,
  `city_snapshot` varchar(100) NOT NULL,
  `province_snapshot` varchar(100) NOT NULL,
  `postal_code_snapshot` varchar(20) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `shipping_cost` decimal(12,2) NOT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `received_confirmed_at` datetime DEFAULT NULL,
  `total` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `courier` varchar(50) DEFAULT NULL,
  `shipping_service` varchar(100) DEFAULT NULL,
  `payment_status` enum('pending','waiting_verification','paid','rejected') NOT NULL DEFAULT 'pending',
  `order_status` enum('new','processed','shipped','completed','cancelled','in_transit') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'new',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `address_id`, `recipient_name_snapshot`, `phone_snapshot`, `address_line_snapshot`, `city_snapshot`, `province_snapshot`, `postal_code_snapshot`, `subtotal`, `shipping_cost`, `tracking_number`, `received_confirmed_at`, `total`, `payment_method`, `courier`, `shipping_service`, `payment_status`, `order_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'INV-20260406150521-544', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 205000.00, 25000.00, NULL, NULL, 230000.00, 'bank_transfer', NULL, NULL, 'pending', 'new', '', '2026-04-06 08:05:21', '2026-04-06 08:05:21'),
(2, 'INV-20260406154738-282', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-06 08:47:38', '2026-04-06 08:47:38'),
(3, 'INV-20260406154910-971', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-06 08:49:10', '2026-04-06 08:49:10'),
(4, 'INV-20260406154942-904', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-06 08:49:42', '2026-04-06 08:49:42'),
(5, 'INV-20260406155502-497', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-06 08:55:02', '2026-04-06 08:55:02'),
(6, 'INV-20260406155817-807', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-06 08:58:17', '2026-04-06 08:58:17'),
(7, 'INV-20260406160050-597', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 179000.00, 25000.00, NULL, NULL, 204000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-06 09:00:50', '2026-04-06 09:00:50'),
(8, 'INV-20260407085931-487', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 205000.00, 25000.00, NULL, NULL, 230000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-07 01:59:31', '2026-04-07 01:59:31'),
(9, 'INV-20260407090057-839', 2, 1, 'zulian', '081234567', 'telang', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 179000.00, 25000.00, NULL, NULL, 204000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:00:57', '2026-04-07 02:00:57'),
(10, 'INV-20260407091950-431', 3, 2, 'kipli anjay', '0812345678', 'kemana mana hatiku senang', 'madura', 'JAWA TIMUR', '47899', 189000.00, 25000.00, NULL, NULL, 214000.00, '', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:19:50', '2026-04-07 02:19:50'),
(11, 'INV-20260407093619-601', 3, 2, 'kipli anjay', '0812345678', 'kemana mana hatiku senang', 'madura', 'JAWA TIMUR', '47899', 205000.00, 25000.00, NULL, NULL, 230000.00, 'ewallet', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:36:19', '2026-04-07 02:36:19'),
(12, 'INV-20260407093801-702', 3, 2, 'kipli anjay', '0812345678', 'kemana mana hatiku senang', 'madura', 'JAWA TIMUR', '47899', 205000.00, 25000.00, NULL, NULL, 230000.00, 'qris', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:38:01', '2026-04-07 02:38:01'),
(13, 'INV-20260407094233-279', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 179000.00, 25000.00, NULL, NULL, 204000.00, 'cod', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:42:33', '2026-04-07 02:42:33'),
(14, 'INV-20260407094309-548', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 179000.00, 25000.00, NULL, NULL, 204000.00, 'transfer', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:43:09', '2026-04-07 02:43:09'),
(15, 'INV-20260407094903-155', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 205000.00, 25000.00, NULL, NULL, 230000.00, 'ewallet', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:49:03', '2026-04-07 02:49:03'),
(16, 'INV-20260407095044-646', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 205000.00, 25000.00, NULL, NULL, 230000.00, 'qris', NULL, NULL, 'pending', 'new', '', '2026-04-07 02:50:44', '2026-04-07 02:50:44'),
(17, 'INV-20260407100412-250', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, 'transfer', NULL, NULL, 'pending', 'new', '', '2026-04-07 03:04:12', '2026-04-07 03:04:12'),
(18, 'INV-20260407100440-163', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, 'ewallet', NULL, NULL, 'pending', 'new', '', '2026-04-07 03:04:40', '2026-04-07 03:04:40'),
(19, 'INV-20260407100458-864', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, 'cod', NULL, NULL, 'pending', 'new', '', '2026-04-07 03:04:58', '2026-04-07 03:04:58'),
(20, 'INV-20260407101303-976', 4, 3, 'assep', '083456789', 'jalan bahagia sama dia', 'madura', 'JAWA TIMUR', '69152', 239000.00, 25000.00, NULL, NULL, 264000.00, 'qris', NULL, NULL, 'pending', 'cancelled', '', '2026-04-07 03:13:03', '2026-04-07 08:03:32'),
(21, 'INV-20260420222719-206', 1, 4, 'Zulfikar Fauzi', '0812345678', 'telang', 'bangkalan', 'jawa timur', '1234', 60000.00, 25000.00, NULL, NULL, 85000.00, 'qris', NULL, NULL, 'waiting_verification', 'shipped', '', '2026-04-20 15:27:19', '2026-04-20 15:35:27'),
(22, 'INV-20260420223951-822', 5, 5, 'Zulfikar Fauzi', '0812345678', 'telang', 'bangkalan', 'jawa timur', '1234', 120000.00, 25000.00, NULL, NULL, 145000.00, 'transfer', NULL, NULL, 'pending', 'new', '', '2026-04-20 15:39:51', '2026-04-20 15:39:51'),
(23, 'INV-20260420224236-958', 5, 5, 'Zulfikar Fauzi', '0812345678', 'telang', 'bangkalan', 'jawa timur', '1234', 120000.00, 25000.00, NULL, NULL, 145000.00, 'qris', NULL, NULL, 'paid', 'completed', '', '2026-04-20 15:42:36', '2026-04-20 17:08:06'),
(24, 'INV-20260420233046-562', 5, 5, 'Zulfikar Fauzi', '0812345678', 'telang', 'bangkalan', 'jawa timur', '1234', 239000.00, 25000.00, NULL, NULL, 264000.00, 'qris', NULL, NULL, 'paid', 'completed', '', '2026-04-20 16:30:46', '2026-04-20 16:34:47'),
(25, 'INV-20260505020436-185', 6, 6, '24-054 ZULFIKAR FAUZI', '085735514152', '-Campor', 'KABUPATEN BANGKALAN', 'JAWA TIMUR', '69152', 60000.00, 25000.00, NULL, NULL, 85000.00, 'bank_transfer', NULL, NULL, 'pending', 'completed', '', '2026-05-04 19:04:36', '2026-05-04 19:08:30'),
(26, 'INV-20260511081712-530', 7, 7, 'jihad', '0987654321', 'sj', 'sj', 'sj', '1', 1000000.00, 25000.00, NULL, NULL, 1025000.00, 'bank_transfer', NULL, NULL, 'pending', 'completed', '', '2026-05-11 01:17:12', '2026-05-11 01:19:37'),
(27, 'INV-20260602080253-705', 8, 8, 'k', '0987654321', 'asdfghj', 'asdfgh', 'asdfg', '1234', 360000.00, 25000.00, NULL, NULL, 385000.00, 'qris', NULL, NULL, 'pending', 'new', '', '2026-06-02 01:02:53', '2026-06-02 01:02:53'),
(28, 'INV-20260602083123-544', 7, 7, 'jihad', '0987654321', 'sj', 'sj', 'sj', '1', 60000.00, 25000.00, NULL, NULL, 85000.00, 'bank_transfer', NULL, NULL, 'paid', 'processed', '', '2026-06-02 01:31:23', '2026-06-02 01:46:55'),
(29, 'INV-20260602084619-631', 7, 7, 'jihad', '0987654321', 'sj', 'sj', 'sj', '1', 179000.00, 25000.00, NULL, NULL, 204000.00, 'qris', NULL, NULL, 'pending', 'new', '', '2026-06-02 01:46:19', '2026-06-02 01:46:19'),
(30, 'INV-20260602090441-835', 7, 7, 'jihad', '0987654321', 'sj', 'sj', 'sj', '1', 120000.00, 25000.00, NULL, NULL, 145000.00, 'qris', NULL, NULL, 'paid', 'shipped', '', '2026-06-02 02:04:41', '2026-06-02 02:05:48'),
(31, 'INV-20260611221014-977', 10, 9, 'Rendi', '08974879695', 'Jl. Tambak Wedi', 'Surabaya', 'Jatim', '0987', 120000.00, 25000.00, NULL, NULL, 145000.00, 'qris', NULL, NULL, 'paid', 'processed', '', '2026-06-11 15:10:14', '2026-06-11 16:05:05'),
(32, 'INV-20260611221256-750', 10, 9, 'Rendi', '08974879695', 'Jl. Tambak Wedi', 'Surabaya', 'Jatim', '0987', 239000.00, 25000.00, '', NULL, 264000.00, 'bank_transfer', NULL, NULL, 'pending', 'in_transit', '', '2026-06-11 15:12:56', '2026-06-12 06:46:05'),
(33, 'INV-20260612135036-441', 11, 10, 'Renday', '56127631827387', 'Surabaya', 'Surabaya', 'Jawa Timur', '62197', 60000.00, 20000.00, 'JNE127132718', '2026-06-12 13:52:31', 80000.00, 'qris', 'JNE', NULL, 'paid', 'completed', 'aku jawa', '2026-06-12 06:50:36', '2026-06-12 06:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `product_price` decimal(12,2) NOT NULL,
  `qty` int NOT NULL,
  `line_total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `qty`, `line_total`) VALUES
(1, 1, 5, 'Barrier Repair Serum', 205000.00, 1, 205000.00),
(2, 2, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(3, 3, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(4, 4, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(5, 5, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(6, 6, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(7, 7, 4, 'Daily UV Shield SPF 50', 179000.00, 1, 179000.00),
(8, 8, 5, 'Barrier Repair Serum', 205000.00, 1, 205000.00),
(9, 9, 4, 'Daily UV Shield SPF 50', 179000.00, 1, 179000.00),
(10, 10, 1, 'Hydrating Glow Serum', 189000.00, 1, 189000.00),
(11, 11, 5, 'Barrier Repair Serum', 205000.00, 1, 205000.00),
(12, 12, 5, 'Barrier Repair Serum', 205000.00, 1, 205000.00),
(13, 13, 4, 'Daily UV Shield SPF 50', 179000.00, 1, 179000.00),
(14, 14, 4, 'Daily UV Shield SPF 50', 179000.00, 1, 179000.00),
(15, 15, 5, 'Barrier Repair Serum', 205000.00, 1, 205000.00),
(16, 16, 5, 'Barrier Repair Serum', 205000.00, 1, 205000.00),
(17, 17, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(18, 18, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(19, 19, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(20, 20, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(21, 21, 7, 'hadalabo', 60000.00, 1, 60000.00),
(22, 22, 8, 'Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++', 120000.00, 1, 120000.00),
(23, 23, 8, 'Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++', 120000.00, 1, 120000.00),
(24, 24, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(25, 25, 7, 'hadalabo', 60000.00, 1, 60000.00),
(26, 26, 10, 'jihad', 1000000.00, 1, 1000000.00),
(27, 27, 8, 'Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++', 120000.00, 3, 360000.00),
(28, 28, 7, 'hadalabo', 60000.00, 1, 60000.00),
(29, 29, 4, 'Daily UV Shield SPF 50', 179000.00, 1, 179000.00),
(30, 30, 8, 'Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++', 120000.00, 1, 120000.00),
(31, 31, 8, 'Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++', 120000.00, 1, 120000.00),
(32, 32, 6, 'Overnight Renewal Cream', 239000.00, 1, 239000.00),
(33, 33, 7, 'hadalabo', 60000.00, 1, 60000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment_confirmations`
--

CREATE TABLE `payment_confirmations` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `transfer_amount` decimal(12,2) NOT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text,
  `verified_by` int DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_confirmations`
--

INSERT INTO `payment_confirmations` (`id`, `order_id`, `sender_name`, `bank_name`, `transfer_amount`, `proof_image`, `verification_status`, `admin_notes`, `verified_by`, `verified_at`, `created_at`) VALUES
(1, 21, 'fauzi', 'QRIS', 85000.00, 'img_69e646c19d0ea1.00511235.png', 'pending', NULL, NULL, NULL, '2026-04-20 15:31:13'),
(2, 23, 'fauzi', 'QRIS', 145000.00, 'img_69e649809a2f55.67376435.png', 'verified', '', 1, '2026-04-20 22:43:54', '2026-04-20 15:42:56'),
(3, 24, 'fauzi', 'QRIS', 264000.00, 'img_69e655003b9757.17945661.png', 'verified', '', 1, '2026-04-20 23:32:27', '2026-04-20 16:32:00'),
(4, 28, 'bibil', 'sdfghjk', 1000.00, 'img_6a1e3297c3bb66.57798444.png', 'verified', '', 1, '2026-06-02 08:46:54', '2026-06-02 01:32:07'),
(5, 30, 'bibil', 'QRIS', 9998.00, 'img_6a1e3a4bcb97c1.99662798.jpeg', 'verified', '', 1, '2026-06-02 09:05:13', '2026-06-02 02:04:59'),
(6, 31, 'Rendi', 'QRIS', 145.00, 'img_6a2ad038486ce5.14587751.jpg', 'verified', '', 1, '2026-06-11 23:05:07', '2026-06-11 15:11:52'),
(7, 33, 'Rendi', 'QRIS', 80000.00, 'img_6a2bac53828a14.37091708.jpg', 'verified', 'mbg', 1, '2026-06-12 13:51:21', '2026-06-12 06:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `brand_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `expired_date` date DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand_id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `expired_date`, `sku`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Hydrating Glow Serum', 'hydrating-glow-serum', 'Serum ringan dengan hyaluronic acid untuk menjaga kelembapan kulit.', 189000.00, 49, NULL, 'SERUM-001', 'img_6a1e38dce151e2.00761650.jpeg', 1, '2026-04-05 05:26:39', '2026-06-02 01:58:52'),
(2, 1, 2, 'Gentle Facial Cleanser', 'gentle-facial-cleanser', 'Cleanser lembut untuk mengangkat kotoran tanpa membuat kulit kering.', 149000.00, 60, NULL, 'CLEANSER-001', 'img_6a1e38bca41a94.83663930.jpeg', 1, '2026-04-05 05:26:39', '2026-06-02 01:58:20'),
(3, 2, 3, 'Brightening Day Cream', 'brightening-day-cream', 'Krim siang dengan niacinamide untuk membantu kulit tampak cerah.', 219000.00, 35, NULL, 'MOIST-001', 'img_6a1e34f60138d8.73099811.jpeg', 1, '2026-04-05 05:26:39', '2026-06-02 01:42:14'),
(4, 3, 4, 'Daily UV Shield SPF 50', 'daily-uv-shield-spf-50', 'Sunscreen nyaman untuk perlindungan harian dari sinar UV.', 179000.00, 40, NULL, 'SUN-001', 'img_6a1e34db67a585.59768689.jpeg', 1, '2026-04-05 05:26:39', '2026-06-02 01:46:19'),
(5, 2, 1, 'Barrier Repair Serum', 'barrier-repair-serum', 'Serum untuk membantu memperkuat skin barrier.', 205000.00, 19, NULL, 'SERUM-002', 'img_6a1e34c6e3f162.84380209.jpeg', 1, '2026-04-05 05:26:39', '2026-06-02 01:41:26'),
(6, 3, 3, 'Overnight Renewal Cream', 'overnight-renewal-cream', 'Night cream lembut untuk pemakaian malam hari.', 239000.00, 9, NULL, 'MOIST-002', 'img_6a1e34737e0fd6.27525474.jpeg', 1, '2026-04-05 05:26:39', '2026-06-11 15:12:56'),
(7, 1, 3, 'hadalabo', 'hadalabo-1775540460', 'Hada Labo Gokujyun Ultimate Moisturizing (seri garis oranye/putih) adalah rangkaian skincare Jepang yang berfokus pada hidrasi intensif. Mengandung beberapa jenis Hyaluronic Acid untuk melembapkan, menghaluskan, dan menjaga elastisitas kulit tanpa alkohol, pewangi, atau pewarna. Teksturnya ringan, cepat meresap, dan aman untuk kulit sensitif.', 60000.00, 6, NULL, 'MOIST-003', 'img_6a1e346040eca2.64578252.jpeg', 1, '2026-04-07 05:41:00', '2026-06-12 06:50:36'),
(8, 1, 4, 'Azarine Hydrasoothe Sunscreen Gel SPF 45 PA++++', 'azarine-hydrasoothe-sunscreen-gel-spf-45-pa-1775546859', 'Biore UV Aqua Rich Watery Essence SPF 50+ PA++++ adalah tabir surya dengan tekstur berbasis air yang sangat ringan dan memberikan sensasi dingin saat menyentuh kulit. Produk ini menggunakan teknologi Micro Defense untuk melindungi kulit hingga ke celah terkecil tanpa meninggalkan rasa lengket atau lapisan putih (white cast). Selain melindungi dari sinar UV, formulanya yang diperkaya dengan Hyaluronic Acid menjaga kulit tetap lembap dan terhidrasi, sehingga sangat nyaman digunakan sebagai dasar riasan sehari-hari.', 120000.00, 26, NULL, 'SUNSCREEN-001', 'img_6a1e33df654861.39018212.jpeg', 1, '2026-04-07 07:27:39', '2026-06-11 15:10:14');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `is_primary`, `created_at`) VALUES
(1, 8, 'img_69d4b7c59be035.01897333.jpg', 0, '2026-04-07 07:52:37'),
(2, 9, 'img_69f8f55ef3d8e2.00029821.jpg', 1, '2026-05-04 19:37:03'),
(3, 10, 'img_6a012dd7197bf4.07620674.png', 1, '2026-05-11 01:16:07'),
(4, 1, 'img_6a0ba9a06de491.75948866.jpeg', 1, '2026-05-19 00:06:56'),
(5, 7, 'img_6a1e33a7111139.27977298.jpeg', 1, '2026-06-02 01:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'bank_name', 'BCA'),
(2, 'bank_account_name', 'PT Glowe Skincare'),
(3, 'bank_account_number', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_rates`
--

CREATE TABLE `shipping_rates` (
  `id` int NOT NULL,
  `label` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_rates`
--

INSERT INTO `shipping_rates` (`id`, `label`, `city`, `cost`, `is_active`, `created_at`) VALUES
(1, '3', 'bangkalan', 25000.00, 1, '2026-04-06 07:16:50'),
(2, '2', 'sampang', 25000.00, 1, '2026-04-07 06:48:56'),
(3, '1', 'Surabaya', 20000.00, 1, '2026-06-12 06:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `movement_type` enum('in','out','adjustment') NOT NULL,
  `qty` int NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `movement_type`, `qty`, `note`, `created_by`, `created_at`) VALUES
(1, 5, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260406150521-544', 2, '2026-04-06 08:05:21'),
(2, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260406154738-282', 2, '2026-04-06 08:47:38'),
(3, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260406154910-971', 2, '2026-04-06 08:49:10'),
(4, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260406154942-904', 2, '2026-04-06 08:49:42'),
(5, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260406155502-497', 2, '2026-04-06 08:55:02'),
(6, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260406155817-807', 2, '2026-04-06 08:58:17'),
(7, 4, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260406160050-597', 2, '2026-04-06 09:00:50'),
(8, 5, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407085931-487', 2, '2026-04-07 01:59:31'),
(9, 4, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407090057-839', 2, '2026-04-07 02:00:57'),
(10, 1, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407091950-431', 3, '2026-04-07 02:19:50'),
(11, 5, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407093619-601', 3, '2026-04-07 02:36:19'),
(12, 5, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407093801-702', 3, '2026-04-07 02:38:01'),
(13, 4, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407094233-279', 4, '2026-04-07 02:42:33'),
(14, 4, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407094309-548', 4, '2026-04-07 02:43:09'),
(15, 5, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407094903-155', 4, '2026-04-07 02:49:03'),
(16, 5, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407095044-646', 4, '2026-04-07 02:50:44'),
(17, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407100412-250', 4, '2026-04-07 03:04:12'),
(18, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407100440-163', 4, '2026-04-07 03:04:40'),
(19, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407100458-864', 4, '2026-04-07 03:04:58'),
(20, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260407101303-976', 4, '2026-04-07 03:13:03'),
(21, 8, 'in', 18, '', NULL, '2026-04-07 07:51:32'),
(22, 7, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260420222719-206', 1, '2026-04-20 15:27:19'),
(23, 8, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260420223951-822', 5, '2026-04-20 15:39:51'),
(24, 8, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260420224236-958', 5, '2026-04-20 15:42:36'),
(25, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260420233046-562', 5, '2026-04-20 16:30:46'),
(26, 7, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260505020436-185', 6, '2026-05-04 19:04:36'),
(27, 10, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260511081712-530', 7, '2026-05-11 01:17:12'),
(28, 8, 'out', 3, 'Pengurangan stok otomatis untuk order INV-20260602080253-705', 8, '2026-06-02 01:02:53'),
(29, 7, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260602083123-544', 7, '2026-06-02 01:31:23'),
(30, 4, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260602084619-631', 7, '2026-06-02 01:46:19'),
(31, 8, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260602090441-835', 7, '2026-06-02 02:04:41'),
(32, 8, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260611221014-977', 10, '2026-06-11 15:10:14'),
(33, 6, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260611221256-750', 10, '2026-06-11 15:12:56'),
(34, 7, 'out', 1, 'Pengurangan stok otomatis untuk order INV-20260612135036-441', 11, '2026-06-12 06:50:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`, `reset_token`, `reset_expires_at`) VALUES
(1, 'Admin Glowe', 'admin@glowe.test', '082337236078', '$2y$10$xo7EpdcguJ/buiAeYJEj2.SW8tiIA9gyFXCeWAUoaOBsGLETLbbNm', 'admin', '2026-04-05 05:26:39', '2026-04-05 06:26:50', NULL, NULL),
(2, 'zulian', 'zulian@gmail.com', '081234567', '$2y$10$7jMeKOSNxYXhK3TX5G2YOuU3FCvgUu6UXIawQohWHgaHTxmDDGh8.', 'user', '2026-04-06 07:14:05', '2026-04-06 07:14:05', NULL, NULL),
(3, 'kipli', 'kipli@gmail.com', '0123456789', '$2y$10$AxpQe3XjtPENd8TlMWHPaeFktolNT86Frmjr9l.Gy.PEe7Kr2qF/W', 'user', '2026-04-07 02:18:14', '2026-04-07 02:18:14', NULL, NULL),
(4, 'assep', 'assep@gmail.com', '08345678', '$2y$10$EK0k54fWgxzN3TZSz7AFouBdNTh.jwDpqk7ciT9rEAtxWa5l9ANx6', 'user', '2026-04-07 02:41:33', '2026-04-07 02:41:33', NULL, NULL),
(5, 'Zulfikar Fauzi', 'kasazta@gmail.com', '0812345678', '$2y$10$AOrZ27gT1AupTianCXVfDu3wN9nWzQ85L9rMdAcvLwTaqC/AaDM.q', 'user', '2026-04-20 15:36:38', '2026-04-20 15:36:38', NULL, NULL),
(6, '24-054 ZULFIKAR FAUZI', 'zulvkr@gmail.com', '085735514152', '$2y$10$Wy.aGV6YD/Er1dRwQB/X4eU9viWVTng0WrPT7us885rGEvDqZO7J2', 'user', '2026-05-04 19:03:42', '2026-05-04 19:03:42', NULL, NULL),
(7, 'jihad', 'jihad@gmail.com', '0812345678', '$2y$10$oQ23xbk2PDT/wXD.KKxLy.SO.J.UnAH1Ijg7y3cVjo6pZYOPLxz7C', 'user', '2026-05-11 01:11:21', '2026-06-02 00:55:22', '878c5121f33ac38d37ad692d1208d77ab1448e2693938ee2147640ba46a64343', '2026-06-02 08:25:22'),
(8, 'jihad', 'achmadjihadfisabilillah123@gmail.com', '085172280957', '$2y$10$nD/tIhf5TQRwbqsCdFz8JOpMlKFM7M8FYucNvZCBlfQkk0jXWscZa', 'user', '2026-05-19 00:43:54', '2026-06-02 01:00:33', NULL, NULL),
(9, 'jih', 'jih@gmail.com', '894456878977', '$2y$10$NtuuFfG.rU9M7cdqX/GV9OA7Wt8kpiG61RIYvccRDYAB64EjuOjr2', 'user', '2026-06-02 00:24:31', '2026-06-02 00:24:31', NULL, NULL),
(10, 'Rendi', 'rendiyf@gmail.com', '08974879695', '$2y$10$h9hGrNk7sEQ0d8MIkVe8vucnQySk2TIz80/.BOzRnn8.79DP2DOya', 'user', '2026-06-11 15:04:47', '2026-06-11 15:04:47', NULL, NULL),
(11, 'coba dulu', 'coba@gmail.com', '456789219872', '$2y$10$LD7wH75zwMbblCoxi.rV/.gbsZMTFvneX3.5nJl7rdQZIQvdTcjny', 'user', '2026-06-12 06:46:48', '2026-06-12 06:47:16', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cart_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cart_product` (`cart_id`,`product_id`),
  ADD KEY `idx_cart_items_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_invoice_order` (`order_id`),
  ADD UNIQUE KEY `uk_invoice_number` (`invoice_number`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_messages_user` (`user_id`),
  ADD KEY `idx_messages_replied_by` (`replied_by`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment_confirmations`
--
ALTER TABLE `payment_confirmations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_payment_verified_by` (`verified_by`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_stock_movements_created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `payment_confirmations`
--
ALTER TABLE `payment_confirmations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_admin` FOREIGN KEY (`replied_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
