--create card table
CREATE TABLE IF NOT EXISTS `custom_activity_cards` (
  `phone` varchar(20) NOT NULL COMMENT 'Phone number',
  `card_count` int(2) NOT NULL DEFAULT 0 COMMENT 'The count of cards',
  `status` smallint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'Status, 2,used,1,enabled 0,disabled',
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp COMMENT 'Create date',
  `last_update_date` timestamp NOT NULL DEFAULT current_timestamp COMMENT 'Last update date',
  PRIMARY KEY(`phone`)
);