-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 08, 2012 at 03:38 PM
-- Server version: 5.5.22
-- PHP Version: 5.3.10-1ubuntu3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cococo`
--

-- --------------------------------------------------------

--
-- Table structure for table `debts`
--

CREATE TABLE IF NOT EXISTS `debts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_src` int(11) NOT NULL,
  `id_user_dst` int(11) NOT NULL,
  `creator` enum('src','dst') COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `descr` text COLLATE utf8_unicode_ci,
  `is_payback` tinyint(1) NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL,
  `date_real` date DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_src` (`id_user_src`),
  KEY `id_user_dst` (`id_user_dst`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `favs`
--

CREATE TABLE IF NOT EXISTS `favs` (
  `id_user` int(11) NOT NULL,
  `id_user_fav` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_user_fav`),
  KEY `id_user_fav` (`id_user_fav`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locales`
--

CREATE TABLE IF NOT EXISTS `locales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `locales`
--

INSERT INTO `locales` (`id`, `lang`, `name`) VALUES
(1, 'fr', 'fran'),
(2, 'en', 'english'),
(3, 'de', 'deutsch');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` varchar(48) COLLATE utf8_unicode_ci NOT NULL,
  `id_locale` int(11) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'def0',
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_users_locales` (`id_locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vu_active_users`
--
CREATE TABLE IF NOT EXISTS `vu_active_users` (
`id` int(11)
,`last_name` text
,`first_name` text
,`full_name` mediumtext
,`username` varchar(30)
,`avatar` varchar(128)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `vu_debts_details`
--
CREATE TABLE IF NOT EXISTS `vu_debts_details` (
`id_debt` int(11)
,`id_user_src` int(11)
,`id_user_dst` int(11)
,`creator` enum('src','dst')
,`amount` int(11)
,`descr` text
,`is_payback` tinyint(1)
,`is_confirmed` tinyint(1)
,`date_real` date
,`date_creation` datetime
,`dst_first_name` text
,`dst_last_name` text
,`dst_username` varchar(30)
,`dst_full_name` mediumtext
,`src_first_name` text
,`src_last_name` text
,`src_username` varchar(30)
,`src_full_name` mediumtext
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `vu_users_locales`
--
CREATE TABLE IF NOT EXISTS `vu_users_locales` (
`id` int(11)
,`username` varchar(30)
,`full_name` mediumtext
,`locale` varchar(32)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `vu_user_favs`
--
CREATE TABLE IF NOT EXISTS `vu_user_favs` (
`id_user` int(11)
,`id_user_fav` int(11)
,`username` varchar(30)
,`first_name` text
,`last_name` text
,`avatar` varchar(128)
,`dst_full_name` mediumtext
);
-- --------------------------------------------------------

--
-- Structure for view `vu_active_users`
--
DROP TABLE IF EXISTS `vu_active_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cococo`@`localhost` SQL SECURITY DEFINER VIEW `vu_active_users` AS select `users`.`id` AS `id`,`users`.`last_name` AS `last_name`,`users`.`first_name` AS `first_name`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `full_name`,`users`.`username` AS `username`,`users`.`avatar` AS `avatar` from `users` where (`users`.`is_active` = 1);

-- --------------------------------------------------------

--
-- Structure for view `vu_debts_details`
--
DROP TABLE IF EXISTS `vu_debts_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cococo`@`localhost` SQL SECURITY DEFINER VIEW `vu_debts_details` AS select `debts`.`id` AS `id_debt`,`debts`.`id_user_src` AS `id_user_src`,`debts`.`id_user_dst` AS `id_user_dst`,`debts`.`creator` AS `creator`,`debts`.`amount` AS `amount`,`debts`.`descr` AS `descr`,`debts`.`is_payback` AS `is_payback`,`debts`.`is_confirmed` AS `is_confirmed`,`debts`.`date_real` AS `date_real`,`debts`.`date_creation` AS `date_creation`,`u_dst`.`first_name` AS `dst_first_name`,`u_dst`.`last_name` AS `dst_last_name`,`u_dst`.`username` AS `dst_username`,concat(`u_dst`.`first_name`,' ',`u_dst`.`last_name`) AS `dst_full_name`,`u_src`.`first_name` AS `src_first_name`,`u_src`.`last_name` AS `src_last_name`,`u_src`.`username` AS `src_username`,concat(`u_src`.`first_name`,' ',`u_src`.`last_name`) AS `src_full_name` from ((`debts` left join `users` `u_src` on((`u_src`.`id` = `debts`.`id_user_src`))) left join `users` `u_dst` on((`u_dst`.`id` = `debts`.`id_user_dst`)));

-- --------------------------------------------------------

--
-- Structure for view `vu_users_locales`
--
DROP TABLE IF EXISTS `vu_users_locales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cococo`@`localhost` SQL SECURITY DEFINER VIEW `vu_users_locales` AS select `users`.`id` AS `id`,`users`.`username` AS `username`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `full_name`,`locales`.`name` AS `locale` from (`users` left join `locales` on((`users`.`id_locale` = `locales`.`id`))) order by `users`.`username`;

-- --------------------------------------------------------

--
-- Structure for view `vu_user_favs`
--
DROP TABLE IF EXISTS `vu_user_favs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cococo`@`localhost` SQL SECURITY DEFINER VIEW `vu_user_favs` AS select `favs`.`id_user` AS `id_user`,`favs`.`id_user_fav` AS `id_user_fav`,`users`.`username` AS `username`,`users`.`first_name` AS `first_name`,`users`.`last_name` AS `last_name`,`users`.`avatar` AS `avatar`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `dst_full_name` from (`users` join `favs`) where ((`users`.`id` = `favs`.`id_user_fav`) and (`users`.`is_active` = 1));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `debts`
--
ALTER TABLE `debts`
  ADD CONSTRAINT `debts_ibfk_1` FOREIGN KEY (`id_user_src`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `debts_ibfk_2` FOREIGN KEY (`id_user_dst`) REFERENCES `users` (`id`);

--
-- Constraints for table `favs`
--
ALTER TABLE `favs`
  ADD CONSTRAINT `favs_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favs_ibfk_2` FOREIGN KEY (`id_user_fav`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_locales` FOREIGN KEY (`id_locale`) REFERENCES `locales` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
