-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-rtiphonet.alwaysdata.net
-- Generation Time: Oct 15, 2023 at 03:20 PM
-- Server version: 10.6.14-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rtiphonet_foodtruck`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom`) VALUES
(1, 'sucre'),
(2, 'sel');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_retrait` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `statut` varchar(255) NOT NULL,
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_commande`
--

CREATE TABLE `detail_commande` (
  `id_detail` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_plat` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_supplements`
--

CREATE TABLE `detail_supplements` (
  `id_detailsuppl` int(11) NOT NULL,
  `id_plats` int(11) NOT NULL,
  `id_suppl` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plats`
--

CREATE TABLE `plats` (
  `id_plat` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `composition` varchar(255) NOT NULL,
  `prix` decimal(10,0) NOT NULL,
  `id_categorie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plats`
--

INSERT INTO `plats` (`id_plat`, `nom`, `composition`, `prix`, `id_categorie`) VALUES
(1, 'MOGETTE', 'Salade, mogettes, moutarde', 3, 2),
(2, 'SAUCISSE', '', 5, 2),
(3, 'RILLETTES', '', 5, 2),
(4, 'SAUCISSE-MOGETTES', 'Mogettes saucisse', 6, 2),
(5, 'RILLETTES-MOGETTES', '', 6, 2),
(6, 'ESCARGOTS', 'Escargots-beurre-persil', 8, 2),
(7, 'FROMAGE de Chevre', '', 4, 2),
(15, 'Confiture', 'Une confiture au choix (Fraise ou Figue ou orange)', 3, 1),
(16, 'Miel', 'Miel d\'acacia', 3, 1),
(17, 'Nutella', 'Nutella', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplements`
--

CREATE TABLE `supplements` (
  `id_suppl` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prix` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplements`
--

INSERT INTO `supplements` (`id_suppl`, `nom`, `prix`) VALUES
(1, 'Ketchup', 1),
(2, 'Moutarde', 1),
(3, 'Mayonnaise', 1),
(4, 'Champignons', 2),
(5, 'Chantilly', 2),
(6, 'Une boule de glace vanille', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `pts_fidelite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nom`, `prenom`, `email`, `passwd`, `pts_fidelite`) VALUES
(1, 'Galonnier', 'Didier', 'didi@gmail.com', 'zfesdfdf', 25012);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `fk_user1` (`id_user`);

--
-- Indexes for table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_commande1` (`id_commande`),
  ADD KEY `fk_plats1` (`id_plat`);

--
-- Indexes for table `detail_supplements`
--
ALTER TABLE `detail_supplements`
  ADD PRIMARY KEY (`id_detailsuppl`),
  ADD KEY `fk_plats2` (`id_plats`),
  ADD KEY `fk_suppl1` (`id_suppl`),
  ADD KEY `fk_commande2` (`id_commande`);

--
-- Indexes for table `plats`
--
ALTER TABLE `plats`
  ADD PRIMARY KEY (`id_plat`),
  ADD KEY `fk_catego1` (`id_categorie`);

--
-- Indexes for table `supplements`
--
ALTER TABLE `supplements`
  ADD PRIMARY KEY (`id_suppl`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_commande`
--
ALTER TABLE `detail_commande`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_supplements`
--
ALTER TABLE `detail_supplements`
  MODIFY `id_detailsuppl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plats`
--
ALTER TABLE `plats`
  MODIFY `id_plat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `supplements`
--
ALTER TABLE `supplements`
  MODIFY `id_suppl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `fk_user1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD CONSTRAINT `fk_commande1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plats1` FOREIGN KEY (`id_plat`) REFERENCES `plats` (`id_plat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_supplements`
--
ALTER TABLE `detail_supplements`
  ADD CONSTRAINT `fk_commande2` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plats2` FOREIGN KEY (`id_plats`) REFERENCES `detail_commande` (`id_plat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_suppl1` FOREIGN KEY (`id_suppl`) REFERENCES `supplements` (`id_suppl`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `plats`
--
ALTER TABLE `plats`
  ADD CONSTRAINT `fk_catego1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
