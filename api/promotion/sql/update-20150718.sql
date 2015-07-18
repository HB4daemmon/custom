--create custom table for promotion
CREATE TABLE IF NOT EXISTS `custom_promotions` (
  `promotion_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Promotion id',
  `coupon_id` int(10) unsigned NOT NULL COMMENT 'Coupon id',
  `phone` varchar(20) NOT NULL COMMENT 'Phone number',
  `catalog` varchar(20) COMMENT 'Catalog',
  `enable_flag` smallint(1) unsigned NOT NULL COMMENT 'Enable flag, 1,enabled 0,disabled',
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp COMMENT 'Create date',
  `last_update_date` timestamp NOT NULL DEFAULT current_timestamp COMMENT 'Last update date',
  PRIMARY KEY(`promotion_id`)
);

--demo sql
INSERT INTO `custom_promotions`( `coupon_id`, `phone`, `catalog`, `enable_flag`, `creation_date`, `last_update_date`)
VALUES
();