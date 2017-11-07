-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 07, 2017 at 02:20 AM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projetphps3`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `username` varchar(254) NOT NULL,
  `image_name` varchar(254) NOT NULL,
  PRIMARY KEY (`username`,`image_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `image_name` varchar(254) NOT NULL,
  `price` float NOT NULL,
  `description` varchar(256) NOT NULL,
  PRIMARY KEY (`image_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `image_keyword`
--

DROP TABLE IF EXISTS `image_keyword`;
CREATE TABLE IF NOT EXISTS `image_keyword` (
  `image_name` varchar(256) NOT NULL,
  `keyword_name` varchar(16) NOT NULL,
  PRIMARY KEY (`image_name`,`keyword_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

DROP TABLE IF EXISTS `keyword`;
CREATE TABLE IF NOT EXISTS `keyword` (
  `keyword_name` varchar(16) NOT NULL,
  PRIMARY KEY (`keyword_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(16) NOT NULL,
  `mail` varchar(256) DEFAULT NULL,
  `password` varchar(254) NOT NULL,
  `admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `mail`, `password`, `admin`) VALUES
('Balthov60', 'Balthov60@gmail.com', 'd82088cc541fbbd332c10fe68fecdd34', 0),
('root', 'root@mail.com', '21232f297a57a5a743894a0e4a801fc3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_image`
--

DROP TABLE IF EXISTS `user_image`;
CREATE TABLE IF NOT EXISTS `user_image` (
  `username` varchar(16) NOT NULL,
  `image_name` varchar(254) NOT NULL,
  PRIMARY KEY (`username`,`image_name`) USING BTREE,
  KEY `FK_ID_IMAGE` (`image_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
