-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Czas generowania: 13 Gru 2024, 20:29
-- Wersja serwera: 5.7.24
-- Wersja PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `modaliza`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `articles`
--

CREATE TABLE `articles` (
  `id_art` int(10) NOT NULL,
  `ID_STRIPE` varchar(150) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `quantite` int(10) NOT NULL,
  `prix` double NOT NULL,
  `url_photo` varchar(225) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `articles`
--

INSERT INTO `articles` (`id_art`, `ID_STRIPE`, `nom`, `quantite`, `prix`, `url_photo`, `description`) VALUES
(1, 'prod_RJ6M5eXiY2ew08', 'Top', 8, 50, 'top.jpg', 'sans manches, longueur crop, coupe slim'),
(2, 'prod_RJ6QFArLr1ESJK', 'Chemise', 0, 60, 'chemise.jpg', ' blanche, longueur crop, oversize'),
(3, 'prod_RJ6RRxhYgRvC34', 'Veste', 8, 100, 'veste.jpg', 'oversize, marron, élégant ');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `clients`
--

CREATE TABLE `clients` (
  `id_client` int(10) NOT NULL,
  `ID_STRIPE` varchar(255) DEFAULT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse` text NOT NULL,
  `numero` text NOT NULL,
  `mail` varchar(100) NOT NULL,
  `mdp` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `clients`
--

INSERT INTO `clients` (`id_client`, `ID_STRIPE`, `nom`, `prenom`, `adresse`, `numero`, `mail`, `mdp`) VALUES
(6, 'cus_RLjPNtjWXcJyqg', 'zar', 'ghs', 'france', '12345678', 'dfhdfhd@gmail.com', '$2y$10$Ub9XSgu59NkCc8V0GAU5EO.c1GmKREEh2pczrusGDxA4fMsU2s1be'),
(7, 'cus_RLjPhMXLIT6JRw', 'Samuilava', 'Yelizaveta', 'montpellier', '00000000000', 'lizasamujlova01@gmail.com', '$2y$10$XKiNLE7MyLC4ETpMmHPijuVt4k/HHFbAhKskMwzNnltp.HIoKq/ky'),
(8, 'cus_RLjPAjAmadU0wf', 'vieville', 'maelle', '5 rue du piquepoult', '0698413615', 'maellevieville02@gmail.com', '$2y$10$CY.RwDZpZ1RVd76UJ1k.X.7LNhISV70NBPRneo.YDqJoVSdzAm46a'),
(12, 'cus_RLjPp9cHpzDXuX', 'Samuilav', 'Yelizaveta', ' de villefranche', '0749171751', 'li@gmail.com', '$2y$10$ySkSLsx21e4.bUxnsVSsyuUbeVa603XV4spH42SnMgxwypiQ8soKa'),
(14, 'cus_RLjPyaVA8jSKyw', 'Se', 'Ye', '15 rue de villefranche', '0749171751', 'li1@gmail.com', '$2y$10$eR5I6sHDaKRU3JSaDQlIhuD2BGV5aNjNYn1KPFqN6h0bK.kM/vVeG'),
(16, 'cus_RLjPCNouyDv3i4', 'li', 'moi', '15 r', '0234568487', 'li1234@gmail.com', '$2y$10$T6pBS.H1ZuVE3XiOiLZmq.JciuNWGtwUAx78XLhtYT0UQ2qLp5qJ6'),
(17, 'cus_RLjPuM8UuDTCks', 'LUI', 'LUI', 'LUI', '123456789', 'lui@gmail.com', '$2y$10$6rPxVxtRlNxjs8z6Zr4ZguRUeWFasQ4KfV8lrg/41oUl3tqIzk1/m'),
(21, 'cus_RLjPH40ZsS0BRB', 'Samuilava', 'Yelizaveta', '15 rue de villefranche', '0749171751', 'liza@gmail.com', '$2y$10$.NfgA3z1OkBa25GRTvEcReJCsDNUVFPoWLkadgtlJux82aHs4O6ga'),
(22, 'cus_RLjPiubVM2QVYS', 'SamuilavaL', 'Yelizaveta', '15 rue de villefranche', '0749171751', 'liz12345678@gmail.com', '$2y$10$vDK5mPQP9hdjpOOBUM13FehjzVJc4uKWSA.48fp8vs0JaPY5Mb1zC'),
(23, 'cus_RLjPMJn6uvki3C', 'SamuilavaL', 'Yelizaveta', '15 rue de villefranche', '0749171751', 'liz12345678!@gmail.com', '$2y$10$O.dpyAeADXRHFLaNlc/5IO9UY8CTt3blug9gk6RLuCk7XnFU.QEFu'),
(24, 'cus_RLjPcHhEo5Cfcp', 'Samuilava', 'Yelizaveta', 'fr', '0749171751', 'lizasam1@gmail.com', '$2y$10$/Fxz6dvww0H29EWmC1bY7eF7oLZ/Wan4YYUJIuAl30biAH6SJ1/CW'),
(25, 'cus_RLjPgDNenhwcb5', 'anna', 'anna', 'espagne', '1234567890', 'anna@gmail.com', '$2y$10$WXUbyAjlH9zjEDqldbMUh.ZzVM1VgP6XvAfVVWsyEUAAhAOrK4d.G'),
(26, 'cus_RLjPqxAHod948B', 'Nom', 'Prenom', 'adresse', '1234567890', 'moi@gmail.com', '$2y$10$3Vfl0JIunijpcSr19a6gteeO35nnWkqxZmZE0HzaM5drw3uMiFkRG'),
(27, 'cus_RLjPuWI0Q1EebD', 'Moi', 'Moi', 'Moi', '0749171751', 'moimoi@gmail.com', '$2y$10$2sF6XpMy76fXCll5COnO4Ob4mnGzq9pfmbrFL3tmKNicskgNdfo36'),
(28, 'cus_RLjPpnltTCVVdh', 'x', 'hlidcs', 'jzlx', '1234567890', 'Liza1@gmail.com', '$2y$10$R3.MHEjXAtPrEh9O/4uiVeX30LzHafeh38gOq4izui2qhC2G7SIBC'),
(29, 'cus_RLjPGU8QBcgEKf', 'Samuilava', 'Yelizaveta', '15 rue de villefranche', '0749171751', 'qwerty@gmail.com', '$2y$10$IL/gzUS6Ife6ZxUvKxKK9u/Fv/2Qr18VG1mY180d3n0avYJlBFReu'),
(30, 'cus_RLjP7ZVYIFe1ns', 'Samuilava', 'Yelizaveta', '15 rue de villefranche', '0749171751', 'wert@gmail.com', '$2y$10$7UnlPKYPBFgeisQ109R/ruoicqIZ4AgAf0AN/slo2R0dEj3o2p7Sm'),
(31, 'cus_RLjPkFmqodznHW', 'Samuilava', 'Yelizaveta', '15 rue de villefranche', '0749171751', 'nmmnmnm@gmail.com', '$2y$10$FWZAUaXx6XIBUYn0aTufUOe4XWPt4O/RFO.EbY.MLlCGkSO2MyKpu'),
(32, 'cus_ROJvX9W6EbZ8ak', 'Maria', 'Maria', '14 rue', '1234567890', 'maria@gmail.com', '$2y$10$c5wo.Juj2ma5LNFVL7HKvumjan0X2X1foIUhJrqtdHTWlQJ60gkTW'),
(33, NULL, 'Alex', 'Alex', '15 rue', '1234567890', 'alex@gmail.com', '$2y$10$eGUBD0YHwl2adwP5NCseE.KTaZ7SGOS7NlZr9n1By4vVyPc9Ppfhu'),
(34, 'cus_ROLqsnAgBkgooE', 'Lera', 'Lera', 'rue de', '1234567890', 'lera@gmail.com', '$2y$10$8PQcgkuG7IQKkg/kZc4NXujvS6l9eP8eZeoUY4mEQb9WBS5vOftIy'),
(35, NULL, 'Emma', 'Emma', 'rue de emma', '1234567890', 'emma@gmail.com', '$2y$10$6OKYEIJrZQ9P1.qGoC9rZuV5EpVVL3pRwDZZfe7d3SQuuxvrNKQ8G'),
(36, NULL, 'Kate', 'Kate', 'rue de kate', '1234567890', 'kate@gmail.com', '$2y$10$U0DCfscUb6WKHyhHUnMda.l68cgMDl0X9WlmcBi3CYZGl/0sGAb26'),
(37, NULL, 'Max', 'Max', 'rue de Max', '1234567890', 'max@gmail.com', '$2y$10$s6ltLC5STMFS.ZoPCBKa3uYiLon2IsXlqZBSYYb8gqf2kf90kRRcK'),
(38, NULL, 'Nina', 'Nina', 'rue de ina', '1234567890', 'nina@gmail.com', '$2y$10$s8SpAWWzi3eJsP71Ye9Y2uyyV/RPImmQFqpgr3i8.6q.NbDz73LQe'),
(39, NULL, 'Eliza', 'Eliza', 'rue', '1234567890', 'eliza@gmail.com', '$2y$10$rq308O1yMqoRc55dvoTxo.b7SVPydFgnOn6/l9F2zcGoDUrjBK5GC'),
(40, NULL, 'Clara', 'Clara', 'rue de clara', '1234567890', 'clara@gmail.com', '$2y$10$FaeTdGIYcCqvdFJA.WYIK.apagQWwiVCa/2fDIKafHhI1853Q/jUS'),
(41, NULL, 'Mark', 'Mark', 'rue de', '1234567890', 'mark@gmail.com', '$2y$10$gbZ8pn5M1v.oVengPkMs.OjzLHwgARPAZ.leyeEiqHJ0Ybtxld3EK'),
(42, 'cus_ROMKJGbMnlXxFp', 'Karl', 'Karl', 'rue', '1234567890', 'karl@gmail.com', '$2y$10$L8k1mlDasdEmIW6PVNgPqO8vgqEsPa0q8FnU5WbktaQXXYddhRQ82');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `id_art` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `envoi` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `commandes`
--

INSERT INTO `commandes` (`id_commande`, `id_art`, `id_client`, `quantite`, `envoi`) VALUES
(1, 3, 16, 4, 0),
(2, 1, 16, 1, 0),
(3, 2, 16, 1, 0),
(4, 2, 16, 1, 0),
(5, 3, 16, 1, 0),
(6, 3, 16, 1, 0),
(7, 2, 16, 1, 0),
(8, 2, 16, 1, 0),
(9, 3, 16, 1, 0),
(10, 2, 16, 2, 0),
(11, 1, 16, 1, 0),
(12, 1, 16, 1, 0),
(13, 3, 7, 1, 0),
(14, 3, 7, 1, 0),
(15, 2, 25, 1, 0),
(16, 2, 7, 1, 0),
(17, 2, 31, 1, 0),
(18, 1, 25, 1, 0),
(19, 2, 25, 1, 0),
(20, 2, 25, 1, 0),
(21, 1, 25, 1, 0),
(22, 1, 25, 1, 0),
(23, 2, 25, 1, 0),
(24, 2, 25, 1, 0),
(25, 3, 25, 1, 0),
(26, 3, 25, 1, 0),
(27, 3, 25, 1, 0),
(28, 3, 25, 1, 0),
(29, 2, 25, 1, 0),
(30, 3, 25, 1, 0),
(31, 3, 25, 1, 0),
(32, 3, 25, 1, 0),
(33, 3, 25, 1, 0),
(34, 2, 25, 1, 0),
(35, 3, 25, 1, 0),
(36, 3, 25, 1, 0),
(37, 3, 25, 1, 0),
(38, 2, 25, 1, 0),
(39, 2, 25, 1, 0),
(40, 2, 25, 1, 0),
(41, 2, 25, 1, 0),
(42, 1, 32, 1, 0),
(43, 2, 34, 16, 0),
(44, 2, 42, 4, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_enregistrement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `message`
--

INSERT INTO `message` (`id`, `message`, `date_enregistrement`) VALUES
(1, 'salut', '2024-12-11 19:43:33'),
(2, 'bonjour', '2024-12-11 19:44:15'),
(4, 'salut', '2024-12-11 19:52:12'),
(5, 'salut', '2024-12-13 10:44:46'),
(6, 'salut', '2024-12-13 10:44:46'),
(7, 'salut', '2024-12-13 10:44:47'),
(8, 'salut', '2024-12-13 10:44:47'),
(9, 'salut', '2024-12-13 10:44:52'),
(10, 'ca va?', '2024-12-13 10:52:37'),
(11, 'et toi?', '2024-12-13 10:52:55'),
(12, 'oui et toi?', '2024-12-13 10:53:46'),
(13, 'tu fais quoi?', '2024-12-13 10:54:11'),
(14, 'rien et toi?', '2024-12-13 10:54:21'),
(15, 'coucou', '2024-12-13 11:27:04'),
(16, 'bonjour', '2024-12-13 11:27:44'),
(17, 'bonjour a toi aussi!', '2024-12-13 11:28:00'),
(18, 'salut', '2024-12-13 11:44:22'),
(19, 'salut', '2024-12-13 11:44:31'),
(20, 'salut', '2024-12-13 13:19:20'),
(21, 'salut', '2024-12-13 13:21:17'),
(22, 'ca va?', '2024-12-13 13:21:26'),
(23, 'salut', '2024-12-13 13:21:45'),
(24, 'salut mark!!!!!', '2024-12-13 14:14:54'),
(25, 'salut mark!!!!!', '2024-12-13 14:14:58'),
(26, 'salut anna!!!!! :)', '2024-12-13 14:15:14');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id_art`);

--
-- Indeksy dla tabeli `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- Indeksy dla tabeli `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_art` (`id_art`),
  ADD KEY `id_client` (`id_client`);

--
-- Indeksy dla tabeli `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT dla tabeli `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT dla tabeli `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_art`) REFERENCES `articles` (`id_art`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
