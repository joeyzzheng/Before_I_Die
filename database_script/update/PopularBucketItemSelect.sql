USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `PopularBucketItemSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `PopularBucketItemSelect` ()
BEGIN

	DROP TABLE IF EXISTS `BucketItemLikeCount`;
	CREATE TEMPORARY TABLE BucketItemLikeCount
    (
		BucketItemID BIGINT(64)
    );

	INSERT INTO BucketItemLikeCount
	SELECT LikeCount.BucketItemID
    FROM
		(SELECT 
			BIL.BucketItemID,
			COUNT(*) AS LikeCount
		FROM
			BucketItemLike BIL
		WHERE BIL.Status = 1
		GROUP BY BIL.BucketItemID) LikeCount
	ORDER BY LikeCount.LikeCount DESC
    LIMIT 12;
        
	SELECT
		U.Username AS Username,
		BI.ID AS BucketItemID,
		BI.Title AS BucketItemTitle,
        BI.Content AS BucketItemContent,
        BI.Image AS Image,
        BI.OrderIndex AS OrderIndex,
        BI.CreateDate AS CreateDate,
        BI.CompleteTime AS CompleteTime
    FROM
		BucketItemLikeCount BILC
        INNER JOIN BucketItem BI ON BI.ID = BILC.BucketItemID
        INNER JOIN BucketList BL ON BL.ID = BI.BucketListID
        INNER JOIN Users U ON U.ID = BL.UserID;
        
	DROP TABLE `BucketItemLikeCount`;
            
END//
DELIMITER ;