-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2024 at 10:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assets`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang_seq`
--

CREATE TABLE `barang_seq` (
  `next_val` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_seq`
--

INSERT INTO `barang_seq` (`next_val`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(1, 'Elektronik', '2024-10-31 01:34:59'),
(2, 'Kursi', '2024-10-31 01:34:59'),
(3, 'Meja', '2024-10-31 01:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `daftar_barang`
--

CREATE TABLE `daftar_barang` (
  `id` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `room` enum('Ruang Utama','Ruang Meeting') NOT NULL,
  `category_id` int(11) NOT NULL,
  `tahun_pengadaan` int(11) NOT NULL CHECK (`tahun_pengadaan` >= 1900),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daftar_barang`
--

INSERT INTO `daftar_barang` (`id`, `description`, `room`, `category_id`, `tahun_pengadaan`, `created_at`) VALUES
('TMS-RU-0001', 'Meja Kerja', 'Ruang Utama', 3, 2024, '2024-10-31 01:35:57');

--
-- Triggers `daftar_barang`
--
DELIMITER $$
CREATE TRIGGER `set_barang_id` BEFORE INSERT ON `daftar_barang` FOR EACH ROW BEGIN
    DECLARE next_id INT;
    DECLARE room_code VARCHAR(2);
    
    -- Insert and get the next sequence value
    INSERT INTO barang_seq VALUES (NULL);
    SET next_id = LAST_INSERT_ID();
    
    -- Determine room code
    IF NEW.room = 'Ruang Utama' THEN
        SET room_code = 'RU';
    ELSE 
        SET room_code = 'RM';
    END IF;
    
    -- Set the new ID
    SET NEW.id = CONCAT('TMS-', 
                       room_code, '-',
                       LPAD(next_id, 4, '0'));
    
    -- Clean up sequence table to prevent it from growing too large
    DELETE FROM barang_seq WHERE next_val < next_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_daftar_barang`
-- (See below for the actual view)
--
CREATE TABLE `v_daftar_barang` (
`kode_barang` varchar(20)
,`deskripsi` text
,`ruangan` enum('Ruang Utama','Ruang Meeting')
,`kategori` varchar(50)
,`tahun_pengadaan` int(11)
,`tanggal_input` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `v_daftar_barang`
--
DROP TABLE IF EXISTS `v_daftar_barang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_daftar_barang`  AS SELECT `db`.`id` AS `kode_barang`, `db`.`description` AS `deskripsi`, `db`.`room` AS `ruangan`, `c`.`category_name` AS `kategori`, `db`.`tahun_pengadaan` AS `tahun_pengadaan`, `db`.`created_at` AS `tanggal_input` FROM (`daftar_barang` `db` join `categories` `c` on(`db`.`category_id` = `c`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang_seq`
--
ALTER TABLE `barang_seq`
  ADD PRIMARY KEY (`next_val`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `daftar_barang`
--
ALTER TABLE `daftar_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang_seq`
--
ALTER TABLE `barang_seq`
  MODIFY `next_val` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daftar_barang`
--
ALTER TABLE `daftar_barang`
  ADD CONSTRAINT `daftar_barang_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
