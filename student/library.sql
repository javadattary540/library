-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 05, 2026 at 08:54 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first`, `last`, `username`, `password`, `email`, `contact`) VALUES
(1, 'javad', 'attar', 'javadattar', '$2y$10$0FvRRNtXGOKvWoG/fKHBGekAKLKVyGVorseJkRe9x4tm0ivVyvHt.', 'javad@gmail.com', '33424464');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `bid` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `authors` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `edition` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `department` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bid`, `name`, `authors`, `edition`, `status`, `quantity`, `department`) VALUES
(1, 'electronics', 'melta', '3rd', 'Availbale', 5, 'EEE'),
(3, 'alis', 'habkins', '2th', 'Available', 8, 'SFE'),
(8, 'اشنایی با حیوانات اهلی', 'موذنی', '1', 'Available', 20, 'فردوسی مشهد'),
(12, 'ارباب حلقه', 'jr talkin', '3rd', 'Available', 3, 'harvard');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comment` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`) VALUES
(20, 'کتاب مورد علاقه خود را توانستم پیدا کنم . متشکرم');

-- --------------------------------------------------------

--
-- Table structure for table `issue_book`
--

DROP TABLE IF EXISTS `issue_book`;
CREATE TABLE IF NOT EXISTS `issue_book` (
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `bid` int NOT NULL,
  `approve` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `issue` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `return` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_book`
--

INSERT INTO `issue_book` (`username`, `bid`, `approve`, `issue`, `return`) VALUES
('ali', 2, 'Yes', '2019-01-30', '2019-02-28'),
('ali', 3, '', '', ''),
('ali', 3, '', '', ''),
('ali', 2, '', '', ''),
('ali', 2, '', '', ''),
('ali', 3, '', '', ''),
('ali', 3, '', '', ''),
('ali', 2, '', '', ''),
('ali', 2, '', '', ''),
('ali', 2, '', '', ''),
('ali', 1, '', '', ''),
('ali', 12, '', '', ''),
('ali', 1, '', '', ''),
('ali', 1, '', '', ''),
('ali', 8, '', '', ''),
('ali', 3, '', '', ''),
('ali', 1, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `first` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `roll` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contact` int NOT NULL,
  `pic` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`first`, `last`, `username`, `password`, `roll`, `email`, `contact`, `pic`) VALUES
('ali', 'sohrabi', 'ali', '1234', 2, 'ali@gmail.com', 33422565, 'p.jpg'),
('gholi', 'ghloizade', 'gholi', '123456', 2, 'gholi@gmail.com', 33422121, 'p.jpg'),
('javad', 'attar', 'javadattary', '12737', 1, 'javadattar1364@gmail.com', 33424464, 'p.jpg'),
('saeed', 'mohammadi', 'saeed', '123', 2, 'saeed@gmail.com', 3385676, 'p.jpg'),
('fatemeh', 'golchin', 'fatemeh', '123', 2, 'faty@gmai.com', 33424464, 'p.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
