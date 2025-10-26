-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 10:55 PM
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
-- Database: `301_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id_account` int(50) NOT NULL,
  `username_account` varchar(40) DEFAULT NULL,
  `email_account` varchar(40) DEFAULT NULL,
  `password_account` varchar(256) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `salt_account` varchar(256) DEFAULT NULL,
  `role_account` varchar(6) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่สร้างบัญชี'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id_account`, `username_account`, `email_account`, `password_account`, `birthdate`, `salt_account`, `role_account`, `created_at`) VALUES
(17, 'sorieketon', 'sorieketon@email.com', '$argon2id$v=19$m=131072,t=4,p=3$Rm40RElVQW9jSGFYTGg3TA$9IjgNrgNwNqiIJ3qkGQKwmlCRHPossweST7HRzR19Rk', '2025-10-05', 'ae0b8a453918cff49a9ec62aa075aac1', 'member', '2025-10-27 02:59:18'),
(18, 'ice', 'ice@email.com', '$argon2id$v=19$m=131072,t=4,p=3$L2JoTUJ5blR3cmdhRVo0Mg$S466Ws3Tw6HwOU9RjPN6EbvLoF5Q9OynMjrxcwkOWkE', '2025-10-20', '86cd4ee75b9bf232ca270fb4714f0300', 'admin', '2025-10-27 02:59:18'),
(19, 'aize', 'aize@email.com', '$argon2id$v=19$m=131072,t=4,p=3$ZDU3dkRKQ0FzQm54ODQ4SA$2hC0kV8DjVKtlUG8KU1HAentPxi4DSRsSJjlhaWzcsg', '2025-10-03', '2d37a9b30f449b89e8eb9feed9b4454d', 'member', '2025-10-27 02:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL COMMENT 'ID ข่าวสาร',
  `title` varchar(255) NOT NULL COMMENT 'หัวข้อข่าวสาร',
  `content` text NOT NULL COMMENT 'เนื้อหาข่าวสาร',
  `admin_id` int(11) DEFAULT NULL COMMENT 'ID ของ Admin ที่เผยแพร่',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่เผยแพร่ครั้งแรก',
  `updated_at` datetime DEFAULT NULL COMMENT 'วันที่แก้ไขล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ตารางเก็บข้อมูลข่าวสาร';

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `admin_id`, `created_at`, `updated_at`) VALUES
(6, 'Battlefield 6', 'จะมีการอัพเดตขึ้นเป็น SS1 ในวันที่ 28/10/68', 18, '2025-10-27 02:50:21', NULL),
(7, 'League of Legends', 'ขณะนี้ LoL กำลังมี Tournament World Championship 2025 ซึ่งได้เข้าสู่รอบ 8 ทีมสุดท้ายแล้ว', 18, '2025-10-27 02:51:57', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id_account`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id_account` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID ข่าวสาร', AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `account` (`id_account`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
