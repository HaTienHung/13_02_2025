-- MySQL dump 10.13  Distrib 8.4.4, for Linux (aarch64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	8.4.4

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_user_id_foreign` (`user_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (21,5,6,2,'2025-03-16 16:28:00','2025-03-16 16:28:00');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (2,'Đồ uống','do-uong','2025-02-28 05:55:27','2025-03-28 13:21:23'),(3,'Snacks','snacks','2025-02-28 05:55:27','2025-02-28 05:55:27'),(6,'Kẹo','keo','2025-03-29 03:14:58','2025-04-15 03:41:47'),(7,'Socola','socola','2025-04-15 03:53:23','2025-04-15 03:53:23');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_transactions`
--

DROP TABLE IF EXISTS `inventory_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `type` enum('import','export') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_transactions_product_id_foreign` (`product_id`),
  CONSTRAINT `inventory_transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_transactions`
--

LOCK TABLES `inventory_transactions` WRITE;
/*!40000 ALTER TABLE `inventory_transactions` DISABLE KEYS */;
INSERT INTO `inventory_transactions` VALUES (5,3,50,'import','2025-02-28 05:57:00','2025-02-28 05:57:00'),(6,4,50,'import','2025-02-28 05:57:00','2025-02-28 05:57:00'),(7,5,50,'import','2025-02-28 05:57:00','2025-02-28 05:57:00'),(8,4,2,'export','2025-02-28 06:01:45','2025-02-28 06:01:45'),(9,4,1,'import','2025-02-28 06:02:55','2025-02-28 06:02:55'),(10,3,1,'export','2025-02-28 06:02:55','2025-02-28 06:02:55'),(11,4,1,'import','2025-03-09 13:55:11','2025-03-09 13:55:11'),(12,3,1,'import','2025-03-09 13:55:11','2025-03-09 13:55:11'),(13,4,2,'export','2025-03-10 10:16:32','2025-03-10 10:16:32'),(14,5,3,'export','2025-03-10 15:23:45','2025-03-10 15:23:45'),(15,5,10,'export','2025-03-11 03:15:01','2025-03-11 03:15:01'),(16,4,2,'export','2025-03-11 09:54:41','2025-03-11 09:54:41'),(17,5,3,'import','2025-03-11 09:54:41','2025-03-11 09:54:41'),(18,4,1,'export','2025-03-11 09:55:30','2025-03-11 09:55:30'),(19,4,3,'import','2025-03-11 09:55:44','2025-03-11 09:55:44'),(20,5,8,'export','2025-03-11 10:02:14','2025-03-11 10:02:14'),(21,5,6,'import','2025-03-11 10:03:22','2025-03-11 10:03:22'),(22,5,2,'import','2025-03-11 10:04:48','2025-03-11 10:04:48'),(23,4,2,'export','2025-03-11 11:13:17','2025-03-11 11:13:17'),(24,5,2,'export','2025-03-11 11:13:17','2025-03-11 11:13:17'),(25,5,6,'export','2025-03-11 11:13:17','2025-03-11 11:13:17'),(26,4,3,'export','2025-03-11 11:13:17','2025-03-11 11:13:17'),(27,4,2,'export','2025-03-11 11:17:21','2025-03-11 11:17:21'),(28,4,10,'export','2025-03-11 11:17:42','2025-03-11 11:17:42'),(29,4,8,'import','2025-03-11 11:21:12','2025-03-11 11:21:12'),(30,5,10,'import','2025-03-11 11:21:12','2025-03-11 11:21:12'),(31,5,10,'export','2025-03-11 11:22:37','2025-03-11 11:22:37'),(32,5,8,'import','2025-03-11 11:23:07','2025-03-11 11:23:07'),(33,5,1,'export','2025-03-11 11:40:58','2025-03-11 11:40:58'),(34,5,2,'export','2025-03-11 11:41:16','2025-03-11 11:41:16'),(35,5,5,'export','2025-03-13 11:00:03','2025-03-13 11:00:03'),(36,4,4,'export','2025-03-13 11:00:03','2025-03-13 11:00:03'),(37,5,4,'import','2025-03-16 14:50:43','2025-03-16 14:50:43'),(38,5,2,'export','2025-03-16 16:26:12','2025-03-16 16:26:12'),(39,4,2,'export','2025-03-16 16:28:35','2025-03-16 16:28:35'),(40,4,2,'export','2025-03-16 16:29:47','2025-03-16 16:29:47'),(41,5,5,'export','2025-03-16 16:31:24','2025-03-16 16:31:24'),(42,4,4,'import','2025-03-16 16:31:24','2025-03-16 16:31:24'),(43,5,1,'import','2025-03-16 16:31:32','2025-03-16 16:31:32'),(44,6,100,'import','2025-03-16 16:33:22','2025-03-16 16:33:22'),(45,5,2,'export','2025-03-16 16:34:31','2025-03-16 16:34:31'),(46,5,1,'import','2025-03-16 16:35:31','2025-03-16 16:35:31'),(47,4,2,'import','2025-03-16 16:35:39','2025-03-16 16:35:39'),(60,4,5,'export','2025-03-26 10:57:19','2025-03-26 10:57:19'),(61,4,1,'import','2025-03-26 11:10:15','2025-03-26 11:10:15'),(62,4,1,'export','2025-04-06 13:02:00','2025-04-06 13:02:00'),(63,4,1,'export','2025-04-06 13:04:23','2025-04-06 13:04:23'),(64,6,1,'export','2025-04-09 04:55:15','2025-04-09 04:55:15'),(65,5,1,'export','2025-04-09 04:55:15','2025-04-09 04:55:15'),(66,7,99,'import','2025-04-15 05:13:35','2025-04-15 05:13:35'),(67,10,99,'import','2025-04-19 09:34:30','2025-04-19 09:34:30'),(68,18,99,'import','2025-04-19 09:50:05','2025-04-19 09:50:05'),(69,18,1,'export','2025-04-19 09:50:21','2025-04-19 09:50:21'),(70,7,1,'export','2025-04-19 09:50:21','2025-04-19 09:50:21');
/*!40000 ALTER TABLE `inventory_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_02_13_134442_create_categories_table',1),(5,'2025_02_13_134442_create_orders_table',1),(6,'2025_02_13_134442_create_products_table',1),(7,'2025_02_13_134443_create_inventory_transactions_table',1),(8,'2025_02_13_134443_create_order_items_table',1),(9,'2025_02_13_140302_add_role_to_users_table',1),(10,'2025_02_13_144835_create_personal_access_tokens_table',1),(11,'2025_02_15_033047_add_total_price_to_orders_table',1),(12,'2025_02_15_034628_rename_quantity_to_stock_in_inventory',1),(13,'2025_02_23_130202_create_cart_items_table',1),(14,'2025_02_28_043029_add_default_stock_to_inventory_transactions',2),(15,'2025_02_28_043534_update_enum_type_in_inventory_transactions',3),(16,'2025_02_28_045027_rename_stock_to_quantity_in_inventory_transactions',4),(17,'2025_03_06_041827_add_image_to_products_table',5),(18,'2025_03_22_134033_add_phone_number_to_users_table',6),(19,'2025_03_24_044031_create_roles_table',7),(20,'2025_03_26_110556_remove_role_column_from_users_table',8),(21,'2025_04_22_112440_add_image_url_to_products_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (26,40,18,1,31000.00,'2025-04-19 09:50:21','2025-04-19 09:50:21'),(27,40,7,1,42500.00,'2025-04-19 09:50:21','2025-04-19 09:50:21');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (40,4,73500.00,'pending','2025-04-19 09:50:21','2025-04-19 09:50:21');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (4,'App\\Models\\User',10,'API Token','3b162d252c3ccd5373417067b5c55541c1eb703928e210500501856b7eecb33e','[\"*\"]','2025-03-25 13:10:23',NULL,'2025-03-25 13:04:45','2025-03-25 13:10:23'),(27,'App\\Models\\User',12,'API Token','05e786ccaa54c7a366aaf032c0666f1a5cb09d031cfb99f61149ae5e71d4a11e','[\"*\"]',NULL,NULL,'2025-04-01 03:19:38','2025-04-01 03:19:38'),(43,'App\\Models\\User',13,'API Token','8c01457e34c1ef346c9d158d099da8ed033a95c69102a75e941bbeb09703d229','[\"*\"]','2025-04-07 13:11:46',NULL,'2025-04-07 13:11:37','2025-04-07 13:11:46'),(64,'App\\Models\\User',8,'API Token','62d63bd2cf7a0e91209baf6a93b76499e50289a4fb1b79551b84f569b6eeab90','[\"*\"]','2025-04-19 09:48:29',NULL,'2025-04-18 04:37:41','2025-04-19 09:48:29'),(82,'App\\Models\\User',4,'API Token','a927d6f1fdcc0c6067920ed955c367948f9a114b37f513a3696d3e1c0a0ef58e','[\"*\"]','2025-04-22 14:05:02',NULL,'2025-04-22 03:57:25','2025-04-22 14:05:02'),(83,'App\\Models\\User',3,'API Token','0af3f8c0aacb674985d008536c5d0b9e8cc1d37d9cd4154ea394f736dd47bf65','[\"*\"]','2025-04-23 03:22:05',NULL,'2025-04-22 03:59:07','2025-04-23 03:22:05');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (3,'Nước Tăng Lực Monster Energy Ultra Energy Drink 355ml','ostar','Monster Energy Ultra là nước tăng lực hàng đầu tại Mỹ, là biểu tượng toàn cầu và có mặt trên 125 quốc gia. Monster thể hiện đẳng cấp, phong cách sống khác biệt, là hiện thân của sự mạnh mẽ, huyền bí và vui nhộn.\r\n\r\nMonster là thức uống dành cho thế hệ trẻ dám sống khác biệt và đương đầu với thử thách mới.\r\n\r\nMonster luôn gắn kết với các hoạt động thể thao phiêu lưu mạo hiểm, những trò chơi trực tuyến và âm nhạc sôi động. Hướng dẫn sử dụng Lắc nhẹ trước khi uống, dùng ngay sau khi mở nắp. Ngon hơn khi uống lạnh. Bảo quản: Để nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp hoặc nơi có nhiệt độ cao.\r\n\r\nThành phần: Carbonated Water, Acid (Citric Acid), Taurine (0.4%), Acidity Regulator (Sodium Citrate), Flavourings, Panax Ginseng Root Extract (0.08%), Sweeteners (Sucralose, Acesulfame K), Caffeine (0.03%), Preservatives (Sorbic Acid, Benzoic Acid), L-Carnitine L-Tartrate (0.015%), Vitamins (B3, B5, B6, B12), Sodium Chloride, D-Glucronolactone, Guarana Seed Extract (0.002%), Inositol.',20500.00,2,'2025-02-28 05:55:51','2025-04-22 13:58:41','products/w2umupx6s5ayv2hmra16','https://res.cloudinary.com/dmoex743w/image/upload/v1745330319/products/w2umupx6s5ayv2hmra16.webp'),(4,'Nước Tăng Lực Monster Energy Lon 355ML','cocacola','Nước tăng lực Monster Energy là nước tăng lực hàng đầu tại Mỹ, là biểu tượng toàn cầu và có mặt trên 125 quốc gia. Monster thể hiện đẳng cấp, phong cách sống khác biệt, là hiện thân của sự mạnh mẽ, huyền bí và vui nhộn.\r\n\r\nMonster là thức uống dành cho thế hệ trẻ dám sống khác biệt và đương đầu với thử thách mới. Monster luôn gắn kết với các hoạt động thể thao phiêu lưu mạo hiểm, những trò chơi trực tuyến và âm nhạc sôi động.\r\n\r\nSản phẩm có thành phần từ nước bão hòa CO2, Sucroza, chiết xuất đường nho, chiết xuất nhân sâm, ... mang đến một loại nước uống thơm ngon, hấp dẫn với hương vị đặc trưng.\r\n\r\nHướng dẫn sử dụng: Dùng để uống, ngon hơn khi uống lạnh. Bảo quản: Nơi thoáng mát, khô ráo. Nơi sản xuất: Việt Nam.',20500.00,2,'2025-02-28 05:55:51','2025-04-22 13:58:51','products/qesrbqsdm6axegfv2igd','https://res.cloudinary.com/dmoex743w/image/upload/v1745330329/products/qesrbqsdm6axegfv2igd.webp'),(5,'Snack Khoai Tây Pringles Vị Phô Mai','qua-cam','Thành phần: Khoai tây khô, dầu thực phẩm, dầu hạt bông, dầu đậu nành, bột bắp, tinh bột lúa mì và maltodextrin, muối, bột gạo, dextrose và các thành phần từ bột mì.\r\n\r\nKhoai tây được chiên theo công nghệ hiện đại, tiên tiến đảm bảo mang đến chất lượng tốt nhất và hương vị thơm ngon, hấp dẫn cho bạn khi thưởng thức.\r\n\r\nThành phần: Khoai tây khô, dầu thực vật, muối, dầu cọ, gia vị.\r\n\r\nSử dụng: Dùng trực tiếp ngay khi mở bao bì sản phẩm. Bảo quản: Nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp.',42500.00,3,'2025-02-28 05:55:51','2025-04-22 14:04:00','products/ftprl2yzonnh4xoxyopr','https://res.cloudinary.com/dmoex743w/image/upload/v1745330639/products/ftprl2yzonnh4xoxyopr.jpg'),(6,'Snack Khoai Tây Pringles Vị Cay Đặc Biệt','qua-dua-hau','Thành phần: Khoai tây khô, dầu thực phẩm, dầu hạt bông, dầu đậu nành, bột bắp, tinh bột lúa mì và maltodextrin, muối, bột gạo, dextrose và các thành phần từ bột mì.\r\n\r\nKhoai tây được chiên theo công nghệ hiện đại, tiên tiến đảm bảo mang đến chất lượng tốt nhất và hương vị thơm ngon, hấp dẫn cho bạn khi thưởng thức.\r\n\r\nThành phần: Khoai tây khô, dầu thực vật, muối, dầu cọ, gia vị.\r\n\r\nSử dụng: Dùng trực tiếp ngay khi mở bao bì sản phẩm. Bảo quản: Nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp.',42500.00,3,'2025-03-06 04:36:25','2025-04-22 14:03:47','products/kg7xp99qnimx6mqas0if','https://res.cloudinary.com/dmoex743w/image/upload/v1745330625/products/kg7xp99qnimx6mqas0if.jpg'),(7,'Snack Khoai Tây Pringles Vị Kem Chua Và Hành','qua-nho','Khoai tây Pringles Sour Onion được làm từ khoai tây nguyên chất chứa nhiều chất dinh dưỡng tốt cho sức khỏe. Gia vị có trong sản phẩm đều được kiểm định an toàn với người dùng. Khoai tây được cắt lát mỏng, trải qua quá trình chế biến để giữ được màu vàng tươi tự nhiên. Khoai tây giòn tan kết hợp với gia vị đậm đà kích thích vị giác của bạn.\r\nThành phần: Khoai tây (70%), dầu thực vật (dầu cọ), tinh bột khoai tây, glucose, muối, đường, chất điều vị (monosodium glutamate (E621)), bột gia vị hương hành chua,...\r\nSử dụng: Dùng trực tiếp ngay khi mở bao bì sản phẩm.\r\nBảo quản: Nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp. Xuất xứ: Việt Nam.',42500.00,3,'2025-03-06 05:11:37','2025-04-22 14:03:34','products/oma4pt8i1s8lfndz6glm','https://res.cloudinary.com/dmoex743w/image/upload/v1745330612/products/oma4pt8i1s8lfndz6glm.webp'),(10,'Snack Khoai Tây Pringles Vị Tự Nhiên','oishi','Thành phần: Khoai tây khô, dầu thực phẩm, dầu hạt bông, dầu đậu nành, bột bắp, tinh bột lúa mì và maltodextrin, muối, bột gạo, dextrose và các thành phần từ bột mì.\r\n\r\nKhoai tây được chiên theo công nghệ hiện đại, tiên tiến đảm bảo mang đến chất lượng tốt nhất và hương vị thơm ngon, hấp dẫn cho bạn khi thưởng thức.\r\n\r\nThành phần: Khoai tây khô, dầu thực vật, muối, dầu cọ, gia vị.\r\n\r\nSử dụng: Dùng trực tiếp ngay khi mở bao bì sản phẩm. Bảo quản: Nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp.',42500.00,3,'2025-03-18 05:19:11','2025-04-22 14:04:12','products/aywgjtnz5hwtmpuhewgu','https://res.cloudinary.com/dmoex743w/image/upload/v1745330650/products/aywgjtnz5hwtmpuhewgu.webp'),(11,'Snack Khoai Tây Pringles Vị Ớt Ngọt','poca','Thành phần: Khoai tây khô, dầu thực phẩm, dầu hạt bông, dầu đậu nành, bột bắp, tinh bột lúa mì và maltodextrin, muối, bột gạo, dextrose và các thành phần từ bột mì.\r\n\r\nKhoai tây được chiên theo công nghệ hiện đại, tiên tiến đảm bảo mang đến chất lượng tốt nhất và hương vị thơm ngon, hấp dẫn cho bạn khi thưởng thức.\r\n\r\nThành phần: Khoai tây khô, dầu thực vật, muối, dầu cọ, gia vị.\r\n\r\nSử dụng: Dùng trực tiếp ngay khi mở bao bì sản phẩm. Bảo quản: Nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp.',42500.00,3,'2025-03-18 05:19:11','2025-04-22 14:03:11','products/bjygloywi84gtrnhfzz2','https://res.cloudinary.com/dmoex743w/image/upload/v1745330590/products/bjygloywi84gtrnhfzz2.webp'),(12,'Monster Energy Ultra Paradise Energy Drink 355ml','monster-energy-ultra-paradise-energy-drink-355ml','Nước Tăng Lực Monster Energy Ultra Paradise Lon 355ml với hương vị táo và kiwi thơm hấp dẫn, vị ga sảng khoái, dồi dào các thành phần dinh dưỡng giúp tăng lực hấp dẫn như vitamin, taurine, caffeine, inositol,...Nước tăng lực Monster Ultra Paradise 355ml chính hãng nước tăng lực Monster được sản xuất tại Malaysia cho bạn cảm giác sảng khoái\r\nNước Tăng Lực Monster Energy Ultra Paradise với hàm lượng caffeine khoảng 120 mg trong một lon nước có dung tích 355ml giúp đem đến sinh lực dồi dào và minh mẫn, đánh thức năng lượng hoạt động cả ngày dài. Đây chính là thức uống lý tưởng với những công việc đòi hỏi tập trung cao hay phải di chuyển nhiều. \r\nNước Tăng Lực Monster Energy Ultra Paradise được sản xuất trên dây truyền hiện đại dựa theo các tiêu chuẩn kiểm định khắt khe của Nhật. Chất lượng Nhật Bản giúp bạn yên tâm về chất lượng cũng như vấn đề về sức khỏe khi sử dụng.',20500.00,2,'2025-04-13 13:12:24','2025-04-22 13:59:03','products/vtmkrkdkdn4jcxvnagrh','https://res.cloudinary.com/dmoex743w/image/upload/v1745330342/products/vtmkrkdkdn4jcxvnagrh.webp'),(13,'Nước Tăng Lực Warrior Vị Nho Lon 325ml','nuoc-tang-luc-warrior-vi-nho-lon-325ml','Nước Tăng Lực Warrior Vị Nho Lon 325ml với hương vị nho thơm ngon, ngọt dịu mà không gắt cổ, có chứa nhóm vitamin B (B3, B6, B12) giúp thúc đẩy quá trình trao đổi chất, cung cấp năng lượng bền bỉ cho cả thể chất và não bộ. \r\nSản phẩm được sản xuất dựa trên dây chuyền công nghệ hiện đại, khép kín nên bạn hoàn toàn yên tâm khi sử dụng. Khi thưởng thức Nước Tăng Lực Warrior Nho Dâu giúp xua tan mọi cảm giác mệt mỏi, căng thẳng ngay tức thì, đem lại cảm giác thoải mái nhất cho bạn sau mỗi lần sử dụng.\r\nVới thiết kế đóng lon tiện dụng, sản phẩm là người bạn đồng hành lý tưởng trong các chuyến đi chơi, dã ngoại hay các buổi luyện tập thể thao.',10500.00,2,'2025-04-13 13:25:17','2025-04-22 13:57:34','products/ciio9rjhgiuwkcaqc6lr','https://res.cloudinary.com/dmoex743w/image/upload/v1745330252/products/ciio9rjhgiuwkcaqc6lr.webp'),(14,'Nước Tăng Lực Warrior Hương Dâu Lon 325ml','nuoc-tang-luc-warrior-huong-dau-lon-325ml','Nước tăng lực vị dâu thơm ngon, ngọt dịu mà không gắt cổ, Warrior hương dâu 325ml chính hãng nước tăng lực Warrior được mệnh danh là “chiến binh năng lượng” chứa vitamin B3, B6, B12 giúp thúc đẩy quá trình trao đổi chất, cung cấp năng lượng bền bỉ cho cả thể chất và não bộ, duy trì sự tỉnh táo',10500.00,2,'2025-04-13 13:36:13','2025-04-22 13:58:10','products/wo2szhs9b7rvxk2ymaig','https://res.cloudinary.com/dmoex743w/image/upload/v1745330289/products/wo2szhs9b7rvxk2ymaig.webp'),(15,'Socola Lotte Ghana Gói 70g','socola-lotte-ghana-goi-70g','Socola Lotte Ghana gói 70g có vị đắng tự nhiên của cacao nguyên chất, ngọt vừa phải, bên trong có sự xuất hiện của hạnh nhân vô cùng bổ dưỡng.\r\n\r\nSản phẩm được sản xuất bởi các thành phần chất lượng đã qua chọn lọc bao gồm đường, bột sữa hỗn hợp (24%), Sữa bột nguyên kem (90%) ca cao mass (10%), dầu thực vật, chất nhũ hóa, hương liệu nhân tạo,v.v\r\n\r\nVới thiết kế dạng thanh tiện lợi, có độ giòn dễ bẻ, khi cắn một miếng sẽ tan ngay trong miệng tạo nên hương vị ngon khó cưỡng.',25500.00,7,'2025-04-18 13:33:28','2025-04-22 14:02:46','products/f8aa04rjtca488fjblyh','https://res.cloudinary.com/dmoex743w/image/upload/v1745330564/products/f8aa04rjtca488fjblyh.jpg'),(16,'Socola Lotte Hạt Hạnh Nhân Hộp 46g','socola-lotte-hat-hanh-nhan-hop-46g',NULL,28000.00,7,'2025-04-18 13:34:30','2025-04-22 14:02:26','products/dvdohv8ebfahlqvs63cq','https://res.cloudinary.com/dmoex743w/image/upload/v1745330544/products/dvdohv8ebfahlqvs63cq.webp'),(17,'Socola ABC Lotte Gói 187g','keo-socola-abc-lotte-goi-187g','Kẹo Socola ABC Lotte gói 187g sở hữu vị thơm ngon, không quá đắng, là sự hòa quyện tuyệt vời giữa socola và sữa giúp kích thích vị giác của người dùng.\r\n\r\nSocola ABC được sản xuất thành từng viên nhỏ, được đóng gói tiện lợi, dễ dàng bảo quản và có thể mang theo sản phẩm khi đi du lịch hay đi picnic.\r\n\r\nSản phẩm được sản xuất và đóng gói tại Hàn Quốc, đảm bảo được chất lượng và luôn được nhiều người dùng tin tưởng.',79000.00,7,'2025-04-18 13:35:08','2025-04-22 14:01:50','products/wcswybrhb9tihiejcdsr','https://res.cloudinary.com/dmoex743w/image/upload/v1745330508/products/wcswybrhb9tihiejcdsr.webp'),(18,'Kẹo Playmore Dưa Hấu 22g','keo-playmore-dua-hau-22g','Kẹo ngậm dạng viên cứng, vị dưa hấu tươi mát kết hợp với Methol bạc hà tạo cảm giác the, thông cổ và mát lạnh.\r\n\r\nDạng chai nhựa siêu dễ thương, dễ dàng bỏ túi mang đi xa.\r\n\r\nNgoài ra bao bì sản phẩm còn nhỏ xinh bắt mắt có thể tái sử dụng để đựng đồ.',31000.00,6,'2025-04-18 13:37:30','2025-04-22 14:00:46','products/qvxc3led1mscfhoh88ja','https://res.cloudinary.com/dmoex743w/image/upload/v1745330445/products/qvxc3led1mscfhoh88ja.webp'),(19,'Kẹo Coffeeshot Cappuccino Kopiko Gói 140G','keo-coffeeshot-cappuccino-kopiko-goi-140g','Kẹo cà phê Coffeeshot Cappuccino Kopiko chứa nhiều thành phần dinh dưỡng với đường, glucose, dầu thực vật, sữa bột, tinh chất cà phê... mang lại sản phẩm với chất lượng cao.\r\n\r\nKẹo cà phê Coffeeshot Cappuccino Kopiko với mùi vị thơm ngon, ngọt dịu của hương cà phê, giúp kích thích vị giác.\r\n\r\nSản phẩm đóng gói tiện dụng để dễ dàng mang đi khi du lịch, dã ngoại, có thể sử dụng mọi lúc.',16600.00,6,'2025-04-18 13:38:43','2025-04-22 14:01:35','products/bpvjuk1uwecon7ntwvsd','https://res.cloudinary.com/dmoex743w/image/upload/v1745330493/products/bpvjuk1uwecon7ntwvsd.webp'),(20,'Kẹo Gum Xylitol Không Đường Hương Lime Mint Hũ 130.5g','keo-gum-xylitol-khong-duong-huong-lime-mint-hu-1305g','Kẹo Gum Xylitol Không Đường Hương Chanh Bạc Hà với công thức mới và hàm lượng chất ngọt tự nhiên Xylitol chiếm hơn 50% trong thành phần tạo ngọt nên có tác dụng ngăn ngừa sâu răng.\r\n\r\nThành phần: Chất tạo ngọt: Xylitol, Maltitol Aspartam, cốt gôm, hương bạc hà chanh giống tự nhiên và tổng hợp, chất làm dày(414),...\r\n\r\nKẹo gum Xylitol đã được chứng minh có thể ngăn ngừa sâu răng. Và cách hiệu quả nhất là nhai 2 viên kẹo gum có chứa Xylitol của Lotte sau mỗi bữa ăn.',49000.00,6,'2025-04-18 13:39:32','2025-04-22 13:59:23','products/io5lwdcdyevlf2ufyt3r','https://res.cloudinary.com/dmoex743w/image/upload/v1745330362/products/io5lwdcdyevlf2ufyt3r.webp'),(21,'Kẹo Alpenliebe Hương Xoài Nhân Muối Ớt 217.5g','keo-alpenliebe-huong-xoai-nhan-muoi-ot-2175g','Kẹo Alpenliebe Hương Xoài Nhân Muối Ớt là sự kết hợp mới lạ giữa vị chua ngọt dịu của xoài cùng vị cay cay từ muối ớt, tan ngay từ đầu lưỡi, thơm ngon khó cưỡng. Thật sự ngon ngất ngây không thể chối từ.\r\n\r\nSản phẩm đóng thành nhiều viên nhỏ gọn, có thể mang theo bên mình khi ra ngoài. Và chia sẻ dễ dàng cùng bạn bè, người thân.\r\n\r\nThành Phần: đường, siro glucoza, bột gia vị muối ớt (4,1%), (đường, maltodextrin, muối, bột ớt, tinh bột, chất điều chỉnh độ acid (acid citric (330), acid malic (296), hương liệu giống tự nhiên, chất chống đông vón (dyoxyd silic vô định hình (551), chất tạo ngọt tổng hợp (asapsam (951), sucralose (955), dextroza, chất điều chỉnh độ acid (acid lactic (270), acis malis (296), acid citric (330), hương xoài giống tự nhiên, màu tổng hợp (tartrazin (102) allura red AC (129), orilliant blue FCF (133).\r\n\r\nHướng dẫn sử dụng: Dùng trực tiếp sau khi mở bao bì sản phẩm. Bảo quản nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp. Hạn sử dụng: 12 tháng kể từ ngày sản xuất. Xuất xứ: Việt Nam.',33900.00,6,'2025-04-18 13:42:38','2025-04-22 14:00:08','products/t2jxexlqb8glime6fchn','https://res.cloudinary.com/dmoex743w/image/upload/v1745330406/products/t2jxexlqb8glime6fchn.webp'),(22,'Nước Ngọt Coca Cola Vị Nguyên Bản Lon 235ml','nuoc-ngot-coca-cola-vi-nguyen-ban-lon-235ml','Nước Ngọt Có Gas Coca Cola với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, đem lại cảm giác thoải mái nhất sau mỗi lần sử dụng nên đặc biệt thích hợp sử dụng khi hoạt động nhiều ngoài trời.\r\n\r\nSản phẩm được ưa chuộng bởi thực khách nhiều lứa tuổi trên toàn thế giới. Sản phẩm đã được đóng chai tiện lợi, dễ dàng mang đi và sử dụng khi vui chơi, hoạt động thể thao.\r\n\r\nThành phần: Nước bão hòa CO2, đường HFCS, đường mía, màu thực phẩm, chất điều chỉnh độ acid, hương cola tự nhiên, caffeine.',7500.00,2,'2025-04-20 04:29:21','2025-04-22 14:00:23','products/lzxjxrazh8o9xoxwa8od','https://res.cloudinary.com/dmoex743w/image/upload/v1745330421/products/lzxjxrazh8o9xoxwa8od.webp'),(23,'Nước Tăng Lực Power In Lon 320ml','nuoc-tang-luc-power-in-lon-320ml','Nước tăng lực Power In là sự kết hợp hoàn hảo từ những nguyên liệu chất lượng, mang đến một sản phẩm độc đáo giúp bổ sung năng lượng nhanh chóng, nước tăng lực này không chỉ giúp bạn tỉnh táo mà còn mang lại cảm giác sảng khoái tức thì. Vị chua nhẹ và ngọt thanh tạo nên một trải nghiệm uống thú vị, giúp bạn dễ dàng thưởng thức trong những lúc cần thiết.\r\n\r\nSản phẩm chứa hàm lượng caffeine vừa đủ, kết hợp với Taurine và các vitamin B3, B6, B12, mang lại sự tỉnh táo và cải thiện hiệu suất làm việc. Đặc biệt, Vitamin B5 có trong nước tăng lực Power In giúp thúc đẩy quá trình chuyển hóa năng lượng hiệu quả hơn.\r\n\r\nDù là trong công việc hay hoạt động thể thao, Nước Tăng Lực Power In luôn là lựa chọn lý tưởng để giữ cho bạn luôn tỉnh táo và năng động.',8600.00,2,'2025-04-20 04:29:46','2025-04-22 14:01:21','products/yyyqtiwebwkgf8maiw6w','https://res.cloudinary.com/dmoex743w/image/upload/v1745330480/products/yyyqtiwebwkgf8maiw6w.webp'),(24,'Nước Tăng Lực Rockstar Lon 250ml','nuoc-tang-luc-rockstar-lon-250ml','Nước Tăng Lực Rockstar đem lại cảm giác mãnh liệt nhưng có vị êm dịu dễ uống.\r\n\r\nVới nước tăng lực Rockstar lon 250ml, bạn có thể sử dụng tiết kiệm và lâu dài hơn với giá tốt, đảm bảo cung cấp thường xuyên năng lượng giúp tỉnh táo sảng khoái hơn trong công việc và sinh hoạt hàng ngày...\r\n\r\nThành phần: Nước, đường, chất điều chỉnh độ acid (330, 331), Taurin, hương trái cây tổng hợp, chất bảo quản (202, 211), caffein (320mg/ lít), inositol, vitamin B3, chất chống oxi hóa (385), vitamin B6, chất tạo ngọt tổng hợp (sucralose, trichloro galabdg sucrose) 955, acesulfame kali 950, bột chiết xuất nhân sâm (40mg/ lít), màu tổng hợp, (Tartrazin 102, allura red ac 129).\r\n\r\nHướng dẫn sử dụng: Lắc nhẹ trước khi uống, dùng ngay sau khi mở nắp. Ngon hơn khi uống lạnh. Bảo quản: Để nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp hoặc nơi có nhiệt độ cao. Sản xuất tại: Việt Nam',10600.00,2,'2025-04-20 04:30:51','2025-04-22 14:00:57','products/wt306no9klrhpweya70p','https://res.cloudinary.com/dmoex743w/image/upload/v1745330455/products/wt306no9klrhpweya70p.webp'),(25,'OKF Wake Up Energy Drink 250ml','okf-wake-up-energy-drink-250ml','Nước Tăng Lực OKF Wake Up Lon 250ML có những dưỡng chất bổ sung năng lượng, các vitamin, tốt cho cơ thể, giúp cơ thể bạn có thêm dưỡng chất và kích hoạt năng lượng để cơ thể bạn hoạt động cả ngày dài…Sản phẩm nước tăng lực sẽ mang đến cho bạn năng lượng để đạt hiệu quả tối đa trong công việc và sinh hoạt. Hướng dẫn sử dụng: Dùng uống trực tiếp. Ngon hơn khi uống lạnh. Bảo quản: Để nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp hoặc nơi có nhiệt độ cao. Thời hạn sử dụng sản phẩm: 36 tháng kể từ ngày sản xuất.',17100.00,2,'2025-04-20 04:32:01','2025-04-22 14:01:09','products/ckxl77yggw5tsita8mfs','https://res.cloudinary.com/dmoex743w/image/upload/v1745330467/products/ckxl77yggw5tsita8mfs.webp'),(37,'Sting Strawberry Flavor Energy Drink 330ml','sting-strawberry-flavor-energy-drink-330ml','Nước tăng lực Sting hương dâu tây đỏ có hương vị tự nhiên của dâu tây đỏ, kết hợp với những hương liệu phụ trợ một cách hài hòa tạo nên sự thơm ngon, hấp dẫn.\r\n\r\nSản phẩm là thức uống giúp tỉnh táo và phục hồi năng lượng tức thì.\r\n\r\nNgon hơn khi uống lạnh\r\n\r\nBảo quản nơi khô ráo, thoáng mát',9800.00,2,'2025-04-22 14:47:24','2025-04-22 14:47:24','products/ulruss1awqnqiurn1qzi','https://res.cloudinary.com/dmoex743w/image/upload/v1745333243/products/ulruss1awqnqiurn1qzi.webp');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','2025-03-24 04:58:01','2025-03-24 04:58:01'),(2,'user','2025-03-24 04:58:01','2025-03-24 04:58:01');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('p7gm1F1ihsOWmeVBGCo4LMLVWMV5kqX93Va4aG8F',NULL,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.3 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVZHY2FMVHJYSVYybzNiTlpDTTBUazcwbFNMb3l1N3dOQ0kzbHhYSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1740749870);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint unsigned NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Admin','admin01@gmail.com',NULL,NULL,NULL,'$2y$12$drD6bMiRyQzVYsVYM.1yg.9dYcttTlytgvbLl7pTYU09UtGHO54VK',NULL,'2025-02-28 05:55:28','2025-02-28 05:55:28',1),(4,'Ha Hung 2002','user01@gmail.com','Ha Noi','0387768883',NULL,'$2y$12$ZqrF8CHoF6IVNmT0PRmhNOZvOrkQf07NBZRdyZgdEbD6KCGEGXomS',NULL,'2025-02-28 05:55:28','2025-03-25 09:39:10',2),(5,'Hung Ha','user02@gmail.com',NULL,'0387768881',NULL,'$2y$12$2LjBkGSft69P7IjFRYzHROFKTkSRzAJ1X/rKePvrhb3sURsmETtsK',NULL,'2025-02-28 05:58:25','2025-03-25 04:50:40',2),(6,'user_05','user_05@gmail.com',NULL,NULL,NULL,'$2y$12$Ns2PO/6aPY5EQuznJkveBOCJvNqvotlPTyr2ZQbe1gsJWO3h0cFla',NULL,'2025-03-11 10:02:14','2025-03-11 10:02:14',2),(7,'Ha Tien Hung','user_005@gmail.com',NULL,'0387768880',NULL,'$2y$12$AOq71nmM5kBYP4QmAF1IxeGZ6RplsQzQ3Nt3SV9ixqngnUeZF2Kua',NULL,'2025-03-16 16:34:31','2025-03-25 04:13:28',2),(8,'Nguyen Van A','nguyenvana@example.com',NULL,'0387768882',NULL,'$2y$12$8ocY2ebu6wcJeC09s7vj8.vjY1F9oT7AaDsDyiXJogUz4DNEjw2Se',NULL,'2025-03-25 05:00:05','2025-03-25 05:00:05',2),(9,'Nguyen Van A5','nguyenvana5@example.com',NULL,'0387768885',NULL,'$2y$12$QjtpQvlGIVFacH3Ddz5x.uQJiBpSWke2/VW0giYyryu/.xLTIuggG',NULL,'2025-03-25 10:23:12','2025-03-25 10:23:12',2),(10,'Nguyen Van A6','nguyenvana6@example.com',NULL,'0387768886',NULL,'$2y$12$Hd.GxYk/jrg1YQzzmIfrHuqSLaa8qBWnnhJksHtkOlTPxCKPheoKy',NULL,'2025-03-25 10:23:52','2025-03-25 10:23:52',2),(11,'Ha Tien Hung 02','hungha0929@example.com',NULL,'0387768888',NULL,'$2y$12$6oW8GuarcKkFM0oQ/iB.BeA8vt5g9qs/60Lzp4ha7b0pnxFoainRO',NULL,'2025-04-01 03:10:09','2025-04-01 03:10:09',2),(12,'Hà Tiến Hưng','hungtmt1122@gmail.com','Hà Nội','0387768889',NULL,'$2y$12$OLMxbJL7oXyHw6/6i6TN1O9YPOaDyGEa7qiyOP/49KQMQHETkCCm6',NULL,'2025-04-01 03:19:27','2025-04-01 03:19:27',2),(13,'Dương Minh Khôi','duongminhkhoi@example.com','Park 2 , Times City','0357759709',NULL,'$2y$12$H8xJmOUAl/hvf3Xjbed81uCtbAZBcIOko7hzbtkZaSQi4/VScTru6',NULL,'2025-04-07 13:11:00','2025-04-07 13:11:00',2);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-23  4:57:50
