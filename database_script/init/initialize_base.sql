SET GLOBAL time_zone = '+00:00';

DROP SCHEMA IF EXISTS `Before_I_Die`;
CREATE SCHEMA `Before_I_Die`;

USE `Before_I_Die`;

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users`
(
  `ID` BIGINT(64) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(50) NOT NULL UNIQUE,
  `Email` VARCHAR(200) NOT NULL UNIQUE,
  `FirstName` VARCHAR(50) NOT NULL,
  `LastName` VARCHAR(50) NOT NULL,
  `Title` VARCHAR (100) NULL,
  `Description` VARCHAR (500) NULL,
  `City` VARCHAR(100) NULL,
  `State` VARCHAR(100) NULL,
  `ProfilePic` VARCHAR(200) NOT NULL,
  `Salt` VARCHAR(256) NOT NULL,
  `Password` VARCHAR(256) NOT NULL,
  `LoginAttempt` TINYINT(1) NOT NULL DEFAULT 0,
  `Locked` BIT(1) NOT NULL DEFAULT 0,
  `CreatedDt` DATETIME NOT NULL,
  `Status` BIT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `Username_UNIQUE` (`Username` ASC)
);

DROP TABLE IF EXISTS `HashTag`;
CREATE TABLE `HashTag`
(
  `ID` BIGINT(64) UNSIGNED NOT NULL AUTO_INCREMENT,
  `HashTag` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `HashTag_UNIQUE` (`HashTag` ASC)
);

DROP TABLE IF EXISTS `BucketList`;
CREATE TABLE `BucketList`
(
  `ID` BIGINT(64) UNSIGNED NOT NULL AUTO_INCREMENT,
  `UserID` BIGINT(64) UNSIGNED NOT NULL,
  `CreateDate` DATETIME NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `fk_BucketList_UserID_idx` (`UserID` ASC),
  CONSTRAINT `fk_BucketList_UserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `Before_I_Die`.`Users` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

DROP TABLE IF EXISTS `BucketItem`;
CREATE TABLE `BucketItem`
(
  `ID` BIGINT(64) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Title` VARCHAR(100) NOT NULL,
  `Content` VARCHAR(2000) NULL,
  `Location` VARCHAR(200) NULL,
  `Image` VARCHAR(200) NOT NULL,
  `Private` BIT(1) NOT NULL DEFAULT 1,
  `OrderIndex` INT(32) NOT NULL,
  `CreateDate` DATETIME NOT NULL,
  `CompleteTime` DATETIME NULL,
  `OpenToTorch` BIT(1) NOT NULL DEFAULT 0,
  `InheritFrom` BIGINT(64) UNSIGNED NULL,
  `BucketListID` BIGINT(64) UNSIGNED NOT NULL,
  `Status` BIT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`),
  INDEX `fk_BucketItem_InheritFrom_idx` (`InheritFrom` ASC),
  INDEX `fk_BucketItem_BucketListID_idx` (`BucketListID` ASC),
  CONSTRAINT `fk_BucketItem_InheritFrom`
    FOREIGN KEY (`InheritFrom`)
    REFERENCES `Before_I_Die`.`Users` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_BucketItem_BucketListID`
	FOREIGN KEY (`BucketListID`)
	REFERENCES `Before_I_Die`.`BucketList` (`ID`)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
);

DROP TABLE IF EXISTS `BucketItemHashTag`;
CREATE TABLE `BucketItemHashTag`
(
  `ID` BIGINT(64) UNSIGNED NOT NULL AUTO_INCREMENT,
  `BucketItemID` BIGINT(64) UNSIGNED NOT NULL,
  `HashTagID` BIGINT(64) UNSIGNED NOT NULL,
  `Status` BIT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`),
  INDEX `fk_BucketItemHashTag_BucketItemID_idx` (`BucketItemID` ASC),
  INDEX `fk_BucketItemHashTag_HashTagID_idx` (`HashTagID` ASC),
  CONSTRAINT `fk_BucketItemHashTag_BucketItemID`
    FOREIGN KEY (`BucketItemID`)
    REFERENCES `Before_I_Die`.`BucketItem` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_BucketItemHashTag_HashTagID`
    FOREIGN KEY (`HashTagID`)
    REFERENCES `Before_I_Die`.`HashTag` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

DROP TABLE IF EXISTS `BucketItemComment`;
CREATE TABLE `BucketItemComment` (
  `ID` BIGINT(64) UNSIGNED NOT NULL AUTO_INCREMENT,
  `BucketItemID` BIGINT(64) UNSIGNED NOT NULL,
  `UserID` BIGINT(64) UNSIGNED NOT NULL,
  `UserComment` VARCHAR(500) NOT NULL,
  `CreateDate` DATETIME NOT NULL,
  `Status` BIT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`),
  INDEX `fk_BucketItemComment_BucketItemID_idx` (`BucketItemID` ASC),
  INDEX `fk_BucketItemComment_UserID_idx` (`UserID` ASC),
  CONSTRAINT `fk_BucketItemComment_UserID`
	FOREIGN KEY (`UserID`)
    REFERENCES `Users` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_BucketItemComment_BucketItemID`
    FOREIGN KEY (`BucketItemID`)
    REFERENCES `BucketItem` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
    
DROP TABLE IF EXISTS `BucketItemLike`;
CREATE TABLE `BucketItemLike` (
  `ID` BIGINT(64) UNSIGNED NOT NULL AUTO_INCREMENT,
  `BucketItemID` BIGINT(64) UNSIGNED NOT NULL,
  `UserID` BIGINT(64) UNSIGNED NOT NULL,
  `Status` BIT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`),
  INDEX `fk_BucketItemLike_BucketItemID_idx` (`BucketItemID` ASC),
  INDEX `fk_BucketItemLike_UserID_idx` (`UserID` ASC),
  CONSTRAINT `fk_BucketItemLike_BucketItemID`
    FOREIGN KEY (`BucketItemID`)
    REFERENCES `BucketItem` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_BucketItemLike_UserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `Users` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


