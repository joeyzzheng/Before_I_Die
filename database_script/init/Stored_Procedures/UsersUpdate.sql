USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersUpdate` (IN username VARCHAR(50), IN email VARCHAR(200),
		IN firstName VARCHAR(50), IN lastName VARCHAR(50), IN title VARCHAR(100),
        IN description VARCHAR(500), IN city VARCHAR(100), IN state VARCHAR(100),
        IN profilePic VARCHAR(200))
BEGIN
	DECLARE Result BIT(1);
    DECLARE Msg VARCHAR(100);
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    
    START TRANSACTION;

	UPDATE `Users` U
    SET
        U.Username = username,
        U.Email = email,
        U.FirstName = firstName,
        U.LastName = lastName,
        U.Title = title,
        U.Description = description,
        U.City = city,
        U.State = state,
        U.ProfilePic = profilePic
    WHERE
		U.Username = username
        AND U.status = 1;
        
	IF `_rollback` THEN
		SET Result = 0;
        SET Msg = 'Unknown SQL Exception';
        SELECT Result, Msg;
		ROLLBACK;
	ELSE
		SET Result = 1;
        SET Msg = username;
		SELECT Result, Msg;
		COMMIT;
	END IF;
            
END//
DELIMITER ;