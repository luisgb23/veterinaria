-- MySQL dump 10.13  Distrib 8.1.0, for macos14.0 (x86_64)
--
-- Host: localhost    Database: itapebi
-- ------------------------------------------------------
-- Server version	8.1.0

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
-- Table structure for table `consultas`
--

DROP TABLE IF EXISTS `consultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultas` (
  `ConsultaId` int NOT NULL AUTO_INCREMENT,
  `ConsultaFecha` date DEFAULT NULL,
  `MascotaId` int DEFAULT NULL,
  `ConsultaMotivo` varchar(255) DEFAULT NULL,
  `ConsultaDiagnostico` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ConsultaTratamiento` mediumtext,
  `ConsultaArchivo1` varchar(255) DEFAULT NULL,
  `ConsultaArchivo2` varchar(255) DEFAULT NULL,
  `ConsultaArchivo3` varchar(255) DEFAULT NULL,
  `ConsultaFchCreacion` datetime DEFAULT NULL,
  `ConsultaFchModificacion` datetime DEFAULT NULL,
  `ConsultaEstado` smallint DEFAULT NULL,
  PRIMARY KEY (`ConsultaId`),
  KEY `consultas_FK` (`MascotaId`),
  CONSTRAINT `consultas_FK` FOREIGN KEY (`MascotaId`) REFERENCES `mascotas` (`MascotaId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultas`
--

LOCK TABLES `consultas` WRITE;
/*!40000 ALTER TABLE `consultas` DISABLE KEYS */;
INSERT INTO `consultas` VALUES (1,'2023-12-15',1,'Dolor en pata trasera','dslfdsjfdsjf','dfndsfsf','','','','2023-12-15 16:17:57','2023-12-21 17:31:06',1),(2,'2023-12-15',4,'Ojo lloroso, con pus','dnasdsndjkn','asndsnds','','','','2023-12-15 16:26:35','2023-12-21 17:31:12',1),(3,'2023-12-18',22,'Dolor en pata trasera','dfhsdkhfskdhfksdfldsfhdsljfhsdkjhf','sdfsdfdskfhdskjfhdsfsdkfhlkdsfhdsjhflsdk','Array',NULL,NULL,'2023-12-18 12:09:50',NULL,1),(4,'2023-12-18',8,'Dolor en pata trasera','fdsflsdfds','sdfmnsdmnfnds','Array',NULL,NULL,'2023-12-18 12:12:22',NULL,1),(5,'2023-12-18',1,'Dolor en pata delantera derecha','dlsdsad','sadasd','','','','2023-12-18 12:22:01','2023-12-21 21:07:31',1),(6,'2023-12-18',1,'','','','','','','2023-12-18 12:23:04',NULL,1),(7,'2023-12-18',21,'dolor en nadibula','dsjdlsajda','dasljdlsajdlsa','InnovaNet.pdf','InnovaNet.pdf','InnovaNet.pdf','2023-12-18 12:25:19',NULL,1),(8,'2023-12-15',4,'Ojo lloroso','supuracion en ojo','asndsnds','','','','2023-12-21 16:40:24',NULL,1),(9,'2023-12-15',1,'Dolor en pata trasera','dslfdsjfdsjf','dfndsfsf','','','','2023-12-21 17:12:35',NULL,1),(10,'2023-12-15',4,'Ojo lloroso, con pus','dnasdsndjkn','asndsnds','','','','2023-12-21 17:14:05',NULL,0),(11,'2023-12-15',1,'Dolor en pata trasera','dslfdsjfdsjf','dfndsfsf','','','','2023-12-21 17:14:20',NULL,0);
/*!40000 ALTER TABLE `consultas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mascotas`
--

DROP TABLE IF EXISTS `mascotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mascotas` (
  `MascotaId` int NOT NULL AUTO_INCREMENT,
  `MascotaNombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `EspecieId` int DEFAULT NULL,
  `PropietarioId` int DEFAULT NULL,
  `MascotaFchNac` date DEFAULT NULL,
  `MascotaFchVencVac` date DEFAULT NULL,
  `MascotaFchCreacion` datetime DEFAULT NULL,
  `MascotaFchModificacion` datetime DEFAULT NULL,
  `MascotaEstado` smallint DEFAULT NULL,
  PRIMARY KEY (`MascotaId`),
  KEY `FK_Especie` (`EspecieId`),
  KEY `FK_Propietario` (`PropietarioId`),
  CONSTRAINT `FK_Especie` FOREIGN KEY (`EspecieId`) REFERENCES `especies` (`EspecieId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_Propietario` FOREIGN KEY (`PropietarioId`) REFERENCES `propietarios` (`PropietarioId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mascotas`
--

LOCK TABLES `mascotas` WRITE;
/*!40000 ALTER TABLE `mascotas` DISABLE KEYS */;
INSERT INTO `mascotas` VALUES (1,'Cora',5,1,'2010-01-23','2024-02-01','2023-11-21 19:41:52',NULL,1),(2,'Cora',5,1,'2010-01-01','2023-01-01','2023-11-28 19:46:05',NULL,1),(4,'Chizo',5,1,'2010-01-23','2024-01-23','2023-11-29 11:34:53',NULL,1),(5,'Negrita',5,1,'2021-10-10','2024-03-10','2023-11-29 13:24:39',NULL,1),(6,'Negrita',5,1,'2021-10-10','2024-03-10','2023-11-29 13:24:41',NULL,1),(7,'Negrita',5,1,'2021-10-10','2024-03-10','2023-11-29 13:24:48',NULL,1),(8,'Negrita',5,1,'2021-10-10','2024-03-10','2023-11-29 13:24:49',NULL,1),(19,'Michi',6,1,'2017-02-01','2024-05-10','2023-12-12 10:16:16',NULL,1),(20,'Michi',6,1,'2017-04-10','2024-05-10','2023-12-12 10:18:19',NULL,0),(21,'Michi',6,1,'2018-01-01','2024-02-20','2023-12-12 10:19:31',NULL,0),(22,'Chiquita',5,2,'2019-02-01','2024-12-20','2023-12-12 13:17:23',NULL,0),(23,'Chiquita',5,1,'2020-02-01','2025-02-01','2023-12-12 13:28:30',NULL,1),(24,'Peque',6,1,'2018-01-01','2024-02-20','2023-12-12 16:51:39','2023-12-12 16:59:16',0);
/*!40000 ALTER TABLE `mascotas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuotas`
--

DROP TABLE IF EXISTS `cuotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cuotas` (
  `CuotaId` int NOT NULL AUTO_INCREMENT,
  `CuotaFecha` date DEFAULT NULL,
  `MascotaId` int DEFAULT NULL,
  `CuotaValor` bigint DEFAULT NULL,
  `CuotaObservacion` varchar(255) DEFAULT NULL,
  `CuotaFchCreacion` datetime DEFAULT NULL,
  `CuotaFchModificacion` datetime DEFAULT NULL,
  `CuotaEstado` smallint DEFAULT NULL,
  PRIMARY KEY (`CuotaId`),
  KEY `cuotas_FK` (`MascotaId`),
  CONSTRAINT `cuotas_FK` FOREIGN KEY (`MascotaId`) REFERENCES `mascotas` (`MascotaId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuotas`
--

LOCK TABLES `cuotas` WRITE;
/*!40000 ALTER TABLE `cuotas` DISABLE KEYS */;
INSERT INTO `cuotas` VALUES (1,'2023-12-01',1,250,'Cuota diciembre','2023-12-22 10:46:05','2023-12-22 11:29:23',1),(2,'2023-12-01',1,250,'Cuota diciembre','2023-12-22 10:46:20',NULL,0),(3,'2023-12-01',1,300,'Cuota diciembre','2023-12-22 11:18:31',NULL,0),(4,'2023-12-01',4,150,'Cuota diciembre','2023-12-22 11:29:44',NULL,1);
/*!40000 ALTER TABLE `cuotas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `UsuarioId` int NOT NULL AUTO_INCREMENT,
  `UsuarioNombre` varchar(100) DEFAULT NULL,
  `UsuarioUser` varchar(100) DEFAULT NULL,
  `UsuarioPwd` varchar(100) DEFAULT NULL,
  `UsuarioFchCreacion` date DEFAULT NULL,
  `UsuarioEstado` smallint DEFAULT NULL,
  PRIMARY KEY (`UsuarioId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Mariana Zammit','mariana','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','2023-11-16',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especies`
--

DROP TABLE IF EXISTS `especies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `especies` (
  `EspecieId` int NOT NULL AUTO_INCREMENT,
  `EspecieNombre` varchar(100) DEFAULT NULL,
  `EspecieFchCreacion` datetime DEFAULT NULL,
  `EspecieFchModificacion` datetime DEFAULT NULL,
  `EspecieEstado` smallint DEFAULT NULL,
  PRIMARY KEY (`EspecieId`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especies`
--

LOCK TABLES `especies` WRITE;
/*!40000 ALTER TABLE `especies` DISABLE KEYS */;
INSERT INTO `especies` VALUES (5,'Canino','2023-11-16 00:00:00',NULL,1),(6,'Felino','2023-11-16 00:00:00',NULL,1),(13,'Equinos','2023-11-16 19:12:37',NULL,1),(14,'Peces','2023-11-16 19:14:29',NULL,1),(15,'Peces','2023-11-17 16:04:56','2023-11-21 14:47:27',0),(16,'Roedores','2023-11-21 14:28:17',NULL,1),(17,'Reptiles','2023-11-29 14:23:40',NULL,0);
/*!40000 ALTER TABLE `especies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propietarios`
--

DROP TABLE IF EXISTS `propietarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `propietarios` (
  `PropietarioId` int NOT NULL AUTO_INCREMENT,
  `PropietarioNombre` varchar(100) DEFAULT NULL,
  `PropietarioApellido` varchar(100) DEFAULT NULL,
  `PropietarioTelefono` varchar(10) DEFAULT NULL,
  `PropietarioDireccion` varchar(255) DEFAULT NULL,
  `PropietarioEmail` varchar(100) DEFAULT NULL,
  `PropietarioFchCreacion` datetime DEFAULT NULL,
  `PropietarioFchModificacion` datetime DEFAULT NULL,
  `PropietarioEstado` smallint DEFAULT NULL,
  PRIMARY KEY (`PropietarioId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propietarios`
--

LOCK TABLES `propietarios` WRITE;
/*!40000 ALTER TABLE `propietarios` DISABLE KEYS */;
INSERT INTO `propietarios` VALUES (1,'Luis','Geymonat','094054281','Los cedros apto.24','luisgeymonatbidart@gmail.com','2023-11-16 00:00:00',NULL,1),(2,'Lucrecia','Dubroca','098557383','Los Cedros apto 24','lucreciadubroca@gmail.com','2023-11-16 18:47:01',NULL,1),(4,'Jorge','Perez Lindo','098700100','av. artigas 1234','jperez@gmail.com','2023-11-16 19:36:27','2023-11-29 14:09:06',1),(5,'Maria','Lopez','091290350','av. artigas 1234','marialopez@gmail.com','2023-11-21 15:14:30','2023-11-21 15:28:48',0);
/*!40000 ALTER TABLE `propietarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-22 11:37:52
