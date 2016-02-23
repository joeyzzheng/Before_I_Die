USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersInsert`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersInsert` (IN username VARCHAR(50), IN email VARCHAR(200), IN firstName VARCHAR(50),
	IN lastName VARCHAR(50), IN title VARCHAR(100), IN description VARCHAR(500), IN city VARCHAR(100),
    IN state VARCHAR(100), IN profilePic VARCHAR(100), IN salt VARCHAR(256), IN password VARCHAR(256))
BEGIN
	INSERT INTO `Users`
    (Username, Email, FirstName, LastName, Title, Description, City, State, ProfilePic, Salt, Password)
    VALUES 
	(username, email, firstName, lastName, title, description, city, state, profilePic, salt, password);
            
END//
DELIMITER ;