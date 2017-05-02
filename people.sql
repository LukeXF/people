-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 02, 2017 at 03:46 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `people`
--

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `people_id` varchar(24) NOT NULL,
  `people_isActive` tinyint(1) NOT NULL,
  `people_currency` varchar(1) NOT NULL,
  `people_balance` float NOT NULL,
  `people_picture` varchar(255) NOT NULL,
  `people_age` int(3) NOT NULL,
  `people_eyeColor` enum('green','brown','blue','hazel') NOT NULL,
  `people_first_name` varchar(255) NOT NULL,
  `people_last_name` varchar(255) NOT NULL,
  `people_gender` enum('male','female') NOT NULL,
  `people_company` varchar(255) NOT NULL,
  `people_email` varchar(255) NOT NULL,
  `people_phone` varchar(17) NOT NULL,
  `people_address` varchar(255) NOT NULL,
  `people_about` text NOT NULL,
  `people_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `people_latitude` float(10,6) NOT NULL,
  `people_longitude` float(10,6) NOT NULL,
  `people_tags_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_unique_id` int(11) NOT NULL,
  `tag_person_id` varchar(24) NOT NULL,
  `tag_value` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD UNIQUE KEY `people_id` (`people_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD UNIQUE KEY `tag_unique_id` (`tag_unique_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_unique_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15473;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
