CREATE DATABASE  IF NOT EXISTS `ratingdatabase` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ratingdatabase`;
-- MySQL dump 10.13  Distrib 5.6.24, for Win64 (x86_64)
--
-- Host: hpr.cloudapp.net    Database: ratingdatabase
-- ------------------------------------------------------
-- Server version	5.5.45

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
-- Table structure for table `competitors`
--

DROP TABLE IF EXISTS `competitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competitors` (
  `CompetitorName` varchar(50) NOT NULL,
  `GroupName` varchar(254) NOT NULL,
  `Metadata` longtext,
  PRIMARY KEY (`CompetitorName`,`GroupName`),
  KEY `FK_Competitors_0` (`GroupName`),
  CONSTRAINT `FK_Competitors_0` FOREIGN KEY (`GroupName`) REFERENCES `ratinggroups` (`GroupName`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `EventID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(254) NOT NULL,
  `EventStart` datetime NOT NULL,
  `Metadata` longtext,
  PRIMARY KEY (`EventID`),
  KEY `FK_Events_0` (`GroupName`),
  KEY `IND_EVENTS_1` (`EventStart`),
  CONSTRAINT `FK_Events_0` FOREIGN KEY (`GroupName`) REFERENCES `ratinggroups` (`GroupName`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=250068 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jsonresults`
--

DROP TABLE IF EXISTS `jsonresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jsonresults` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DateFrom` date NOT NULL,
  `DateTo` date NOT NULL,
  `JSON` longtext,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=515 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `Email` varchar(254) NOT NULL,
  `PasswordHash` varchar(64) NOT NULL,
  `PasswordSalt` varchar(13) NOT NULL,
  `ConfirmationString` varchar(13) DEFAULT NULL,
  `CreatedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Admin` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permvars`
--

DROP TABLE IF EXISTS `permvars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permvars` (
  `id` varchar(32) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ratinggroups`
--

DROP TABLE IF EXISTS `ratinggroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratinggroups` (
  `GroupName` varchar(254) NOT NULL,
  `Tau` double NOT NULL,
  `InitVol` double NOT NULL,
  `MinPeriodDays` double NOT NULL,
  `Owner` varchar(254) NOT NULL DEFAULT 'joeycumines@gmail.com',
  `Metadata` longtext,
  PRIMARY KEY (`GroupName`),
  KEY `fk_ratinggroups_1_idx` (`Owner`),
  CONSTRAINT `fk_ratinggroups_1` FOREIGN KEY (`Owner`) REFERENCES `members` (`Email`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `CompetitorName` varchar(50) NOT NULL,
  `GroupName` varchar(254) NOT NULL,
  `RatingDate` datetime NOT NULL,
  `RatingValue` double NOT NULL,
  `RatingDeviation` double NOT NULL,
  `Metadata` longtext,
  PRIMARY KEY (`CompetitorName`,`GroupName`,`RatingDate`),
  KEY `RATING_INDEX` (`RatingValue`),
  KEY `RATING_DATE_INDEX` (`RatingDate`),
  KEY `RATING_GROUP_INDEX` (`GroupName`),
  KEY `RATING_NAME_INDEX` (`CompetitorName`),
  CONSTRAINT `FK_Ratings_CompKey_Competitors` FOREIGN KEY (`CompetitorName`, `GroupName`) REFERENCES `competitors` (`CompetitorName`, `GroupName`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `EventID` int(11) NOT NULL,
  `CompetitorName` varchar(50) NOT NULL,
  `GroupName` varchar(254) NOT NULL,
  `Position` int(11) NOT NULL,
  `Metadata` longtext,
  PRIMARY KEY (`EventID`,`CompetitorName`,`GroupName`),
  KEY `FK_Results_CompKey_Competitors` (`CompetitorName`,`GroupName`),
  CONSTRAINT `FK_Results_0` FOREIGN KEY (`EventID`) REFERENCES `events` (`EventID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_Results_CompKey_Competitors` FOREIGN KEY (`CompetitorName`, `GroupName`) REFERENCES `competitors` (`CompetitorName`, `GroupName`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scheduledcompetitors`
--

DROP TABLE IF EXISTS `scheduledcompetitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scheduledcompetitors` (
  `EventID` int(11) NOT NULL,
  `CompetitorName` varchar(50) NOT NULL,
  `GroupName` varchar(254) NOT NULL,
  `Metadata` longtext,
  `RatingDate` datetime NOT NULL,
  PRIMARY KEY (`EventID`,`CompetitorName`,`GroupName`),
  KEY `FK_ScheduledCompetitors_CompKey_Competitors` (`CompetitorName`,`GroupName`),
  KEY `FK_scheduledcompetitors_rati_idx` (`CompetitorName`,`GroupName`,`RatingDate`),
  CONSTRAINT `FK_scheduledcompetitors_rati` FOREIGN KEY (`CompetitorName`, `GroupName`, `RatingDate`) REFERENCES `ratings` (`CompetitorName`, `GroupName`, `RatingDate`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_ScheduledCompetitors_0` FOREIGN KEY (`EventID`) REFERENCES `scheduledevents` (`EventID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_ScheduledCompetitors_CompKey_Competitors` FOREIGN KEY (`CompetitorName`, `GroupName`) REFERENCES `competitors` (`CompetitorName`, `GroupName`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scheduledevents`
--

DROP TABLE IF EXISTS `scheduledevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scheduledevents` (
  `EventID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupName` varchar(254) NOT NULL,
  `EventStart` datetime NOT NULL,
  `Metadata` longtext,
  `updateID` int(11) NOT NULL,
  PRIMARY KEY (`EventID`),
  KEY `FK_ScheduledEvents_0` (`GroupName`),
  KEY `FK_ScheduledEvents_1_idx` (`updateID`),
  CONSTRAINT `FK_ScheduledEvents_1` FOREIGN KEY (`updateID`) REFERENCES `scheduledupdates` (`updateID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_ScheduledEvents_0` FOREIGN KEY (`GroupName`) REFERENCES `ratinggroups` (`GroupName`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scheduledupdates`
--

DROP TABLE IF EXISTS `scheduledupdates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scheduledupdates` (
  `updateID` int(11) NOT NULL AUTO_INCREMENT,
  `updateTime` datetime NOT NULL,
  PRIMARY KEY (`updateID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-03 14:16:41
