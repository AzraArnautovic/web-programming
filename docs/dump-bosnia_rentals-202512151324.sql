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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listings`
--

LOCK TABLES `listings` WRITE;
/*!40000 ALTER TABLE `listings` DISABLE KEYS */;
INSERT INTO `listings` VALUES (2,11,'Old Town Studio ','Stari Grad','Baščaršija 15',50.00,1,1,'Central',28,'assets/listings/1a.jpg','Cozy studio in Baščaršija. Walk everywhere.','2025-10-23 14:28:34','2025-12-04 15:13:12','Wi-Fi, Heating, Kitchen'),(3,11,'Riverside Loft ','Ilidža','Aleja Bosne Srebrene 12',60.00,1,2,'Central',35,'assets/listings/bohoroom.jpg','Modern loft, near Vrelo Bosne and tram station.','2025-10-23 14:29:14','2025-12-04 15:17:17','Wi-Fi, Heating, Kitchen'),(5,11,'Modern City Loft','Novo Sarajevo','Zmaja od Bosne 12',95.00,1,1,'Electric',42,'assets/listings/modern.jpg','Sleek and minimalist loft perfect for young professionals or digital nomads.','2025-10-24 10:47:44','2025-12-04 15:17:17','Wi-Fi, Heating, Kitchen'),(6,11,'Cottage core','Tuzla','Cesta boraca 5',60.00,2,1,'Electric',50,'assets/listings/cottage.jpg','Cute cottage with a garden.','2025-10-24 22:46:27','2025-12-04 14:58:26','Wi-Fi, Kitchen, Balcony'),(7,11,'Sunny Apartment ','Mostar','Old Town 25',260.00,2,1,'Electric',140,'assets/listings/exoticpool.jpg','Spacious flat with balcony view and a luxurious pool.','2025-10-24 22:47:02','2025-12-04 15:21:38','Wi-Fi, Kitchen, Balcony'),(8,11,'Con Calma Apartment','Sarajevo','Skenderija 6',85.00,2,1,'Electric',50,'assets/listings/cozyroom.jpg','Spacious apartment with nice balcony view.','2025-10-24 22:47:14','2025-12-04 15:00:35','Wi-Fi, Kitchen, Balcony'),(12,12,'Bistrik house - Sarajevo','Stari Grad','Bistrik 61',100.00,2,1,'Central',45,'assets/listings/oneroom.jpg','Cozy apartment near Baščaršija.','2025-11-10 21:12:15','2025-12-04 15:19:35','Wi-Fi, Parking, Kitchen'),(16,25,'Luxury Riverside Penthouse - Sarajevo','Stari Grad','Obala Kulina Bana 27',250.00,3,2,'Central',120,'assets/listings/luxury.jpg','Elegant penthouse with panoramic views of Miljacka river.','2025-11-18 20:45:37','2025-12-04 15:19:35','Wi-Fi, Parking, Kitchen'),(17,25,'Zen Garden Studio','Zenica','Crkvice 5',70.00,2,1,'Central',45,'assets/listings/2a.jpg','Peaceful studio with private courtyard, ideal for remote workers.','2025-11-18 20:47:40','2025-12-04 15:24:32','Wi-Fi, Parking, Kitchen'),(27,27,'test','sa','Ispod grada 1',32.00,1,1,'Central',23,NULL,NULL,'2025-12-05 15:12:25',NULL,'wifi');
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,12,'Hello! I\'m interested in your apartment. Is it available next week?','2025-10-24 11:42:50',11),(2,17,'Hello, is your Mostar apartment available next weekend?','2025-10-24 22:46:27',11),(3,17,'Hello, is your Mostar apartment available next weekend?','2025-10-24 22:47:02',11),(4,17,'Hello, is your Mostar apartment available next weekend?','2025-10-24 22:47:14',11),(7,12,'Hi, is this listing still available?','2025-11-10 09:45:00',17),(10,25,'Hi, is this listing still available?','2025-11-10 09:45:00',12),(11,25,'Hi, is this listing still available?','2025-11-10 09:45:00',12),(13,25,'Hi, is this listing still available?','2025-11-10 09:45:00',11),(15,18,'Hi, is this listing still available?','2025-11-10 09:45:00',11),(16,27,'hi','2025-12-05 14:29:20',11),(17,12,'Hi, is ur apartment still available ?','2025-12-05 15:23:54',27),(18,28,'hi','2025-12-07 20:13:39',11);
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
  `guests` int DEFAULT '0',
  `special_requests` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_res_listing_dates` (`listings_id`,`start_date`,`end_date`),
  KEY `fk_res_user` (`users_id`),
  CONSTRAINT `fk_res_listing` FOREIGN KEY (`listings_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_res_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_res_dates` CHECK ((`start_date` < `end_date`)),
  CONSTRAINT `reservations_chk_1` CHECK ((`total_price` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (2,2,12,'2025-11-01','2025-11-05',250.00,'completed','2025-10-24 10:48:39',0,NULL),(3,2,16,'2025-12-01','2025-12-05',300.00,'pending','2025-10-24 22:46:27',0,NULL),(4,2,16,'2025-12-01','2025-12-05',300.00,'completed','2025-10-24 22:47:02',0,NULL),(6,2,12,'2025-12-01','2025-12-05',300.00,'pending','2025-11-05 15:16:01',0,NULL),(8,8,12,'2025-11-01','2025-11-05',300.00,'completed','2025-11-10 21:19:42',3,NULL),(9,8,12,'2025-10-12','2025-10-15',240.00,'pending','2025-11-10 21:20:01',2,NULL),(10,27,12,'2025-12-06','2025-12-08',100.00,'completed','2025-12-05 15:14:06',1,NULL),(12,2,28,'2025-12-03','2025-12-04',50.00,'completed','2025-12-07 19:30:37',-1,NULL),(14,7,28,'2025-12-08','2025-12-10',520.00,'pending','2025-12-07 21:07:59',3,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (11,'user','Updated Firstname','Updated Lastname','updated@example.com','$2y$10$xu6muT6MQOV2C8bUfjcpY./pnxRzTawZiFmiRzh/b/BVH414c0Y.O','2025-10-23 13:51:01','2025-11-10 21:36:57'),(12,'user','Amalenyy','Renter','amir.renter@example.com','$2y$10$ixgKeajquyY0yhg55Xvtf.S5Ld5Z1NuXvBf/hSN0eoF.HHlN0QG9m','2025-10-23 13:51:02','2025-10-29 15:28:28'),(16,'user','Lala','Renter','lala.renter@example.com','$2y$10$phYYfN97qvfN/U5EhiKgUO8ZL9xFupETEAnC4cpMUziRJlWeoFoya','2025-10-24 22:05:24',NULL),(17,'user','Lala1','Renter','lala1.renter@example.com','$2y$10$MjgRK2qJNR7zdSTe0MogXeNoDF3VhM/.UMRn0DIJqPjLU/zYgH5oK','2025-10-24 22:08:54',NULL),(18,'user','Amar','Renter','amar.renter@example.com','$2y$10$oRMf7Jn8ejoHnnyVIEoHJe9bJLDOYFJy5Mb6Z2wbofxPlYDk3Szm2','2025-10-29 14:58:54',NULL),(22,'landlord','Amar','Hadžić','amar@example.com','$2y$10$.vRjv509QoSD0PH90eLMruri7Sg/nbfPM9DuJYdN8XEa3eA6gVUvm','2025-11-10 19:49:47',NULL),(24,'landlord','Maluma','Hadžić','maluma@example.com','$2y$10$gaVWAaD3L4q0n2nXbbe2Oe.CvowR39vakHGk11QZavXULPdy96vVy','2025-11-10 21:35:55',NULL),(25,'landlord','Azra','arnautović','azra@example.com','$2y$10$8l9NH1Wb4J3hPkAtI.9FGeHGWufb88R1MT/Yq7s5wNqpWvag/09he','2025-11-18 11:56:38',NULL),(26,'landlord','Amar','Hadžić','amar4@example.com','$2y$10$Q6MJV1VBJptBqFHJYFPOhOWRitVe88ZxRY5C8Cl7zXQ5rmTNmzFxe','2025-11-26 11:58:37',NULL),(27,'landlord','A','Hadžić','a@example.com','$2y$10$A07lJh9u9pIqjdkV.soF/ukVTHhGUslvjAfz4gNL1uPKbmBChXtcC','2025-12-03 14:41:35',NULL),(28,'user','Kiki','Kikic','k@example.com','$2y$10$Sho/6/PvZzBpj9.CuAg/Le/q56OWhsd8zibQfGvZnlxPHRVx1sOkq','2025-12-05 16:26:54',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` VALUES (1,12,2,'2025-10-24 10:54:49'),(2,17,2,'2025-10-24 22:46:27'),(3,17,2,'2025-10-24 22:47:02'),(4,17,2,'2025-10-24 22:47:14'),(8,18,7,'2025-11-18 21:21:57'),(9,28,2,'2025-12-07 20:10:23');
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

-- Dump completed on 2025-12-15 13:24:19
