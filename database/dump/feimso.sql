-- MySQL dump 10.13  Distrib 8.4.7, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: feimso
-- ------------------------------------------------------
-- Server version	8.4.7

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
-- Table structure for table `advertisements`
--

DROP TABLE IF EXISTS `advertisements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advertisements` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentStatus` tinyint(1) DEFAULT NULL,
  `vendorId` text COLLATE utf8mb4_unicode_ci,
  `startDate` datetime(6) DEFAULT NULL,
  `endDate` datetime(6) DEFAULT NULL,
  `isPaused` tinyint(1) DEFAULT NULL,
  `priority` text COLLATE utf8mb4_unicode_ci,
  `type` text COLLATE utf8mb4_unicode_ci,
  `title` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `coverImage` text COLLATE utf8mb4_unicode_ci,
  `profileImage` text COLLATE utf8mb4_unicode_ci,
  `video` text COLLATE utf8mb4_unicode_ci,
  `showRating` tinyint(1) DEFAULT NULL,
  `showReview` tinyint(1) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advertisements_status_index` (`status`),
  KEY `advertisements_paymentstatus_index` (`paymentStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advertisements`
--

LOCK TABLES `advertisements` WRITE;
/*!40000 ALTER TABLE `advertisements` DISABLE KEYS */;
/*!40000 ALTER TABLE `advertisements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner_items`
--

DROP TABLE IF EXISTS `banner_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banner_items` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_publish` tinyint(1) DEFAULT NULL,
  `set_order` bigint DEFAULT NULL,
  `position` text COLLATE utf8mb4_unicode_ci,
  `web_banner` text COLLATE utf8mb4_unicode_ci,
  `redirect_type` text COLLATE utf8mb4_unicode_ci,
  `redirect_id` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banner_items_sectionid_index` (`sectionId`),
  KEY `banner_items_is_publish_index` (`is_publish`),
  KEY `banner_items_set_order_index` (`set_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner_items`
--

LOCK TABLES `banner_items` WRITE;
/*!40000 ALTER TABLE `banner_items` DISABLE KEYS */;
INSERT INTO `banner_items` VALUES ('demo_banner_1','section_shop',1,1,'top','http://feimso.test/img/web_banner_img.png','',NULL,'{\"title\": \"Welcome to Feimso\"}','2026-10-01 19:36:25.473128','2026-10-08 21:00:48.373563');
/*!40000 ALTER TABLE `banner_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booked_table`
--

DROP TABLE IF EXISTS `booked_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booked_table` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `authorID` text COLLATE utf8mb4_unicode_ci,
  `author` json DEFAULT NULL,
  `vendorID` text COLLATE utf8mb4_unicode_ci,
  `vendor` json DEFAULT NULL,
  `date` datetime(6) DEFAULT NULL,
  `totalGuest` text COLLATE utf8mb4_unicode_ci,
  `guestFirstName` text COLLATE utf8mb4_unicode_ci,
  `guestLastName` text COLLATE utf8mb4_unicode_ci,
  `guestEmail` text COLLATE utf8mb4_unicode_ci,
  `guestPhone` text COLLATE utf8mb4_unicode_ci,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `specialRequest` text COLLATE utf8mb4_unicode_ci,
  `firstVisit` tinyint(1) DEFAULT NULL,
  `occasion` text COLLATE utf8mb4_unicode_ci,
  `status` text COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booked_table_date_index` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booked_table`
--

LOCK TABLES `booked_table` WRITE;
/*!40000 ALTER TABLE `booked_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `booked_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `listing_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `seller_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `booking_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sale|rental|service|experience',
  `start_date` datetime(6) DEFAULT NULL,
  `end_date` datetime(6) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `deposit` decimal(12,2) DEFAULT NULL,
  `service_fee` decimal(12,2) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AED',
  `payment_method` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|paid|failed|refunded|chargeback',
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_amount` decimal(12,2) DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|confirmed|in_progress|completed|cancelled|disputed',
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `fulfilment_status` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'awaiting|ready|picked_up|in_transit|delivered|returned',
  `condition_report_pre` text COLLATE utf8mb4_unicode_ci,
  `condition_report_post` text COLLATE utf8mb4_unicode_ci,
  `buyer_notes` text COLLATE utf8mb4_unicode_ci,
  `seller_notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL,
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_listing_id_index` (`listing_id`),
  KEY `bookings_buyer_id_index` (`buyer_id`),
  KEY `bookings_seller_id_index` (`seller_id`),
  KEY `bookings_booking_type_index` (`booking_type`),
  KEY `bookings_status_index` (`status`),
  CONSTRAINT `bookings_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_listing_id_foreign` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `photo` text COLLATE utf8mb4_unicode_ci,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_publish` tinyint(1) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brands_sectionid_index` (`sectionId`),
  KEY `brands_is_publish_index` (`is_publish`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES ('brand_feimso','Feimso','http://feimso.test/img/logo_web.png','section_shop',1,'{\"id\": \"brand_feimso\"}','2026-10-01 19:36:25.400946','2026-10-08 21:00:48.282391');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_profiles`
--

DROP TABLE IF EXISTS `business_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_profiles` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_licence_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_licence_authority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_licence_expiry` date DEFAULT NULL,
  `vat_trn` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `licence_document_urls` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo_url` text COLLATE utf8mb4_unicode_ci,
  `cover_url` text COLLATE utf8mb4_unicode_ci,
  `social_links` json DEFAULT NULL,
  `verification_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL,
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_profiles_user_id_unique` (`user_id`),
  KEY `business_profiles_verification_status_index` (`verification_status`),
  CONSTRAINT `business_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_profiles`
--

LOCK TABLES `business_profiles` WRITE;
/*!40000 ALTER TABLE `business_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `business_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_pages`
--

DROP TABLE IF EXISTS `cms_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_pages` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cms_pages_slug_index` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_pages`
--

LOCK TABLES `cms_pages` WRITE;
/*!40000 ALTER TABLE `cms_pages` DISABLE KEYS */;
INSERT INTO `cms_pages` VALUES ('cms_about-us','about-us','About us','<p>About us content.</p>','{\"publish\": true}','2026-10-01 19:36:25.476936','2026-10-08 21:00:48.380052'),('cms_help','help','Help','<p>Help content.</p>','{\"publish\": true}','2026-10-01 19:36:25.480594','2026-10-08 21:00:48.384734');
/*!40000 ALTER TABLE `cms_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `expiresAt` datetime(6) DEFAULT NULL,
  `isEnabled` tinyint(1) DEFAULT NULL,
  `isPublic` tinyint(1) DEFAULT NULL,
  `section_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendorID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupons_code_index` (`code`),
  KEY `coupons_expiresat_index` (`expiresAt`),
  KEY `coupons_isenabled_index` (`isEnabled`),
  KEY `coupons_ispublic_index` (`isPublic`),
  KEY `coupons_section_id_index` (`section_id`),
  KEY `coupons_vendorid_index` (`vendorID`),
  KEY `coupons_vendor_id_index` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creator_pipeline`
--

DROP TABLE IF EXISTS `creator_pipeline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `creator_pipeline` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creator_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stylist|designer|photographer|model|wardrobe_curator|boutique|vintage|other',
  `cohort` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'founding|alpha|beta|general',
  `pipeline_stage` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'prospecting' COMMENT 'prospecting|contacted|applied|approved|activated|transacting|repeat',
  `listing_count` int NOT NULL DEFAULT '0',
  `transaction_count` int NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `assigned_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity_at` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL,
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creator_pipeline_user_id_index` (`user_id`),
  KEY `creator_pipeline_email_index` (`email`),
  KEY `creator_pipeline_pipeline_stage_index` (`pipeline_stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creator_pipeline`
--

LOCK TABLES `creator_pipeline` WRITE;
/*!40000 ALTER TABLE `creator_pipeline` DISABLE KEYS */;
/*!40000 ALTER TABLE `creator_pipeline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creator_verifications`
--

DROP TABLE IF EXISTS `creator_verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `creator_verifications` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `role` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credential_type` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. trade_licence|professional_licence|id_document|advertiser_permit',
  `licence_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issuing_authority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `licence_expiry` date DEFAULT NULL,
  `document_urls` json DEFAULT NULL COMMENT 'uploaded licence / ID scans',
  `vat_trn` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advertiser_permit_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending|verified|rejected|expired|suspended',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `reviewed_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewed_at` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL,
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creator_verifications_user_id_index` (`user_id`),
  KEY `creator_verifications_status_index` (`status`),
  CONSTRAINT `creator_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creator_verifications`
--

LOCK TABLES `creator_verifications` WRITE;
/*!40000 ALTER TABLE `creator_verifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `creator_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `isActive` tinyint(1) DEFAULT NULL,
  `symbol` text COLLATE utf8mb4_unicode_ci,
  `code` text COLLATE utf8mb4_unicode_ci,
  `decimal_degits` bigint DEFAULT NULL,
  `symbolAtRight` tinyint(1) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `currencies_isactive_index` (`isActive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES ('aed',1,'AED','AED',2,0,'{\"name\": \"UAE Dirham\"}','2026-10-01 19:36:25.354575','2026-10-01 20:03:05.485750');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disputes`
--

DROP TABLE IF EXISTS `disputes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disputes` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complainant_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `respondent_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispute_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'item_not_received|item_not_as_described|damage|payment|other',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidence_urls` json DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open' COMMENT 'open|under_review|resolved|closed|escalated',
  `resolution` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'refund_buyer|release_seller|partial_refund|no_action',
  `refund_amount` decimal(12,2) DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `handled_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolved_at` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL,
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disputes_booking_id_index` (`booking_id`),
  KEY `disputes_complainant_id_index` (`complainant_id`),
  KEY `disputes_respondent_id_index` (`respondent_id`),
  KEY `disputes_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disputes`
--

LOCK TABLES `disputes` WRITE;
/*!40000 ALTER TABLE `disputes` DISABLE KEYS */;
/*!40000 ALTER TABLE `disputes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dynamic_notification`
--

DROP TABLE IF EXISTS `dynamic_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dynamic_notification` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci,
  `message` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dynamic_notification_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_notification`
--

LOCK TABLES `dynamic_notification` WRITE;
/*!40000 ALTER TABLE `dynamic_notification` DISABLE KEYS */;
INSERT INTO `dynamic_notification` VALUES ('ntf_booking_placed','booking_placed','New booking','A new service booking was placed.',NULL,'2026-10-01 20:14:27.999012','2026-10-01 20:14:27.999012'),('ntf_dinein_accepted','dinein_accepted','Booking confirmed','Your table booking has been accepted.',NULL,'2026-10-01 20:14:27.995641','2026-10-01 20:14:27.995641'),('ntf_dinein_placed','dinein_placed','New table booking','A new table booking request was placed.',NULL,'2026-10-01 20:14:27.991691','2026-10-01 20:14:27.991691'),('ntf_driver_accepted','driver_accepted','Driver assigned','A driver has accepted your order.',NULL,'2026-10-01 20:14:28.031487','2026-10-01 20:14:28.031487'),('ntf_driver_completed','driver_completed','Order delivered','Your order has been delivered.',NULL,'2026-10-01 20:14:28.035016','2026-10-01 20:14:28.035016'),('ntf_order_placed','order_placed','New order','You have received a new order.',NULL,'2026-10-01 20:14:27.983824','2026-10-01 20:14:27.983824'),('ntf_rental_booked','rental_booked','New rental','A new rental was booked.',NULL,'2026-10-01 20:14:28.006790','2026-10-01 20:14:28.006790'),('ntf_restaurant_accepted','restaurant_accepted','Order accepted','Your order has been accepted by the restaurant.',NULL,'2026-10-01 20:14:28.010784','2026-10-01 20:14:28.010784'),('ntf_restaurant_rejected','restaurant_rejected','Order rejected','Sorry, your order was rejected.',NULL,'2026-10-01 20:14:28.015030','2026-10-01 20:14:28.015030'),('ntf_schedule_order','schedule_order','Scheduled order','A new scheduled order was placed.',NULL,'2026-10-01 20:14:27.987445','2026-10-01 20:14:27.987445'),('ntf_service_cancelled','service_cancelled','Booking cancelled','A service booking was cancelled.',NULL,'2026-10-01 20:14:28.002695','2026-10-01 20:14:28.002695'),('ntf_store_accepted','store_accepted','Order accepted','Your order has been accepted by the store.',NULL,'2026-10-01 20:14:28.019797','2026-10-01 20:14:28.019797'),('ntf_store_completed','store_completed','Order delivered','Your order has been delivered.',NULL,'2026-10-01 20:14:28.023962','2026-10-01 20:14:28.023962'),('ntf_store_intransit','store_intransit','Order on the way','Your order is on the way.',NULL,'2026-10-01 20:14:28.027829','2026-10-01 20:14:28.027829'),('ntf_takeaway_completed','takeaway_completed','Order ready','Your takeaway order is complete.',NULL,'2026-10-01 20:14:28.038365','2026-10-01 20:14:28.038365');
/*!40000 ALTER TABLE `dynamic_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_templates` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci,
  `message` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_templates_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
INSERT INTO `email_templates` VALUES ('tpl_new_car_book','new_car_book','Your ride #{orderid} has been booked','<p>Hi {username},</p>\n<p>Thank you for your order <strong>#{orderid}</strong> on {date}.</p>\n{productdetails}\n<table cellpadding=\"4\">\n<tr><td>Subtotal</td><td>{subtotal}</td></tr>\n<tr><td>Discount {coupon}</td><td>{discountamount}</td></tr>\n<tr><td>Special discount</td><td>{specialdiscountamount}</td></tr>\n{taxdetails}\n<tr><td>Delivery</td><td>{shippingcharge}</td></tr>\n<tr><td>Tip</td><td>{tipamount}</td></tr>\n<tr><td><strong>Total</strong></td><td><strong>{totalAmount}</strong></td></tr>\n</table>\n<p>Payment: {paymentmethod}<br>Deliver to: {address}</p>',NULL,'2026-10-01 20:14:27.979288','2026-10-01 20:14:27.979288'),('tpl_new_ondemand_book','new_ondemand_book','Your booking #{orderid} has been placed','<p>Hi {username},</p>\n<p>Thank you for your order <strong>#{orderid}</strong> on {date}.</p>\n{productdetails}\n<table cellpadding=\"4\">\n<tr><td>Subtotal</td><td>{subtotal}</td></tr>\n<tr><td>Discount {coupon}</td><td>{discountamount}</td></tr>\n<tr><td>Special discount</td><td>{specialdiscountamount}</td></tr>\n{taxdetails}\n<tr><td>Delivery</td><td>{shippingcharge}</td></tr>\n<tr><td>Tip</td><td>{tipamount}</td></tr>\n<tr><td><strong>Total</strong></td><td><strong>{totalAmount}</strong></td></tr>\n</table>\n<p>Payment: {paymentmethod}<br>Deliver to: {address}</p>',NULL,'2026-10-01 20:14:27.975205','2026-10-01 20:14:27.975205'),('tpl_new_order_placed','new_order_placed','Your order #{orderid} has been placed','<p>Hi {username},</p>\n<p>Thank you for your order <strong>#{orderid}</strong> on {date}.</p>\n{productdetails}\n<table cellpadding=\"4\">\n<tr><td>Subtotal</td><td>{subtotal}</td></tr>\n<tr><td>Discount {coupon}</td><td>{discountamount}</td></tr>\n<tr><td>Special discount</td><td>{specialdiscountamount}</td></tr>\n{taxdetails}\n<tr><td>Delivery</td><td>{shippingcharge}</td></tr>\n<tr><td>Tip</td><td>{tipamount}</td></tr>\n<tr><td><strong>Total</strong></td><td><strong>{totalAmount}</strong></td></tr>\n</table>\n<p>Payment: {paymentmethod}<br>Deliver to: {address}</p>',NULL,'2026-10-01 20:14:27.969894','2026-10-01 20:14:27.969894');
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
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
-- Table structure for table `favorite_item`
--

DROP TABLE IF EXISTS `favorite_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorite_item` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_id` text COLLATE utf8mb4_unicode_ci,
  `section_id` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorite_item_user_id_index` (`user_id`),
  KEY `favorite_item_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite_item`
--

LOCK TABLES `favorite_item` WRITE;
/*!40000 ALTER TABLE `favorite_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorite_provider`
--

DROP TABLE IF EXISTS `favorite_provider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorite_provider` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorite_provider_user_id_index` (`user_id`),
  KEY `favorite_provider_provider_id_index` (`provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite_provider`
--

LOCK TABLES `favorite_provider` WRITE;
/*!40000 ALTER TABLE `favorite_provider` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite_provider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorite_service`
--

DROP TABLE IF EXISTS `favorite_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorite_service` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_author_id` text COLLATE utf8mb4_unicode_ci,
  `section_id` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorite_service_user_id_index` (`user_id`),
  KEY `favorite_service_service_id_index` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite_service`
--

LOCK TABLES `favorite_service` WRITE;
/*!40000 ALTER TABLE `favorite_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorite_vendor`
--

DROP TABLE IF EXISTS `favorite_vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorite_vendor` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorite_vendor_user_id_index` (`user_id`),
  KEY `favorite_vendor_store_id_index` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorite_vendor`
--

LOCK TABLES `favorite_vendor` WRITE;
/*!40000 ALTER TABLE `favorite_vendor` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorite_vendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feimso_referrals`
--

DROP TABLE IF EXISTS `feimso_referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feimso_referrals` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referee_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referee_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invite_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent' COMMENT 'sent|registered|activated|transacted',
  `channel` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activated_at` datetime(6) DEFAULT NULL,
  `transacted_at` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL,
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feimso_referrals_referrer_id_index` (`referrer_id`),
  KEY `feimso_referrals_referee_id_index` (`referee_id`),
  KEY `feimso_referrals_referral_code_index` (`referral_code`),
  KEY `feimso_referrals_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feimso_referrals`
--

LOCK TABLES `feimso_referrals` WRITE;
/*!40000 ALTER TABLE `feimso_referrals` DISABLE KEYS */;
/*!40000 ALTER TABLE `feimso_referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foods_review`
--

DROP TABLE IF EXISTS `foods_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foods_review` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `CustomerId` text COLLATE utf8mb4_unicode_ci,
  `VendorId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orderid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `photos` json DEFAULT NULL,
  `uname` text COLLATE utf8mb4_unicode_ci,
  `profile` text COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foods_review_vendorid_index` (`VendorId`),
  KEY `foods_review_orderid_index` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foods_review`
--

LOCK TABLES `foods_review` WRITE;
/*!40000 ALTER TABLE `foods_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `foods_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gift_cards`
--

DROP TABLE IF EXISTS `gift_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gift_cards` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `isEnable` tinyint(1) DEFAULT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `message` text COLLATE utf8mb4_unicode_ci,
  `expiryDay` bigint DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gift_cards_isenable_index` (`isEnable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gift_cards`
--

LOCK TABLES `gift_cards` WRITE;
/*!40000 ALTER TABLE `gift_cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `gift_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gift_purchases`
--

DROP TABLE IF EXISTS `gift_purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gift_purchases` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `giftId` text COLLATE utf8mb4_unicode_ci,
  `giftTitle` text COLLATE utf8mb4_unicode_ci,
  `price` double DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `redeem` tinyint(1) DEFAULT NULL,
  `userid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `giftCode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `giftPin` text COLLATE utf8mb4_unicode_ci,
  `createdDate` datetime(6) DEFAULT NULL,
  `expireDate` datetime(6) DEFAULT NULL,
  `paymentType` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gift_purchases_userid_index` (`userid`),
  KEY `gift_purchases_giftcode_index` (`giftCode`),
  KEY `gift_purchases_createddate_index` (`createdDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gift_purchases`
--

LOCK TABLES `gift_purchases` WRITE;
/*!40000 ALTER TABLE `gift_purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `gift_purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_review`
--

DROP TABLE IF EXISTS `items_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_review` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `CustomerId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `VendorId` text COLLATE utf8mb4_unicode_ci,
  `productId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driverId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orderid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `photos` json DEFAULT NULL,
  `uname` text COLLATE utf8mb4_unicode_ci,
  `profile` text COLLATE utf8mb4_unicode_ci,
  `reviewAttributes` json DEFAULT NULL,
  `createdAt` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_review_customerid_index` (`CustomerId`),
  KEY `items_review_productid_index` (`productId`),
  KEY `items_review_driverid_index` (`driverId`),
  KEY `items_review_orderid_index` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_review`
--

LOCK TABLES `items_review` WRITE;
/*!40000 ALTER TABLE `items_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `items_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listings`
--

DROP TABLE IF EXISTS `listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listings` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seller_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `listing_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sale|rental|service|experience',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `photos` json DEFAULT NULL,
  `category` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `currency` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AED',
  `deposit_amount` decimal(12,2) DEFAULT NULL COMMENT 'rental deposit / hold',
  `condition` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'new|like_new|good|fair',
  `authenticity_declared` tinyint(1) NOT NULL DEFAULT '0',
  `prohibited_check` tinyint(1) NOT NULL DEFAULT '0',
  `rental_available` tinyint(1) NOT NULL DEFAULT '0',
  `rental_price_per_day` decimal(12,2) DEFAULT NULL,
  `rental_blocked_dates` json DEFAULT NULL,
  `rental_pickup_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pickup|delivery',
  `late_return_rules` text COLLATE utf8mb4_unicode_ci,
  `damage_loss_policy` text COLLATE utf8mb4_unicode_ci,
  `booking_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'fixed|hourly',
  `available_from` datetime DEFAULT NULL,
  `available_to` datetime DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `location_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'in_person|remote|both',
  `location_address` text COLLATE utf8mb4_unicode_ci,
  `liability_acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  `scope_of_work` text COLLATE utf8mb4_unicode_ci,
  `max_participants` int DEFAULT NULL,
  `fulfilment_details` text COLLATE utf8mb4_unicode_ci,
  `cancellation_policy` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'flexible|moderate|strict',
  `delivery_option` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'pickup|courier|both',
  `delivery_charge` decimal(8,2) DEFAULT NULL,
  `return_policy` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT 'draft|active|paused|sold|archived|rejected',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL,
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listings_seller_id_index` (`seller_id`),
  KEY `listings_listing_type_index` (`listing_type`),
  KEY `listings_status_index` (`status`),
  KEY `listings_is_published_index` (`is_published`),
  CONSTRAINT `listings_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listings`
--

LOCK TABLES `listings` WRITE;
/*!40000 ALTER TABLE `listings` DISABLE KEYS */;
/*!40000 ALTER TABLE `listings` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_100000_create_password_resets_table',1),(2,'2019_08_19_000000_create_failed_jobs_table',1),(3,'2019_12_14_000001_create_personal_access_tokens_table',1),(4,'2026_10_02_000100_create_advertisements_table',1),(5,'2026_10_02_000101_create_banner_items_table',1),(6,'2026_10_02_000102_create_booked_table_table',1),(7,'2026_10_02_000103_create_brands_table',1),(8,'2026_10_02_000104_create_cms_pages_table',1),(9,'2026_10_02_000105_create_coupons_table',1),(10,'2026_10_02_000106_create_currencies_table',1),(11,'2026_10_02_000107_create_dynamic_notification_table',1),(12,'2026_10_02_000108_create_email_templates_table',1),(13,'2026_10_02_000109_create_favorite_item_table',1),(14,'2026_10_02_000110_create_favorite_provider_table',1),(15,'2026_10_02_000111_create_favorite_service_table',1),(16,'2026_10_02_000112_create_favorite_vendor_table',1),(17,'2026_10_02_000113_create_foods_review_table',1),(18,'2026_10_02_000114_create_gift_cards_table',1),(19,'2026_10_02_000115_create_gift_purchases_table',1),(20,'2026_10_02_000116_create_items_review_table',1),(21,'2026_10_02_000117_create_parcel_categories_table',1),(22,'2026_10_02_000118_create_parcel_coupons_table',1),(23,'2026_10_02_000119_create_parcel_orders_table',1),(24,'2026_10_02_000120_create_parcel_weight_table',1),(25,'2026_10_02_000121_create_provider_categories_table',1),(26,'2026_10_02_000122_create_provider_orders_table',1),(27,'2026_10_02_000123_create_providers_coupons_table',1),(28,'2026_10_02_000124_create_providers_services_table',1),(29,'2026_10_02_000125_create_providers_workers_table',1),(30,'2026_10_02_000126_create_referral_table',1),(31,'2026_10_02_000127_create_rental_coupons_table',1),(32,'2026_10_02_000128_create_rental_orders_table',1),(33,'2026_10_02_000129_create_rental_packages_table',1),(34,'2026_10_02_000130_create_rental_vehicle_type_table',1),(35,'2026_10_02_000131_create_review_attributes_table',1),(36,'2026_10_02_000132_create_sections_table',1),(37,'2026_10_02_000133_create_settings_table',1),(38,'2026_10_02_000134_create_story_table',1),(39,'2026_10_02_000135_create_tax_table',1),(40,'2026_10_02_000136_create_users_table',1),(41,'2026_10_02_000137_create_vendor_attributes_table',1),(42,'2026_10_02_000138_create_vendor_categories_table',1),(43,'2026_10_02_000139_create_vendor_orders_table',1),(44,'2026_10_02_000140_create_vendor_products_table',1),(45,'2026_10_02_000141_create_vendors_table',1),(46,'2026_10_02_000142_create_wallet_table',1),(47,'2026_10_02_000143_create_zone_table',1),(48,'2026_10_02_000200_add_uuid_alias_to_users_table',2),(49,'2026_10_08_000001_add_feimso_fields_to_users_table',3),(50,'2026_10_08_000002_create_creator_verifications_table',4),(51,'2026_10_08_000003_create_business_profiles_table',4),(52,'2026_10_08_000004_create_listings_table',4),(53,'2026_10_08_000005_create_bookings_table',4),(54,'2026_10_08_000006_create_disputes_table',4),(55,'2026_10_08_000007_create_creator_pipeline_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcel_categories`
--

DROP TABLE IF EXISTS `parcel_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parcel_categories` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  `set_order` bigint DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parcel_categories_sectionid_index` (`sectionId`),
  KEY `parcel_categories_publish_index` (`publish`),
  KEY `parcel_categories_set_order_index` (`set_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcel_categories`
--

LOCK TABLES `parcel_categories` WRITE;
/*!40000 ALTER TABLE `parcel_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `parcel_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcel_coupons`
--

DROP TABLE IF EXISTS `parcel_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parcel_coupons` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `expiresAt` datetime(6) DEFAULT NULL,
  `isEnabled` tinyint(1) DEFAULT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isPublic` tinyint(1) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parcel_coupons_code_index` (`code`),
  KEY `parcel_coupons_expiresat_index` (`expiresAt`),
  KEY `parcel_coupons_isenabled_index` (`isEnabled`),
  KEY `parcel_coupons_sectionid_index` (`sectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcel_coupons`
--

LOCK TABLES `parcel_coupons` WRITE;
/*!40000 ALTER TABLE `parcel_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `parcel_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcel_orders`
--

DROP TABLE IF EXISTS `parcel_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parcel_orders` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `adminCommission` text COLLATE utf8mb4_unicode_ci,
  `adminCommissionType` text COLLATE utf8mb4_unicode_ci,
  `author` json DEFAULT NULL,
  `authorID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` datetime(6) DEFAULT NULL,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `discountLabel` text COLLATE utf8mb4_unicode_ci,
  `couponId` text COLLATE utf8mb4_unicode_ci,
  `distance` text COLLATE utf8mb4_unicode_ci,
  `isSchedule` tinyint(1) DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `receiverNote` text COLLATE utf8mb4_unicode_ci,
  `parcelWeight` text COLLATE utf8mb4_unicode_ci,
  `parcelWeightCharge` text COLLATE utf8mb4_unicode_ci,
  `paymentCollectByReceiver` tinyint(1) DEFAULT NULL,
  `payment_method` text COLLATE utf8mb4_unicode_ci,
  `receiver` json DEFAULT NULL,
  `receiverLatLong` json DEFAULT NULL,
  `receiverPickupDateTime` datetime(6) DEFAULT NULL,
  `sender` json DEFAULT NULL,
  `senderLatLong` json DEFAULT NULL,
  `senderPickupDateTime` datetime(6) DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci,
  `subTotal` text COLLATE utf8mb4_unicode_ci,
  `sectionId` text COLLATE utf8mb4_unicode_ci,
  `parcelCategoryID` text COLLATE utf8mb4_unicode_ci,
  `parcelType` text COLLATE utf8mb4_unicode_ci,
  `parcelImages` json DEFAULT NULL,
  `taxSetting` json DEFAULT NULL,
  `driverId` text COLLATE utf8mb4_unicode_ci,
  `driver` json DEFAULT NULL,
  `rejectedByDrivers` json DEFAULT NULL,
  `sourcePoint` json DEFAULT NULL,
  `destinationPoint` json DEFAULT NULL,
  `senderZoneId` text COLLATE utf8mb4_unicode_ci,
  `receiverZoneId` text COLLATE utf8mb4_unicode_ci,
  `reviewsCount` bigint DEFAULT NULL,
  `reviewsSum` double DEFAULT NULL,
  `vendorID` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parcel_orders_authorid_index` (`authorID`),
  KEY `parcel_orders_createdat_index` (`createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcel_orders`
--

LOCK TABLES `parcel_orders` WRITE;
/*!40000 ALTER TABLE `parcel_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `parcel_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcel_weight`
--

DROP TABLE IF EXISTS `parcel_weight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parcel_weight` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `delivery_charge` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcel_weight`
--

LOCK TABLES `parcel_weight` WRITE;
/*!40000 ALTER TABLE `parcel_weight` DISABLE KEYS */;
/*!40000 ALTER TABLE `parcel_weight` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_categories`
--

DROP TABLE IF EXISTS `provider_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_categories` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `publish` tinyint(1) DEFAULT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` bigint DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_categories_publish_index` (`publish`),
  KEY `provider_categories_sectionid_index` (`sectionId`),
  KEY `provider_categories_level_index` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_categories`
--

LOCK TABLES `provider_categories` WRITE;
/*!40000 ALTER TABLE `provider_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_orders`
--

DROP TABLE IF EXISTS `provider_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_orders` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `address` json DEFAULT NULL,
  `adminCommission` text COLLATE utf8mb4_unicode_ci,
  `adminCommissionType` text COLLATE utf8mb4_unicode_ci,
  `author` json DEFAULT NULL,
  `authorID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `couponCode` text COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime(6) DEFAULT NULL,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `discountLabel` text COLLATE utf8mb4_unicode_ci,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `extraCharges` text COLLATE utf8mb4_unicode_ci,
  `extraChargesDescription` text COLLATE utf8mb4_unicode_ci,
  `extraPaymentStatus` tinyint(1) DEFAULT NULL,
  `newScheduleDateTime` datetime(6) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `otp` text COLLATE utf8mb4_unicode_ci,
  `paymentStatus` tinyint(1) DEFAULT NULL,
  `payment_method` text COLLATE utf8mb4_unicode_ci,
  `provider` json DEFAULT NULL,
  `quantity` bigint DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `scheduleDateTime` datetime(6) DEFAULT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci,
  `taxSetting` json DEFAULT NULL,
  `workerId` text COLLATE utf8mb4_unicode_ci,
  `startTime` datetime(6) DEFAULT NULL,
  `endTime` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_orders_authorid_index` (`authorID`),
  KEY `provider_orders_createdat_index` (`createdAt`),
  KEY `provider_orders_sectionid_index` (`sectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_orders`
--

LOCK TABLES `provider_orders` WRITE;
/*!40000 ALTER TABLE `provider_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `providers_coupons`
--

DROP TABLE IF EXISTS `providers_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `providers_coupons` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `expiresAt` datetime(6) DEFAULT NULL,
  `isEnabled` tinyint(1) DEFAULT NULL,
  `isPublic` tinyint(1) DEFAULT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `providerId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `providers_coupons_code_index` (`code`),
  KEY `providers_coupons_expiresat_index` (`expiresAt`),
  KEY `providers_coupons_isenabled_index` (`isEnabled`),
  KEY `providers_coupons_ispublic_index` (`isPublic`),
  KEY `providers_coupons_sectionid_index` (`sectionId`),
  KEY `providers_coupons_providerid_index` (`providerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `providers_coupons`
--

LOCK TABLES `providers_coupons` WRITE;
/*!40000 ALTER TABLE `providers_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `providers_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `providers_services`
--

DROP TABLE IF EXISTS `providers_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `providers_services` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `author` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorName` text COLLATE utf8mb4_unicode_ci,
  `title` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` text COLLATE utf8mb4_unicode_ci,
  `disPrice` text COLLATE utf8mb4_unicode_ci,
  `priceUnit` text COLLATE utf8mb4_unicode_ci,
  `photos` json DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `g` json DEFAULT NULL,
  `coordinates` json DEFAULT NULL,
  `categoryId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subCategoryId` text COLLATE utf8mb4_unicode_ci,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  `days` json DEFAULT NULL,
  `startTime` text COLLATE utf8mb4_unicode_ci,
  `endTime` text COLLATE utf8mb4_unicode_ci,
  `reviewsCount` bigint DEFAULT NULL,
  `reviewsSum` double DEFAULT NULL,
  `createdAt` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `providers_services_author_index` (`author`),
  KEY `providers_services_categoryid_index` (`categoryId`),
  KEY `providers_services_sectionid_index` (`sectionId`),
  KEY `providers_services_publish_index` (`publish`),
  KEY `providers_services_createdat_index` (`createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `providers_services`
--

LOCK TABLES `providers_services` WRITE;
/*!40000 ALTER TABLE `providers_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `providers_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `providers_workers`
--

DROP TABLE IF EXISTS `providers_workers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `providers_workers` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `firstName` text COLLATE utf8mb4_unicode_ci,
  `lastName` text COLLATE utf8mb4_unicode_ci,
  `email` text COLLATE utf8mb4_unicode_ci,
  `phoneNumber` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `profilePictureURL` text COLLATE utf8mb4_unicode_ci,
  `reviewsCount` bigint DEFAULT NULL,
  `reviewsSum` double DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `providers_workers`
--

LOCK TABLES `providers_workers` WRITE;
/*!40000 ALTER TABLE `providers_workers` DISABLE KEYS */;
/*!40000 ALTER TABLE `providers_workers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referral`
--

DROP TABLE IF EXISTS `referral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referral` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `referralCode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referralBy` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referral_referralcode_index` (`referralCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referral`
--

LOCK TABLES `referral` WRITE;
/*!40000 ALTER TABLE `referral` DISABLE KEYS */;
INSERT INTO `referral` VALUES ('nEY1xxxXTU83nXqMHKfdwoXODW2l','590105',NULL,'{\"id\": \"nEY1xxxXTU83nXqMHKfdwoXODW2l\"}','2026-10-07 21:47:39.123108','2026-10-07 21:47:39.123108');
/*!40000 ALTER TABLE `referral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rental_coupons`
--

DROP TABLE IF EXISTS `rental_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rental_coupons` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `expiresAt` datetime(6) DEFAULT NULL,
  `isEnabled` tinyint(1) DEFAULT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isPublic` tinyint(1) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rental_coupons_code_index` (`code`),
  KEY `rental_coupons_expiresat_index` (`expiresAt`),
  KEY `rental_coupons_isenabled_index` (`isEnabled`),
  KEY `rental_coupons_sectionid_index` (`sectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental_coupons`
--

LOCK TABLES `rental_coupons` WRITE;
/*!40000 ALTER TABLE `rental_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `rental_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rental_orders`
--

DROP TABLE IF EXISTS `rental_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rental_orders` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `adminCommission` text COLLATE utf8mb4_unicode_ci,
  `adminCommissionType` text COLLATE utf8mb4_unicode_ci,
  `author` json DEFAULT NULL,
  `authorID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bookingDateTime` datetime(6) DEFAULT NULL,
  `couponId` text COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime(6) DEFAULT NULL,
  `discount` text COLLATE utf8mb4_unicode_ci,
  `discountLabel` text COLLATE utf8mb4_unicode_ci,
  `discountType` text COLLATE utf8mb4_unicode_ci,
  `driverId` text COLLATE utf8mb4_unicode_ci,
  `driver` json DEFAULT NULL,
  `endKitoMetersReading` text COLLATE utf8mb4_unicode_ci,
  `startKitoMetersReading` text COLLATE utf8mb4_unicode_ci,
  `endTime` datetime(6) DEFAULT NULL,
  `startTime` datetime(6) DEFAULT NULL,
  `otpCode` text COLLATE utf8mb4_unicode_ci,
  `paymentMethod` text COLLATE utf8mb4_unicode_ci,
  `paymentStatus` tinyint(1) DEFAULT NULL,
  `rentalPackageModel` json DEFAULT NULL,
  `rentalVehicleType` json DEFAULT NULL,
  `rejectedByDrivers` json DEFAULT NULL,
  `sectionId` text COLLATE utf8mb4_unicode_ci,
  `sourceLocation` json DEFAULT NULL,
  `sourceLocationName` text COLLATE utf8mb4_unicode_ci,
  `sourcePoint` json DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci,
  `subTotal` text COLLATE utf8mb4_unicode_ci,
  `taxSetting` json DEFAULT NULL,
  `vehicleId` text COLLATE utf8mb4_unicode_ci,
  `zoneId` text COLLATE utf8mb4_unicode_ci,
  `reviewsCount` bigint DEFAULT NULL,
  `reviewsSum` double DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rental_orders_authorid_index` (`authorID`),
  KEY `rental_orders_createdat_index` (`createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental_orders`
--

LOCK TABLES `rental_orders` WRITE;
/*!40000 ALTER TABLE `rental_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `rental_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rental_packages`
--

DROP TABLE IF EXISTS `rental_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rental_packages` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `baseFare` double DEFAULT NULL,
  `includedHours` double DEFAULT NULL,
  `includedDistance` double DEFAULT NULL,
  `extraKmFare` double DEFAULT NULL,
  `extraMinuteFare` double DEFAULT NULL,
  `vehicleTypeId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `createdAt` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rental_packages_vehicletypeid_index` (`vehicleTypeId`),
  KEY `rental_packages_published_index` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental_packages`
--

LOCK TABLES `rental_packages` WRITE;
/*!40000 ALTER TABLE `rental_packages` DISABLE KEYS */;
/*!40000 ALTER TABLE `rental_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rental_vehicle_type`
--

DROP TABLE IF EXISTS `rental_vehicle_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rental_vehicle_type` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `rental_vehicle_icon` text COLLATE utf8mb4_unicode_ci,
  `isActive` tinyint(1) DEFAULT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rental_vehicle_type_isactive_index` (`isActive`),
  KEY `rental_vehicle_type_sectionid_index` (`sectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rental_vehicle_type`
--

LOCK TABLES `rental_vehicle_type` WRITE;
/*!40000 ALTER TABLE `rental_vehicle_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `rental_vehicle_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_attributes`
--

DROP TABLE IF EXISTS `review_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review_attributes` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_attributes`
--

LOCK TABLES `review_attributes` WRITE;
/*!40000 ALTER TABLE `review_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `isActive` tinyint(1) DEFAULT NULL,
  `serviceType` text COLLATE utf8mb4_unicode_ci,
  `sectionImage` text COLLATE utf8mb4_unicode_ci,
  `color` text COLLATE utf8mb4_unicode_ci,
  `dine_in_active` tinyint(1) DEFAULT NULL,
  `nearByRadius` text COLLATE utf8mb4_unicode_ci,
  `delivery_charge` double DEFAULT NULL,
  `is_product_details` tinyint(1) DEFAULT NULL,
  `cab_service_template` text COLLATE utf8mb4_unicode_ci,
  `adminCommision` json DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sections_isactive_index` (`isActive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES ('section_beauty','Beauty & Cosmetics',1,'Ecommerce Service','http://feimso.test/img/feimso/photos/fashion-08.jpg','#d1657f',0,'50',10,0,NULL,'{\"type\": \"percentage\", \"enable\": false, \"commission\": 0}','{\"id\": \"section_beauty\"}','2026-10-08 21:00:48.247408','2026-10-08 21:00:48.247408'),('section_shop','Fashion',1,'Ecommerce Service','http://feimso.test/img/feimso/photos/fashion-04.jpg','#c9973a',0,'50',10,0,NULL,'{\"type\": \"percentage\", \"enable\": false, \"commission\": 0}','{\"id\": \"section_shop\"}','2026-10-01 19:36:25.374824','2026-10-08 21:00:48.240700');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('AdminCommission','{\"isEnabled\": false}','2026-10-01 19:36:25.347619','2026-10-01 20:03:05.475995'),('CODSettings','{\"isEnabled\": true}','2026-10-01 19:36:25.296947','2026-10-01 20:03:05.415842'),('ContactUs','{\"Email\": \"support@feimso.local\", \"Phone\": \"+971 4 000 0000\", \"Address\": \"Dubai, United Arab Emirates\"}','2026-10-01 19:36:25.289421','2026-10-01 20:03:05.406306'),('DeliveryCharge','{\"amount\": 5.0, \"vendor_can_modify\": false, \"delivery_charges_per_km\": 1.0, \"minimum_delivery_charges\": 5.0, \"minimum_delivery_charges_within_km\": 3.0}','2026-10-01 19:36:25.246753','2026-10-01 20:03:05.336939'),('DriverNearBy','{\"distanceType\": \"Km\", \"driverRadios\": \"50\", \"selectedMapType\": \"osm\", \"enableOTPTripStartForRental\": false}','2026-10-01 19:36:25.238547','2026-10-01 20:03:05.324717'),('MercadoPago','{\"PublicKey\": \"\", \"isEnabled\": false, \"AccessToken\": \"\", \"isSandboxEnabled\": true}','2026-10-01 19:36:25.327273','2026-10-01 20:03:05.453093'),('PaytmSettings','{\"isEnabled\": false}','2026-10-01 19:36:25.343556','2026-10-01 20:03:05.471334'),('VendorNearBy','{\"radios\": \"50\"}','2026-10-01 19:36:25.242340','2026-10-01 20:03:05.330724'),('Version','{\"web_version\": \"1.0.0\"}','2026-10-01 19:36:25.257438','2026-10-01 20:03:05.355194'),('flutterWave','{\"isEnable\": false, \"isSandbox\": true, \"publicKey\": \"\", \"secretKey\": \"\", \"encryptionKey\": \"\"}','2026-10-01 19:36:25.323126','2026-10-01 20:03:05.448518'),('footerTemplate','{\"footerTemplate\": \"\"}','2026-10-01 19:36:25.277594','2026-10-01 20:03:05.391719'),('globalSettings','{\"appLogo\": \"http://feimso.test/img/logo_web.png\", \"isSelfDelivery\": false, \"applicationName\": \"Feimso\", \"defaultCountryCode\": \"+971\", \"isEnableAdsFeature\": false}','2026-10-01 19:36:25.228999','2026-10-01 20:03:05.308482'),('googleMapKey','{\"key\": \"\"}','2026-10-01 19:36:25.250443','2026-10-01 20:03:05.343118'),('homepageTemplate','{\"homepageTemplate\": \"<section class=\\\"py-5 bg-light\\\" style=\\\"min-height:100vh\\\">\\n  <div class=\\\"container\\\">\\n    <div class=\\\"text-center mb-4\\\"><div id=\\\"logo_web\\\"></div></div>\\n    <div class=\\\"row justify-content-center\\\">\\n      <div class=\\\"col-lg-7\\\">\\n        <div class=\\\"bg-white shadow-sm rounded p-4\\\">\\n          <h4 class=\\\"mb-3 text-center\\\">Where should we deliver?</h4>\\n          <div class=\\\"input-group location-group mb-4\\\">\\n            <input type=\\\"text\\\" id=\\\"user_locationnew\\\" class=\\\"form-control\\\" placeholder=\\\"Search your address\\\" autocomplete=\\\"off\\\">\\n            <div class=\\\"input-group-append\\\">\\n              <button type=\\\"button\\\" class=\\\"btn btn-outline-secondary locate-me\\\"><i class=\\\"feather-map-pin\\\"></i> Locate me</button>\\n            </div>\\n          </div>\\n          <h6 class=\\\"mb-2\\\">Choose a service</h6>\\n          <div class=\\\"cat-slider\\\" id=\\\"sections\\\"></div>\\n          <div class=\\\"text-center mt-4\\\">\\n            <button type=\\\"button\\\" class=\\\"btn btn-primary btn-lg btn-continue px-5\\\">Continue</button>\\n          </div>\\n        </div>\\n      </div>\\n    </div>\\n  </div>\\n</section>\"}','2026-10-01 19:36:25.274378','2026-10-01 20:03:05.386732'),('languages','{\"list\": [{\"slug\": \"en\", \"title\": \"English\", \"is_rtl\": false, \"isActive\": true}, {\"slug\": \"ar\", \"title\": \"العربية\", \"is_rtl\": true, \"isActive\": true}]}','2026-10-01 19:36:25.267581','2026-10-01 20:03:05.373283'),('maintenance_settings','{\"isMaintenanceModeForCustomer\": false}','2026-10-01 19:36:25.292694','2026-10-01 20:03:05.410748'),('midtrans_settings','{\"image\": \"\", \"enable\": false, \"isSandbox\": true, \"serverKey\": \"\"}','2026-10-01 19:36:25.335372','2026-10-01 20:03:05.462599'),('notification_setting','{\"serviceJson\": \"\"}','2026-10-01 19:36:25.271247','2026-10-01 20:03:05.379736'),('orange_money_settings','{\"auth\": \"\", \"image\": \"\", \"enable\": false, \"clientId\": \"\", \"cancelUrl\": \"\", \"isSandbox\": true, \"notifyUrl\": \"\", \"returnUrl\": \"\", \"merchantKey\": \"\", \"clientSecret\": \"\"}','2026-10-01 19:36:25.338828','2026-10-01 20:03:05.467363'),('payFastSettings','{\"isEnable\": false, \"isSandbox\": true, \"cancel_url\": \"\", \"notify_url\": \"\", \"return_url\": \"\", \"merchant_id\": \"\", \"merchant_key\": \"\"}','2026-10-01 19:36:25.315634','2026-10-01 20:03:05.439542'),('payStack','{\"isEnable\": false, \"isSandbox\": true, \"publicKey\": \"\", \"secretKey\": \"\"}','2026-10-01 19:36:25.319453','2026-10-01 20:03:05.443956'),('paypalSettings','{\"isLive\": false, \"isEnabled\": false, \"paypalAppId\": \"\", \"paypalClient\": \"\", \"paypalSecret\": \"\"}','2026-10-01 19:36:25.311253','2026-10-01 20:03:05.434985'),('placeHolderImage','{\"image\": \"http://feimso.test/img/pro1.jpg\"}','2026-10-01 19:36:25.234477','2026-10-01 20:03:05.319157'),('privacyPolicy','{\"privacy_policy\": \"<p>Privacy policy goes here.</p>\"}','2026-10-01 19:36:25.281741','2026-10-01 20:03:05.396835'),('razorpaySettings','{\"isEnabled\": false, \"razorpayKey\": \"\", \"razorpaySecret\": \"\", \"isSandboxEnabled\": true}','2026-10-01 19:36:25.304176','2026-10-01 20:03:05.425811'),('specialDiscountOffer','{\"isEnable\": false}','2026-10-01 19:36:25.260627','2026-10-01 20:03:05.361012'),('story','{\"isEnabled\": false}','2026-10-01 19:36:25.264354','2026-10-01 20:03:05.367464'),('stripeSettings','{\"isEnabled\": false, \"stripeKey\": \"\", \"stripeSecret\": \"\", \"isSandboxEnabled\": true, \"clientpublishableKey\": \"\"}','2026-10-01 19:36:25.307603','2026-10-01 20:03:05.430330'),('taxSetting','{\"isEnabled\": false}','2026-10-01 19:36:25.351129','2026-10-01 20:03:05.480530'),('termsAndConditions','{\"terms_and_condition\": \"<p>Terms and conditions go here.</p>\"}','2026-10-01 19:36:25.285202','2026-10-01 20:03:05.401644'),('vendor','{\"subscription_model\": false}','2026-10-01 19:36:25.254015','2026-10-01 20:03:05.349023'),('walletSettings','{\"isEnabled\": true}','2026-10-01 19:36:25.300674','2026-10-01 20:03:05.420632'),('xendit_settings','{\"image\": \"\", \"apiKey\": \"\", \"enable\": false, \"isSandbox\": true}','2026-10-01 19:36:25.331629','2026-10-01 20:03:05.457632');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story`
--

DROP TABLE IF EXISTS `story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `vendorID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videoUrl` json DEFAULT NULL,
  `videoThumbnail` text COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `story_vendorid_index` (`vendorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story`
--

LOCK TABLES `story` WRITE;
/*!40000 ALTER TABLE `story` DISABLE KEYS */;
/*!40000 ALTER TABLE `story` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax`
--

DROP TABLE IF EXISTS `tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable` tinyint(1) DEFAULT NULL,
  `sectionId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tax_country_index` (`country`),
  KEY `tax_enable_index` (`enable`),
  KEY `tax_sectionid_index` (`sectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax`
--

LOCK TABLES `tax` WRITE;
/*!40000 ALTER TABLE `tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstName` text COLLATE utf8mb4_unicode_ci,
  `lastName` text COLLATE utf8mb4_unicode_ci,
  `phoneNumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countryCode` text COLLATE utf8mb4_unicode_ci,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `feimso_role` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'buyer|individual_seller|licensed_creator|business|advertising_creator',
  `verification_status` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'unverified' COMMENT 'unverified|pending|verified|expired|suspended|rejected',
  `verification_notes` text COLLATE utf8mb4_unicode_ci,
  `verification_expires_at` datetime(6) DEFAULT NULL,
  `referral_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invite_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `profilePictureURL` text COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime(6) DEFAULT NULL,
  `coordinates` json DEFAULT NULL,
  `g` json DEFAULT NULL,
  `wallet_amount` double DEFAULT NULL,
  `shippingAddress` json DEFAULT NULL,
  `fcmToken` text COLLATE utf8mb4_unicode_ci,
  `vendorID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewsCount` double DEFAULT NULL,
  `reviewsSum` double DEFAULT NULL,
  `subscriptionTotalOrders` bigint DEFAULT NULL,
  `subscriptionPlanId` text COLLATE utf8mb4_unicode_ci,
  `subscriptionExpiryDate` datetime(6) DEFAULT NULL,
  `subscription_plan` json DEFAULT NULL,
  `adminCommission` json DEFAULT NULL,
  `carNumber` text COLLATE utf8mb4_unicode_ci,
  `carName` text COLLATE utf8mb4_unicode_ci,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin GENERATED ALWAYS AS (`id`) VIRTUAL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  KEY `users_phonenumber_index` (`phoneNumber`),
  KEY `users_role_index` (`role`),
  KEY `users_active_index` (`active`),
  KEY `users_vendorid_index` (`vendorID`),
  KEY `users_feimso_role_index` (`feimso_role`),
  KEY `users_verification_status_index` (`verification_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `firstName`, `lastName`, `phoneNumber`, `countryCode`, `role`, `feimso_role`, `verification_status`, `verification_notes`, `verification_expires_at`, `referral_code`, `referred_by`, `invite_code`, `active`, `profilePictureURL`, `createdAt`, `coordinates`, `g`, `wallet_amount`, `shippingAddress`, `fcmToken`, `vendorID`, `reviewsCount`, `reviewsSum`, `subscriptionTotalOrders`, `subscriptionPlanId`, `subscriptionExpiryDate`, `subscription_plan`, `adminCommission`, `carNumber`, `carName`, `password`, `remember_token`, `email_verified_at`, `extra`, `_created`, `_updated`) VALUES ('admin00000000000000000000000','admin@feimso.local','Feimso','Admin','502073768','+971','admin',NULL,'unverified',NULL,NULL,NULL,NULL,NULL,1,NULL,'2026-10-01 19:45:24.000000',NULL,NULL,0,'[{\"id\": \"25yvfF99GiGFqVSG3n39\", \"address\": \"Tower 1, Apt 1203\", \"landmark\": null, \"locality\": \"The Dubai Mall, Downtown Dubai, Dubai, United Arab Emirates\", \"location\": {\"latitude\": 25.1975, \"longitude\": 55.2796}, \"addressAs\": \"Home\", \"isDefault\": true}]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$10$5FDek6vZhiC3/dfVXIyWfeqoFZPAnK.8tugjU4Bf2Ic/kdegbOnbm',NULL,NULL,NULL,'2026-10-01 19:36:25.000000','2026-10-01 20:12:37.166998'),('customer00000000000000000000','customer@feimso.local','Umer','Customer','502224659','+971','customer',NULL,'unverified',NULL,NULL,NULL,NULL,NULL,1,NULL,'2026-10-01 19:45:24.000000',NULL,NULL,0,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$10$kqYvUUcFPbWcpJeY4CFMxewtg3FLT20LzcXWuS69htLG88RnqBWC6','wJ3eCchiVOmP8IUKt3ky3SXvUrFV6oRtsjR6JZUbXVcq3y2L8X8jVA8YhYc8',NULL,NULL,'2026-10-01 19:36:25.000000','2026-10-01 20:02:39.000000'),('nEY1xxxXTU83nXqMHKfdwoXODW2l','umer.rehman996@gmail.com','Umer','Rehman','3024688336','+92','customer',NULL,'unverified',NULL,NULL,NULL,NULL,NULL,1,NULL,'2026-10-07 21:47:39.131247','{\"lat\": 0, \"lng\": 0, \"__fs\": \"geo\"}','{\"geohash\": \"7zzzzzzzzz\", \"geopoint\": {\"lat\": 0, \"lng\": 0, \"__fs\": \"geo\"}}',NULL,'[{\"id\": \"bcdXDmzZhGVQjGJPiaot\", \"address\": \"3\", \"landmark\": null, \"locality\": \"Dubai, United Arab Emirates\", \"location\": {\"latitude\": 24.8047465, \"longitude\": 56.1460508}, \"addressAs\": \"Home\", \"isDefault\": true}]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$2y$10$gMllLxBfmp8XR6j4e8daTuf3DCo2rXkt9SkGU.c.hNxNZjHKq2RKq','YPqHD29czaN2CuNGHRsgMImEmTNiLUlPLQmy0i2K2WlbzsGHWDy0CzOXbnAT',NULL,'{\"id\": \"nEY1xxxXTU83nXqMHKfdwoXODW2l\"}','2026-10-07 21:47:38.000000','2026-10-10 11:46:27.517098'),('vendorowner00000000000000000','vendor@feimso.local','Demo','Vendor','503525884','+971','vendor',NULL,'unverified',NULL,NULL,NULL,NULL,NULL,1,NULL,'2026-10-01 19:45:24.000000',NULL,NULL,0,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-01 19:36:25.000000','2026-10-01 19:45:24.000000');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_attributes`
--

DROP TABLE IF EXISTS `vendor_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor_attributes` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_attributes`
--

LOCK TABLES `vendor_attributes` WRITE;
/*!40000 ALTER TABLE `vendor_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendor_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_categories`
--

DROP TABLE IF EXISTS `vendor_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor_categories` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `photo` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `section_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  `show_in_homepage` tinyint(1) DEFAULT NULL,
  `review_attributes` json DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_categories_section_id_index` (`section_id`),
  KEY `vendor_categories_publish_index` (`publish`),
  KEY `vendor_categories_show_in_homepage_index` (`show_in_homepage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_categories`
--

LOCK TABLES `vendor_categories` WRITE;
/*!40000 ALTER TABLE `vendor_categories` DISABLE KEYS */;
INSERT INTO `vendor_categories` VALUES ('cat_footwear','Footwear','http://feimso.test/img/feimso/photos/fashion-10.jpg','','section_shop',1,1,'[]','{\"id\": \"cat_footwear\"}','2026-10-08 21:00:48.267502','2026-10-08 21:00:48.267502'),('cat_makeup','Makeup','http://feimso.test/img/feimso/photos/fashion-08.jpg','','section_beauty',1,1,'[]','{\"id\": \"cat_makeup\"}','2026-10-08 21:00:48.277336','2026-10-08 21:00:48.277336'),('cat_men','Men','http://feimso.test/img/feimso/photos/fashion-09.jpg','','section_shop',1,1,'[]','{\"id\": \"cat_men\"}','2026-10-08 21:00:48.257290','2026-10-08 21:00:48.257290'),('cat_skincare','Skincare','http://feimso.test/img/feimso/photos/fashion-08.jpg','','section_beauty',1,1,'[]','{\"id\": \"cat_skincare\"}','2026-10-08 21:00:48.272242','2026-10-08 21:00:48.272242'),('cat_streetwear','Streetwear','http://feimso.test/img/feimso/photos/fashion-01.jpg','','section_shop',1,1,'[]','{\"id\": \"cat_streetwear\"}','2026-10-08 21:00:48.262964','2026-10-08 21:00:48.262964'),('cat_women','Women','http://feimso.test/img/feimso/photos/fashion-06.jpg','','section_shop',1,1,'[]','{\"id\": \"cat_women\"}','2026-10-08 21:00:48.252869','2026-10-08 21:00:48.252869');
/*!40000 ALTER TABLE `vendor_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_orders`
--

DROP TABLE IF EXISTS `vendor_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor_orders` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `authorID` text COLLATE utf8mb4_unicode_ci,
  `author` json DEFAULT NULL,
  `vendorID` text COLLATE utf8mb4_unicode_ci,
  `vendor` json DEFAULT NULL,
  `products` json DEFAULT NULL,
  `address` json DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci,
  `createdAt` datetime(6) DEFAULT NULL,
  `section_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `couponCode` text COLLATE utf8mb4_unicode_ci,
  `couponId` text COLLATE utf8mb4_unicode_ci,
  `discount` double DEFAULT NULL,
  `specialDiscount` json DEFAULT NULL,
  `deliveryCharge` text COLLATE utf8mb4_unicode_ci,
  `tip_amount` text COLLATE utf8mb4_unicode_ci,
  `adminCommission` text COLLATE utf8mb4_unicode_ci,
  `adminCommissionType` text COLLATE utf8mb4_unicode_ci,
  `taxSetting` json DEFAULT NULL,
  `tax_label` text COLLATE utf8mb4_unicode_ci,
  `tax` text COLLATE utf8mb4_unicode_ci,
  `payment_method` text COLLATE utf8mb4_unicode_ci,
  `takeAway` tinyint(1) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `scheduleTime` datetime(6) DEFAULT NULL,
  `driver` json DEFAULT NULL,
  `driverID` text COLLATE utf8mb4_unicode_ci,
  `estimatedTimeToPrepare` text COLLATE utf8mb4_unicode_ci,
  `rejectedByDrivers` json DEFAULT NULL,
  `triggerDelevery` datetime(6) DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_orders_createdat_index` (`createdAt`),
  KEY `vendor_orders_section_id_index` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_orders`
--

LOCK TABLES `vendor_orders` WRITE;
/*!40000 ALTER TABLE `vendor_orders` DISABLE KEYS */;
INSERT INTO `vendor_orders` VALUES ('6abebf51c0aa0','admin00000000000000000000000','{\"id\": \"admin00000000000000000000000\", \"role\": \"admin\", \"email\": \"admin@feimso.local\", \"active\": true, \"lastName\": \"Admin\", \"createdAt\": {\"n\": 0, \"s\": 1790883924, \"__fs\": \"ts\"}, \"firstName\": \"Feimso\", \"countryCode\": \"+971\", \"phoneNumber\": \"502073768\", \"wallet_amount\": 0, \"shippingAddress\": [{\"id\": \"25yvfF99GiGFqVSG3n39\", \"address\": \"Tower 1, Apt 1203\", \"landmark\": null, \"locality\": \"The Dubai Mall, Downtown Dubai, Dubai, United Arab Emirates\", \"location\": {\"latitude\": 25.1975, \"longitude\": 55.2796}, \"addressAs\": \"Home\", \"isDefault\": true}]}','demo_vendor_pizza','{\"g\": {\"geohash\": \"thrnwtxtx4\", \"geopoint\": {\"lat\": 25.08, \"lng\": 55.14, \"__fs\": \"geo\"}}, \"id\": \"demo_vendor_pizza\", \"photo\": \"http://feimso.test/img/starter2.jpg\", \"title\": \"Pizza Corner\", \"author\": \"vendorowner00000000000000000\", \"photos\": [], \"zoneId\": \"zone_uae\", \"filters\": [], \"latitude\": 25.08, \"location\": \"Dubai, United Arab Emirates\", \"createdAt\": {\"n\": 233152000, \"s\": 1790883407, \"__fs\": \"ts\"}, \"longitude\": 55.14, \"categoryID\": [\"cat_pizza\"], \"reststatus\": true, \"reviewsSum\": 0, \"section_id\": \"section_food\", \"coordinates\": {\"lat\": 25.08, \"lng\": 55.14, \"__fs\": \"geo\"}, \"description\": \"Pizza Corner — demo store\", \"phonenumber\": \"+971500000000\", \"openDineTime\": \"09:00 AM\", \"reviewsCount\": 0, \"workingHours\": [{\"day\": \"Sunday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Monday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Tuesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Wednesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Thursday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Friday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Saturday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}], \"categoryTitle\": [\"Pizza\"], \"closeDineTime\": \"11:00 PM\", \"isSelfDelivery\": false, \"restaurantCost\": 60, \"specialDiscount\": [], \"enabledDiveInFuture\": true, \"restaurantMenuPhotos\": [], \"specialDiscountEnable\": false}','[{\"id\": \"demo_p_margherita\", \"name\": \"Margherita Pizza\", \"size\": null, \"photo\": \"http://feimso.test/img/pro3.jpg\", \"price\": \"35\", \"extras\": [], \"quantity\": 1, \"vendorID\": \"demo_vendor_pizza\", \"category_id\": \"cat_pizza\", \"extras_price\": \"0\", \"variant_info\": null, \"discountPrice\": \"35\"}]','{\"id\": \"25yvfF99GiGFqVSG3n39\", \"address\": \"Tower 1, Apt 1203\", \"landmark\": null, \"locality\": \"The Dubai Mall, Downtown Dubai, Dubai, United Arab Emirates\", \"location\": {\"latitude\": 25.1975, \"longitude\": 55.2796}, \"addressAs\": \"Home\", \"isDefault\": true}','Order Placed','2026-10-01 20:15:19.849603','section_food',NULL,NULL,0,'{\"specialType\": null, \"special_discount\": 0, \"special_discount_label\": 0}','19.1',NULL,'0','fixed','[]',NULL,'0','cod',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'{\"id\": \"6abebf51c0aa0\"}','2026-10-01 20:15:19.850310','2026-10-01 20:15:19.850310'),('6aca25a04333c','nEY1xxxXTU83nXqMHKfdwoXODW2l','{\"g\": {\"geohash\": \"7zzzzzzzzz\", \"geopoint\": {\"lat\": 0, \"lng\": 0, \"__fs\": \"geo\"}}, \"id\": \"nEY1xxxXTU83nXqMHKfdwoXODW2l\", \"role\": \"customer\", \"email\": \"umer.rehman996@gmail.com\", \"active\": true, \"lastName\": \"Rehman\", \"createdAt\": {\"n\": 131247000, \"s\": 1791409659, \"__fs\": \"ts\"}, \"firstName\": \"Umer\", \"coordinates\": {\"lat\": 0, \"lng\": 0, \"__fs\": \"geo\"}, \"countryCode\": \"+92\", \"phoneNumber\": \"3024688336\", \"shippingAddress\": [{\"id\": \"bcdXDmzZhGVQjGJPiaot\", \"address\": \"3\", \"landmark\": null, \"locality\": \"Dubai, United Arab Emirates\", \"location\": {\"latitude\": 24.8047465, \"longitude\": 56.1460508}, \"addressAs\": \"Home\", \"isDefault\": true}]}','demo_vendor_beauty','{\"g\": {\"geohash\": \"thrr3squwg\", \"geopoint\": {\"lat\": 25.2048, \"lng\": 55.2708, \"__fs\": \"geo\"}}, \"id\": \"demo_vendor_beauty\", \"photo\": \"http://feimso.test/img/feimso/photos/fashion-08.jpg\", \"title\": \"Glow Studio\", \"author\": \"vendorowner00000000000000000\", \"photos\": [], \"zoneId\": \"zone_uae\", \"filters\": [], \"latitude\": 25.2048, \"location\": \"Dubai, United Arab Emirates\", \"createdAt\": {\"n\": 185060000, \"s\": 1791493248, \"__fs\": \"ts\"}, \"longitude\": 55.2708, \"categoryID\": [\"cat_skincare\", \"cat_makeup\"], \"reststatus\": true, \"reviewsSum\": 0, \"section_id\": \"section_beauty\", \"coordinates\": {\"lat\": 25.2048, \"lng\": 55.2708, \"__fs\": \"geo\"}, \"description\": \"Glow Studio — demo store\", \"phonenumber\": \"+971500000000\", \"openDineTime\": \"09:00 AM\", \"reviewsCount\": 0, \"workingHours\": [{\"day\": \"Sunday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Monday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Tuesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Wednesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Thursday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Friday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Saturday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}], \"categoryTitle\": [\"Skincare\", \"Makeup\"], \"closeDineTime\": \"11:00 PM\", \"isSelfDelivery\": false, \"restaurantCost\": 60, \"specialDiscount\": [], \"enabledDiveInFuture\": false, \"restaurantMenuPhotos\": [], \"specialDiscountEnable\": false}','[{\"id\": \"demo_p_lipstick\", \"name\": \"Velvet Matte Lipstick\", \"size\": null, \"photo\": \"http://feimso.test/img/feimso/photos/fashion-04.jpg\", \"price\": \"85\", \"extras\": [], \"quantity\": 1, \"vendorID\": \"demo_vendor_beauty\", \"category_id\": \"cat_makeup\", \"extras_price\": \"0\", \"variant_info\": null, \"discountPrice\": null}]','{\"id\": \"bcdXDmzZhGVQjGJPiaot\", \"address\": \"3\", \"landmark\": null, \"locality\": \"Dubai, United Arab Emirates\", \"location\": {\"latitude\": 24.8047465, \"longitude\": 56.1460508}, \"addressAs\": \"Home\", \"isDefault\": true}','Order Placed','2026-10-10 11:47:00.909526','section_beauty',NULL,NULL,0,'{\"specialType\": null, \"special_discount\": 0, \"special_discount_label\": 0}','10',NULL,'0','fixed','[]',NULL,'0','cod',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'{\"id\": \"6aca25a04333c\"}','2026-10-10 11:47:00.910680','2026-10-10 11:47:00.910680');
/*!40000 ALTER TABLE `vendor_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor_products`
--

DROP TABLE IF EXISTS `vendor_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendor_products` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` double DEFAULT NULL,
  `disPrice` double DEFAULT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci,
  `photos` json DEFAULT NULL,
  `vendorID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoryID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brandID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  `createdAt` datetime(6) DEFAULT NULL,
  `quantity` bigint DEFAULT NULL,
  `veg` tinyint(1) DEFAULT NULL,
  `nonveg` tinyint(1) DEFAULT NULL,
  `takeawayOption` tinyint(1) DEFAULT NULL,
  `calories` bigint DEFAULT NULL,
  `grams` bigint DEFAULT NULL,
  `proteins` bigint DEFAULT NULL,
  `fats` bigint DEFAULT NULL,
  `addOnsTitle` json DEFAULT NULL,
  `addOnsPrice` json DEFAULT NULL,
  `sizePrice` json DEFAULT NULL,
  `size` json DEFAULT NULL,
  `item_attribute` json DEFAULT NULL,
  `product_specification` json DEFAULT NULL,
  `reviewsCount` bigint DEFAULT NULL,
  `reviewsSum` double DEFAULT NULL,
  `reviewAttributes` json DEFAULT NULL,
  `isDigitalProduct` tinyint(1) DEFAULT NULL,
  `digitalProduct` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_products_vendorid_index` (`vendorID`),
  KEY `vendor_products_categoryid_index` (`categoryID`),
  KEY `vendor_products_brandid_index` (`brandID`),
  KEY `vendor_products_section_id_index` (`section_id`),
  KEY `vendor_products_publish_index` (`publish`),
  KEY `vendor_products_createdat_index` (`createdAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor_products`
--

LOCK TABLES `vendor_products` WRITE;
/*!40000 ALTER TABLE `vendor_products` DISABLE KEYS */;
INSERT INTO `vendor_products` VALUES ('demo_p_blouse','Linen Summer Blouse','Linen Summer Blouse (demo product)',150,119,'http://feimso.test/img/feimso/photos/fashion-02.jpg','[\"http://feimso.test/img/feimso/photos/fashion-02.jpg\"]','demo_vendor_boutique','cat_women','brand_feimso','section_shop',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_blouse\"}','2026-10-08 21:00:48.335862','2026-10-08 21:00:48.335862'),('demo_p_coat','Powder Blue Trench Coat','Powder Blue Trench Coat (demo product)',590,0,'http://feimso.test/img/feimso/photos/fashion-07.jpg','[\"http://feimso.test/img/feimso/photos/fashion-07.jpg\"]','demo_vendor_boutique','cat_women','brand_feimso','section_shop',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_coat\"}','2026-10-08 21:00:48.320347','2026-10-08 21:00:48.320347'),('demo_p_dress','Floral Wrap Dress','Floral Wrap Dress (demo product)',320,279,'http://feimso.test/img/feimso/photos/fashion-06.jpg','[\"http://feimso.test/img/feimso/photos/fashion-06.jpg\"]','demo_vendor_boutique','cat_women','brand_feimso','section_shop',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_dress\"}','2026-10-08 21:00:48.314377','2026-10-08 21:00:48.314377'),('demo_p_lipstick','Velvet Matte Lipstick','Velvet Matte Lipstick (demo product)',85,0,'http://feimso.test/img/feimso/photos/fashion-04.jpg','[\"http://feimso.test/img/feimso/photos/fashion-04.jpg\"]','demo_vendor_beauty','cat_makeup','brand_feimso','section_beauty',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_lipstick\"}','2026-10-08 21:00:48.367478','2026-10-08 21:00:48.367478'),('demo_p_serum','Radiance Face Serum','Radiance Face Serum (demo product)',180,149,'http://feimso.test/img/feimso/photos/fashion-08.jpg','[\"http://feimso.test/img/feimso/photos/fashion-08.jpg\"]','demo_vendor_beauty','cat_skincare','brand_feimso','section_beauty',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_serum\"}','2026-10-08 21:00:48.360953','2026-10-08 21:00:48.360953'),('demo_p_set','Sunshine Hoodie Set','Sunshine Hoodie Set (demo product)',380,329,'http://feimso.test/img/feimso/photos/fashion-01.jpg','[\"http://feimso.test/img/feimso/photos/fashion-01.jpg\"]','demo_vendor_street','cat_streetwear','brand_feimso','section_shop',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_set\"}','2026-10-08 21:00:48.348413','2026-10-08 21:00:48.348413'),('demo_p_shirt','Essential V-Neck Tee','Essential V-Neck Tee (demo product)',95,0,'http://feimso.test/img/feimso/photos/fashion-09.jpg','[\"http://feimso.test/img/feimso/photos/fashion-09.jpg\"]','demo_vendor_boutique','cat_men','brand_feimso','section_shop',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_shirt\"}','2026-10-01 19:36:25.461527','2026-10-08 21:00:48.328872'),('demo_p_sneaker','Red Runner Sneakers','Red Runner Sneakers (demo product)',450,399,'http://feimso.test/img/feimso/photos/fashion-10.jpg','[\"http://feimso.test/img/feimso/photos/fashion-10.jpg\"]','demo_vendor_street','cat_footwear','brand_feimso','section_shop',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_sneaker\"}','2026-10-08 21:00:48.354465','2026-10-08 21:00:48.354465'),('demo_p_trousers','Striped Wide-Leg Trousers','Striped Wide-Leg Trousers (demo product)',210,0,'http://feimso.test/img/feimso/photos/fashion-05.jpg','[\"http://feimso.test/img/feimso/photos/fashion-05.jpg\"]','demo_vendor_street','cat_streetwear','brand_feimso','section_shop',1,'2026-10-08 21:00:48.185060',-1,0,0,0,0,0,0,0,'[]','[]','[]','[]',NULL,'[]',0,0,NULL,0,NULL,'{\"id\": \"demo_p_trousers\"}','2026-10-08 21:00:48.341914','2026-10-08 21:00:48.341914');
/*!40000 ALTER TABLE `vendor_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `photo` text COLLATE utf8mb4_unicode_ci,
  `location` text COLLATE utf8mb4_unicode_ci,
  `phonenumber` text COLLATE utf8mb4_unicode_ci,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `coordinates` json DEFAULT NULL,
  `g` json DEFAULT NULL,
  `author` text COLLATE utf8mb4_unicode_ci,
  `section_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoneId` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoryID` json DEFAULT NULL,
  `createdAt` datetime(6) DEFAULT NULL,
  `reviewsCount` bigint DEFAULT NULL,
  `reviewsSum` double DEFAULT NULL,
  `reststatus` tinyint(1) DEFAULT NULL,
  `workingHours` json DEFAULT NULL,
  `specialDiscountEnable` tinyint(1) DEFAULT NULL,
  `specialDiscount` json DEFAULT NULL,
  `deliveryCharge` json DEFAULT NULL,
  `isSelfDelivery` tinyint(1) DEFAULT NULL,
  `adminCommission` json DEFAULT NULL,
  `enabledDiveInFuture` tinyint(1) DEFAULT NULL,
  `openDineTime` text COLLATE utf8mb4_unicode_ci,
  `closeDineTime` text COLLATE utf8mb4_unicode_ci,
  `restaurantCost` double DEFAULT NULL,
  `restaurantMenuPhotos` json DEFAULT NULL,
  `subscriptionPlanId` text COLLATE utf8mb4_unicode_ci,
  `subscription_plan` json DEFAULT NULL,
  `subscriptionExpiryDate` datetime(6) DEFAULT NULL,
  `subscriptionTotalOrders` bigint DEFAULT NULL,
  `photos` json DEFAULT NULL,
  `filters` json DEFAULT NULL,
  `categoryTitle` json DEFAULT NULL,
  `vendorCuisineID` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendors_section_id_index` (`section_id`),
  KEY `vendors_zoneid_index` (`zoneId`),
  KEY `vendors_createdat_index` (`createdAt`),
  KEY `vendors_reviewssum_index` (`reviewsSum`),
  KEY `vendors_enableddiveinfuture_index` (`enabledDiveInFuture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES ('demo_vendor_beauty','Glow Studio','Glow Studio — demo store','http://feimso.test/img/feimso/photos/fashion-08.jpg','Dubai, United Arab Emirates','+971500000000',25.2048,55.2708,'{\"lat\": 25.2048, \"lng\": 55.2708, \"__fs\": \"geo\"}','{\"geohash\": \"thrr3squwg\", \"geopoint\": {\"lat\": 25.2048, \"lng\": 55.2708, \"__fs\": \"geo\"}}','vendorowner00000000000000000','section_beauty','zone_uae','[\"cat_skincare\", \"cat_makeup\"]','2026-10-08 21:00:48.185060',0,0,1,'[{\"day\": \"Sunday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Monday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Tuesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Wednesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Thursday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Friday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Saturday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}]',0,'[]',NULL,0,NULL,0,'09:00 AM','11:00 PM',60,'[]',NULL,NULL,NULL,NULL,'[]','[]','[\"Skincare\", \"Makeup\"]',NULL,'{\"id\": \"demo_vendor_beauty\"}','2026-10-08 21:00:48.305804','2026-10-08 21:00:48.305804'),('demo_vendor_boutique','Feimso Boutique','Feimso Boutique — demo store','http://feimso.test/img/feimso/photos/fashion-03.jpg','Dubai, United Arab Emirates','+971500000000',25.1972,55.2744,'{\"lat\": 25.1972, \"lng\": 55.2744, \"__fs\": \"geo\"}','{\"geohash\": \"thrr3g186f\", \"geopoint\": {\"lat\": 25.1972, \"lng\": 55.2744, \"__fs\": \"geo\"}}','vendorowner00000000000000000','section_shop','zone_uae','[\"cat_women\", \"cat_men\", \"cat_footwear\"]','2026-10-08 21:00:48.185060',0,0,1,'[{\"day\": \"Sunday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Monday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Tuesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Wednesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Thursday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Friday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Saturday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}]',0,'[]',NULL,0,NULL,0,'09:00 AM','11:00 PM',60,'[]',NULL,NULL,NULL,NULL,'[]','[]','[\"Women\", \"Men\", \"Footwear\"]',NULL,'{\"id\": \"demo_vendor_boutique\"}','2026-10-01 19:36:25.423384','2026-10-08 21:00:48.289849'),('demo_vendor_street','Urban Edit','Urban Edit — demo store','http://feimso.test/img/feimso/photos/fashion-11.jpg','Dubai, United Arab Emirates','+971500000000',25.08,55.14,'{\"lat\": 25.08, \"lng\": 55.14, \"__fs\": \"geo\"}','{\"geohash\": \"thrnwtxtx4\", \"geopoint\": {\"lat\": 25.08, \"lng\": 55.14, \"__fs\": \"geo\"}}','vendorowner00000000000000000','section_shop','zone_uae','[\"cat_streetwear\", \"cat_footwear\"]','2026-10-08 21:00:48.185060',0,0,1,'[{\"day\": \"Sunday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Monday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Tuesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Wednesday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Thursday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Friday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}, {\"day\": \"Saturday\", \"timeslot\": [{\"to\": \"23:59\", \"from\": \"00:00\"}]}]',0,'[]',NULL,0,NULL,0,'09:00 AM','11:00 PM',60,'[]',NULL,NULL,NULL,NULL,'[]','[]','[\"Streetwear\", \"Footwear\"]',NULL,'{\"id\": \"demo_vendor_street\"}','2026-10-08 21:00:48.299229','2026-10-08 21:00:48.299229');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet`
--

DROP TABLE IF EXISTS `wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `date` datetime(6) DEFAULT NULL,
  `isTopUp` tinyint(1) DEFAULT NULL,
  `order_id` text COLLATE utf8mb4_unicode_ci,
  `payment_method` text COLLATE utf8mb4_unicode_ci,
  `payment_status` text COLLATE utf8mb4_unicode_ci,
  `serviceType` text COLLATE utf8mb4_unicode_ci,
  `transactionUser` text COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `transactionId` text COLLATE utf8mb4_unicode_ci,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_user_id_index` (`user_id`),
  KEY `wallet_date_index` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet`
--

LOCK TABLES `wallet` WRITE;
/*!40000 ALTER TABLE `wallet` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zone`
--

DROP TABLE IF EXISTS `zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zone` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  `area` json DEFAULT NULL,
  `extra` json DEFAULT NULL COMMENT 'fields without a dedicated column',
  `_created` datetime(6) DEFAULT NULL,
  `_updated` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zone_publish_index` (`publish`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zone`
--

LOCK TABLES `zone` WRITE;
/*!40000 ALTER TABLE `zone` DISABLE KEYS */;
INSERT INTO `zone` VALUES ('zone_uae',1,'[{\"lat\": 26.4, \"lng\": 51.4, \"__fs\": \"geo\"}, {\"lat\": 26.4, \"lng\": 56.6, \"__fs\": \"geo\"}, {\"lat\": 22.5, \"lng\": 56.6, \"__fs\": \"geo\"}, {\"lat\": 22.5, \"lng\": 51.4, \"__fs\": \"geo\"}]','{\"id\": \"zone_uae\", \"name\": \"UAE\"}','2026-10-01 19:36:25.365799','2026-10-08 21:00:48.230632');
/*!40000 ALTER TABLE `zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'feimso'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-15  0:26:27
