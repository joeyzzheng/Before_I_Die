USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemCommentSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemCommentSelect` (IN bucketItemID BIGINT(64))
BEGIN
	SELECT
		BIC.BucketItemID AS BucketItemID,
        BIC.UserComment AS Comment,
        BIC.CreateDate AS CreatedDate
	FROM
		BucketItemComment BIC
	WHERE
		BIC.Status = 1
        AND BIC.BucketItemID = bucketItemID
    ORDER BY BIC.CreateDate DESC;
            
END//
DELIMITER ;