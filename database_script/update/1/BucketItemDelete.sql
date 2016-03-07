USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemDelete`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemDelete` (IN itemID BIGINT(64), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (itemID IS NULL) THEN
		SET Result = 0;
        SET Msg = 'The bucket item id is NOT given';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
	UPDATE BucketItem BI
    SET BI.Status = 0
    WHERE BI.ID = itemID;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemDelete: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = itemID;
		COMMIT;
	END IF;

END//
DELIMITER ;