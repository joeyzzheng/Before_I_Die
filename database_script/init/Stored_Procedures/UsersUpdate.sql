USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersUpdate`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersUpdate` (IN username VARCHAR(50), IN email VARCHAR(200),
		IN firstName VARCHAR(50), IN lastName VARCHAR(50), IN title VARCHAR(100),
        IN description VARCHAR(500), IN city VARCHAR(100), IN state VARCHAR(100),
        IN profilePic VARCHAR(100))
BEGIN
	UPDATE `Users`
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
		U.username = username
        AND U.status = 1;
            
END//
DELIMITER ;