-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 05, 2021 at 01:10 PM
-- Server version: 10.1.47-MariaDB-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `AIS`
--

USE AIS;

-- --------------------------------------------------------

--
-- Table structure for table `education_background`
--

CREATE TABLE `education_background` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `level` varchar(15) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `index_no` varchar(50) DEFAULT NULL,
  `examination_authority` varchar(50) DEFAULT NULL,
  `examination_center_no` varchar(50) DEFAULT NULL,
  `year_completed` int(4) DEFAULT NULL,
  `equivalent_index_no` varchar(50) DEFAULT NULL,
  `score` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `education_background`
--

INSERT INTO `education_background` (`id`, `student_id`, `level`, `school_name`, `index_no`, `examination_authority`, `examination_center_no`, `year_completed`, `equivalent_index_no`, `score`) VALUES
(18, 4, 'O Level', 'Arusha Secondary', 'S-1234', 'NECTA', '3234234234', 1990, '34234234', 'Div III'),
(19, 4, 'A Level', 'Arusha Secondary', 'S-12345', 'NECTA', '32342342346', 1997, '342342347', 'Div III'),
(20, 4, 'NTA 3', 'Arusha Secondary', 'S-1234', 'NECTA', '3234234234', 1990, '34234234', 'Div III');

-- --------------------------------------------------------

--
-- Table structure for table `registry`
--

CREATE TABLE `registry` (
  `id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `sex` varchar(15) DEFAULT NULL,
  `marital_status` varchar(15) DEFAULT NULL,
  `disability` tinyint(1) DEFAULT NULL,
  `disability_description` text,
  `postal_address` varchar(100) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `ward` varchar(50) DEFAULT NULL,
  `referral` varchar(100) DEFAULT NULL,
  `referral_other` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `registry`
--

INSERT INTO `registry` (`id`, `type`, `first_name`, `surname`, `middle_name`, `date_of_birth`, `place_of_birth`, `sex`, `marital_status`, `disability`, `disability_description`, `postal_address`, `phone_no`, `email`, `region`, `district`, `ward`, `referral`, `referral_other`) VALUES
(4, 'Enrollment', 'Rama', 'Maabad', 'Issa', '2021-05-05', 'Mbeya', 'Male', 'Married', 0, '', '13340 Arusha', '0987889899', 'lemajunior@hotmail.com', 'Arusha', 'Kyela', 'Mbulumbulu', 'Other', 'asdasdasasd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `education_background`
--
ALTER TABLE `education_background`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registry`
--
ALTER TABLE `registry`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `education_background`
--
ALTER TABLE `education_background`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `registry`
--
ALTER TABLE `registry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
