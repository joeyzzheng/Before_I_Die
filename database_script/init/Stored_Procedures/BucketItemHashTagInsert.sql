USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemHashTagInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemHashTagInsert` (IN bucketItemID BIGINT(64), IN hashTag1 VARCHAR(30), IN hashTag2 VARCHAR(30), IN hashTag3 VARCHAR(30), IN hashTag4 VARCHAR(30), IN hashTag5 VARCHAR(30))
this_proc: BEGIN
    DECLARE Result INT;
    DECLARE Msg VARCHAR(100);
    DECLARE hashTagID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
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
        SET Msg = 'Unknown SQL Exception';
        SELECT Result, Msg;
		ROLLBACK;
	ELSE
		SET Result = 1;
		SET Msg = CAST(last_insert_id() AS CHAR(100));
		SELECT Result, Msg;
		COMMIT;
	END IF;

END//
DELIMITER ;