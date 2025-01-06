-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 12:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assetpatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'view', 'category', 'Viewed categories list', NULL, '{\"total_categories\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 18:59:25', '2024-11-28 18:59:25'),
(2, 1, 'view', 'maintenance', 'Viewed maintenance logs list', NULL, '{\"filter_status\":\"active\",\"total_logs\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 18:59:36', '2024-11-28 18:59:36'),
(3, 1, 'view', 'asset_request', 'Viewed asset requests list', NULL, '{\"total_requests\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:01:32', '2024-11-28 19:01:32'),
(4, 1, 'view', 'barang', 'User viewed barang list', NULL, '{\"total_items\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:03:02', '2024-11-28 19:03:02'),
(5, 1, 'view', 'room', 'Viewed rooms list', NULL, '{\"total_rooms\":4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:03:07', '2024-11-28 19:03:07'),
(6, 1, 'view', 'category', 'Viewed categories list', NULL, '{\"total_categories\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:03:12', '2024-11-28 19:03:12'),
(7, 1, 'create', 'category', 'Created new category: xxx', NULL, '{\"name\":\"xxx\",\"description\":null,\"updated_at\":\"2024-11-29T02:07:24.000000Z\",\"created_at\":\"2024-11-29T02:07:24.000000Z\",\"id\":4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:07:25', '2024-11-28 19:07:25'),
(8, 1, 'update_status', 'maintenance', 'Updated maintenance status to completed for: TV 8K UHD', '{\"id\":10,\"barang_id\":1,\"maintenance_date\":\"2024-11-30\",\"description\":\"fatal\",\"cost\":\"250000.00\",\"performed_by\":\"TEST\",\"status\":\"pending\",\"created_at\":\"2024-11-29T01:47:14.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"due_date\":null}', '{\"id\":10,\"barang_id\":1,\"maintenance_date\":\"2024-11-30\",\"description\":\"fatal\",\"cost\":\"250000.00\",\"performed_by\":\"TEST\",\"status\":\"completed\",\"created_at\":\"2024-11-29T01:47:14.000000Z\",\"updated_at\":\"2024-11-29T02:10:41.000000Z\",\"due_date\":null,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:10:41', '2024-11-28 19:10:41'),
(9, 1, 'view', 'barang', 'Accessed barang creation form', NULL, '{\"available_categories\":4,\"available_rooms\":4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:11:02', '2024-11-28 19:11:02'),
(10, 1, 'create', 'barang', 'Added new barang: na wl', NULL, '{\"name\":\"na wl\",\"description\":\"mahal\",\"room_id\":\"3\",\"category_id\":\"2\",\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000\",\"status\":\"siap_dipakai\",\"asset_tag\":\"TMS-CS-00005\",\"updated_at\":\"2024-11-29T02:11:17.000000Z\",\"created_at\":\"2024-11-29T02:11:17.000000Z\",\"id\":4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:11:17', '2024-11-28 19:11:17'),
(11, 1, 'create', 'asset_request', 'Created new asset request: Proyektor 8K UHD SONY', NULL, '{\"name\":\"Proyektor 8K UHD SONY\",\"category\":\"Furniture\",\"price\":\"20000000000\",\"description\":null,\"approver_email\":\"adityanathaniel43@gmail.com\",\"requester_email\":\"tms@gmail.com\",\"user_id\":1,\"status\":\"pending\",\"approval_token\":\"wAqOLUkwwLrHSlIbbXKEu3Jh3NrH8c1g\",\"updated_at\":\"2024-11-29T02:11:43.000000Z\",\"created_at\":\"2024-11-29T02:11:43.000000Z\",\"id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:11:43', '2024-11-28 19:11:43'),
(12, 1, 'create', 'room', 'Created new room: Bunker', NULL, '{\"name\":\"Bunker\",\"floor\":\"12\",\"building\":\"TMS\",\"capacity\":\"10\",\"responsible_person\":\"Saya\",\"updated_at\":\"2024-11-29T02:12:27.000000Z\",\"created_at\":\"2024-11-29T02:12:27.000000Z\",\"id\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:12:27', '2024-11-28 19:12:27'),
(13, 1, 'update', 'category', 'Updated category: xxx-proto1q', '{\"id\":4,\"name\":\"xxx-proto1\",\"description\":null,\"created_at\":\"2024-11-29T02:07:24.000000Z\",\"updated_at\":\"2024-11-29T02:13:11.000000Z\"}', '{\"id\":4,\"name\":\"xxx-proto1q\",\"description\":null,\"created_at\":\"2024-11-29T02:07:24.000000Z\",\"updated_at\":\"2024-11-29T02:14:34.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:14:34', '2024-11-28 19:14:34'),
(14, 1, 'view', 'barang', 'Accessed barang creation form', NULL, '{\"available_categories\":4,\"available_rooms\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:16:23', '2024-11-28 19:16:23'),
(15, 1, 'create', 'barang', 'Added new barang: na wlX', NULL, '{\"name\":\"na wlX\",\"description\":\"mahal\",\"room_id\":\"2\",\"category_id\":\"1\",\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000\",\"status\":\"siap_dipakai\",\"asset_tag\":\"TMS-RM-00006\",\"updated_at\":\"2024-11-29T02:16:36.000000Z\",\"created_at\":\"2024-11-29T02:16:36.000000Z\",\"id\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:16:36', '2024-11-28 19:16:36'),
(16, 1, 'view', 'barang', 'Accessed edit form for barang: Kipas angin pakai Mesin pesawat', NULL, '{\"id\":2,\"name\":\"Kipas angin pakai Mesin pesawat\",\"asset_tag\":\"TMS-RU-00003\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"5000000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:39:52.000000Z\",\"updated_at\":\"2024-11-29T01:42:25.000000Z\",\"status\":\"dalam_perbaikan\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:16:54', '2024-11-28 19:16:54'),
(17, 1, 'update', 'barang', 'Updated barang: Kipas angin pakai Mesin pesawat', '{\"id\":2,\"name\":\"Kipas angin pakai Mesin pesawat\",\"asset_tag\":\"TMS-RU-00003\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"5000000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:39:52.000000Z\",\"updated_at\":\"2024-11-29T01:42:25.000000Z\",\"status\":\"dalam_perbaikan\"}', '{\"id\":2,\"name\":\"Kipas angin pakai Mesin pesawat\",\"asset_tag\":\"TMS-RU-00003\",\"category_id\":\"1\",\"room_id\":\"1\",\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"50000\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:39:52.000000Z\",\"updated_at\":\"2024-11-29T02:17:03.000000Z\",\"status\":\"dalam_perbaikan\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:17:03', '2024-11-28 19:17:03'),
(18, 1, 'update', 'room', 'Updated room: name from \'Bunker\' to \'Bunker1\', capacity from \'10\' to \'10\', responsible person from \'Saya\' to \'Saya Sendiri\'', '{\"id\":5,\"name\":\"Bunker\",\"floor\":\"12\",\"building\":\"TMS\",\"capacity\":10,\"responsible_person\":\"Saya\",\"created_at\":\"2024-11-29T02:12:27.000000Z\",\"updated_at\":\"2024-11-29T02:12:27.000000Z\"}', '{\"id\":5,\"name\":\"Bunker1\",\"floor\":\"12\",\"building\":\"TMS\",\"capacity\":\"10\",\"responsible_person\":\"Saya Sendiri\",\"created_at\":\"2024-11-29T02:12:27.000000Z\",\"updated_at\":\"2024-11-29T02:20:55.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:20:55', '2024-11-28 19:20:55'),
(19, 1, 'view', 'barang', 'Accessed barang creation form', NULL, '{\"available_categories\":4,\"available_rooms\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:21:10', '2024-11-28 19:21:10'),
(20, 1, 'create', 'barang', 'Added new barang: na wl', NULL, '{\"name\":\"na wl\",\"description\":\"mahal\",\"room_id\":\"2\",\"category_id\":\"3\",\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000\",\"status\":\"siap_dipakai\",\"asset_tag\":\"TMS-RM-00007\",\"updated_at\":\"2024-11-29T02:21:19.000000Z\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"id\":6}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:21:19', '2024-11-28 19:21:19'),
(21, 1, 'view', 'barang', 'Accessed edit form for barang: na wl', NULL, '{\"id\":6,\"name\":\"na wl\",\"asset_tag\":\"TMS-RM-00007\",\"category_id\":3,\"room_id\":2,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"updated_at\":\"2024-11-29T02:21:19.000000Z\",\"status\":\"siap_dipakai\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:21:31', '2024-11-28 19:21:31'),
(22, 1, 'update', 'barang', 'Updated barang: name from \'na wl\' to \'nawel\'', '{\"id\":6,\"name\":\"na wl\",\"asset_tag\":\"TMS-RM-00007\",\"category_id\":3,\"room_id\":2,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"updated_at\":\"2024-11-29T02:21:19.000000Z\",\"status\":\"siap_dipakai\"}', '{\"id\":6,\"name\":\"nawel\",\"asset_tag\":\"TMS-RM-00007\",\"category_id\":3,\"room_id\":2,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"updated_at\":\"2024-11-29T02:21:37.000000Z\",\"status\":\"siap_dipakai\",\"room\":{\"id\":2,\"name\":\"RM ( Ruang Meeting )\",\"floor\":\"1\",\"building\":\"TMS\",\"capacity\":10,\"responsible_person\":\"Pak Fajar\",\"created_at\":\"2024-11-19T06:12:14.000000Z\",\"updated_at\":\"2024-11-19T06:12:14.000000Z\"},\"category\":{\"id\":3,\"name\":\"XLOO\",\"description\":null,\"created_at\":\"2024-11-29T02:05:25.000000Z\",\"updated_at\":\"2024-11-29T02:05:25.000000Z\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:21:37', '2024-11-28 19:21:37'),
(23, 1, 'view', 'barang', 'Accessed edit form for barang: nawel', NULL, '{\"id\":6,\"name\":\"nawel\",\"asset_tag\":\"TMS-RM-00007\",\"category_id\":3,\"room_id\":2,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"updated_at\":\"2024-11-29T02:21:37.000000Z\",\"status\":\"siap_dipakai\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:21:58', '2024-11-28 19:21:58'),
(24, 1, 'update', 'barang', 'Updated barang: name from \'nawel\' to \'nawelx\', description from \'mahal\' to \'mahalw222\', room from \'RM ( Ruang Meeting )\' to \'CS ( Customer Service )\', category from \'XLOO\' to \'TYPE X\', purchase cost from \'120000.00\' to \'500000000.00\'', '{\"id\":6,\"name\":\"nawel\",\"asset_tag\":\"TMS-RM-00007\",\"category_id\":3,\"room_id\":2,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"updated_at\":\"2024-11-29T02:21:37.000000Z\",\"status\":\"siap_dipakai\"}', '{\"id\":6,\"name\":\"nawelx\",\"asset_tag\":\"TMS-RM-00007\",\"category_id\":2,\"room_id\":3,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"500000000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahalw222\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"updated_at\":\"2024-11-29T02:22:16.000000Z\",\"status\":\"siap_dipakai\",\"room\":{\"id\":3,\"name\":\"CS ( Customer Service )\",\"floor\":\"1\",\"building\":\"TMS\",\"capacity\":10,\"responsible_person\":\"Ejaaa\",\"created_at\":\"2024-11-19T06:12:45.000000Z\",\"updated_at\":\"2024-11-19T06:12:45.000000Z\"},\"category\":{\"id\":2,\"name\":\"TYPE X\",\"description\":null,\"created_at\":\"2024-11-29T01:52:56.000000Z\",\"updated_at\":\"2024-11-29T01:52:56.000000Z\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:22:17', '2024-11-28 19:22:17'),
(25, 1, 'create', 'category', 'Created new category: test', NULL, '{\"name\":\"test\",\"description\":null,\"updated_at\":\"2024-11-29T02:49:13.000000Z\",\"created_at\":\"2024-11-29T02:49:13.000000Z\",\"id\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:49:13', '2024-11-28 19:49:13'),
(26, 1, 'update', 'room', 'Updated room: name from \'Bunker1\' to \'Bunker2\', floor from \'12\' to \'10\', building from \'TMS\' to \'TM5\', capacity from \'10\' to \'5\', responsible person from \'Saya Sendiri\' to \'Saya\'', '{\"id\":5,\"name\":\"Bunker1\",\"floor\":\"12\",\"building\":\"TMS\",\"capacity\":10,\"responsible_person\":\"Saya Sendiri\",\"created_at\":\"2024-11-29T02:12:27.000000Z\",\"updated_at\":\"2024-11-29T02:20:55.000000Z\"}', '{\"id\":5,\"name\":\"Bunker2\",\"floor\":\"10\",\"building\":\"TM5\",\"capacity\":\"5\",\"responsible_person\":\"Saya\",\"created_at\":\"2024-11-29T02:12:27.000000Z\",\"updated_at\":\"2024-11-29T02:49:42.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:49:42', '2024-11-28 19:49:42'),
(27, 1, 'create', 'maintenance', 'Created new maintenance log for: TV 8K UHD', NULL, '{\"barang_id\":\"1\",\"description\":\"fatal\",\"maintenance_date\":\"2024-11-29\",\"cost\":\"250000\",\"performed_by\":\"TEST\",\"status\":\"scheduled\",\"updated_at\":\"2024-11-29T02:52:05.000000Z\",\"created_at\":\"2024-11-29T02:52:05.000000Z\",\"id\":11,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:52:05', '2024-11-28 19:52:05'),
(28, 1, 'update_status', 'maintenance', 'Updated maintenance status to completed for: TV 8K UHD', '{\"id\":11,\"barang_id\":1,\"maintenance_date\":\"2024-11-29\",\"description\":\"fatal\",\"cost\":\"250000.00\",\"performed_by\":\"TEST\",\"status\":\"scheduled\",\"created_at\":\"2024-11-29T02:52:05.000000Z\",\"updated_at\":\"2024-11-29T02:52:05.000000Z\",\"due_date\":null}', '{\"id\":11,\"barang_id\":1,\"maintenance_date\":\"2024-11-29\",\"description\":\"fatal\",\"cost\":\"250000.00\",\"performed_by\":\"TEST\",\"status\":\"completed\",\"created_at\":\"2024-11-29T02:52:05.000000Z\",\"updated_at\":\"2024-11-29T02:53:02.000000Z\",\"due_date\":null,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:53:02', '2024-11-28 19:53:02'),
(29, 1, 'view', 'barang', 'Accessed edit form for barang: na wlX', NULL, '{\"id\":5,\"name\":\"na wlX\",\"asset_tag\":\"TMS-RM-00006\",\"category_id\":1,\"room_id\":2,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:16:36.000000Z\",\"updated_at\":\"2024-11-29T02:16:36.000000Z\",\"status\":\"siap_dipakai\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:54:35', '2024-11-28 19:54:35'),
(30, 1, 'update', 'barang', 'Updated barang: room from \'RM ( Ruang Meeting )\' to \'RU ( Ruangan Utama)\'', '{\"id\":5,\"name\":\"na wlX\",\"asset_tag\":\"TMS-RM-00006\",\"category_id\":1,\"room_id\":2,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:16:36.000000Z\",\"updated_at\":\"2024-11-29T02:16:36.000000Z\",\"status\":\"siap_dipakai\"}', '{\"id\":5,\"name\":\"na wlX\",\"asset_tag\":\"TMS-RM-00006\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"120000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-29T02:16:36.000000Z\",\"updated_at\":\"2024-11-29T02:54:45.000000Z\",\"status\":\"siap_dipakai\",\"room\":{\"id\":1,\"name\":\"RU ( Ruangan Utama)\",\"floor\":\"1\",\"building\":\"TMS\",\"capacity\":6,\"responsible_person\":\"Pak Iwan\",\"created_at\":\"2024-11-19T05:20:19.000000Z\",\"updated_at\":\"2024-11-19T05:20:19.000000Z\"},\"category\":{\"id\":1,\"name\":\"Elektronik\",\"description\":null,\"created_at\":\"2024-11-19T05:20:09.000000Z\",\"updated_at\":\"2024-11-19T05:20:09.000000Z\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 19:54:45', '2024-11-28 19:54:45'),
(31, 1, 'create', 'maintenance', 'Created new maintenance log for: TV 8K UHD', NULL, '{\"barang_id\":\"1\",\"description\":\"layar\",\"maintenance_date\":\"2024-11-29\",\"cost\":\"250000\",\"performed_by\":\"Niel\",\"status\":\"scheduled\",\"updated_at\":\"2024-11-29T03:04:01.000000Z\",\"created_at\":\"2024-11-29T03:04:01.000000Z\",\"id\":12,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 20:04:01', '2024-11-28 20:04:01'),
(32, 1, 'update_status', 'maintenance', 'Updated maintenance for \'TV 8K UHD\': status changed from \'scheduled\' to \'pending\'', '{\"id\":12,\"barang_id\":1,\"maintenance_date\":\"2024-11-29\",\"description\":\"layar\",\"cost\":\"250000.00\",\"performed_by\":\"Niel\",\"status\":\"scheduled\",\"created_at\":\"2024-11-29T03:04:01.000000Z\",\"updated_at\":\"2024-11-29T03:04:01.000000Z\",\"due_date\":null}', '{\"id\":12,\"barang_id\":1,\"maintenance_date\":\"2024-11-29\",\"description\":\"layar\",\"cost\":\"250000.00\",\"performed_by\":\"Niel\",\"status\":\"pending\",\"created_at\":\"2024-11-29T03:04:01.000000Z\",\"updated_at\":\"2024-11-29T03:04:10.000000Z\",\"due_date\":null,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-11-28 20:04:10', '2024-11-28 20:04:10'),
(33, 1, 'create', 'maintenance', 'Created new maintenance log for: TV 8K UHD', NULL, '{\"barang_id\":\"1\",\"description\":\"lcd bocor\",\"maintenance_date\":\"2024-12-05\",\"cost\":\"500000\",\"performed_by\":\"adit\",\"status\":\"scheduled\",\"updated_at\":\"2024-12-04T07:57:33.000000Z\",\"created_at\":\"2024-12-04T07:57:33.000000Z\",\"id\":13,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-04 00:57:33', '2024-12-04 00:57:33'),
(34, 1, 'update_status', 'maintenance', 'Updated maintenance status to completed for: TV 8K UHD', '{\"id\":13,\"barang_id\":1,\"maintenance_date\":\"2024-12-05\",\"description\":\"lcd bocor\",\"cost\":\"500000.00\",\"performed_by\":\"adit\",\"status\":\"scheduled\",\"created_at\":\"2024-12-04T07:57:33.000000Z\",\"updated_at\":\"2024-12-04T07:57:33.000000Z\",\"due_date\":null}', '{\"id\":13,\"barang_id\":1,\"maintenance_date\":\"2024-12-05\",\"description\":\"lcd bocor\",\"cost\":\"500000.00\",\"performed_by\":\"adit\",\"status\":\"completed\",\"created_at\":\"2024-12-04T07:57:33.000000Z\",\"updated_at\":\"2024-12-04T07:58:19.000000Z\",\"due_date\":null,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-04 00:58:19', '2024-12-04 00:58:19'),
(35, 1, 'create', 'asset_request', 'Created new asset request: kipas', NULL, '{\"name\":\"kipas\",\"category\":\"Electronics\",\"price\":\"500000\",\"description\":\"panas\",\"approver_email\":\"adityanathaniel43@gmail.com\",\"requester_email\":\"tms@gmail.com\",\"user_id\":1,\"status\":\"pending\",\"approval_token\":\"cJTowYMxblJNJYSz3AE4G0GjOGpqqx4U\",\"updated_at\":\"2024-12-04T07:59:39.000000Z\",\"created_at\":\"2024-12-04T07:59:39.000000Z\",\"id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-04 00:59:39', '2024-12-04 00:59:39'),
(36, 1, 'complete_maintenance', 'maintenance', 'Completed maintenance for: TV 8K UHD', '{\"id\":12,\"barang_id\":1,\"maintenance_date\":\"2024-11-29\",\"description\":\"layar\",\"cost\":\"250000.00\",\"performed_by\":\"Niel\",\"status\":\"pending\",\"created_at\":\"2024-11-29T03:04:01.000000Z\",\"updated_at\":\"2024-11-29T03:04:10.000000Z\",\"due_date\":null,\"completion_date\":null,\"actions_taken\":null,\"results\":null,\"replaced_parts\":null,\"total_cost\":null,\"equipment_status\":null,\"recommendations\":null,\"additional_notes\":null,\"technician_name\":null,\"follow_up_priority\":null}', '{\"id\":12,\"barang_id\":1,\"maintenance_date\":\"2024-11-29\",\"description\":\"layar\",\"cost\":\"250000.00\",\"performed_by\":\"Niel\",\"status\":\"completed\",\"created_at\":\"2024-11-29T03:04:01.000000Z\",\"updated_at\":\"2024-12-06T02:38:14.000000Z\",\"due_date\":null,\"completion_date\":\"2024-12-07T09:36\",\"actions_taken\":\"Menganti layar lcd ke ULTRA 8K SAMSUNG WIDEBODY\",\"results\":\"sukses layar diganti dan berhasil jadi lagi untuk pengunaan yang lama\",\"replaced_parts\":\"Layar LCD\",\"total_cost\":\"1500000\",\"equipment_status\":\"fully_repaired\",\"recommendations\":\"tidak perlu\",\"additional_notes\":null,\"technician_name\":\"Aditya\",\"follow_up_priority\":\"low\",\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-05 19:38:14', '2024-12-05 19:38:14'),
(37, 1, 'create', 'maintenance', 'Created new maintenance log for: TV 8K UHD', NULL, '{\"barang_id\":\"1\",\"description\":\"butuh perbaikan\",\"maintenance_date\":\"2024-12-07\",\"cost\":\"50000\",\"performed_by\":\"Niel\",\"status\":\"scheduled\",\"updated_at\":\"2024-12-06T02:52:03.000000Z\",\"created_at\":\"2024-12-06T02:52:03.000000Z\",\"id\":14,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-05 19:52:03', '2024-12-05 19:52:03'),
(38, 1, 'view', 'barang', 'Accessed edit form for barang: nawelx', NULL, '{\"id\":6,\"name\":\"nawelx\",\"asset_tag\":\"TMS-RM-00007\",\"category_id\":2,\"room_id\":3,\"purchase_date\":\"2024-11-29\",\"purchase_cost\":\"500000000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahalw222\",\"created_at\":\"2024-11-29T02:21:19.000000Z\",\"updated_at\":\"2024-11-29T02:22:16.000000Z\",\"status\":\"siap_dipakai\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-06 01:20:10', '2024-12-06 01:20:10'),
(39, 1, 'create', 'maintenance', 'Created new maintenance log for: TV 8K UHD', NULL, '{\"barang_id\":\"1\",\"description\":\"K\",\"maintenance_date\":\"2024-12-11\",\"cost\":\"2112\",\"performed_by\":\"Kevin\",\"status\":\"scheduled\",\"updated_at\":\"2024-12-09T20:18:57.000000Z\",\"created_at\":\"2024-12-09T20:18:57.000000Z\",\"id\":15,\"asset\":{\"id\":1,\"name\":\"TV 8K UHD\",\"asset_tag\":\"TMS-RU-00002\",\"category_id\":1,\"room_id\":1,\"purchase_date\":\"2024-11-19\",\"purchase_cost\":\"500000.00\",\"manufacturer\":null,\"model\":null,\"serial_number\":null,\"description\":\"mahal\",\"created_at\":\"2024-11-19T05:22:25.000000Z\",\"updated_at\":\"2024-11-29T02:08:00.000000Z\",\"status\":\"dalam_perbaikan\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-09 13:18:57', '2024-12-09 13:18:57'),
(40, 1, 'view', 'barang', 'Accessed barang creation form', NULL, '{\"available_categories\":5,\"available_rooms\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 14:20:38', '2024-12-15 14:20:38'),
(41, 1, 'create', 'barang', 'Added new barang: Farfx', NULL, '{\"name\":\"Farfx\",\"description\":\"aaa\",\"room_id\":\"2\",\"category_id\":\"3\",\"purchase_date\":\"2024-12-15\",\"purchase_cost\":\"150000\",\"status\":\"siap_dipakai\",\"asset_tag\":\"TMS-RM-00008\",\"updated_at\":\"2024-12-15T21:20:57.000000Z\",\"created_at\":\"2024-12-15T21:20:57.000000Z\",\"id\":7}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 14:20:57', '2024-12-15 14:20:57'),
(42, 1, 'create', 'barang', 'Added new barang: Farfx', NULL, '{\"name\":\"Farfx\",\"description\":\"aaa\",\"room_id\":\"2\",\"category_id\":\"3\",\"purchase_date\":\"2024-12-15\",\"purchase_cost\":\"150000\",\"status\":\"siap_dipakai\",\"asset_tag\":\"TMS-RM-00009\",\"updated_at\":\"2024-12-15T21:20:57.000000Z\",\"created_at\":\"2024-12-15T21:20:57.000000Z\",\"id\":8}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 14:20:57', '2024-12-15 14:20:57'),
(43, 1, 'view', 'barang', 'Accessed barang creation form', NULL, '{\"available_categories\":5,\"available_rooms\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 14:23:14', '2024-12-15 14:23:14'),
(44, 1, 'create', 'barang', 'Added new barang: Parfum', NULL, '{\"name\":\"Parfum\",\"description\":\"SSSSSS\",\"room_id\":\"1\",\"category_id\":\"2\",\"purchase_date\":\"2024-12-17\",\"purchase_cost\":\"1500000\",\"status\":\"siap_dipakai\",\"asset_tag\":\"TMS-RU-00010\",\"updated_at\":\"2024-12-15T21:23:31.000000Z\",\"created_at\":\"2024-12-15T21:23:31.000000Z\",\"id\":9}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 14:23:31', '2024-12-15 14:23:31'),
(45, 1, 'view', 'barang', 'Accessed barang creation form', NULL, '{\"available_categories\":5,\"available_rooms\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 15:54:58', '2024-12-15 15:54:58'),
(46, 1, 'view', 'barang', 'Accessed barang creation form', NULL, '{\"available_categories\":5,\"available_rooms\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 15:57:01', '2024-12-15 15:57:01'),
(47, 1, 'create', 'barang', 'Added new barang: Parfumx', NULL, '{\"name\":\"Parfumx\",\"description\":\"SSSSSS\",\"room_id\":\"2\",\"category_id\":\"3\",\"purchase_date\":\"2024-12-15\",\"purchase_cost\":\"1500000\",\"status\":\"siap_dipakai\",\"asset_tag\":\"TMS-00011\",\"updated_at\":\"2024-12-15T22:57:10.000000Z\",\"created_at\":\"2024-12-15T22:57:10.000000Z\",\"id\":10}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 15:57:10', '2024-12-15 15:57:10'),
(48, 1, 'create', 'asset_request', 'Created new asset request: Kertas', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:16:58', '2024-12-15 16:16:58'),
(49, 1, 'approve', 'asset_request', 'Approved asset request and created asset: Kertas', NULL, '{\"asset_id\":12}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:25:41', '2024-12-15 16:25:41'),
(50, 1, 'create', 'asset_request', 'Created new asset request: KertasX', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:30:39', '2024-12-15 16:30:39'),
(51, 1, 'approve', 'asset_request', 'Approved asset request and created asset: KertasX', NULL, '{\"asset_id\":13}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:30:50', '2024-12-15 16:30:50'),
(52, 1, 'create', 'asset_request', 'Created new asset request: KertasXX', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:32:32', '2024-12-15 16:32:32'),
(53, 1, 'approve', 'asset_request', 'Approved asset request and created asset: KertasXX', NULL, '{\"asset_id\":14}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:32:38', '2024-12-15 16:32:38'),
(54, 1, 'create', 'asset_request', 'Created new asset request: KertasS', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:36:25', '2024-12-15 16:36:25'),
(55, 1, 'approve', 'asset_request', 'Approved asset request and created asset: KertasS', NULL, '{\"asset_id\":15}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', '2024-12-15 16:36:39', '2024-12-15 16:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `asset_tag` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_cost` decimal(15,2) NOT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `name`, `asset_tag`, `category_id`, `room_id`, `purchase_date`, `purchase_cost`, `manufacturer`, `model`, `serial_number`, `description`, `created_at`, `updated_at`, `status`) VALUES
(1, 'TV 8K UHD', 'TMS-RU-00002', 1, 1, '2024-11-19', 500000.00, NULL, NULL, NULL, 'mahal', '2024-11-18 22:22:25', '2024-12-15 15:03:41', 'siap_dipakai'),
(2, 'Kipas angin pakai Mesin pesawat', 'TMS-RU-00003', 1, 1, '2024-11-19', 50000.00, NULL, NULL, NULL, 'mahal', '2024-11-18 22:39:52', '2024-11-28 19:17:03', 'dalam_perbaikan'),
(3, 'Kipas angin pakai Mesin Jet', 'TMS-RU-00004', 1, 1, '2024-11-19', 120000.00, NULL, NULL, NULL, 'mahal', '2024-11-18 23:11:37', '2024-11-18 23:11:37', 'siap_dipakai'),
(4, 'na wl', 'TMS-CS-00005', 2, 3, '2024-11-29', 120000.00, NULL, NULL, NULL, 'mahal', '2024-11-28 19:11:17', '2024-12-15 15:32:01', 'perlu_perbaikan'),
(5, 'na wlX', 'TMS-RM-00006', 1, 1, '2024-11-29', 120000.00, NULL, NULL, NULL, 'mahal', '2024-11-28 19:16:36', '2024-12-15 15:24:30', 'rusak'),
(6, 'nawelx', 'TMS-RM-00007', 2, 3, '2024-11-29', 500000000.00, NULL, NULL, NULL, 'mahalw222', '2024-11-28 19:21:19', '2024-11-28 19:22:16', 'siap_dipakai'),
(7, 'Farfx', 'TMS-RM-00008', 3, 2, '2024-12-15', 150000.00, NULL, NULL, NULL, 'aaa', '2024-12-15 14:20:57', '2024-12-15 15:19:19', 'rusak'),
(8, 'Farfx', 'TMS-RM-00009', 3, 2, '2024-12-15', 150000.00, NULL, NULL, NULL, 'aaa', '2024-12-15 14:20:57', '2024-12-15 14:20:57', 'siap_dipakai'),
(9, 'Parfum', 'TMS-RU-00010', 2, 1, '2024-12-17', 1500000.00, NULL, NULL, NULL, 'SSSSSS', '2024-12-15 14:23:31', '2024-12-15 14:57:04', 'perlu_perbaikan'),
(10, 'Parfumx', 'TMS-00011', 3, 2, '2024-12-15', 1500000.00, NULL, NULL, NULL, 'SSSSSS', '2024-12-15 15:57:10', '2024-12-15 15:57:10', 'siap_dipakai'),
(12, 'Kertas', 'TMS-00001', 3, 2, '2024-12-15', 500000.00, NULL, NULL, NULL, 'Butuh', '2024-12-15 16:25:41', '2024-12-15 16:25:41', 'siap_dipakai'),
(13, 'KertasX', 'TMS-00002', 2, 3, '2024-12-15', 500000.00, NULL, NULL, NULL, 'ww', '2024-12-15 16:30:50', '2024-12-15 16:30:50', 'siap_dipakai'),
(14, 'KertasXX', 'TMS-00003', 2, 2, '2024-12-15', 500000.00, NULL, NULL, NULL, 'ww', '2024-12-15 16:32:38', '2024-12-15 16:32:38', 'siap_dipakai'),
(15, 'KertasS', 'TMS-00004', 1, 1, '2024-12-15', 500000.00, NULL, NULL, NULL, NULL, '2024-12-15 16:36:39', '2024-12-15 16:36:39', 'siap_dipakai');

-- --------------------------------------------------------

--
-- Table structure for table `asset_requests`
--

CREATE TABLE `asset_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `requester_email` varchar(255) NOT NULL,
  `status` enum('pending','approved','declined') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approval_token` varchar(255) DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_requests`
--

INSERT INTO `asset_requests` (`id`, `name`, `category`, `price`, `description`, `requester_email`, `status`, `notes`, `user_id`, `approved_at`, `created_at`, `updated_at`, `approval_token`, `room_id`) VALUES
(1, 'Proyektor 8K UHD SONY', 'Electronics', 20000000000.00, 'butuh', 'tms@gmail.com', 'approved', NULL, 1, NULL, '2024-11-22 01:19:14', '2024-11-22 01:23:10', NULL, NULL),
(2, 'Proyektor 8K UHD SONY', 'Furniture', 20000000000.00, NULL, 'tms@gmail.com', 'pending', NULL, 1, NULL, '2024-11-28 19:11:43', '2024-11-28 19:11:43', 'wAqOLUkwwLrHSlIbbXKEu3Jh3NrH8c1g', NULL),
(3, 'kipas', 'Electronics', 500000.00, 'panas', 'tms@gmail.com', 'pending', NULL, 1, NULL, '2024-12-04 00:59:39', '2024-12-04 00:59:39', 'cJTowYMxblJNJYSz3AE4G0GjOGpqqx4U', NULL),
(4, 'Kertas', 'XLOO', 500000.00, 'Butuh', 'tms@gmail.com', 'approved', NULL, 1, '2024-12-15 16:25:41', '2024-12-15 16:16:58', '2024-12-15 16:25:41', NULL, 2),
(5, 'KertasX', 'TYPE X', 500000.00, 'ww', 'tms@gmail.com', 'approved', NULL, 1, '2024-12-15 16:30:50', '2024-12-15 16:30:39', '2024-12-15 16:30:50', NULL, 3),
(6, 'KertasXX', 'TYPE X', 500000.00, 'ww', 'tms@gmail.com', 'approved', NULL, 1, '2024-12-15 16:32:38', '2024-12-15 16:32:32', '2024-12-15 16:32:38', NULL, 2),
(7, 'KertasS', 'Elektronik', 500000.00, NULL, 'tms@gmail.com', 'approved', NULL, 1, '2024-12-15 16:36:39', '2024-12-15 16:36:25', '2024-12-15 16:36:39', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `asset_transfers`
--

CREATE TABLE `asset_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `from_room` varchar(255) NOT NULL,
  `to_room` varchar(255) NOT NULL,
  `transfer_date` date NOT NULL,
  `reason` text NOT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_seq`
--

CREATE TABLE `barang_seq` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `next_val` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang_seq`
--

INSERT INTO `barang_seq` (`id`, `next_val`, `created_at`, `updated_at`) VALUES
(1, 12, NULL, '2024-12-15 15:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', NULL, '2024-11-18 22:20:09', '2024-11-18 22:20:09'),
(2, 'TYPE X', NULL, '2024-11-28 18:52:56', '2024-11-28 18:52:56'),
(3, 'XLOO', NULL, '2024-11-28 19:05:25', '2024-11-28 19:05:25'),
(4, 'xxx-proto1q', NULL, '2024-11-28 19:07:24', '2024-11-28 19:14:34'),
(5, 'test', NULL, '2024-11-28 19:49:13', '2024-11-28 19:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `daftar_barang`
--

CREATE TABLE `daftar_barang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `condition` enum('good','needs_maintenance','damaged') NOT NULL DEFAULT 'good',
  `last_maintenance` date DEFAULT NULL,
  `next_maintenance` date DEFAULT NULL,
  `status` enum('active','maintenance','retired') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_date` date NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `performed_by` varchar(255) NOT NULL,
  `status` enum('scheduled','pending','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_date` date NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `performed_by` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completion_date` datetime DEFAULT NULL,
  `actions_taken` text DEFAULT NULL,
  `results` text DEFAULT NULL,
  `replaced_parts` text DEFAULT NULL,
  `total_cost` decimal(15,2) DEFAULT NULL,
  `equipment_status` varchar(255) DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `technician_name` varchar(255) DEFAULT NULL,
  `follow_up_priority` varchar(255) DEFAULT NULL,
  `approval_status` varchar(100) NOT NULL DEFAULT 'pending',
  `archived_at` timestamp NULL DEFAULT NULL,
  `approval_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_logs`
--

INSERT INTO `maintenance_logs` (`id`, `barang_id`, `maintenance_date`, `description`, `cost`, `performed_by`, `status`, `created_at`, `updated_at`, `due_date`, `completion_date`, `actions_taken`, `results`, `replaced_parts`, `total_cost`, `equipment_status`, `recommendations`, `additional_notes`, `technician_name`, `follow_up_priority`, `approval_status`, `archived_at`, `approval_date`) VALUES
(1, 1, '2024-11-20', 'Spec RTX', 2000.00, 'TEST', 'completed', '2024-11-18 22:23:18', '2024-12-09 13:53:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(2, 2, '2024-11-20', 'Spec Mampus', 20000.00, 'Niel', 'completed', '2024-11-18 22:40:27', '2024-12-09 13:54:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(3, 1, '2024-11-20', 'Spec Mampus', 200000.00, 'Niel', 'completed', '2024-11-18 22:42:54', '2024-12-09 13:57:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(4, 2, '2024-11-20', 'Spec Mampus', 20000.00, 'Niel', 'completed', '2024-11-18 23:18:02', '2024-12-09 13:57:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(5, 1, '2024-11-23', 'rusak total', 500000.00, 'Niel', 'completed', '2024-11-22 01:07:52', '2024-12-09 13:58:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(6, 1, '2024-11-23', 'lcd', 700000.00, 'Niel', 'completed', '2024-11-22 01:12:01', '2024-12-09 13:55:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(7, 1, '2024-11-29', 'fatal', 250000.00, 'TEST', 'completed', '2024-11-28 18:28:19', '2024-11-28 18:36:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL),
(8, 2, '2024-11-30', 'fatal', 250000.00, 'TEST', 'completed', '2024-11-28 18:38:00', '2024-11-28 18:39:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL),
(9, 2, '2024-11-30', 'fatal', 2400000.00, 'TEST', 'completed', '2024-11-28 18:42:25', '2024-12-09 13:56:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(10, 1, '2024-11-30', 'fatal', 250000.00, 'TEST', 'completed', '2024-11-28 18:47:14', '2024-12-15 13:45:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'initial_approved', NULL, NULL),
(11, 1, '2024-11-29', 'fatal', 250000.00, 'TEST', 'completed', '2024-11-28 19:52:05', '2024-12-09 13:59:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(12, 1, '2024-11-29', 'layar', 250000.00, 'Niel', 'completed', '2024-11-28 20:04:01', '2024-12-05 19:38:14', NULL, '2024-12-07 09:36:00', 'Menganti layar lcd ke ULTRA 8K SAMSUNG WIDEBODY', 'sukses layar diganti dan berhasil jadi lagi untuk pengunaan yang lama', 'Layar LCD', 1500000.00, 'fully_repaired', 'tidak perlu', NULL, 'Aditya', 'low', 'pending', NULL, NULL),
(13, 1, '2024-12-05', 'lcd bocor', 500000.00, 'adit', 'completed', '2024-12-04 00:57:33', '2024-12-09 14:00:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(14, 1, '2024-12-07', 'butuh perbaikan', 50000.00, 'Niel', 'completed', '2024-12-05 19:52:03', '2024-12-09 14:00:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(15, 1, '2024-12-11', 'K', 2112.00, 'Kevin', 'completed', '2024-12-09 13:18:57', '2024-12-15 15:07:07', NULL, '2024-12-15 00:00:00', 'wwwwww', 'wwwwww', 'wwwwwwww', 5000000.00, 'berfungsi_100', 'www', 'www', 'niell', NULL, 'approved', NULL, '2024-12-15 15:07:07'),
(16, 1, '2024-12-12', 'ww', 1111.00, 'XXX1', 'completed', '2024-12-09 13:30:23', '2024-12-09 13:53:14', NULL, '2024-12-14 03:48:00', 'aaa', 'aaa', 'aaa', 2222222.00, 'partially_repaired', 'ssss', 'ddddd', 'EQQ', 'low', 'approved', NULL, NULL),
(17, 3, '2024-12-16', 'ww', 11111111.00, '1aas', 'completed', '2024-12-09 14:06:51', '2024-12-15 13:45:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'initial_approved', NULL, NULL),
(18, 1, '2024-12-11', 'aa', 1111111.00, 'aaaa', 'completed', '2024-12-09 14:12:03', '2024-12-15 13:45:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(19, 5, '2024-12-11', 'www', 22222.00, 'Niel', 'completed', '2024-12-09 14:20:39', '2024-12-15 15:02:23', NULL, '2024-12-25 00:00:00', 'Mengganti motherboard', 'Ternyata chipnya patah', 'motherbaord', 500000.00, 'fully_operational', NULL, NULL, 'Niel', 'low', 'approved', NULL, '2024-12-15 15:02:23'),
(20, 2, '2024-12-11', 'aaa', 1111122.00, 'Niel', 'completed', '2024-12-09 14:22:10', '2024-12-15 15:14:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, '2024-12-15 15:14:18'),
(21, 2, '2024-12-17', 'Segera', 150000.00, 'Niel', 'completed', '2024-12-15 13:31:48', '2024-12-15 13:45:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL),
(22, 2, '2024-12-30', 'SSSSSS', 122222.00, 'Niel', 'archived', '2024-12-15 13:45:43', '2024-12-15 14:08:23', NULL, '2024-12-18 00:00:00', 'sssssss', 'ss', 'ss', 250000.00, 'Jadi 100%', NULL, NULL, 'Niel', 'tidak perlu', 'pending', '2024-12-15 14:08:23', NULL),
(23, 6, '2024-12-25', 'wss', 150000.00, 'XXX1', 'completed', '2024-12-15 14:16:12', '2024-12-15 15:14:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, '2024-12-15 15:14:08'),
(24, 9, '2024-12-17', 'Take home', 150000.00, 'XXX1', 'completed', '2024-12-15 14:23:53', '2024-12-15 15:01:30', NULL, '2024-12-17 00:00:00', 'wwwww', 'wwwwwww', 'wwwwwwwww', 500000.00, 'berfungsi_sebagian', NULL, NULL, 'niell', NULL, 'pending', NULL, '2024-12-15 15:01:30'),
(25, 9, '2024-12-24', 'wwwww', 25000.00, 'Kevin', 'completed', '2024-12-15 15:12:29', '2024-12-15 15:14:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, '2024-12-15 15:14:00'),
(26, 7, '2024-12-24', 'ww', 588.00, 'XXX1', 'completed', '2024-12-15 15:16:33', '2024-12-15 15:16:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, '2024-12-15 15:16:40'),
(27, 7, '2024-12-17', 'ww', 588.00, 'XXX1', 'completed', '2024-12-15 15:17:18', '2024-12-15 15:22:37', NULL, '2024-12-15 00:00:00', 'wwwww', 'wwww', 'wwwwww', 5000000.00, 'rusak_total', 'rer', 'rrr', 'EQQ', NULL, 'approved', NULL, '2024-12-15 15:22:37'),
(28, 5, '2024-12-18', 'RER', 233333.00, 'XXX1', 'completed', '2024-12-15 15:24:04', '2024-12-15 15:24:36', NULL, '2024-12-15 00:00:00', 'EEEE', 'EEEEE', 'EEEEEE', 500000.00, 'rusak_total', NULL, NULL, 'niell', NULL, 'approved', NULL, '2024-12-15 15:24:36'),
(29, 4, '2024-12-18', 'helo', 500000.00, 'Niel', 'completed', '2024-12-15 15:27:39', '2024-12-15 15:28:20', NULL, '2024-12-15 00:00:00', 'eEEEEEEEE', 'EEEEEEE', 'EEEEEEEEEE', 900000.00, 'menunggu_komponen', 'w', NULL, 'EQQ', NULL, 'approved', NULL, '2024-12-15 15:28:20'),
(30, 4, '2024-12-18', 'eee', 500000.00, 'XXX1', 'completed', '2024-12-15 15:31:33', '2024-12-15 15:32:07', NULL, '2024-12-15 00:00:00', 'EEEEE', 'EEEEEE', 'EEEEEE', 600000.00, 'berfungsi_sebagian', NULL, NULL, 'EQQ', NULL, 'approved', NULL, '2024-12-15 15:32:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(13, '2014_10_12_000000_create_users_table', 1),
(14, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(15, '2019_08_19_000000_create_failed_jobs_table', 1),
(16, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(17, '2024_11_03_161243_create_all_asset_tables', 1),
(18, '2024_11_03_162143_create_barang_seq_table', 1),
(19, '2024_11_03_162854_create_sessions_table', 1),
(20, '2024_11_03_203709_add_due_date_to_maintenance_logs_table', 1),
(21, '2024_11_10_224550_create_asset_requests_table', 1),
(22, '2024_11_10_232756_add_approval_token_to_asset_requests_table', 1),
(23, '2024_11_10_234007_update_asset_requests_table_nullable_token', 1),
(24, '2024_11_19_042404_update_status_column_in_assets_table', 1),
(25, '2024_11_19_052142_modify_assets_status_enum', 2),
(26, '2024_11_29_238989_create_activity_logs_table', 3),
(27, '2024_12_05_042110_create_stock_check_tables', 4),
(28, '2024_12_06_481824_add_completion_fields_to_maintenance_logs', 5),
(29, '2024_12_09_202746_create_maintenance_table', 6),
(30, '2024_12_09_203100_add_approval_status_to_maintenance_logs', 7),
(31, '2024_12_09_205032_modify_status_column_in_maintenance_logs', 8),
(32, '2024_12_09_210515_update_maintenance_logs_column_lengths', 9),
(33, '2024_12_15_210545_add_archived_at_to_maintenance_logs', 10),
(34, '2024_12_15_212617_add_approval_date_to_maintenance_logs', 11),
(35, '2024_12_15_214955_alter_assets_table_status_column', 12),
(36, '2024_12_15_231400_add_room_id_to_asset_requests_table', 13),
(37, '2024_12_15_231600_remove_approver_email_from_asset_requests_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `floor` varchar(255) NOT NULL,
  `building` varchar(255) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `responsible_person` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `floor`, `building`, `capacity`, `responsible_person`, `created_at`, `updated_at`) VALUES
(1, 'RU ( Ruangan Utama)', '1', 'TMS', 6, 'Pak Iwan', '2024-11-18 22:20:19', '2024-11-18 22:20:19'),
(2, 'RM ( Ruang Meeting )', '1', 'TMS', 10, 'Pak Fajar', '2024-11-18 23:12:14', '2024-11-18 23:12:14'),
(3, 'CS ( Customer Service )', '1', 'TMS', 10, 'Ejaaa', '2024-11-18 23:12:45', '2024-11-18 23:12:45'),
(4, 'KADIV', '1', 'TMS', 10, 'Bu meli', '2024-11-18 23:13:09', '2024-11-18 23:13:09'),
(5, 'Bunker2', '10', 'TM5', 5, 'Saya', '2024-11-28 19:12:27', '2024-11-28 19:49:42');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('n4YHQPkL9Yc20w4FVIhM3Xz1GIrE2IrTSpPc4brn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibWY3RVVacDh5WVI5SFdaQldQYmV1dkJmcG1iYW1rZkZEUmRIVHYwSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iYXJhbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1734306791);

-- --------------------------------------------------------

--
-- Table structure for table `stock_checks`
--

CREATE TABLE `stock_checks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('ongoing','completed') NOT NULL DEFAULT 'ongoing',
  `completed_at` timestamp NULL DEFAULT NULL,
  `last_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_checks`
--

INSERT INTO `stock_checks` (`id`, `created_by`, `status`, `completed_at`, `last_updated_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'completed', '2024-12-04 21:37:24', '2024-12-04 21:27:39', '2024-12-04 21:27:39', '2024-12-04 21:37:24'),
(2, 1, 'completed', '2024-12-04 22:23:03', '2024-12-04 22:22:48', '2024-12-04 22:22:48', '2024-12-04 22:23:03'),
(3, 1, 'completed', '2024-12-04 22:26:24', '2024-12-04 22:26:19', '2024-12-04 22:26:19', '2024-12-04 22:26:24'),
(4, 1, 'completed', '2024-12-05 18:40:40', '2024-12-05 18:40:10', '2024-12-05 18:40:10', '2024-12-05 18:40:40'),
(5, 1, 'completed', '2024-12-05 18:47:04', '2024-12-05 18:45:15', '2024-12-05 18:45:15', '2024-12-05 18:47:04'),
(6, 1, 'completed', '2024-12-05 18:56:09', '2024-12-05 18:56:05', '2024-12-05 18:56:05', '2024-12-05 18:56:09'),
(7, 1, 'completed', '2024-12-05 19:01:31', '2024-12-05 19:01:24', '2024-12-05 19:01:24', '2024-12-05 19:01:31'),
(8, 1, 'completed', '2024-12-05 20:06:44', '2024-12-05 19:01:46', '2024-12-05 19:01:46', '2024-12-05 20:06:44'),
(9, 1, 'ongoing', NULL, '2024-12-09 13:06:58', '2024-12-09 13:06:58', '2024-12-09 13:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `stock_check_items`
--

CREATE TABLE `stock_check_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_check_id` bigint(20) UNSIGNED NOT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_check_items`
--

INSERT INTO `stock_check_items` (`id`, `stock_check_id`, `asset_id`, `description`, `is_checked`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'donedon', 1, '2024-12-04 21:28:10', '2024-12-04 21:31:35'),
(2, 1, 2, 'done', 1, '2024-12-04 21:31:35', '2024-12-04 21:31:35'),
(3, 2, 1, 'Ada yang rusak bagian LCD ujung', 1, '2024-12-04 22:22:48', '2024-12-04 22:22:48'),
(4, 2, 4, 'keadaan 85%', 1, '2024-12-04 22:22:48', '2024-12-04 22:22:48'),
(5, 2, 5, 'keadaan 95%', 1, '2024-12-04 22:22:49', '2024-12-04 22:22:49'),
(6, 2, 6, 'tidak layak', 1, '2024-12-04 22:22:49', '2024-12-04 22:22:49'),
(7, 3, 1, NULL, 1, '2024-12-04 22:26:20', '2024-12-04 22:26:20'),
(8, 3, 2, NULL, 1, '2024-12-04 22:26:20', '2024-12-04 22:26:20'),
(9, 3, 3, NULL, 1, '2024-12-04 22:26:20', '2024-12-04 22:26:20'),
(10, 6, 1, NULL, 1, '2024-12-05 18:56:05', '2024-12-05 18:56:05'),
(11, 6, 2, NULL, 1, '2024-12-05 18:56:05', '2024-12-05 18:56:05'),
(12, 7, 1, NULL, 1, '2024-12-05 19:01:24', '2024-12-05 19:01:24'),
(13, 7, 2, NULL, 1, '2024-12-05 19:01:24', '2024-12-05 19:01:24'),
(14, 8, 1, NULL, 1, '2024-12-05 19:01:46', '2024-12-05 19:01:46'),
(15, 8, 2, NULL, 1, '2024-12-05 19:01:46', '2024-12-05 19:01:46'),
(16, 8, 3, NULL, 1, '2024-12-05 19:06:58', '2024-12-05 19:06:58'),
(17, 9, 1, NULL, 1, '2024-12-09 13:06:58', '2024-12-09 13:06:58'),
(18, 9, 2, NULL, 1, '2024-12-09 13:06:58', '2024-12-09 13:06:58'),
(19, 9, 3, NULL, 1, '2024-12-09 13:06:58', '2024-12-09 13:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'tms@gmail.com', NULL, '$2y$10$GDql1cn/WXszZp1UQhh0Benz.HYyBo1NAS.xddXU4l9RLraerMmIm', NULL, '2024-11-18 22:19:47', '2024-11-18 22:19:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assets_asset_tag_unique` (`asset_tag`),
  ADD KEY `assets_category_id_foreign` (`category_id`),
  ADD KEY `assets_room_id_foreign` (`room_id`);

--
-- Indexes for table `asset_requests`
--
ALTER TABLE `asset_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_requests_approval_token_unique` (`approval_token`),
  ADD KEY `asset_requests_user_id_foreign` (`user_id`),
  ADD KEY `asset_requests_room_id_foreign` (`room_id`);

--
-- Indexes for table `asset_transfers`
--
ALTER TABLE `asset_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_transfers_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `barang_seq`
--
ALTER TABLE `barang_seq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daftar_barang`
--
ALTER TABLE `daftar_barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daftar_barang_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_logs_barang_id_foreign` (`barang_id`);

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
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rooms_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_checks`
--
ALTER TABLE `stock_checks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_checks_created_by_foreign` (`created_by`);

--
-- Indexes for table `stock_check_items`
--
ALTER TABLE `stock_check_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_check_items_stock_check_id_foreign` (`stock_check_id`),
  ADD KEY `stock_check_items_asset_id_foreign` (`asset_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `asset_requests`
--
ALTER TABLE `asset_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `asset_transfers`
--
ALTER TABLE `asset_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_seq`
--
ALTER TABLE `barang_seq`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `daftar_barang`
--
ALTER TABLE `daftar_barang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_checks`
--
ALTER TABLE `stock_checks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stock_check_items`
--
ALTER TABLE `stock_check_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `assets_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `asset_requests`
--
ALTER TABLE `asset_requests`
  ADD CONSTRAINT `asset_requests_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `asset_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `asset_transfers`
--
ALTER TABLE `asset_transfers`
  ADD CONSTRAINT `asset_transfers_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `assets` (`id`);

--
-- Constraints for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_checks`
--
ALTER TABLE `stock_checks`
  ADD CONSTRAINT `stock_checks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_check_items`
--
ALTER TABLE `stock_check_items`
  ADD CONSTRAINT `stock_check_items_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`),
  ADD CONSTRAINT `stock_check_items_stock_check_id_foreign` FOREIGN KEY (`stock_check_id`) REFERENCES `stock_checks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
