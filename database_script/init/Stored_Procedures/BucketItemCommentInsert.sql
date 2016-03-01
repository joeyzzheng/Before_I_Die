USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemCommentInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemCommentInsert` (IN bucketItemID BIGINT(64), IN userComment VARCHAR(500), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc: BEGIN
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
        
	IF (bucketItemID IS NULL OR userComment IS NULL) THEN 
    BEGIN
		SET Result = 0;
        SET Msg = 'The bucket item id is not given.';
        ROLLBACK;
		LEAVE this_proc;
	END;
	END IF;
        
	INSERT INTO BucketItemComment
    (BucketItemID, UserComment, CreateDate)
    VALUES
    (bucketItemID, userComment, utc_timestamp());
    
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