-- MySQL dump 10.13  Distrib 5.5.23, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: cococo
-- ------------------------------------------------------
-- Server version	5.5.23-2-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `debts`
--

DROP TABLE IF EXISTS `debts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debts` (
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
  KEY `id_user_dst` (`id_user_dst`),
  CONSTRAINT `debts_ibfk_1` FOREIGN KEY (`id_user_src`) REFERENCES `users` (`id`),
  CONSTRAINT `debts_ibfk_2` FOREIGN KEY (`id_user_dst`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `debts`
--

LOCK TABLES `debts` WRITE;
/*!40000 ALTER TABLE `debts` DISABLE KEYS */;
/*!40000 ALTER TABLE `debts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favs`
--

DROP TABLE IF EXISTS `favs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favs` (
  `id_user` int(11) NOT NULL,
  `id_user_fav` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_user_fav`),
  KEY `id_user_fav` (`id_user_fav`),
  CONSTRAINT `favs_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `favs_ibfk_2` FOREIGN KEY (`id_user_fav`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favs`
--

LOCK TABLES `favs` WRITE;
/*!40000 ALTER TABLE `favs` DISABLE KEYS */;
/*!40000 ALTER TABLE `favs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locales`
--

DROP TABLE IF EXISTS `locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locales`
--

LOCK TABLES `locales` WRITE;
/*!40000 ALTER TABLE `locales` DISABLE KEYS */;
INSERT INTO `locales` VALUES (1,'fr','fran'),(2,'en','english'),(3,'de','deutsch');
/*!40000 ALTER TABLE `locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_locale` int(11) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'def0',
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_users_locales` (`id_locale`),
  CONSTRAINT `fk_users_locales` FOREIGN KEY (`id_locale`) REFERENCES `locales` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vu_active_users`
--

DROP TABLE IF EXISTS `vu_active_users`;
/*!50001 DROP VIEW IF EXISTS `vu_active_users`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vu_active_users` (
  `id` int(11),
  `last_name` text,
  `first_name` text,
  `full_name` mediumtext,
  `username` varchar(30),
  `avatar` varchar(128)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vu_debts_details`
--

DROP TABLE IF EXISTS `vu_debts_details`;
/*!50001 DROP VIEW IF EXISTS `vu_debts_details`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vu_debts_details` (
  `id_debt` int(11),
  `id_user_src` int(11),
  `id_user_dst` int(11),
  `creator` enum('src','dst'),
  `amount` int(11),
  `descr` text,
  `is_payback` tinyint(1),
  `is_confirmed` tinyint(1),
  `date_real` date,
  `date_creation` datetime,
  `dst_first_name` text,
  `dst_last_name` text,
  `dst_username` varchar(30),
  `dst_full_name` mediumtext,
  `src_first_name` text,
  `src_last_name` text,
  `src_username` varchar(30),
  `src_full_name` mediumtext
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vu_user_favs`
--

DROP TABLE IF EXISTS `vu_user_favs`;
/*!50001 DROP VIEW IF EXISTS `vu_user_favs`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vu_user_favs` (
  `id_user` int(11),
  `id_user_fav` int(11),
  `username` varchar(30),
  `first_name` text,
  `last_name` text,
  `avatar` varchar(128),
  `dst_full_name` mediumtext
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vu_users_locales`
--

DROP TABLE IF EXISTS `vu_users_locales`;
/*!50001 DROP VIEW IF EXISTS `vu_users_locales`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vu_users_locales` (
  `id` int(11),
  `username` varchar(30),
  `full_name` mediumtext,
  `locale` varchar(32)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vu_active_users`
--

/*!50001 DROP TABLE IF EXISTS `vu_active_users`*/;
/*!50001 DROP VIEW IF EXISTS `vu_active_users`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vu_active_users` AS select `users`.`id` AS `id`,`users`.`last_name` AS `last_name`,`users`.`first_name` AS `first_name`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `full_name`,`users`.`username` AS `username`,`users`.`avatar` AS `avatar` from `users` where (`users`.`is_active` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vu_debts_details`
--

/*!50001 DROP TABLE IF EXISTS `vu_debts_details`*/;
/*!50001 DROP VIEW IF EXISTS `vu_debts_details`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vu_debts_details` AS select `debts`.`id` AS `id_debt`,`debts`.`id_user_src` AS `id_user_src`,`debts`.`id_user_dst` AS `id_user_dst`,`debts`.`creator` AS `creator`,`debts`.`amount` AS `amount`,`debts`.`descr` AS `descr`,`debts`.`is_payback` AS `is_payback`,`debts`.`is_confirmed` AS `is_confirmed`,`debts`.`date_real` AS `date_real`,`debts`.`date_creation` AS `date_creation`,`u_dst`.`first_name` AS `dst_first_name`,`u_dst`.`last_name` AS `dst_last_name`,`u_dst`.`username` AS `dst_username`,concat(`u_dst`.`first_name`,' ',`u_dst`.`last_name`) AS `dst_full_name`,`u_src`.`first_name` AS `src_first_name`,`u_src`.`last_name` AS `src_last_name`,`u_src`.`username` AS `src_username`,concat(`u_src`.`first_name`,' ',`u_src`.`last_name`) AS `src_full_name` from ((`debts` left join `users` `u_src` on((`u_src`.`id` = `debts`.`id_user_src`))) left join `users` `u_dst` on((`u_dst`.`id` = `debts`.`id_user_dst`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vu_user_favs`
--

/*!50001 DROP TABLE IF EXISTS `vu_user_favs`*/;
/*!50001 DROP VIEW IF EXISTS `vu_user_favs`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vu_user_favs` AS select `favs`.`id_user` AS `id_user`,`favs`.`id_user_fav` AS `id_user_fav`,`users`.`username` AS `username`,`users`.`first_name` AS `first_name`,`users`.`last_name` AS `last_name`,`users`.`avatar` AS `avatar`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `dst_full_name` from (`users` join `favs`) where ((`users`.`id` = `favs`.`id_user_fav`) and (`users`.`is_active` = 1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vu_users_locales`
--

/*!50001 DROP TABLE IF EXISTS `vu_users_locales`*/;
/*!50001 DROP VIEW IF EXISTS `vu_users_locales`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vu_users_locales` AS select `users`.`id` AS `id`,`users`.`username` AS `username`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `full_name`,`locales`.`name` AS `locale` from (`users` left join `locales` on((`users`.`id_locale` = `locales`.`id`))) order by `users`.`username` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-06-09 15:02:37
