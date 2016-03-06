USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UsersSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UsersSelect` (IN username VARCHAR(50))
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
		U.Username = username
        AND U.status = 1;
            
END//
DELIMITER ;