USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketListSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketListSelect` (IN username VARCHAR(50))
BEGIN
	SELECT
		(SELECT ID FROM BucketItem WHERE BucketItem.ID = BI.ID) AS BucketItemID,
        (SELECT Title FROM BucketItem WHERE BucketItem.ID = BI.ID) AS BucketItemTitle,
        (SELECT Content FROM BucketItem WHERE BucketItem.ID = BI.ID) AS BucketItemContent,
        (SELECT CompleteTime FROM BucketItem WHERE BucketItem.ID = BI.ID) AS BucketItemCompleteTime,
        (SELECT Location FROM BucketItem WHERE BucketItem.ID = BI.ID) AS Location,
        (SELECT Image FROM BucketItem WHERE BucketItem.ID = BI.ID) AS Image,
        (SELECT Private FROM BucketItem WHERE BucketItem.ID = BI.ID) AS Private,
        (SELECT OrderIndex FROM BucketItem WHERE BucketItem.ID = BI.ID) AS OrderIndex,
        (SELECT CreateDate FROM BucketItem WHERE BucketItem.ID = BI.ID) AS CreatedDate,
        (SELECT OpenToTorch FROM BucketItem WHERE BucketItem.ID = BI.ID) AS OpenToTorch,
        (SELECT Done FROM BucketItem WHERE BucketItem.ID = BI.ID) AS Completed,
        GROUP_CONCAT(DISTINCT HT.HashTag ORDER BY HT.ID ASC) AS HashTags
	FROM
		Users U
        INNER JOIN BucketList BL ON BL.UserID = U.ID
        INNER JOIN BucketItem BI ON BI.BucketListID = BL.ID
        LEFT OUTER JOIN BucketItemHashTag BIHT ON BIHT.BucketItemID = BI.ID
        LEFT OUTER JOIN HashTag HT ON HT.ID = BIHT.HashTagID
	WHERE
		U.Status = 1
        and U.Username = username
        AND BL.Status = 1
        AND BI.Status = 1
        AND (BIHT.Status = 1 OR BIHT.Status IS NULL)
        AND (HT.Status = 1 OR HT.Status IS NULL)
	GROUP BY BI.ID
    ORDER BY BI.OrderIndex, BI.ID DESC;
        
            
END//
DELIMITER ;