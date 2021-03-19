-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: 127.0.0.1:3306
-- Χρόνος δημιουργίας: 19 Μαρ 2021 στις 15:51:41
-- Έκδοση διακομιστή: 10.4.10-MariaDB
-- Έκδοση PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `erg3_theotokatos`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `courses_type` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `semester` int(11) NOT NULL,
  `ects` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_types` (`courses_type`),
  KEY `course_users` (`users`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `courses`
--

INSERT INTO `courses` (`id`, `users`, `title`, `courses_type`, `description`, `semester`, `ects`) VALUES
(6, 11, 'ΠΛΗ10', 1, 'Εισαγωγή στην πληροφορική', 1, 5),
(9, 17, 'ΠΛΗ11', 1, 'Τεχνολογία λογισμικού 1', 1, 5),
(10, 12, 'ΠΛΗ12', 1, 'Μαθηματικά 2', 1, 5),
(11, 12, 'ΠΛΗ20', 1, 'Μαθηματικά 2', 2, 5),
(12, 17, 'ΠΛΗ21', 1, 'Ψηφιακά συστήματα', 2, 5),
(13, 17, 'ΠΛΗ22', 1, 'Δίκτυα', 2, 5),
(14, 18, 'ΠΛΗ30', 1, 'Θεμελιώσεις Επιστήμης Η/Υ', 3, 5),
(15, 18, 'ΠΛΗ31', 1, 'Τεχνιτή Νοημοσύνη', 3, 5),
(16, 11, 'ΠΛΗ23', 2, 'Τηλεματική, Διαδίκτυα & Κοινωνία', 3, 5),
(17, 17, 'ΠΛΗ37', 2, 'Εκπαίδευση πληροφορικής', 3, 5);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `courses_type`
--

DROP TABLE IF EXISTS `courses_type`;
CREATE TABLE IF NOT EXISTS `courses_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `courses_type`
--

INSERT INTO `courses_type` (`id`, `name`) VALUES
(1, 'Βασικό'),
(2, 'Επιλογής');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `enrolements`
--

DROP TABLE IF EXISTS `enrolements`;
CREATE TABLE IF NOT EXISTS `enrolements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users` int(11) NOT NULL,
  `courses` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `regdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `enroled_status` (`status`),
  KEY `enroled_courses` (`courses`),
  KEY `enroled_users` (`users`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `enrolements`
--

INSERT INTO `enrolements` (`id`, `users`, `courses`, `status`, `grade`, `regdate`) VALUES
(6, 9, 6, 1, 0, NULL),
(7, 9, 9, 1, 10, NULL),
(8, 10, 10, 2, 6, NULL),
(9, 10, 11, 2, 10, NULL),
(10, 13, 12, 2, 0, NULL),
(11, 13, 13, 2, 0, NULL),
(12, 14, 14, 2, 10, NULL),
(13, 19, 14, 2, 0, NULL),
(14, 20, 15, 2, 0, NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'Διαχειριστές'),
(2, 'Διδάσκοντες'),
(3, 'Φοιτητές');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `semester`
--

DROP TABLE IF EXISTS `semester`;
CREATE TABLE IF NOT EXISTS `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `semester_users` (`users`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `semester`
--

INSERT INTO `semester` (`id`, `users`, `semester`) VALUES
(1, 9, 1),
(2, 10, 1),
(3, 13, 2),
(4, 14, 2),
(5, 19, 2),
(6, 20, 3),
(9, 10, 2),
(10, 28, 1),
(11, 9, 2);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Εγγεγραμένος'),
(2, 'Μη Εγγεγραμένος'),
(3, 'Προβιβάσιμος');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `mobilephone` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `regdate` date DEFAULT NULL,
  `am` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `password`, `role`, `mobilephone`, `address`, `birthdate`, `regdate`, `am`) VALUES
(9, 'Δέσποινακι', 'Αλεξιάδουss', 'despoina@test.gr', '987654321', 3, '2107798699', 'Δωδεκανήσου 18', '1979-10-19', NULL, '1234'),
(10, 'Αναμπέλααα', 'Ανατολίτσα', 'anatoli@test.gr', '526996', 3, '6988569845', 'Κορυτσάς 33', NULL, NULL, '234'),
(11, 'Δημήτρης', 'Βεργάδος', 'vergados.dimitrios@ac.eap.gr', '123', 2, '2610367-338', NULL, NULL, NULL, NULL),
(12, 'Παναγιώτης', 'Καρκαζής', 'karkazis.panagiotis@ac.eap.gr', '552366998558', 2, '2610367320', '', '2021-03-19', NULL, NULL),
(13, 'Δημήτρης', 'Αλεξανδρίδης', 'dimitris@gmail.com', '123', 3, '6988587542', NULL, NULL, NULL, NULL),
(14, 'Νικόλαος', 'Ψαλτάκης the master', 'nikpsal@hotmail.com', '111', 3, '6522584526', '', NULL, NULL, ''),
(15, 'Γραματέας 1', 'lastname1', 'gramateas@hotmail.com', '111', 1, '5566856652', NULL, NULL, NULL, NULL),
(16, 'Γραματέας 2', 'lastname2', 'gramateas2@email.com', '1233', 1, '4452696852', NULL, NULL, NULL, NULL),
(17, 'Καθηγητής 1', 'Καθηγητής 1', 'kathigitis@eap.gr', '321', 2, '5585566685', '', NULL, NULL, ''),
(18, 'Καθηγητής 2', 'Καθηγητής 2', 'adsad@eap.gr', '222', 2, NULL, NULL, NULL, NULL, NULL),
(19, 'Φοιτητής 1', 'Φοιτητής 1', 'fititis@eap.gr', '1223', 3, NULL, NULL, NULL, NULL, NULL),
(20, 'Φοιτητής 2', 'Φοιτητής 2', 'fititi2s@eap.gr', '1112233', 3, NULL, NULL, NULL, NULL, '213'),
(28, 'EYTHYMIOS', 'Θεοτοκάτος', 'themhz@gmail.com', '526996', 1, '', '', NULL, '2021-03-17', '');

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `course_types` FOREIGN KEY (`courses_type`) REFERENCES `courses_type` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `course_users` FOREIGN KEY (`users`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `enrolements`
--
ALTER TABLE `enrolements`
  ADD CONSTRAINT `enroled_courses` FOREIGN KEY (`courses`) REFERENCES `courses` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `enroled_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `enroled_users` FOREIGN KEY (`users`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `semester`
--
ALTER TABLE `semester`
  ADD CONSTRAINT `semester_users` FOREIGN KEY (`users`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Περιορισμοί για πίνακα `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_roles` FOREIGN KEY (`role`) REFERENCES `role` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
