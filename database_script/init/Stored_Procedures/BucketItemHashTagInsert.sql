USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemHashTagInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemHashTagInsert` (IN bucketItemID BIGINT(64), IN hashTag1 VARCHAR(30), IN hashTag2 VARCHAR(30), IN hashTag3 VARCHAR(30), IN hashTag4 VARCHAR(30), IN hashTag5 VARCHAR(30), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc: BEGIN
    DECLARE hashTagID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (bucketItemID IS NULL) THEN
		SET Result = 0;
        SET Msg = 'The bucket item id is NOT given.';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    UPDATE BucketItemHashTag BIHT
    SET BIHT.Status = 0
    WHERE BIHT.BucketItemID = BucketItemID;
    
    IF (hashTag1 IS NOT NULL) THEN		
	BEGIN		
 		IF (NOT EXISTS(		
 			SELECT 1 FROM HashTag HT WHERE HT.HashTag = hashTag1))		
 		THEN		
 			INSERT INTO HashTag (HashTag) VALUES (hashTag1);		
		END IF;		
         		
		SET hashTagID = (SELECT ID FROM HashTag HT WHERE HT.HashTag = hashTag1);		

		INSERT INTO BucketItemHashTag
		(BucketItemID, HashTagID)		
		VALUES		
		(bucketItemID, hashTagID);
	END;
	END IF;
    
    IF (hashTag2 IS NOT NULL) THEN		
	BEGIN		
 		IF (NOT EXISTS(		
 			SELECT 1 FROM HashTag HT WHERE HT.HashTag = hashTag2))		
 		THEN		
 			INSERT INTO HashTag (HashTag) VALUES (hashTag2);		
		END IF;		
         		
		SET hashTagID = (SELECT ID FROM HashTag HT WHERE HT.HashTag = hashTag2);		

		INSERT INTO BucketItemHashTag
		(BucketItemID, HashTagID)		
		VALUES		
		(bucketItemID, hashTagID);
	END;
	END IF;
    
    IF (hashTag3 IS NOT NULL) THEN		
	BEGIN		
 		IF (NOT EXISTS(		
 			SELECT 1 FROM HashTag HT WHERE HT.HashTag = hashTag3))		
 		THEN		
 			INSERT INTO HashTag (HashTag) VALUES (hashTag3);		
		END IF;		
         		
		SET hashTagID = (SELECT ID FROM HashTag HT WHERE HT.HashTag = hashTag3);		

		INSERT INTO BucketItemHashTag
		(BucketItemID, HashTagID)		
		VALUES		
		(bucketItemID, hashTagID);
	END;
	END IF;
    
    IF (hashTag4 IS NOT NULL) THEN		
	BEGIN		
 		IF (NOT EXISTS(		
 			SELECT 1 FROM HashTag HT WHERE HT.HashTag = hashTag4))		
 		THEN		
 			INSERT INTO HashTag (HashTag) VALUES (hashTag4);		
		END IF;		
         		
		SET hashTagID = (SELECT ID FROM HashTag HT WHERE HT.HashTag = hashTag4);		

		INSERT INTO BucketItemHashTag
		(BucketItemID, HashTagID)		
		VALUES		
		(bucketItemID, hashTagID);
	END;
	END IF;
    
    IF (hashTag5 IS NOT NULL) THEN		
	BEGIN		
 		IF (NOT EXISTS(		
 			SELECT 1 FROM HashTag HT WHERE HT.HashTag = hashTag5))		
 		THEN		
 			INSERT INTO HashTag (HashTag) VALUES (hashTag5);		
		END IF;		
         		
		SET hashTagID = (SELECT ID FROM HashTag HT WHERE HT.HashTag = hashTag5);		

		INSERT INTO BucketItemHashTag
		(BucketItemID, HashTagID)		
		VALUES		
		(bucketItemID, hashTagID);
	END;
	END IF;

    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemHashTagInsert: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
		SET Msg = bucketItemID;
		COMMIT;
	END IF;

END//
DELIMITER ;