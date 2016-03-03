USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemOrderIndexUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemOrderIndexUpdate` (IN itemID BIGINT(64), IN orderIndex INT(32), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (itemID IS NULL OR orderIndex IS NULL) THEN
		SET Result = 0;
        SET Msg = 'No bucket item id or order index.';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    UPDATE BucketItem BI
    SET
		BI.OrderIndex = IFNULL(orderIndex, 0)
	WHERE BI.ID = itemID;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemOrderIndexUpdate: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = itemID;
		COMMIT;
	END IF;

END//
DELIMITER ;