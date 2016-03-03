USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemInheritInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemInheritInsert` (IN bucketItemID BIGINT(64), IN childUsername VARCHAR(50), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc: BEGIN
	DECLARE allowToTorch BIT(1);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (bucketItemID IS NULL OR childUsername IS NULL) THEN
		SET Result = 0;
        SET Msg = 'The bucket item id or child username is NOT given';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    SET allowToTorch = (SELECT BI.OpenToTorch FROM BucketItem BI WHERE BI.ID = bucketItemID);
    IF (allowToTorch = 0) THEN
		SET Result = 0;
        SET Msg = 'The given bucket item is NOT allowed to be inherited.';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    INSERT INTO BucketItem
    (Title, Content, Location, Image, Private, CreateDate, OpenToTorch, InheritFrom, BucketListID)
    SELECT
		BI.Title,
        BI.Content,
        BI.Location,
        BI.Image,
        BI.Private,
        utc_timestamp(),
        1,
        (SELECT BL.UserID FROM BucketList BL WHERE BL.ID = BI.BucketListID),
        (SELECT BL.ID FROM Users U INNER JOIN BucketList BL ON BL.UserID = U.ID WHERE U.Username = childUsername AND U.Status = 1)
    FROM
		BucketItem BI
	WHERE BI.id = bucketItemID;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
		SET Msg = CAST(last_insert_id() AS CHAR(100));
		COMMIT;
	END IF;

END//
DELIMITER ;