USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemCommentInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemCommentInsert` (IN bucketItemID BIGINT(64), IN username VARCHAR(50), IN userComment VARCHAR(500), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc: BEGIN
	DECLARE userID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
        
	IF (bucketItemID IS NULL OR username IS NULL OR userComment IS NULL) THEN 
    BEGIN
		SET Result = 0;
        SET Msg = 'The bucket item id, username or user comment is not given.';
        ROLLBACK;
		LEAVE this_proc;
	END;
	END IF;
    
    SET userID = (SELECT ID FROM Users U WHERE U.Username = username AND U.Status = 1);
    
    IF (userID IS NULL) THEN
		SET Result = 0;
        SET Msg = 'The given username does NOT exist.';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
        
	INSERT INTO BucketItemComment
    (BucketItemID, UserComment, UserID, CreateDate)
    VALUES
    (bucketItemID, userComment, userID, utc_timestamp());
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemCommentInsert: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
		SET Msg = CAST(last_insert_id() AS CHAR(100));
		COMMIT;
	END IF;

END//
DELIMITER ;