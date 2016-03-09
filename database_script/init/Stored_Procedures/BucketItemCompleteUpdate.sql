USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemCompleteUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemCompleteUpdate` (IN username VARCHAR(50), IN itemID BIGINT(64), IN completed BIT(1), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	
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
    
    IF (itemID IS NULL OR completed IS NULL) THEN
		SET Result = 0;
        SET Msg = 'No bucket item id or completed flag.';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    IF (completed = 1) THEN
		UPDATE BucketItem BI
		SET
			BI.CompleteTime = utc_timestamp()
		WHERE BI.ID = itemID;
	ELSE
		UPDATE BucketItem BI
		SET
			BI.CompleteTime = NULL
		WHERE BI.ID = itemID;
    END IF;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemCompleteUpdate: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = itemID;
		COMMIT;
	END IF;

END//
DELIMITER ;