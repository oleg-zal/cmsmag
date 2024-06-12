-- MySQL dump 10.13  Distrib 5.7.42, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: shop
-- ------------------------------------------------------
-- Server version	5.7.42-0ubuntu0.18.04.1

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
-- Table structure for table `advantages`
--

DROP TABLE IF EXISTS `advantages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advantages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `visible` tinyint(4) DEFAULT '1',
  `menu_position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advantages`
--

LOCK TABLES `advantages` WRITE;
/*!40000 ALTER TABLE `advantages` DISABLE KEYS */;
INSERT INTO `advantages` VALUES (1,'Преимущество 1','advantages/adv1.png',1,1),(2,'Преимущество 2','advantages/adv2.png',1,2),(3,'Преимущество 3','advantages/adv3.png',1,3),(4,'Преимущество 4','advantages/adv4.png',1,4),(5,'Преимущество 5','advantages/adv5.png',1,5),(6,'Преимущество 6','advantages/adv6.png',1,6);
/*!40000 ALTER TABLE `advantages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blocked_access`
--

DROP TABLE IF EXISTS `blocked_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocked_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `trying` tinyint(1) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocked_access`
--

LOCK TABLES `blocked_access` WRITE;
/*!40000 ALTER TABLE `blocked_access` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocked_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `visible` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `ketwords` varchar(400) DEFAULT NULL,
  `description` varchar(400) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Товары',1,NULL,1,NULL,'','goods',NULL,'<p>Общие товары для потребления</p>'),(2,'Услуги',1,NULL,2,NULL,'','service',NULL,'<p>Услуги общие</p>'),(3,'Хозяйственные продукты',1,1,1,NULL,NULL,NULL,NULL,''),(4,'Строительные материалы',1,1,2,NULL,NULL,NULL,NULL,''),(5,'Продукты питания',1,1,3,NULL,NULL,NULL,NULL,''),(6,'Услуги парикмахера',1,2,1,NULL,NULL,NULL,NULL,''),(7,'Услуги мастера',1,2,2,NULL,NULL,NULL,NULL,''),(8,'Услуги слесаря',1,2,3,NULL,NULL,NULL,NULL,'');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filters`
--

DROP TABLE IF EXISTS `filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `visible` tinyint(4) DEFAULT '1',
  `parent_id` int(11) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filters`
--

