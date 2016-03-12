USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `RecommendationUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `RecommendationUpdate` (OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	
    DECLARE currentUserID BIGINT(64);
    DECLARE loopDone BOOL DEFAULT 0;
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE currentUserCursor CURSOR FOR SELECT ID FROM Users WHERE Status = 1;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET loopDone = 1;
    
    OPEN currentUserCursor;
    
    START TRANSACTION;
    
    DELETE FROM UserRecommendation;
    
	read_loop: LOOP
		FETCH currentUserCursor INTO currentUserID;
        IF loopDone THEN
			LEAVE read_loop;
        END IF;
        
        INSERT INTO UserRecommendation
        (UserID, RecommendUserID)
        SELECT currentUserID, U.ID
        FROM Users U
        WHERE
			U.State = (SELECT State FROM Users WHERE ID = currentUserID)
            AND U.ID != currentUserID
            AND U.Status = 1;
        
	END LOOP;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'RecommendationUpdate: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = itemID;
		COMMIT;
	END IF;

END//
DELIMITER ;