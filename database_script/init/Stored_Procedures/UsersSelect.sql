USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersSelect` (IN username VARCHAR(50))
BEGIN
	SELECT
		U.ID,
        U.username,
        U.Email,
        U.FirstName,
        U.LastName,
        U.Title,
        U.Description,
        U.City,
        U.State,
        U.ProfilePic
    FROM Users U
    WHERE
		U.Username = username
        AND U.status = 1
        AND Locked = 0;
            
END//
DELIMITER ;