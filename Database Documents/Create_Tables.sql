CREATE TABLE `Hall` (
  `hallName` varchar(45) NOT NULL,
  `description` mediumtext,
  PRIMARY KEY (`hallName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `User` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `globalRole` enum('Student','Lecturer') NOT NULL DEFAULT 'Student',
  `activationStatus` enum('Pending','Active','Banned') NOT NULL DEFAULT 'Active',
  `userName` varchar(35) NOT NULL,
  `passwordHash` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `metaPoints` int(11) NOT NULL DEFAULT '0',
  `academicPoints` int(11) NOT NULL DEFAULT '0',
  `communityPoints` int(11) NOT NULL DEFAULT '0',
  `lastGotPoints` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userName_UNIQUE` (`userName`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1 COMMENT='Table of Users';

CREATE TABLE `Block` (
  `blockID` int(11) NOT NULL AUTO_INCREMENT,
  `blockName` varchar(45) NOT NULL,
  `description` mediumtext NOT NULL,
  `parentHall` varchar(45) NOT NULL,
  PRIMARY KEY (`blockID`),
  UNIQUE KEY `unique_index` (`blockName`,`parentHall`),
  KEY `parentHall_idx` (`parentHall`),
  CONSTRAINT `BlockHallKey` FOREIGN KEY (`parentHall`) REFERENCES `Hall` (`hallName`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

CREATE TABLE `Room` (
  `roomID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `content` mediumtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parentBlock` int(11) NOT NULL,
  `creator` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`roomID`),
  UNIQUE KEY `unique_index` (`title`,`parentBlock`),
  KEY `room/block_idx` (`parentBlock`),
  KEY `userRoomKey_idx` (`creator`),
  CONSTRAINT `RoomBlockKey` FOREIGN KEY (`parentBlock`) REFERENCES `Block` (`blockID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `userRoomKey` FOREIGN KEY (`creator`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

CREATE TABLE `Comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `roomID` int(11) NOT NULL,
  `commentText` mediumtext NOT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`commentID`),
  KEY `userID_idx` (`userID`),
  KEY `roomKey_idx` (`roomID`),
  KEY `CommentParentKey_idx` (`parent`),
  CONSTRAINT `CommentParentKey` FOREIGN KEY (`parent`) REFERENCES `Comments` (`commentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CommentsRoomKey` FOREIGN KEY (`roomID`) REFERENCES `Room` (`roomID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `CommentsUserKey` FOREIGN KEY (`userID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `CommentVotes` (
  `userID` int(11) NOT NULL,
  `commentID` int(11) NOT NULL,
  `voteType` enum('Up','Down') NOT NULL,
  PRIMARY KEY (`userID`,`commentID`),
  CONSTRAINT `userID` FOREIGN KEY (`userID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `RoomVotes` (
  `userID` int(11) NOT NULL,
  `roomID` int(11) NOT NULL,
  `voteType` enum('Up','Down') NOT NULL,
  PRIMARY KEY (`userID`,`roomID`),
  KEY `roomKey_idx` (`roomID`),
  CONSTRAINT `VotesRoomKey` FOREIGN KEY (`roomID`) REFERENCES `Room` (`roomID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `VotesUserKey` FOREIGN KEY (`userID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Subscribe` (
  `userID` int(11) NOT NULL,
  `blockID` int(11) NOT NULL,
  `blockRole` enum('Member','Admin') NOT NULL DEFAULT 'Member',
  `subscriptionDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`,`blockID`),
  KEY `blockKey_idx` (`blockID`),
  CONSTRAINT `SubscribeBlockKey` FOREIGN KEY (`blockID`) REFERENCES `Block` (`blockID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `SubscribeUserKey` FOREIGN KEY (`userID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

