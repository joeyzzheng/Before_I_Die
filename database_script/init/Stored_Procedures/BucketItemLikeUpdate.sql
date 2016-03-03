-- This procedure can either insert (like) or "delete" (unlike) a BucketItemLike record.

USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemLikeUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemLikeUpdate` (IN bucketItemID BIGINT(64), IN username VARCHAR(50), IN Liked BIT(1), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc: BEGIN
	DECLARE userID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (bucketItemID IS NULL OR username IS NULL OR Liked IS NULL) THEN 
    BEGIN
		SET Result = 0;
        SET Msg = 'The bucket item id or username or liked is not given.';
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
        
	IF (Liked) THEN
	BEGIN
		IF (NOT EXISTS( 
			SELECT 1 
            FROM BucketItemLike BIL 
            WHERE 
				BIL.BucketItemID = bucketItemID
                AND BIL.UserID = userID
                AND BIL.Status = 1)) 
		THEN
			INSERT INTO BucketItemLike
			(BucketItemID, UserID)
			VALUES
        (	bucketItemID, userID);
        END IF;
    END;
    ELSE
    BEGIN
		UPDATE BucketItemLike BIL
        SET BIL.Status = 0
        WHERE
			BIL.BucketItemID = bucketItemID
            AND BIL.Userid = userID;
    END;
    END IF;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'BucketItemLikeUpdate: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
		SET Msg = BucketItemID;
		COMMIT;
	END IF;

END//
DELIMITER ;