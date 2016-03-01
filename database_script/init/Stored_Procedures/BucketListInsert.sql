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
		SELECT 1 FROM BucketList BL WHERE BL.UserID = userID AND BL.Status = 1))
	THEN
		SET Result = 0;
        SET Msg = 'A bucketlist for this user already exists.';
		ROLLBACK;
		LEAVE this_proc;
    ELSE
    BEGIN
		INSERT INTO BucketList
        (UserID, CreateDate, Status)
        VALUES
        (userID, utc_timestamp(), 1);
        
        SET Result = 1;
        SET Msg = CAST(last_insert_id() AS CHAR(100));
	END;
	END IF;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'Unknown SQL Exception';
		ROLLBACK;
	ELSE
		COMMIT;
	END IF;
            
END//
DELIMITER ;