-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 02, 2016 at 07:04 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `virlab`
--

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE IF NOT EXISTS `collection` (
  `collectionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexID` int(10) unsigned NOT NULL,
  `collectionName` varchar(50) NOT NULL,
  `collectionDescription` tinytext,
  PRIMARY KEY (`collectionID`),
  KEY `indexID` (`indexID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `eval`
--

CREATE TABLE IF NOT EXISTS `eval` (
  `evalID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `collectionID` int(10) unsigned NOT NULL,
  `functionID` int(10) unsigned NOT NULL,
  `topic` varchar(10) NOT NULL,
  `MAP` double NOT NULL,
  `P30` double NOT NULL,
  PRIMARY KEY (`evalID`),
  KEY `functionID` (`functionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE IF NOT EXISTS `evaluation` (
  `evaluationID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `collectionID` int(10) unsigned NOT NULL,
  `functionID` int(10) unsigned NOT NULL,
  `MAP` double NOT NULL,
  `P30` double NOT NULL,
  PRIMARY KEY (`evaluationID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `function`
--

CREATE TABLE IF NOT EXISTS `function` (
  `functionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `onlyFlag` tinyint(1) NOT NULL DEFAULT '0',
  `functionPath` varchar(128) NOT NULL,
  `functionRef` int(10) unsigned DEFAULT NULL,
  `functionPara1` double DEFAULT NULL,
  `functionPara2` double DEFAULT NULL,
  `functionPara3` double DEFAULT NULL,
  `functionPara4` double DEFAULT NULL,
  `functionPara5` double DEFAULT NULL,
  PRIMARY KEY (`functionID`),
  KEY `groupID` (`groupID`,`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `function`
--

INSERT INTO `function` (`functionID`, `groupID`, `userID`, `onlyFlag`, `functionPath`, `functionRef`, `functionPara1`, `functionPara2`, `functionPara3`, `functionPara4`, `functionPara5`) VALUES
(1, 1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.0-okapiB=0.2.fun', NULL, 1, 0.2, NULL, NULL, NULL),
(2, 1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.0-okapiB=0.3.fun', NULL, 1, 0.3, NULL, NULL, NULL),
(3, 1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.0-okapiB=0.4.fun', NULL, 1, 0.4, NULL, NULL, NULL),
(4, 1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.2-okapiB=0.2.fun', NULL, 1.2, 0.2, NULL, NULL, NULL),
(5, 1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.2-okapiB=0.3.fun', NULL, 1.2, 0.3, NULL, NULL, NULL),
(6, 1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.2-okapiB=0.4.fun', NULL, 1.2, 0.4, NULL, NULL, NULL),
(7, 2, 0, 0, 'users/0/retFun/Dirichlet-dirMu=1500.fun', NULL, 1500, NULL, NULL, NULL, NULL),
(8, 2, 0, 0, 'users/0/retFun/Dirichlet-dirMu=2000.fun', NULL, 2000, NULL, NULL, NULL, NULL),
(9, 2, 0, 0, 'users/0/retFun/Dirichlet-dirMu=2500.fun', NULL, 2500, NULL, NULL, NULL, NULL),
(10, 3, 0, 0, 'users/0/retFun/PivotedNorm-s=0.05.fun', NULL, 0.05, NULL, NULL, NULL, NULL),
(11, 3, 0, 0, 'users/0/retFun/PivotedNorm-s=0.2.fun', NULL, 0.2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `functionGroup`
--

CREATE TABLE IF NOT EXISTS `functionGroup` (
  `groupID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `groupName` varchar(50) NOT NULL,
  `groupDescription` tinytext,
  `groupPath` varchar(128) NOT NULL,
  `groupStatus` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parameter1` varchar(32) DEFAULT NULL,
  `parameter2` varchar(32) DEFAULT NULL,
  `parameter3` varchar(32) DEFAULT NULL,
  `parameter4` varchar(32) DEFAULT NULL,
  `parameter5` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`groupID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `functionGroup`
--

INSERT INTO `functionGroup` (`groupID`, `userID`, `groupName`, `groupDescription`, `groupPath`, `groupStatus`, `parameter1`, `parameter2`, `parameter3`, `parameter4`, `parameter5`) VALUES
(1, 0, 'BM25', NULL, 'users/0/retFun/BM25.fung', 1, 'okapiB', 'okapiK1', NULL, NULL, NULL),
(2, 0, 'Dirichlet', NULL, 'users/0/retFun/Dirichlet.fung', 1, 'dirMu', NULL, NULL, NULL, NULL),
(3, 0, 'PivotedNorm', NULL, 'users/0/retFun/PivotedNorm.fung', 1, 's', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `indexes`
--

CREATE TABLE IF NOT EXISTS `indexes` (
  `indexID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexName` varchar(50) NOT NULL,
  `indexPath` varchar(128) NOT NULL,
  `indexDescription` tinytext,
  PRIMARY KEY (`indexID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qrel`
--

CREATE TABLE IF NOT EXISTS `qrel` (
  `qrelID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `collectionID` int(10) unsigned NOT NULL,
  `topic` varchar(10) NOT NULL,
  `docName` varchar(64) NOT NULL,
  `score` tinyint(4) NOT NULL,
  PRIMARY KEY (`qrelID`),
  KEY `collectionID` (`collectionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qry`
--

CREATE TABLE IF NOT EXISTS `qry` (
  `qryID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `collectionID` int(10) unsigned NOT NULL,
  `topic` varchar(10) NOT NULL,
  `query` text NOT NULL,
  PRIMARY KEY (`qryID`),
  KEY `collectionID` (`collectionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `searchEngine`
--

CREATE TABLE IF NOT EXISTS `searchEngine` (
  `searchID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indexID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `functionID` int(10) unsigned NOT NULL,
  `searchType` tinyint(3) unsigned NOT NULL,
  `searchName` varchar(128) NOT NULL,
  `searchPath` varchar(128) NOT NULL,
  PRIMARY KEY (`searchID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loginName` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `usertype` tinyint(3) unsigned NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `affiliation` varchar(128) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `loginName`, `password`, `usertype`, `firstName`, `lastName`, `affiliation`, `email`) VALUES
(1, 'admin', '9ea8c18a919acce9c8aad1a4bf638eddf4c4da2a', 255, '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
