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
/*
db = lgbuy_itapebi
user = lgbuy_lgbuy
pass = df#gV{H^B-qQ
*/
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
-- Dumping routines for database 'itapebi'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-22 11:42:11
