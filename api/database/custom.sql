INSERT INTO `eav_attribute`(`entity_type_id`, `attribute_code`, `attribute_model`,
`backend_model`, `backend_type`, `backend_table`, `frontend_model`, `frontend_input`,
`frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
`default_value`, `is_unique`, `note`)
VALUES
(1,'mobile',NUll,NUll,'varchar',NULL,NULL,'text','Phone Number',NULL,NULL,0,0,NULL,0,NULL),
(1,'birthday',NUll,NUll,'datetime',NULL,NULL,'date','Birthday',NULL,NULL,0,0,NULL,0,NULL),
(1,'sex',NUll,NUll,'varchar',NULL,NULL,'select','Sex',NULL,NULL,0,0,NULL,0,NULL),
(1,'myimage',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
(1,'nickname',NUll,NUll,'varchar',NULL,NULL,'text','Nick Name',NULL,NULL,0,0,NULL,0,NULL);

INSERT INTO `eav_entity_attribute`(`entity_type_id`,
`attribute_set_id`, `attribute_group_id`, `attribute_id`, `sort_order`)
VALUES
(1,1,1,(SELECT max(cast(attribute_id as unsigned))-4 from eav_attribute),0),
(1,1,1,(SELECT max(cast(attribute_id as unsigned))-3 from eav_attribute),0),
(1,1,1,(SELECT max(cast(attribute_id as unsigned))-2 from eav_attribute),0),
(1,1,1,(SELECT max(cast(attribute_id as unsigned))-1 from eav_attribute),0),
(1,1,1,(SELECT max(cast(attribute_id as unsigned)) from eav_attribute),0);

CREATE TABLE IF NOT EXISTS `custom_promotions` (
  `promotion_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Promotion id',
  `coupon_id` int(10) unsigned NOT NULL COMMENT 'Coupon id',
  `mobile` varchar(20) NOT NULL COMMENT 'Phone number',
  `catalog` varchar(20) COMMENT 'Catalog',
  `enable_flag` smallint(1) unsigned NOT NULL COMMENT 'Enable flag, 1,enabled 0,disabled',
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp COMMENT 'Create date',
  `last_update_date` timestamp NOT NULL COMMENT 'Last update date',
  PRIMARY KEY(`promotion_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `custom_activity_cards` (
  `mobile` varchar(20) NOT NULL COMMENT 'Phone number',
  `card_count` int(2) NOT NULL DEFAULT 0 COMMENT 'The count of cards',
  `status` smallint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'Status, 2,used,1,enabled 0,disabled',
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp COMMENT 'Create date',
  `last_update_date` timestamp NOT NULL COMMENT 'Last update date',
  PRIMARY KEY(`mobile`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `custom_errorcode` (
  `errorcode_id` int(10) NOT NULL AUTO_INCREMENT,
  `errorcode_number` int(10) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` varchar(200),
  PRIMARY KEY(`errorcode_id`),
  UNIQUE KEY (`errorcode_number`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 20150722
INSERT INTO `eav_attribute`(`entity_type_id`, `attribute_code`, `attribute_model`,
                            `backend_model`, `backend_type`, `backend_table`, `frontend_model`, `frontend_input`,
                            `frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
                            `default_value`, `is_unique`, `note`)
VALUES
  (1,'origin_user_id',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
  (1,'reg_city',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL);

-- 20150727
INSERT INTO `eav_attribute`(`entity_type_id`, `attribute_code`, `attribute_model`,
                            `backend_model`, `backend_type`, `backend_table`, `frontend_model`, `frontend_input`,
                            `frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
                            `default_value`, `is_unique`, `note`)
VALUES
  (2,'origin_address_id',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
  (2,'district',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
  (2,'area',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
  (2,'remark',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
  (2,'dateline',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL);

-- 20150730
INSERT INTO `eav_attribute`(`entity_type_id`, `attribute_code`, `attribute_model`,
                            `backend_model`, `backend_type`, `backend_table`, `frontend_model`, `frontend_input`,
                            `frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
                            `default_value`, `is_unique`, `note`)
VALUES
  (3,'origin_cat_id',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
  (3,'origin_path',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL),
  (3,'origin_parent_id',NUll,NUll,'varchar',NULL,NULL,'hidden',NULL,NULL,NULL,0,0,NULL,0,NULL);


