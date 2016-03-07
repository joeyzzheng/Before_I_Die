USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `Login`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `Login` (IN username VARCHAR(200), IN password VARCHAR(256), OUT Result BIT(1), OUT Msg VARCHAR(100))
BEGIN
    
    IF (EXISTS (
			SELECT 1
			FROM Before_I_Die.Users U
			WHERE 
				U.username = username
				AND U.password = password
				AND U.Status = 1
                AND U.Locked = 0
		)) THEN
		SET Result = 1;
        SET Msg = username;
    ELSE
    BEGIN
		SET Result = 0;
        SET Msg = 'Wrong username or password.';
    END;
    END IF;
            
END//
DELIMITER ;