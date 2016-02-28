USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersSelect` (IN email VARCHAR(200))
BEGIN
	SELECT
		U.ID AS UserID,
        U.Username AS Username,
        U.Email AS Email,
        U.FirstName AS FirstName,
        U.LastName AS LastName,
        U.Title AS Title,
        U.Description AS Description,
        U.City AS City,
        U.State AS State,
        U.ProfilePic AS ProfilePicture
    FROM Users U
    WHERE
		U.Email = email
        AND U.status = 1
        AND Locked = 0;
            
END//
DELIMITER ;