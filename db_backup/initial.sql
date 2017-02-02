CREATE TABLE `collection` (
`collectionID` int(10) unsigned NOT NULL,
  `indexID` int(10) unsigned NOT NULL,
  `collectionName` varchar(50) NOT NULL,
  `collectionDescription` tinytext
);
ALTER TABLE `collection`
 ADD PRIMARY KEY (`collectionID`), ADD KEY `indexID` (`indexID`);
ALTER TABLE `collection`
MODIFY `collectionID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `eval` (
`evalID` int(10) unsigned NOT NULL,
  `collectionID` int(10) unsigned NOT NULL,
  `functionID` int(10) unsigned NOT NULL,
  `topic` varchar(10) NOT NULL,
  `MAP` double NOT NULL,
  `P30` double NOT NULL
);
ALTER TABLE `eval`
 ADD PRIMARY KEY (`evalID`), ADD KEY `functionID` (`functionID`);
ALTER TABLE `eval`
MODIFY `evalID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `evaluation` (
`evaluationID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `collectionID` int(10) unsigned NOT NULL,
  `functionID` int(10) unsigned NOT NULL,
  `MAP` double NOT NULL,
  `P30` double NOT NULL
);
ALTER TABLE `evaluation`
 ADD PRIMARY KEY (`evaluationID`);
ALTER TABLE `evaluation`
MODIFY `evaluationID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;

REATE TABLE `function` (
`functionID` int(10) unsigned NOT NULL,
  `groupID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `onlyFlag` tinyint(1) NOT NULL DEFAULT '0',
  `functionPath` varchar(128) NOT NULL,
  `functionRef` int(10) unsigned DEFAULT NULL,
  `functionPara1` double DEFAULT NULL,
  `functionPara2` double DEFAULT NULL,
  `functionPara3` double DEFAULT NULL,
  `functionPara4` double DEFAULT NULL,
  `functionPara5` double DEFAULT NULL
);
INSERT INTO `function` (`groupID`, `userID`, `onlyFlag`, `functionPath`, `functionRef`, `functionPara1`, `functionPara2`, `functionPara3`, `functionPara4`, `functionPara5`) VALUES
(1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.0-okapiB=0.2.fun', NULL, 1, 0.2, NULL, NULL, NULL),
(1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.0-okapiB=0.3.fun', NULL, 1, 0.3, NULL, NULL, NULL),
(1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.0-okapiB=0.4.fun', NULL, 1, 0.4, NULL, NULL, NULL),
(1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.2-okapiB=0.2.fun', NULL, 1.2, 0.2, NULL, NULL, NULL),
(1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.2-okapiB=0.3.fun', NULL, 1.2, 0.3, NULL, NULL, NULL),
(1, 0, 0, 'users/0/retFun/BM25-okapiK1=1.2-okapiB=0.4.fun', NULL, 1.2, 0.4, NULL, NULL, NULL),
(2, 0, 0, 'users/0/retFun/Dirichlet-dirMu=1500.fun', NULL, 1500, NULL, NULL, NULL, NULL),
(2, 0, 0, 'users/0/retFun/Dirichlet-dirMu=2000.fun', NULL, 2000, NULL, NULL, NULL, NULL),
(2, 0, 0, 'users/0/retFun/Dirichlet-dirMu=2500.fun', NULL, 2500, NULL, NULL, NULL, NULL),
(3, 0, 0, 'users/0/retFun/PivotedNorm-s=0.05.fun', NULL, 0.05, NULL, NULL, NULL, NULL),
(3, 0, 0, 'users/0/retFun/PivotedNorm-s=0.2.fun', NULL, 0.2, NULL, NULL, NULL, NULL);
ALTER TABLE `function`
 ADD PRIMARY KEY (`functionID`), ADD KEY `groupID` (`groupID`,`userID`);
ALTER TABLE `function`
MODIFY `functionID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `functionGroup` (
`groupID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `groupName` varchar(50) NOT NULL,
  `groupDescription` tinytext,
  `groupPath` varchar(128) NOT NULL,
  `groupStatus` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parameter1` varchar(32) DEFAULT NULL,
  `parameter2` varchar(32) DEFAULT NULL,
  `parameter3` varchar(32) DEFAULT NULL,
  `parameter4` varchar(32) DEFAULT NULL,
  `parameter5` varchar(32) DEFAULT NULL
);
INSERT INTO `functionGroup` (`groupID`, `userID`, `groupName`, `groupDescription`, `groupPath`, `groupStatus`, `parameter1`, `parameter2`, `parameter3`, `parameter4`, `parameter5`) VALUES
(1, 0, 'BM25', NULL, 'users/0/retFun/BM25.fung', 1, 'okapiB', 'okapiK1', NULL, NULL, NULL),
(2, 0, 'Dirichlet', NULL, 'users/0/retFun/Dirichlet.fung', 1, 'dirMu', NULL, NULL, NULL, NULL),
(3, 0, 'PivotedNorm', NULL, 'users/0/retFun/PivotedNorm.fung', 1, NULL, NULL, NULL, NULL, NULL);
ALTER TABLE `functionGroup`
 ADD PRIMARY KEY (`groupID`), ADD KEY `userID` (`userID`);
ALTER TABLE `functionGroup`
MODIFY `groupID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `indexes` (
`indexID` int(10) unsigned NOT NULL,
  `indexName` varchar(50) NOT NULL,
  `indexPath` varchar(128) NOT NULL,
  `indexDescription` tinytext
);
ALTER TABLE `indexes`
 ADD PRIMARY KEY (`indexID`);
ALTER TABLE `indexes`
MODIFY `indexID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `qrel` (
`qrelID` int(10) unsigned NOT NULL,
  `collectionID` int(10) unsigned NOT NULL,
  `topic` varchar(10) NOT NULL,
  `docName` varchar(64) NOT NULL,
  `score` tinyint(4) NOT NULL
);
ALTER TABLE `qrel`
 ADD PRIMARY KEY (`qrelID`), ADD KEY `collectionID` (`collectionID`);
ALTER TABLE `qrel`
MODIFY `qrelID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `qry` (
`qryID` int(10) unsigned NOT NULL,
  `collectionID` int(10) unsigned NOT NULL,
  `topic` varchar(10) NOT NULL,
  `query` text NOT NULL
);
ALTER TABLE `qry`
 ADD PRIMARY KEY (`qryID`), ADD KEY `collectionID` (`collectionID`);
ALTER TABLE `qry`
MODIFY `qryID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `searchEngine` (
`searchID` int(10) unsigned NOT NULL,
  `indexID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `functionID` int(10) unsigned NOT NULL,
  `searchType` tinyint(3) unsigned NOT NULL,
  `searchName` varchar(128) NOT NULL,
  `searchPath` varchar(128) NOT NULL
);
ALTER TABLE `searchEngine`
 ADD PRIMARY KEY (`searchID`), ADD KEY `userID` (`userID`);
ALTER TABLE `searchEngine`
MODIFY `searchID` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE `user` (
`userID` int(10) unsigned NOT NULL,
  `loginName` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `usertype` tinyint(3) unsigned NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `affiliation` varchar(128) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
);
INSERT INTO `user` (`userID`, `loginName`, `password`, `usertype`, `firstName`, `lastName`, `affiliation`, `email`) VALUES
(1, 'admin', 'bf987b6fc307452432076700ad02945627bcda27', 255, '', '', '', '');
ALTER TABLE `user`
 ADD PRIMARY KEY (`userID`);
ALTER TABLE `user`
MODIFY `userID` int(10) unsigned NOT NULL AUTO_INCREMENT;

