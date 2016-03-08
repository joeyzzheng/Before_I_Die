USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersInsert` (IN username VARCHAR(50), IN email VARCHAR(200), IN firstName VARCHAR(50),
	IN lastName VARCHAR(50), IN title VARCHAR(100), IN description VARCHAR(500), IN city VARCHAR(100),
    IN state VARCHAR(100), IN profilePic VARCHAR(200), IN salt VARCHAR(256), IN password VARCHAR(256), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
    DECLARE NewUserID BIGINT(64);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (username IS NULL OR email IS NULL OR firstName IS NULL OR lastName IS NULL OR salt IS NULL OR password IS NULL) THEN
		SET Result = 0;
        SET Msg = 'Missing required fields';
        ROLLBACK;
        LEAVE this_proc;
    END IF;
    
    IF (EXISTS(
		SELECT 1 FROM Users U WHERE U.Username = username))
	THEN
    BEGIN
		SET Result = 0;
        SET Msg = 'Username already used.';
        ROLLBACK;
		LEAVE this_proc;
    END;
    ELSEIF (EXISTS (
		SELECT 1 FROM Users U WHERE U.Email = email))
	THEN
    BEGIN
		SET Result = 0;
        SET Msg = 'Email already used.';
        ROLLBACK;
		LEAVE this_proc;
	END;
    ELSE
    BEGIN
		INSERT INTO `Users`
		(Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password, CreatedDt)
		VALUES 
		(username, email, firstName, lastName, title, description, city, state, IFNULL(profilePic, '/resource/pic/profilePic/default_profile_pic.png'), salt, password, utc_timestamp());
        
        SET NewUserID = last_insert_id();

		CALL BucketListInsert(username, @BLResult, @BLMsg);
        IF (@BLResult = 1) THEN
        BEGIN
			SET Result = 1;
			SET Msg = NewUserID;
		END;
		ELSE
		BEGIN
			SET Result = 0;
            SET MSG = @BLMsg;
            ROLLBACK;
            LEAVE this_proc;
        END;
        END IF;
        
	END;
	END IF;
    
    IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'UsersInsert: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		COMMIT;
	END IF;
            
END//
DELIMITER ;