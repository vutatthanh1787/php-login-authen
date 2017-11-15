-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: May 04, 2017 at 02:21 PM
-- Server version: 5.6.35-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `djkice_members`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(33) NOT NULL,
  `fullname` varchar(88) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(33) NOT NULL,
  `phoneserviceprovider` varchar(55) NOT NULL,
  `title` varchar(88) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '1' COMMENT 'active or non active account',
  `session` varchar(255) NOT NULL COMMENT 'login token session',
  `ipaddress` varchar(22) NOT NULL COMMENT 'WAN ip address',
  `groupid` int(2) NOT NULL DEFAULT '1' COMMENT 'primary group ID admin/noneadmin',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `password`, `phone`, `phoneserviceprovider`, `title`, `active`, `session`, `ipaddress`, `groupid`, `timestamp`) VALUES
(1, 'admin', 'Firstname Lastname', 'some@email.com', '$2y$12$lI9EGiWcF/p.bQOlCnCoZe5eGjFg2A8FG0Lp2EcFU13PwHR8xsW3O', '1234566756', 'AT&T', 'Master Account', 1, '1QNGianNDIwcPSyua8tDYHRz', '', 2, '2017-04-25 14:30:46'),
(14, 'ddgf', 'dfg', 'dfg', '$2y$12$3Xf5DmrXxrq4yNDmDPzOkudOiIHhlTBGHnEVW0qEvb9BEFILqeisy', '3453245', 'Consumer Cellular', 'dfg', 1, '', '', 1, '2017-04-07 20:07:23');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
