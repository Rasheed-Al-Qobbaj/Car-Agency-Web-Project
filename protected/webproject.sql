-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2024 at 12:07 PM
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

--
-- Dumping data for table `carimages`
--

INSERT INTO `carimages` (`CarID`, `ImageFilename`) VALUES
(5, 'car5img1.jpeg'),
(5, 'car5img2.jpeg'),
(5, 'car5img3.jpeg'),
(6, 'car6img1.jpeg'),
(6, 'car6img2.jpeg'),
(6, 'car6img3.jpeg'),
(7, 'car7img1.jpeg'),
(7, 'car7img2.jpeg'),
(7, 'car7img3.jpeg'),
(9, 'car9img1.jpeg'),
(9, 'car9img2.jpeg'),
(9, 'car9img3.png');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `CarID` int(11) NOT NULL,
  `CarState` varchar(250) NOT NULL DEFAULT 'Available ',
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

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`CarID`, `CarState`, `ReferenceNumber`, `Model`, `Type`, `Make`, `RegistrationYear`, `Color`, `Description`, `PricePerDay`, `CapacityPeople`, `CapacitySuitcases`, `FuelType`, `AvgConsumption`, `Horsepower`, `Length`, `Width`, `GearType`, `Conditions`, `Restrictions`) VALUES
(5, 'Available', '23456', 'XC90', 'SUV', 'Volvo', '2020', 'White', 'Luxurious and spacious, perfect for long drives.', 150.00, 7, 4, 'diesel', 7.00, 235, 5.00, 2.00, 'automatic', '', 'Driver must have a clean driving record.'),
(6, 'Rented', '34567', 'Golf', 'Hatchback', 'VW', '2019', 'Blue', 'Compact, efficient, and great for city driving.', 90.00, 5, 2, 'electric', 0.00, 150, 4.00, 1.00, 'manual', '', 'Charge point access required.'),
(7, 'Rented', '12345', 'Series 3', 'Sedan', 'BMW', '2018', 'Black', 'Well-maintained, low mileage, and fully serviced.', 120.00, 5, 3, 'petrol', 6.00, 255, 4.00, 1.00, 'automatic', '', 'No smoking in the car.'),
(9, 'Damaged', '76543', 'Sportage', 'SUV', 'KIA', '2020', 'White', 'Spacious interior, perfect for long drives.', 90.00, 5, 4, 'diesel', 7.00, 185, 4.00, 1.00, 'manual', '', 'No pets allowed.');

-- --------------------------------------------------------

--
-- Table structure for table `customeraccounts`
--

CREATE TABLE `customeraccounts` (
  `AccountID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `Username` varchar(13) NOT NULL,
  `Password` varchar(12) NOT NULL,
  `ManagerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customeraccounts`
--

INSERT INTO `customeraccounts` (`AccountID`, `CustomerID`, `Username`, `Password`, `ManagerID`) VALUES
(4, 4, 'rashidsrq', '12345678', NULL),
(5, NULL, 'admin', 'root', 1);

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

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `Name`, `Address`, `DateOfBirth`, `NationalID`, `Email`, `Telephone`, `CreditCardNumber`, `CreditCardExpiration`, `CreditCardName`, `CreditCardBank`) VALUES
(4, 'Rasheed Alqobbaj', '1 alwakalat, Ramallah, Palestine', '2024-06-21', '1202474', 'rasheedsrq@gmail.com', '0599767544', '1', '2024-06-21', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `InvoiceID` int(11) NOT NULL,
  `RentalID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `InvoiceDate` date NOT NULL,
  `TotalAmount` decimal(10,2) NOT NULL,
  `CreditCardType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`InvoiceID`, `RentalID`, `CustomerID`, `InvoiceDate`, `TotalAmount`, `CreditCardType`) VALUES
(1, 1, 4, '2024-06-22', 280.00, 'Mastercard'),
(2, 2, 4, '2024-06-22', 360.00, 'Visa'),
(3, 3, 4, '2024-06-22', 280.00, 'Visa'),
(4, 4, 4, '2024-06-22', 10.00, 'Visa');

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

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`LocationID`, `Name`, `Address`, `Telephone`) VALUES
(1, 'Birzeit', '1 Abo-Jazar, Ramallah, Palestine', '0599767545'),
(2, 'House', '123 Alwakalat, Ramallah, Palestine', '0599767546'),
(4, 'Warehouse', '7665 Manara, Ramallah, Palestine', '0599767547');

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

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`ManagerID`, `Username`, `Password`, `PhoneNumber`) VALUES
(1, 'admin', 'root', '0599767544');

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
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`RentalID`, `CustomerID`, `CarID`, `RentalDate`, `ReturnDate`, `TotalCost`, `PickupLocationID`, `ReturnLocationID`, `SpecialRequirements`) VALUES
(1, 4, 6, '2024-06-22 00:00:00', '2024-06-25 00:00:00', 280.00, 2, 4, 'Child Seat'),
(2, 4, 7, '2024-06-27 00:00:00', '2024-06-30 00:00:00', 360.00, 2, 1, ''),
(3, 4, 9, '2024-06-16 00:00:00', '2024-06-19 00:00:00', 280.00, 1, 4, 'Child Seat'),
(4, 4, 5, '2024-06-22 00:00:00', '2024-06-22 00:00:00', 10.00, 1, 1, 'Child Seat');

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
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `ManagerID` (`ManagerID`);

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
  ADD KEY `RentalID` (`RentalID`),
  ADD KEY `CustomerID` (`CustomerID`);

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
  MODIFY `CarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customeraccounts`
--
ALTER TABLE `customeraccounts`
  MODIFY `AccountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `LocationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `ManagerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `RentalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `customeraccounts_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`),
  ADD CONSTRAINT `customeraccounts_ibfk_2` FOREIGN KEY (`ManagerID`) REFERENCES `managers` (`ManagerID`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `CustomerID` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`),
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
