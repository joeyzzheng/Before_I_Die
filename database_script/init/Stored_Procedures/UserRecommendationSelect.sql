USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `UserRecommendationSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `UserRecommendationSelect` (IN username VARCHAR(50))
BEGIN
	SELECT
		REC.ID AS UserID,
        REC.Username AS Username,
        REC.Email AS Email,
        REC.FirstName AS FirstName,
        REC.LastName AS LastName,
        REC.Title AS Title,
        REC.Description AS Description,
        REC.City AS City,
        REC.State AS State,
        REC.ProfilePic AS ProfilePicture
    FROM 
		Users U
        INNER JOIN UserRecommendation UR ON UR.UserID = U.ID
        INNER JOIN Users REC ON REC.ID = UR.RecommendUserID
    WHERE
		U.Username = username
        AND U.status = 1
	LIMIT 2;
            
END//
DELIMITER ;