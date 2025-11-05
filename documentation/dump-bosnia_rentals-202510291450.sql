-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: bosnia_rentals
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `listings`
--

DROP TABLE IF EXISTS `listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `users_id` bigint unsigned NOT NULL,
  `title` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `municipality` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `beds` tinyint unsigned NOT NULL DEFAULT '1',
  `baths` tinyint unsigned NOT NULL DEFAULT '1',
  `heating` enum('Central','Electric','Wood Stove') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size_m2` smallint unsigned DEFAULT NULL,
  `cover_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `amenities` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_listings_user` (`users_id`),
  CONSTRAINT `fk_listings_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `listings_chk_1` CHECK ((`price` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listings`
--

LOCK TABLES `listings` WRITE;
/*!40000 ALTER TABLE `listings` DISABLE KEYS */;
INSERT INTO `listings` VALUES (2,11,'Old Town Studio - Sarajevo','Stari Grad','Baščaršija 15',50.00,1,1,'Central',28,'assets/listings/1a.jpg','Cozy studio in Baščaršija. Walk everywhere.','2025-10-23 14:28:34',NULL,'Wi-Fi, Heating, Kitchen'),(3,11,'Old Town Studio - Sarajevo','Stari Grad','Baščaršija 15',50.00,1,1,'Central',28,'assets/listings/1a.jpg','Cozy studio in Baščaršija. Walk everywhere.','2025-10-23 14:29:14',NULL,'Wi-Fi, Heating, Kitchen'),(4,11,'Old Town Studio - Sarajevo','Stari Grad','Baščaršija 15',50.00,1,1,'Central',28,'assets/listings/1a.jpg','Cozy studio in Baščaršija. Walk everywhere.','2025-10-23 14:29:36',NULL,'Wi-Fi, Heating, Kitchen'),(5,11,'Old Town Studio - Sarajevo','Stari Grad','Baščaršija 15',50.00,1,1,'Central',28,'assets/listings/1a.jpg','Cozy studio in Baščaršija. Walk everywhere.','2025-10-24 10:47:44',NULL,'Wi-Fi, Heating, Kitchen'),(6,11,'Sunny Apartment in Mostar','Mostar','Old Town 25',75.00,2,1,'Electric',50,'assets/listings/2a.jpg','Spacious flat with balcony view.','2025-10-24 22:46:27',NULL,'Wi-Fi, Kitchen, Balcony'),(7,11,'Sunny Apartment in Mostar','Mostar','Old Town 25',75.00,2,1,'Electric',50,'assets/listings/2a.jpg','Spacious flat with balcony view.','2025-10-24 22:47:02',NULL,'Wi-Fi, Kitchen, Balcony'),(8,11,'Sunny Apartment in Mostar','Mostar','Old Town 25',75.00,2,1,'Electric',50,'assets/listings/2a.jpg','Spacious flat with balcony view.','2025-10-24 22:47:14',NULL,'Wi-Fi, Kitchen, Balcony');
/*!40000 ALTER TABLE `listings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receiver_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_msg_user` (`sender_id`,`sent_at`),
  KEY `fk_messages_receiver` (`receiver_id`),
  CONSTRAINT `fk_messages_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_msg_user` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,12,'Hello! I\'m interested in your apartment. Is it available next week?','2025-10-24 11:42:50',11),(2,17,'Hello, is your Mostar apartment available next weekend?','2025-10-24 22:46:27',11),(3,17,'Hello, is your Mostar apartment available next weekend?','2025-10-24 22:47:02',11),(4,17,'Hello, is your Mostar apartment available next weekend?','2025-10-24 22:47:14',11);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `listings_id` bigint unsigned NOT NULL,
  `users_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_res_listing_dates` (`listings_id`,`start_date`,`end_date`),
  KEY `fk_res_user` (`users_id`),
  CONSTRAINT `fk_res_listing` FOREIGN KEY (`listings_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_res_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_res_dates` CHECK ((`start_date` < `end_date`)),
  CONSTRAINT `reservations_chk_1` CHECK ((`total_price` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (1,2,12,'2025-11-01','2025-11-05',250.00,'pending','2025-10-24 10:47:57'),(2,2,12,'2025-11-01','2025-11-05',250.00,'pending','2025-10-24 10:48:39'),(3,2,16,'2025-12-01','2025-12-05',300.00,'pending','2025-10-24 22:46:27'),(4,2,16,'2025-12-01','2025-12-05',300.00,'pending','2025-10-24 22:47:02'),(5,2,16,'2025-12-01','2025-12-05',300.00,'pending','2025-10-24 22:47:14');
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role` enum('landlord','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `first_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (11,'landlord','Lejla','Host','lejla.host2@example.com','$2y$10$.wNFsaVtVcYJ7vM39r7.fOvAAflX2OHDofr6wATFYVzz5SDkTSv9K','2025-10-23 13:51:01',NULL),(12,'user','Amir','Renter','amir.renter@example.com','$2y$10$ixgKeajquyY0yhg55Xvtf.S5Ld5Z1NuXvBf/hSN0eoF.HHlN0QG9m','2025-10-23 13:51:02',NULL),(16,'user','Lala','Renter','lala.renter@example.com','$2y$10$phYYfN97qvfN/U5EhiKgUO8ZL9xFupETEAnC4cpMUziRJlWeoFoya','2025-10-24 22:05:24',NULL),(17,'user','Lala1','Renter','lala1.renter@example.com','$2y$10$MjgRK2qJNR7zdSTe0MogXeNoDF3VhM/.UMRn0DIJqPjLU/zYgH5oK','2025-10-24 22:08:54',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `users_id` bigint unsigned NOT NULL,
  `listings_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_wishlist_user` (`users_id`),
  KEY `fk_wishlist_listing` (`listings_id`),
  CONSTRAINT `fk_wishlist_listing` FOREIGN KEY (`listings_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` VALUES (1,12,2,'2025-10-24 10:54:49'),(2,17,2,'2025-10-24 22:46:27'),(3,17,2,'2025-10-24 22:47:02'),(4,17,2,'2025-10-24 22:47:14');
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'bosnia_rentals'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-29 14:50:25
