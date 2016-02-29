USE `Before_I_Die`;
DROP PROCEDURE IF EXISTS `SaltSelect`;

DELIMITER //
USE `Before_I_Die`//
CREATE PROCEDURE `SaltSelect` (IN username VARCHAR(200))
BEGIN
	SELECT
		U.Salt AS Salt
	FROM
		Users U
	WHERE
		U.Username = username
		AND U.Status = 1;
        
            
END//
DELIMITER ;