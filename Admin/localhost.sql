-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mag 31, 2021 alle 15:07
-- Versione del server: 8.0.21
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_topmusic`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `canzoni`
--

CREATE TABLE IF NOT EXISTS `canzoni` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `NomeCanzone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Autore` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `IMG` varchar(50) NOT NULL,
  `VID` varchar(50) DEFAULT NULL,
  `fkUser` int DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=98 ;

--
-- Dump dei dati per la tabella `canzoni`
--

INSERT INTO `canzoni` (`Id`, `NomeCanzone`, `Autore`, `IMG`, `VID`, `fkUser`) VALUES
(1, 'Gatti', 'Pop Smoke & Travis Scott', '5.jpg', 'pop.gatti.mp4', 18),
(2, 'Without Me', 'Eminem', '1.jpg', 'pop.withoutme.mp4', 18),
(3, 'Yummy', 'Justin Bieber', '3.png', 'pop.yummy.mp4', 18),
(5, 'Sempre Me', 'Ghali', '4.jpg', 'pop.sempreme.mp4', 18),
(7, 'The Bigger Picture', 'Lil Baby', '11.jpg', 'pop.thebiggerpicture.mp4', 18),
(8, 'Calling My Phone', 'Lil Tjay', '6.jpg', 'pop.callingmyphone.mp4', 18),
(9, 'Counting Stars', 'OneRepublic', '12.png', 'pop.countingstars.mp4', 18),
(12, 'Here', 'Alessia Cara', '605606e2c6ae66.03843631.jpg', '605606e2c75ad0.73141070.mp4', 18),
(13, 'Snowman', 'Sia', '60560a708c3164.51803630.jpg', '60560a708cbc14.37941945.mp4', 18),
(16, 'Highest In The Room', 'Travis Scott', '605610bc518e22.33613672.jpg', '605610bc51ef53.32895585.mp4', 18),
(19, 'This Is America', 'Childish Gambino', '6056445f661243.02730460.jpg', '6056445f666546.75764389.mp4', 18),
(22, 'Sicko Mode', 'Travis Scott & Drake', '605799a0777a52.90250111.jpg', '605799a077cca5.21958501.mp4', 18),
(37, 'Friday ', 'Riton & Nightcrawlers', '605af1c1113770.55139207.jpeg', '6059f4f6332504.30529870.mp4', 18),
(55, 'TKN', 'ROSALÍA & Travis Scott', '605b114b4e2d28.43367022.jpg', '605b114b511fc4.93648596.mp4', 18),
(56, 'Something Just Like This', 'The Chainsmokers & Coldplay ', '605b15b0a3d2b3.23302192.png', '605b15b0a63101.14222026.mp4', 17),
(57, 'Why Do You Lie To Me ', 'Topic, A7S & Lil Baby', '605b177acf7802.71168176.jpg', '605b177ad22174.57067293.mp4', 17),
(59, 'Whoopty', 'CJ', '605db033c55d39.58548303.jpg', '605db033c754d4.23247233.mp4', 18),
(62, 'What You Know Bout Love', 'Pop Smoke', '505e24a7051d30.04142525.jpg', '605e24a70762c1.42360397.mp4', 17),
(68, 'Godzilla', 'Eminem  &  Juice WRLD', '609ed2097abd45.00011672.jpeg', '609ed2097d2a07.13570713.mp4', 18),
(83, 'Reminds Me Of You', 'Juice WRLD & The Kid Laroi', '609abf211cfde5.35684182.jpg', '609abf211fd9a9.24598360.mp4', 28),
(84, 'Dubai', 'Rondodasosa', '60893806d0aa10.07388020.jpg', '60893806d2cf42.80087095.mp4', 28),
(90, 'Up&Up', 'Coldplay', '60a396d32111e7.90839769.jpg', '60a396d322f572.51009435.mp4', 28),
(93, 'Ambition Az A Ridah', '2Pac', '60a4e8610c7362.39380322.jpg', '60a4e8610f1163.52370796.mp3', 30),
(96, 'Aloha', 'ElGrandeToto', '60b133ff18aa70.86205478.jpg', '60b133ff1aa6b7.85328051.mp3', 18),
(97, 'Musica Leggerissima', 'Colapesce', '60b35f2b939eb0.58703097.jpg', '60b35f2b954127.12390924.m4a', 32);

-- --------------------------------------------------------

--
-- Struttura della tabella `canzoniPreferite`
--

CREATE TABLE IF NOT EXISTS `canzoniPreferite` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `fkCanzone` int NOT NULL,
  `fkUtente` int NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=97 ;

--
-- Dump dei dati per la tabella `canzoniPreferite`
--

INSERT INTO `canzoniPreferite` (`Id`, `fkCanzone`, `fkUtente`) VALUES
(95, 97, 18),
(94, 22, 18),
(93, 37, 18);

-- --------------------------------------------------------

--
-- Struttura della tabella `segnalazioni`
--

CREATE TABLE IF NOT EXISTS `segnalazioni` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fkCanzone` int NOT NULL,
  `descrizione` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=96 ;

--
-- Dump dei dati per la tabella `segnalazioni`
--

INSERT INTO `segnalazioni` (`id`, `fkCanzone`, `descrizione`) VALUES
(91, 97, 'dl.fsdòflsdfòsdfl'),
(92, 97, 'errrer'),
(93, 97, 'efdf'),
(94, 97, 'xzxzx'),
(95, 97, 'ddsdsdsd');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(60) NOT NULL,
  `Data` varchar(20) DEFAULT NULL,
  `Ora` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=33 ;

--
-- Dump dei dati per la tabella `utenti`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
