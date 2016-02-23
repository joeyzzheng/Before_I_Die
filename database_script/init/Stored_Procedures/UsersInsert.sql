USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersInsert` (IN username VARCHAR(50), IN email VARCHAR(200), IN firstName VARCHAR(50),
	IN lastName VARCHAR(50), IN title VARCHAR(100), IN description VARCHAR(500), IN city VARCHAR(100),
    IN state VARCHAR(100), IN profilePic VARCHAR(100), IN salt VARCHAR(256), IN password VARCHAR(256))
BEGIN
	DECLARE Result BIT(1);
    DECLARE Msg VARCHAR(50);
    
    IF (EXISTS(
		SELECT 1 FROM Users U WHERE U.Username = username))
	THEN
    BEGIN
		SET Result = 0;
        SET Msg = 'Username already used.';
    END;
    ELSEIF (EXISTS (
		SELECT 1 FROM Users U WHERE U.Email = email))
	THEN
    BEGIN
		SET Result = 0;
        SET Msg = 'Email already used.';
	END;
    ELSE
		INSERT INTO `Users`
		(Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password, CreatedDt)
		VALUES 
		(username, email, firstName, lastName, title, description, city, state, profilePic, salt, password, utc_timestamp());
	END IF;
    
    SELECT Result, Msg;
            
END//
DELIMITER ;