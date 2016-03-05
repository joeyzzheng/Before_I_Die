USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `PopularUserSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `PopularUserSelect` ()
BEGIN

	DROP TABLE IF EXISTS `UserLikeCount`;
	CREATE TEMPORARY TABLE UserLikeCount
    (
		UserID BIGINT(64)
    );

	INSERT INTO UserLikeCount
	SELECT LikeCount.UserID
    FROM
		(SELECT 
			U.ID AS UserID,
			COUNT(*) AS LikeCount
		FROM
			Users U
            INNER JOIN BucketList BL ON BL.UserID = U.ID
            INNER JOIN BucketItem BI ON BI.BucketListID = BL.ID
            INNER JOIN BucketItemLike BIL ON BIL.BucketItemID = BI.ID
		WHERE
			U.Status = 1
            AND BI.Status = 1
            AND BIL.Status = 1
		GROUP BY U.ID) LikeCount
	ORDER BY LikeCount.LikeCount DESC
    LIMIT 12;
        
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
    FROM
		UserLikeCount ULC
        INNER JOIN Users U ON U.ID = ULC.UserID;
        
	DROP TABLE `UserLikeCount`;
            
END//
DELIMITER ;