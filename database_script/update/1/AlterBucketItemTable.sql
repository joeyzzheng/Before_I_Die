USE `Before_I_Die`;

ALTER TABLE `BucketItem` 
ADD COLUMN `OpenToTorchDate` DATETIME NULL AFTER `OpenToTorch`;
