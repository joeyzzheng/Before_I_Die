USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemUpdate` (IN itemID BIGINT(64), IN title VARCHAR(100), IN content VARCHAR(2000),
	IN location VARCHAR(200), IN image VARCHAR(200), IN orderIndex INT, OUT Result BIT(1), OUT Msg VARCHAR(100))
BEGIN
	
    DECLARE NewItemID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    UPDATE BucketItem BI
    SET
		BI.Title = title,
        BI.Content = content,
        BI.Location = location,
        BI.Image = image,
        BI.OrderIndex = orderIndex
	WHERE BI.ID = itemID;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = CAST(itemID AS CHAR(100));
		COMMIT;
	END IF;

END//
DELIMITER ;