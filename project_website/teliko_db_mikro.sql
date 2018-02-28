-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 23 Μάη 2017 στις 20:07:36
-- Έκδοση διακομιστή: 5.6.17
-- Έκδοση PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Βάση δεδομένων: `teliko_db`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `o3#apt2006.dat`
--

CREATE TABLE IF NOT EXISTS `o3#apt2006.dat` (
  `date` varchar(15) DEFAULT NULL,
  `T01` varchar(25) DEFAULT NULL,
  `T02` varchar(25) DEFAULT NULL,
  `T03` varchar(25) DEFAULT NULL,
  `T04` varchar(25) DEFAULT NULL,
  `T05` varchar(25) DEFAULT NULL,
  `T06` varchar(25) DEFAULT NULL,
  `T07` varchar(25) DEFAULT NULL,
  `T08` varchar(25) DEFAULT NULL,
  `T09` varchar(25) DEFAULT NULL,
  `T10` varchar(25) DEFAULT NULL,
  `T11` varchar(25) DEFAULT NULL,
  `T12` varchar(25) DEFAULT NULL,
  `T13` varchar(25) DEFAULT NULL,
  `T14` varchar(25) DEFAULT NULL,
  `T15` varchar(25) DEFAULT NULL,
  `T16` varchar(25) DEFAULT NULL,
  `T17` varchar(25) DEFAULT NULL,
  `T18` varchar(25) DEFAULT NULL,
  `T19` varchar(25) DEFAULT NULL,
  `T20` varchar(25) DEFAULT NULL,
  `T21` varchar(25) DEFAULT NULL,
  `T22` varchar(25) DEFAULT NULL,
  `T23` varchar(25) DEFAULT NULL,
  `T24` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `rupos`
--

CREATE TABLE IF NOT EXISTS `rupos` (
  `year` int(5) NOT NULL,
  `type` varchar(6) NOT NULL,
  `stathmos_id` varchar(5) NOT NULL,
  `data` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `stathmos`
--

CREATE TABLE IF NOT EXISTS `stathmos` (
  `name` varchar(20) NOT NULL,
  `id` varchar(6) NOT NULL,
  `location` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(100) NOT NULL,
  `password` varchar(30) NOT NULL,
  `privileges` char(10) NOT NULL,
  `APIkey` varchar(200) NOT NULL,
  `requests_stathmoi` int(11) DEFAULT NULL,
  `requests_mesi_timi` int(11) DEFAULT NULL,
  `requests_apoluti_timi` int(11) DEFAULT NULL,
  PRIMARY KEY (`email`,`APIkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
