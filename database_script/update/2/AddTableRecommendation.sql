USE `Before_I_Die`;

DROP TABLE IF EXISTS `UserRecommendation`;
CREATE TABLE `UserRecommendation` (
  `UserID` BIGINT(64) UNSIGNED NOT NULL,
  `RecommendUserID` BIGINT(64) UNSIGNED NOT NULL,
  INDEX `fk_Recommendation_UserID_idx` (`UserID` ASC),
  INDEX `fk_Recommendation_RecommendUserID_idx` (`RecommendUserID` ASC),
  CONSTRAINT `fk_Recommendation_UserID`
    FOREIGN KEY (`UserID`)
    REFERENCES `Before_I_Die`.`Users` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Recommendation_RecommendUserID`
    FOREIGN KEY (`RecommendUserID`)
    REFERENCES `Before_I_Die`.`Users` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);