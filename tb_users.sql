-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 16 jan 2020 om 10:59
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
-- Tabelstructuur voor tabel `tb_users`
--

CREATE TABLE `tb_users` (
  `uuid` varchar(40) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `role` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(200) NOT NULL,
  `hash_date` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `tb_users`
--

INSERT INTO `tb_users` (`uuid`, `username`, `password`, `email`, `role`, `status`, `hash`, `hash_date`, `timestamp`) VALUES
('21a91163-1aed-4e34-aac9-9b821809d01e', 'eriksteens', '$2y$10$a2vhKYk1kvnTEpw8cKpIWOFqAYSd8FcdMr.9MfQMMdXYjntP6jCu2', 'erik@erik.nl', '1,5', 0, '59d5f7ab4d1f8ca995bd8ce38aff8df96a2c5c6adf31d082d3811c6b99ed927e', '2019-12-04 21:35:18', '2019-12-01 12:21:27'),
('786d8674-584a-476e-8791-77922b07894f', 'hayley', '$2y$10$O0ktxAluErlaFrRvggs0/.iRhAjWrSjJXpM.aA0JwT2AD7b2cf1qa', 'hayley@williams.com', '1', 1, '5044ae72796bda740863bb3489f05d37ded9a6d50709bb98d54a1cca162215af', '2019-12-10 06:17:17', '2019-12-03 20:40:06'),
('b9105af1-60f3-45c6-8e50-4da9cc8da1b5', 'jaimy', '$2y$10$eZ/tkmyzDtrgaysDBQq2dezPNas59KoxL1ZdIfb2d.i26.KsA2u/2', 'jaimybongers123@hotmail.com', '4', 1, '1f0a92476392f067f75085b511f0eeab2c82d11b8cd45370db5d5415d08bb63d', '2019-12-18 09:21:58', '2019-12-17 07:21:58');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
