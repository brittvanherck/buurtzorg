-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 16 jan 2020 om 11:02
-- Serverversie: 10.1.39-MariaDB
-- PHP-versie: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pagebuilder24`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tb_soll`
--

CREATE TABLE `tb_soll` (
  `naamid` varchar(25) NOT NULL,
  `naam` varchar(25) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `gebdatum` date NOT NULL,
  `mail` varchar(100) NOT NULL,
  `vac_id` varchar(25) NOT NULL,
  `status` int(11) NOT NULL,
  `punten` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
