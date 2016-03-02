-- This procedure can either insert (like) or "delete" (unlike) a BucketItemLike record.

USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `BucketItemLikeSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `BucketItemLikeSelect` (IN bucketItemID BIGINT(64))
BEGIN
	SELECT
		U.Username
    FROM
		BucketItemLike BIL
        INNER JOIN Users U ON U.ID = BIL.UserID
	WHERE
		BIL.BucketItemID = bucketItemID
        AND BIL.Status = 1;

END//
DELIMITER ;