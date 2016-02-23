USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemInsert` (IN username VARCHAR(50), IN title VARCHAR(100), IN content VARCHAR(2000),
	IN location VARCHAR(200), IN image VARCHAR(200), IN orderIndex INT, IN hashTag1 VARCHAR(30), IN hashTag2 VARCHAR(30),
    IN hashTag3 VARCHAR(30), IN hashTag4 VARCHAR(30), IN hashTag5 VARCHAR(30))
BEGIN
	DECLARE BucketListID INT;
    DECLARE NewBucketItemID BIGINT(64);
    DECLARE hashTagID BIGINT(64);
    
    SET BucketListID = (
		SELECT U.ID 
        FROM 
			Users U
            INNER JOIN BucketList BL ON BL.OwnerID = U.ID
		WHERE
			U.Username = username
            AND U.Status = 1
            AND BL.Status = 1
		);
        
	INSERT INTO BucketItem
    (Title, Content, CompleteTime, Location, Image, Private, OrderIndex, CreateDate, BucketListID)
    VALUES
    (title, content, null, location, image, 1, orderIndex, utc_timestamp(), BucketListID);
    
    SET NewBucketItemID = last_insert_id();
    
    IF (hashTag1 IS NOT NULL)
    THEN
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
        (NewBucketItemID, hashTagID);
    END;
    END IF;
           
	IF (hashTag2 IS NOT NULL)
    THEN
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
        (NewBucketItemID, hashTagID);
    END;
    END IF;
    
    IF (hashTag3 IS NOT NULL)
    THEN
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
        (NewBucketItemID, hashTagID);
    END;
    END IF;
    
    IF (hashTag4 IS NOT NULL)
    THEN
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
        (NewBucketItemID, hashTagID);
    END;
    END IF;
    
    IF (hashTag5 IS NOT NULL)
    THEN
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
        (NewBucketItemID, hashTagID);
    END;
    END IF;
END//
DELIMITER ;