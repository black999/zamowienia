-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 19 Paź 2017, 22:06
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `baza_zamowienia`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dzialy`
--

CREATE TABLE IF NOT EXISTS `dzialy` (
  `IdDzial` int(11) NOT NULL AUTO_INCREMENT,
  `Nazwa` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IdDzial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `personel`
--

CREATE TABLE IF NOT EXISTS `personel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Imie` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Nazwisko` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `Login` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Haslo` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Dzial` int(11) NOT NULL,
  `uPrac` tinyint(1) NOT NULL,
  `uKier` tinyint(1) NOT NULL,
  `uZampub` tinyint(1) NOT NULL,
  `uKsieg` tinyint(1) NOT NULL,
  `uPrez` tinyint(1) NOT NULL,
  `uAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dzialy_id_fk` (`Dzial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `towary`
--

CREATE TABLE IF NOT EXISTS `towary` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `IdDzial` int(11) NOT NULL,
  `nazwa` text CHARACTER SET cp1250 COLLATE cp1250_polish_ci NOT NULL,
  `cenaZak` decimal(11,2) NOT NULL,
  `dostawca` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE IF NOT EXISTS `zamowienia` (
  `IdZamowienia` int(11) NOT NULL AUTO_INCREMENT,
  `Data` date NOT NULL,
  `Zamawiajacy` int(11) NOT NULL,
  `StatusZatw` tinyint(1) NOT NULL,
  `akcKier` tinyint(4) NOT NULL,
  `akcZam` tinyint(4) NOT NULL,
  `akcKsie` tinyint(4) NOT NULL,
  `akcPrez` tinyint(4) NOT NULL,
  `StatusReal` tinyint(1) NOT NULL,
  `Info` text COLLATE utf8_polish_ci NOT NULL,
  `koszt` varchar(10) COLLATE utf8_polish_ci NOT NULL,
  `kosztOpis` varchar(40) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`IdZamowienia`),
  KEY `Zamawiajacy` (`Zamawiajacy`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=107 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowieniatow`
--

CREATE TABLE IF NOT EXISTS `zamowieniatow` (
  `IdZamowieniaTow` int(11) NOT NULL AUTO_INCREMENT,
  `IdZam` int(11) NOT NULL,
  `Towar` int(11) NOT NULL,
  `Ilosc` int(11) NOT NULL,
  `CenaRealizacji` decimal(11,2) NOT NULL,
  PRIMARY KEY (`IdZamowieniaTow`),
  KEY `Towar` (`Towar`),
  KEY `IdZam` (`IdZam`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=353 ;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `personel`
--
ALTER TABLE `personel`
  ADD CONSTRAINT `dzialy_id_fk` FOREIGN KEY (`Dzial`) REFERENCES `dzialy` (`IdDzial`);

--
-- Ograniczenia dla tabeli `zamowieniatow`
--
ALTER TABLE `zamowieniatow`
  ADD CONSTRAINT `zamowieniatow_id_fk` FOREIGN KEY (`IdZam`) REFERENCES `zamowienia` (`IdZamowienia`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
