USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersUpdate` (IN username VARCHAR(50), IN email VARCHAR(200),
		IN firstName VARCHAR(50), IN lastName VARCHAR(50), IN title VARCHAR(100),
        IN description VARCHAR(500), IN city VARCHAR(100), IN state VARCHAR(100),
        IN profilePic VARCHAR(200), OUT Result BIT(1), OUT Msg VARCHAR(100))
this_proc:BEGIN
	
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;
    
    IF (username IS NULL OR email IS NULL OR firstName IS NULL OR lastName IS NULL) THEN
		SET Result = 0;
        SET Msg = 'Missing required fields';
        ROLLBACK;
        LEAVE this_proc;
    END IF;

	UPDATE `Users` U
    SET
        U.Email = email,
        U.FirstName = firstName,
        U.LastName = lastName,
        U.Title = title,
        U.Description = description,
        U.City = city,
        U.State = state,
        U.ProfilePic = IFNULL(profilePic, '/resource/pic/profilePic/default_profile_pic.png')
    WHERE
		U.Username = username
        AND U.status = 1;
        
	IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'UsersUpdate: Unknown SQL Exception';
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = username;
		COMMIT;
	END IF;
            
END//
DELIMITER ;