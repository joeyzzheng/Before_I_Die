USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `RecentUserSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `RecentUserSelect` ()
BEGIN

	SELECT DISTINCT
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
	FROM
		BucketItem BI
		INNER JOIN BucketList BL ON BL.ID = BI.BucketListID
		INNER JOIN Users U ON U.ID = BL.UserID
	WHERE
		U.Status = 1
        AND BI.Status = 1
	ORDER BY BI.CreateDate DESC
	LIMIT 12;
        

            
END//
DELIMITER ;