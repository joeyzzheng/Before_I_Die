USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemInsert` (IN username VARCHAR(50), IN title VARCHAR(100), IN content VARCHAR(2000),
	IN location VARCHAR(200), IN image VARCHAR(200), IN orderIndex INT, OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc: BEGIN
	DECLARE BucketListID INT;
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (username IS NULL OR title IS NULL OR content IS NULL) THEN
		SET Result = 0;
        SET Msg = 'The username or title or content is NOT given.';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    SET BucketListID = (
		SELECT BL.ID 
        FROM 
			Users U
            INNER JOIN BucketList BL ON BL.UserID = U.ID
		WHERE
			U.Username = username
            AND U.Status = 1
		);
        
	IF (BucketListID IS NULL) THEN 
    BEGIN
		SET Result = 0;
        SET Msg = 'User not found.';
        ROLLBACK;
		LEAVE this_proc;
	END;
	END IF;
        
	INSERT INTO BucketItem
    (Title, Content, CompleteTime, Location, Image, Private, OrderIndex, CreateDate, BucketListID)
    VALUES
    (title, content, null, location, IFNULL(image, '/resource/pic/bucketPic/default_bucket_pic.jpg'), 1, IFNULL(orderIndex, 0), utc_timestamp(), BucketListID);
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemInsert: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
		SET Msg = CAST(last_insert_id() AS CHAR(100));
		COMMIT;
	END IF;

END//
DELIMITER ;