LOCK TABLES `filters` WRITE;
/*!40000 ALTER TABLE `filters` DISABLE KEYS */;
INSERT INTO `filters` VALUES (1,'Color',1,NULL,1,''),(2,'Height',1,NULL,3,''),(3,'Width',1,NULL,2,''),(4,'Green',1,1,1,''),(8,'Red',1,1,2,''),(9,'Black',1,1,3,''),(10,'Blue',1,1,4,''),(11,'2px',1,3,1,''),(12,'4px',1,3,2,''),(13,'8px',1,3,3,''),(14,'10px and more',1,3,4,''),(15,'100 mm',1,2,1,''),(16,'200 mm',1,2,2,''),(17,'600 mm',1,2,3,''),(18,'800 mm and more',1,2,4,'');
/*!40000 ALTER TABLE `filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goods`
--

DROP TABLE IF EXISTS `goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `visible` int(11) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `content` text,
  `keywords` varchar(255) DEFAULT NULL,
  `gallery_img` text,
  `date` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `main_img` varchar(255) DEFAULT NULL,
  `new_gallery_img` varchar(255) DEFAULT NULL,
  `hit` int(11) DEFAULT '0',
  `hot` int(11) DEFAULT '0',
  `sale` int(11) DEFAULT '0',
  `new` int(11) DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_category_id_fk` (`parent_id`),
  CONSTRAINT `goods_category_id_fk` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods`
--

LOCK TABLES `goods` WRITE;
/*!40000 ALTER TABLE `goods` DISABLE KEYS */;
INSERT INTO `goods` VALUES (1,'Мобильный телефон Samsung Galaxy A24 6/128GB Dark Red (SM-A245FDRVSEK)',1,1,'goods/i1.jpeg','<p>dyryh ujjuyjyjy</p>',NULL,'[\"goods\\/i3.jpeg\",\"goods\\/i2.jpeg\",\"goods\\/i1_af2dc445.jpeg\"]','2024-06-08','2024-05-01 19:47:47','mobilniy-telefon-samsung-galaxy-a24-1-1-1',NULL,NULL,1,0,0,0,1,100,0),(2,'Клоунада',1,1,'goods/zelya.jpg','<p>Зеля поц!!!!</p>',NULL,'[\"goods\\/zelya_2e6255e8.jpg\",\"goods\\/zelya_46566e9f.jpg\",\"goods\\/zhirya.jpg\",\"goods\\/zhirya_75b056bc.jpg\"]','2024-06-07','2024-06-08 20:48:07','cloynada-2',NULL,NULL,0,0,0,1,2,200,20),(3,'Бриллиант',1,2,'goods/diam_1.jpg','<p>Любимый камень Лени</p>',NULL,'[\"goods\\/brezh.jpg\",\"goods\\/brezh_10ee9b7f.jpg\",\"goods\\/brezh_efc0571b.jpg\"]','2024-06-08','2024-06-08 20:54:52','briliant-3',NULL,NULL,0,1,0,0,1,1000,0);
/*!40000 ALTER TABLE `goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goods_filters`
--

DROP TABLE IF EXISTS `goods_filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goods_filters` (
  `filters_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  PRIMARY KEY (`filters_id`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods_filters`
--

LOCK TABLES `goods_filters` WRITE;
/*!40000 ALTER TABLE `goods_filters` DISABLE KEYS */;
INSERT INTO `goods_filters` VALUES (4,3),(8,1),(8,3),(9,1),(9,2),(9,3),(10,2),(10,3),(11,3),(12,1),(12,3),(13,1),(15,3),(16,1),(16,3),(17,1),(17,2),(18,2);
/*!40000 ALTER TABLE `goods_filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `information`
--

DROP TABLE IF EXISTS `information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(400) DEFAULT NULL,
  `visible` tinyint(4) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `show_top_menu` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `information`
--

LOCK TABLES `information` WRITE;
/*!40000 ALTER TABLE `information` DISABLE KEYS */;
INSERT INTO `information` VALUES (1,'Оплата и доставка','oplata-i-dostavka',NULL,'',1,3,1),(2,'Акции и скидки','aktsii-i-skidki',NULL,'',1,2,1),(3,'Политика конфиденциальности','politika-konfidentsialnosti',NULL,'',1,1,0);
/*!40000 ALTER TABLE `information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_position` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `visible` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `sub_title` varchar(255) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '1',
  `img` varchar(255) DEFAULT NULL,
  `external_alias` varchar(255) DEFAULT NULL,
  `shot_content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'Акция 1','Продажа ',1,1,'sales/about.jpg','/catalaog/avtozapchasti','Никаких подделок! В Гиперавто принята нулевая терпимость к контрафакту. Неважно, ищете вы заводской «оригинал» или доступный аналог — все автозапчасти должны быть подлинными. Мы являемся официальной точкой продаж автозапчастей в Калужской области ведущих брендов и получаем продукцию напрямую от производителей. Возможность столкнуться с подделками при покупке в Гиперавто исключена.'),(2,'Акция 2','Услуга',2,1,'sales/mash1.jpg','','Достижение цели в поставленные сроки с высоким качеством, с минимальными затратами. Результат не врет! Результат – это наше все! Мы все знаем разницу между «делать» и «сделать». Для нас нет проблем, а есть задачи. Развивая свой профессионализм, мы повышаем нашу результативность непрерывно каждый день.');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `keywords` varchar(400) DEFAULT NULL,
  `description` varchar(400) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `address` varchar(400) DEFAULT NULL,
  `img_years` varchar(255) DEFAULT NULL,
  `number_of_years` int(11) DEFAULT NULL,
  `content` text,
  `shot_content` text,
  `promo_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'Автозапчасти',NULL,'','+39 1221232345','foma.chudnov@ukr.net','settings/logo.svg',NULL,'settings/15.svg',15,NULL,'Самый большой онлайн-ритейлер в стране. С 2005 года мы воплощаем маленькие мечты и грандиозные планы миллионов людей. У нас можно найти буквально все. Мы продаем по справедливой цене и предоставляем гарантию, так как считаем, что онлайн-шопинг должен быть максимально удобным и безопасным. И каждый раз, когда кто-то нажимает «Купить», мы понимаем, что делаем нужное дело.','settings/about.png');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `socials`
--

DROP TABLE IF EXISTS `socials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `socials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `external_alias` varchar(255) DEFAULT NULL,
  `visible` tinyint(4) DEFAULT NULL,
  `menu_position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `socials`
--

LOCK TABLES `socials` WRITE;
/*!40000 ALTER TABLE `socials` DISABLE KEYS */;
INSERT INTO `socials` VALUES (1,'Bado','socials/badoo.png','https://badoo.com',1,4),(2,'Vk','socials/vk.png','https://vk.com',1,3),(3,'Twiter','socials/twiter.png','https://x.com',1,2),(4,'Instagram','socials/instagram.png','https://www.instagram.com',1,1);
/*!40000 ALTER TABLE `socials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `credentials` text,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin',NULL,'05.10.1991','+79283895945','admin@mail.ru','202cb962ac59075b964b07152d234b70',NULL,'2024-05-27 19:47:12');
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

-- Dump completed on 2024-06-12 19:56:32
