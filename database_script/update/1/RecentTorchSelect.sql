USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `RecentTorchSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `RecentTorchSelect` ()
BEGIN
        
	SELECT
		U.Username AS Username,
		BI.ID AS BucketItemID,
        BI.Title AS BucketItemTitle,
        BI.Content AS BucketItemContent,
        BI.Image AS Image
    FROM 
		BucketItem BI
        INNER JOIN BucketList BL ON BL.ID = BI.BucketListID
        INNER JOIN Users U ON U.ID = BL.UserID
    WHERE
		BI.Status = 1
        AND BI.OpenToTorch = 1
        AND BI.Private = 0
        AND U.Status = 1
	ORDER BY
		OpenToTorchDate DESC
	LIMIT 12;
            
END//
DELIMITER ;