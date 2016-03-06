USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketListInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketListInsert` (IN username VARCHAR(50), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	DECLARE userID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    SET userID = (SELECT ID FROM Users U WHERE U.Username = username AND U.Status = 1);
    
    IF (userID IS NULL)
	THEN
    BEGIN
		SET Result = 0;
        SET Msg = 'Username does NOT exist.';
        ROLLBACK;
		LEAVE this_proc;
    END;
    ELSEIF (EXISTS (
		SELECT 1 FROM BucketList BL WHERE BL.UserID = userID))
	THEN
		SET Result = 0;
        SET Msg = 'A bucketlist for this user already exists.';
		ROLLBACK;
		LEAVE this_proc;
    ELSE
    BEGIN
		INSERT INTO BucketList
        (UserID, CreateDate)
        VALUES
        (userID, utc_timestamp());
        
        SET Result = 1;
        SET Msg = last_insert_id();
	END;
	END IF;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketListInsert: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		COMMIT;
	END IF;
            
END//
DELIMITER ;