-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 03:02 PM
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
-- Database: `wb_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `ID` int(11) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `Barangay` varchar(50) NOT NULL,
  `City` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`ID`, `Street`, `Barangay`, `City`) VALUES
(1, ' Rafael Rabaya', 'Talisay', 'Cebu');

-- --------------------------------------------------------

--
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Address_ID` int(11) NOT NULL,
  `Contact_Number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`ID`, `User_ID`, `Address_ID`, `Contact_Number`) VALUES
(1, 2, 1, '094133322221');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` double NOT NULL,
  `Request` longtext NOT NULL,
  `OrderMadeDate` date NOT NULL,
  `Status` enum('Pending','Ongoing','Complete') NOT NULL DEFAULT 'Pending',
  `Payment_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Address_ID` int(11) NOT NULL DEFAULT 1,
  `Total_Payment` double NOT NULL,
  `Total_Quantity` int(11) NOT NULL,
  `PaymentStartedDate` date NOT NULL,
  `Remarks` enum('Ongoing','Completed','Cancelled') NOT NULL DEFAULT 'Ongoing',
  `PaymentCompletedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Price` double NOT NULL,
  `Type` enum('Pork','Chicken','Others') NOT NULL DEFAULT 'Pork',
  `Img_Dir` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `Name`, `Price`, `Type`, `Img_Dir`) VALUES
(1, 'Pork BBQ', 10, 'Pork', 'PorkBBQ.png'),
(2, 'Pork Belly', 70, 'Pork', 'PorkBelly.png'),
(3, 'Chorizo', 35, 'Pork', 'Chorizo.png'),
(4, 'Skinless', 25, 'Pork', 'Skinless.png'),
(5, 'Hotdog', 15, 'Pork', 'Hotdog.png'),
(6, 'Atay Baboy', 30, 'Pork', 'AtayBaboy.png'),
(7, 'Paa', 90, 'Chicken', 'Paa.png'),
(8, 'Petso', 100, 'Chicken', 'Petso.png'),
(9, 'Pako', 80, 'Chicken', 'Pako.png'),
(10, 'Chicken Skin', 40, 'Chicken', 'ChickenSkin.png'),
(11, 'Backbones', 40, 'Chicken', 'Backbones.png'),
(12, 'Atay Manok', 30, 'Chicken', 'AtayManok.png'),
(13, 'Tinae', 15, 'Chicken', 'Tinae.png'),
(14, 'Puso', 10, 'Others', 'Puso.png'),
(15, 'Mineral Water', 20, 'Others', 'MineralWater.png'),
(16, 'Soft Drinks(8 oz)', 20, 'Others', 'SoftDrinks8.png'),
(17, 'Soft Drinks(12 oz)', 30, 'Others', 'SoftDrinks12.png');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `Rating_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Rating` int(5) NOT NULL,
  `Comment` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(255) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `User_Type` enum('Guest','Member','Admin','Cashier') NOT NULL DEFAULT 'Guest'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `First_Name`, `Last_Name`, `Email`, `Password`, `User_Type`) VALUES
(1, 'Admin1234', NULL, 'admin1234@gmail.com', '751cb3f4aa17c36186f4856c8982bf27', 'Admin'),
(2, 'Cashier1234', NULL, 'cashier1234@gmail.com', '37f113e732e039d9f9a5e9570b02d290', 'Cashier'),
(3, 'Customer', '1234', 'customer1234@gmail.com', 'ccef020fff1d8fe0cf4333d8ef6fd74a', 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`Rating_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `Rating_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
