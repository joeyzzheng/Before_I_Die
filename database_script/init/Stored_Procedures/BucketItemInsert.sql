USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemInsert` (IN username VARCHAR(50), IN title VARCHAR(100), IN content VARCHAR(2000),
	IN location VARCHAR(200), IN image VARCHAR(200), IN orderIndex INT)
this_proc: BEGIN
	DECLARE BucketListID INT;
    DECLARE Result INT;
    DECLARE Msg VARCHAR(100);
    
    START TRANSACTION;
    
    SET BucketListID = (
		SELECT BL.ID 
        FROM 
			Users U
            INNER JOIN BucketList BL ON BL.UserID = U.ID
		WHERE
			U.Username = username
            AND U.Status = 1
            AND BL.Status = 1
		);
        
	IF (BucketListID IS NULL) THEN 
    BEGIN
		SET Result = 0;
        SET Msg = 'User not found.';
        SELECT Result, Msg;
        ROLLBACK;
		LEAVE this_proc;
	END;
	END IF;
        
	INSERT INTO BucketItem
    (Title, Content, CompleteTime, Location, Image, Private, OrderIndex, CreateDate, BucketListID)
    VALUES
    (title, content, null, location, image, 1, orderIndex, utc_timestamp(), BucketListID);
    
    SET Result = 1;
    SET Msg = CAST(last_insert_id() AS CHAR(100));
    
    SELECT Result, Msg;
    COMMIT;

END//
DELIMITER ;