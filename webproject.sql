-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2024 at 09:51 PM
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
-- Database: `webproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `carimages`
--

CREATE TABLE `carimages` (
  `CarID` int(11) NOT NULL,
  `ImageFilename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `CarID` int(11) NOT NULL,
  `ReferenceNumber` varchar(20) NOT NULL,
  `Model` varchar(50) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Make` varchar(50) NOT NULL,
  `RegistrationYear` year(4) NOT NULL,
  `Color` varchar(20) NOT NULL,
  `Description` text NOT NULL,
  `PricePerDay` decimal(10,2) NOT NULL,
  `CapacityPeople` int(11) NOT NULL,
  `CapacitySuitcases` int(11) NOT NULL,
  `FuelType` varchar(20) NOT NULL,
  `AvgConsumption` decimal(5,2) NOT NULL,
  `Horsepower` int(11) NOT NULL,
  `Length` decimal(5,2) NOT NULL,
  `Width` decimal(5,2) NOT NULL,
  `GearType` varchar(10) NOT NULL,
  `Conditions` text DEFAULT NULL,
  `Restrictions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customeraccounts`
--

CREATE TABLE `customeraccounts` (
  `AccountID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `Username` varchar(13) NOT NULL,
  `Password` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `NationalID` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telephone` varchar(20) NOT NULL,
  `CreditCardNumber` varchar(20) NOT NULL,
  `CreditCardExpiration` date NOT NULL,
  `CreditCardName` varchar(100) NOT NULL,
  `CreditCardBank` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `InvoiceID` int(11) NOT NULL,
  `RentalID` int(11) NOT NULL,
  `InvoiceDate` date NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `CreditCardNumber` varchar(20) NOT NULL,
  `CreditCardExpiration` date NOT NULL,
  `CreditCardHolderName` varchar(100) NOT NULL,
  `CreditCardBank` varchar(100) NOT NULL,
  `CreditCardType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `LocationID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Telephone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `ManagerID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `RentalID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `CarID` int(11) NOT NULL,
  `RentalDate` datetime NOT NULL,
  `ReturnDate` datetime NOT NULL,
  `TotalCost` decimal(10,2) NOT NULL,
  `PickupLocationID` int(11) NOT NULL,
  `ReturnLocationID` int(11) NOT NULL,
  `SpecialRequirements` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carimages`
--
ALTER TABLE `carimages`
  ADD PRIMARY KEY (`CarID`,`ImageFilename`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`CarID`);

--
-- Indexes for table `customeraccounts`
--
ALTER TABLE `customeraccounts`
  ADD PRIMARY KEY (`AccountID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`InvoiceID`),
  ADD KEY `RentalID` (`RentalID`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`LocationID`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`ManagerID`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`RentalID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `CarID` (`CarID`),
  ADD KEY `PickupLocationID` (`PickupLocationID`),
  ADD KEY `ReturnLocationID` (`ReturnLocationID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `CarID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customeraccounts`
--
ALTER TABLE `customeraccounts`
  MODIFY `AccountID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `LocationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `ManagerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `RentalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carimages`
--
ALTER TABLE `carimages`
  ADD CONSTRAINT `carimages_ibfk_1` FOREIGN KEY (`CarID`) REFERENCES `cars` (`CarID`);

--
-- Constraints for table `customeraccounts`
--
ALTER TABLE `customeraccounts`
  ADD CONSTRAINT `customeraccounts_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`RentalID`) REFERENCES `rentals` (`RentalID`);

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`),
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`CarID`) REFERENCES `cars` (`CarID`),
  ADD CONSTRAINT `rentals_ibfk_3` FOREIGN KEY (`PickupLocationID`) REFERENCES `locations` (`LocationID`),
  ADD CONSTRAINT `rentals_ibfk_4` FOREIGN KEY (`ReturnLocationID`) REFERENCES `locations` (`LocationID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
