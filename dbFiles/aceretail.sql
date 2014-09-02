-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 24, 2014 at 06:25 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aceretail`
--
CREATE DATABASE IF NOT EXISTS `aceretail` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `aceretail`;

-- --------------------------------------------------------

--
-- Table structure for table `all_images`
--

CREATE TABLE IF NOT EXISTS `all_images` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `all_images`
--

INSERT INTO `all_images` (`id`, `name`, `pid`) VALUES
(1, 'productimages/1061691604.jpg', 8),
(7, 'productimages/-590338390.jpg', 3),
(8, 'productimages/349739556.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) NOT NULL,
  `c_desc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `c_name`, `c_desc`) VALUES
(1, 'Electronics', 'Electrical items'),
(2, 'Books', 'Study materials, story books etc..'),
(3, 'other', 'No specific category'),
(4, 'goodies', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hascategory`
--

CREATE TABLE IF NOT EXISTS `hascategory` (
  `cid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`cid`,`pid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hascategory`
--

INSERT INTO `hascategory` (`cid`, `pid`) VALUES
(2, 2),
(1, 3),
(1, 4),
(1, 5),
(3, 6),
(4, 7),
(3, 8);

-- --------------------------------------------------------

--
-- Table structure for table `hasprod`
--

CREATE TABLE IF NOT EXISTS `hasprod` (
  `pid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  PRIMARY KEY (`pid`,`oid`),
  KEY `oid` (`oid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hasprod`
--

INSERT INTO `hasprod` (`pid`, `oid`) VALUES
(2, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delv_date` date NOT NULL,
  `delv_addr` text NOT NULL,
  `o_date` datetime NOT NULL,
  `p_amt` int(11) NOT NULL DEFAULT '1',
  `net_charge` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `delv_date`, `delv_addr`, `o_date`, `p_amt`, `net_charge`) VALUES
(1, '0000-00-00', '222, Dihing, IITG', '2014-04-22 11:11:55', 1, 200),
(2, '2014-04-24', '', '2014-04-22 15:20:40', 1, 30000);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `oid` (`oid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `datetime`, `amount`, `oid`) VALUES
(1, '2014-04-22 11:48:56', 200, 1),
(2, '2014-04-22 15:22:17', 30000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_cost` float NOT NULL,
  `p_name` varchar(255) DEFAULT NULL,
  `p_desc` text NOT NULL,
  `ordered` int(11) NOT NULL DEFAULT '0',
  `sold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `p_cost`, `p_name`, `p_desc`, `ordered`, `sold`) VALUES
(2, 200, ' ab  ', 'Random Product', 1, 1),
(3, 500, 'Asus Charger', 'Laptop charger for Asus Laptops...', 0, 0),
(4, 1300, 'Nokia 1200', 'Nokia \n5MP\nMP3 Player\nBluetooth\n16gb expandable ', 0, 0),
(5, 30000, 'Samsung S3', '2gb Ram', 1, 1),
(6, 15000, 'Nokia N97', 'NOKIA', 0, 0),
(7, 90, 'chocs', 'It is used to catch all sorts of Pokemons save the legendary ones..\nfor now working\nkya bhai\nworking or not... lets see', 0, 0),
(8, 100, 'Pokeball-Normal', 'It is used to catch all sorts of Pokemons save the legendary ones..\nfor now working\nkya bhai\nworking or not... lets see', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `review` text,
  `rate` int(11) DEFAULT '1',
  PRIMARY KEY (`pid`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE IF NOT EXISTS `seller` (
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`pid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`uid`, `pid`) VALUES
(4, 2),
(4, 3),
(2, 4),
(13, 5),
(13, 6),
(14, 7),
(1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `uid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`uid`, `oid`) VALUES
(2, 2),
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webmail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `signup_date` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `activated` enum('0','1') DEFAULT '0',
  `ip` varchar(50) NOT NULL,
  `temp_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `webmail`, `password`, `signup_date`, `last_login`, `activated`, `ip`, `temp_code`) VALUES
(1, 'a.sarkar@iitg.ernet.in', '$1$c9FP7MiL$1vbMTYCXeomjXMK6VaR/G/', '2014-04-21 18:02:18', '2014-04-21 18:02:18', '1', '127.0.0.1', NULL),
(2, 'abhishek.sarkar@iitg.ernet.in', '$1$yM1.Tm0.$/sNC7x42xWOzVJx69RuvE0', '2014-04-21 18:10:22', '2014-04-21 18:10:22', '1', '127.0.0.1', NULL),
(3, 'a.kanwat@iitg.ernet.in', '$1$6SSn.DNx$v82wCkMhPfi9Knwd/tGy7/', '2014-04-21 18:13:51', '2014-04-21 18:13:51', '1', '127.0.0.1', NULL),
(4, 'a.kilaka@iitg.ernet.in', '$1$PFwGJV06$32NyES5f4IOKppMw2jfvC.', '2014-04-21 19:03:32', '2014-04-21 19:03:32', '1', '127.0.0.1', NULL),
(5, 'arpita.sarkar@iitg.ernet.in', '$1$reFSD45a$HCBDrQNSBpk9LvVO9/2DV1', '2014-04-21 19:16:53', '2014-04-21 19:16:53', '0', '127.0.0.1', NULL),
(6, 's.subramani@iitg.ernet.in', '$1$9a6BXwd0$gXP31hGNltiOrG3VBt.C//', '2014-04-21 19:22:05', '2014-04-21 19:22:05', '0', '127.0.0.1', NULL),
(7, 's.priyank@iitg.ernet.in', '$1$qwqbhuzg$A0xsYO9o.ff.GrkmeObZ5/', '2014-04-21 19:26:55', '2014-04-21 19:26:55', '0', '127.0.0.1', NULL),
(8, 'pikachu@iitg.ernet.in', '$1$MUACBTmq$bB6bhlNew0pcFQtllZ11d0', '2014-04-21 19:31:05', '2014-04-21 19:31:05', '0', '127.0.0.1', NULL),
(9, 'charizard@iitg.ernet.in', '$1$NVJOe7.m$gBn46PSlzQet0WPrF0JkO/', '2014-04-21 19:34:12', '2014-04-21 19:34:12', '0', '127.0.0.1', NULL),
(10, 'bulbasaur@iitg.ernet.in', '$1$7.OjOp9I$u85i.ssiDQind0suwYbv30', '2014-04-21 19:42:35', '2014-04-21 19:42:35', '0', '127.0.0.1', NULL),
(11, 'chikorita@iitg.ernet.in', '$1$6zxRoxPd$3BscvkYdbwwEtB1ZpsRCU/', '2014-04-21 19:45:24', '2014-04-21 19:45:24', '0', '127.0.0.1', NULL),
(12, 'squirtle@iitg.ernet.in', '$1$gwaszuFr$hp0p52jMi1TlV4.VW8SGq1', '2014-04-21 19:54:29', '2014-04-21 19:54:29', '0', '127.0.0.1', NULL),
(13, 'p.mamidi@iitg.ernet.in', '$1$5bJ/lPRD$P68i4bGaRuZjq7YA8PGob.', '2014-04-22 15:17:24', '2014-04-22 15:17:24', '1', '127.0.0.1', NULL),
(14, 'a.anupam@iitg.ernet.in', '$1$ZPuXEWLm$uHl.2aDv3RtlDI2Sdv4BF0', '2014-04-22 15:26:39', '2014-04-22 15:26:39', '1', '127.0.0.1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` text,
  `gender` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `phone`, `first_name`, `last_name`, `address`, `gender`) VALUES
(1, '8011218062', 'Abhishek', 'Sarkar', 'B-103, Umiam, IITG', 'm'),
(3, '8011218062', 'Ankit', 'Kanwat', '365, Kapili, IITG', 'm'),
(4, '8011218062', 'Ajay', 'Kilaka', '222, Dihing, IITG', 'm'),
(5, '8011218062', 'Arpita', 'Sarkar', 'C-103, Subansiri, IITG', 'f'),
(6, '8011218062', 'Siddhart', 'Subramani', '241, Dihing, IITG', 'm'),
(7, '8011218062', 'Priyank', 'Sharma', '261, Kapili, IITG', 'm'),
(8, '9531563272', 'Pika', 'Chu', 'jungle, IITG', 'f'),
(9, '9531563272', 'Chari', 'Zard', 'hills, IITg', 'm'),
(10, '9531563272', 'Bulba', 'Saur', 'jungle, IITG', 'm'),
(11, '9531563272', 'Chiko', 'Rita', 'jungle, IITG', 'm'),
(12, '9531563272', 'Squirt', 'Le', 'jungle, IITG', 'm'),
(13, '9531563272', 'Prasanth', 'Mamidi', 'MAnas, IITG', 'm'),
(14, '9090909090', 'anupam', 'agrawal', 'g909-iitg', 'm');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `all_images`
--
ALTER TABLE `all_images`
  ADD CONSTRAINT `all_images_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hascategory`
--
ALTER TABLE `hascategory`
  ADD CONSTRAINT `hascategory_ibfk_1` FOREIGN KEY (`cid`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `hascategory_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hasprod`
--
ALTER TABLE `hasprod`
  ADD CONSTRAINT `hasprod_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hasprod_ibfk_2` FOREIGN KEY (`oid`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `orders` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`id`);

--
-- Constraints for table `seller`
--
ALTER TABLE `seller`
  ADD CONSTRAINT `seller_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seller_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
