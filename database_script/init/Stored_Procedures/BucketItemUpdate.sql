USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemUpdate` (IN username VARCHAR(50), IN itemID BIGINT(64), IN title VARCHAR(100), IN content VARCHAR(2000),
	IN location VARCHAR(200), IN image VARCHAR(200), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	
    DECLARE NewItemID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (NOT EXISTS (
		SELECT 1 
        FROM 
			Users U
            INNER JOIN BucketList BL ON BL.UserID = U.ID
            INNER JOIN BucketItem BI ON BI.BucketListID = BL.ID
		WHERE
			U.Username = username
            AND BI.ID = itemID
	)) THEN
		SET Result = 0;
        SET Msg = 'The user does NOT have the right to perform this action';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    IF (itemID IS NULL OR title IS NULL OR content IS NULL) THEN
		SET Result = 0;
        SET Msg = 'The bucket item id or title or content is NOT given';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    UPDATE BucketItem BI
    SET
		BI.Title = title,
        BI.Content = content,
        BI.Location = location,
        BI.Image = IFNULL(image, '/resource/pic/bucketPic/default_bucket_pic.jpg')
	WHERE BI.ID = itemID;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemUpdate: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = CAST(itemID AS CHAR(100));
		COMMIT;
	END IF;

END//
DELIMITER ;