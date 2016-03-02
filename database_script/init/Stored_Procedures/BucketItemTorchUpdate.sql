USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemTorchUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemTorchUpdate` (IN itemID BIGINT(64), IN openToTorch BIT(1), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (itemID IS NULL OR openToTorch IS NULL) THEN
		SET Result = 0;
        SET Msg = 'No bucket item id or open to torch flag.';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    UPDATE BucketItem BI
    SET
		BI.OpenToTorch = openToTorch
	WHERE BI.ID = itemID;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = itemID;
		COMMIT;
	END IF;

END//
DELIMITER ;