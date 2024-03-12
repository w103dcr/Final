-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2024 at 08:26 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epms`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `EID` int(5) NOT NULL,
  `hire_date` date NOT NULL,
  `LID` int(1) NOT NULL,
  `pay_rate` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`EID`, `hire_date`, `LID`, `pay_rate`) VALUES
(24657, '2024-03-13', 2, 'G2'),
(33577, '1986-03-12', 2, 'G3'),
(34311, '1986-03-12', 1, 'G3'),
(43367, '2023-11-14', 2, 'G2'),
(45196, '2015-08-11', 3, 'G7'),
(69620, '2023-02-05', 1, 'G6');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `LID` int(1) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`LID`, `city`) VALUES
(1, 'Chicago'),
(2, 'Dayton'),
(3, 'New Jersey');

-- --------------------------------------------------------

--
-- Table structure for table `pay_rate`
--

CREATE TABLE `pay_rate` (
  `pay_rate` varchar(10) NOT NULL,
  `scale` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pay_rate`
--

INSERT INTO `pay_rate` (`pay_rate`, `scale`) VALUES
('G5', '');

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `SSN` int(9) UNSIGNED ZEROFILL NOT NULL,
  `EID` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `sex` enum('M','F') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `birth_date` date NOT NULL,
  `contact_number` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `address` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`SSN`, `EID`, `first_name`, `last_name`, `sex`, `birth_date`, `contact_number`, `address`, `email`) VALUES
(325164756, 69620, 'Gary', 'Lemmings', 'M', '1982-05-24', 5553284587, '123 E. Normandy Rd.', 'Gary.Lemmings@gmail.com'),
(444444444, 24657, 'Testing', 'Testing', 'M', '2024-03-12', 5555555555, '123 N. Test St.', 'Testing@Testing.com'),
(684582157, 43367, 'Eugene', 'Yates', 'M', '1985-03-07', 5553641824, '3764 S. Miami St.', 'Eugene.Yates@gmail.com'),
(684845214, 45196, 'Sarah', 'Justyne', 'F', '1974-08-22', 5558716589, '43 W. Stewart St.', 'Sarah.Justyne@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_group` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `access_group`) VALUES
(1, 'Admin', '$2y$10$QUUNqi5WS8CvjIMKwwq0q.NCD2/DzWMna9GuS/Gtp6PHw/XuWu21m', 'admin'),
(2, 'User', '$2y$10$fqfcyj9DzNowhJOiHZHWuO1P3aqNUzSodg7akUm9muDSO704k.odC', 'viewer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`EID`),
  ADD KEY `fk_PR` (`pay_rate`),
  ADD KEY `fk_locations` (`LID`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`LID`);

--
-- Indexes for table `pay_rate`
--
ALTER TABLE `pay_rate`
  ADD PRIMARY KEY (`pay_rate`);

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`SSN`),
  ADD KEY `fk_EID` (`EID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
