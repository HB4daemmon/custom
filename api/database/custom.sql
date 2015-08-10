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

-- 20150731 for mgyx_database
CREATE TABLE IF NOT EXISTS `custom_wx_recall` (
  `recall_id` int(10) NOT NULL AUTO_INCREMENT,
  `return_code` varchar(20) NOT NULL,
  `return_msg` varchar(150) NOT NULL,
  `mch_id` varchar(50),
  `device_info` varchar(50),
  `nonce_str` varchar(50),
  `sign` varchar(50),
  `result_code` varchar(50),
  `err_code` varchar(50),
  `err_code_des` varchar(150),
  `openid` varchar(150),
  `is_subscribe` varchar(5),
  `trade_type` varchar(50),
  `bank_type` varchar(50),
  `total_fee` varchar(50),
  `fee_type` varchar(50),
  `cash_fee` varchar(50),
  `cash_fee_type` varchar(50),
  `coupon_fee` varchar(50),
  `coupon_count` varchar(50),
  `transaction_id` varchar(50),
  `out_trade_no` varchar(50),
  `attach` varchar(150),
  `time_end` varchar(50),
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY(`recall_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 20150804
-- origin_product_id
INSERT INTO `eav_attribute` ( `entity_type_id`, `attribute_code`, `attribute_model`, `backend_model`, `backend_type`, `backend_table`,
                                                                                                                          `frontend_model`, `frontend_input`, `frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
                                                                                                                          `default_value`, `is_unique`, `note`) VALUES
  (4, 'origin_product_id', NULL, NULL, 'varchar', NULL, NULL, 'text', 'origin_product_id', NULL, NULL, 0, 1, NULL, 0, NULL);

INSERT INTO `catalog_eav_attribute` (`attribute_id`, `frontend_input_renderer`, `is_global`, `is_visible`, `is_searchable`, `is_filterable`, `is_comparable`,
                                     `is_visible_on_front`, `is_html_allowed_on_front`, `is_used_for_price_rules`, `is_filterable_in_search`, `used_in_product_listing`,
                                     `used_for_sort_by`, `is_configurable`, `apply_to`, `is_visible_in_advanced_search`, `position`, `is_wysiwyg_enabled`,
                                     `is_used_for_promo_rules`)
VALUES
  ((SELECT max(cast(attribute_id as unsigned)) from eav_attribute), NULL, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, NULL, 0, 0, 0, 0);

INSERT INTO `eav_attribute_label` ( `attribute_id`, `store_id`, `value`) VALUES
  ((SELECT max(cast(attribute_id as unsigned)) from eav_attribute), 1, 'origin_product_id');

-- sort_order
INSERT INTO `eav_attribute` ( `entity_type_id`, `attribute_code`, `attribute_model`, `backend_model`, `backend_type`, `backend_table`,
                              `frontend_model`, `frontend_input`, `frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
                              `default_value`, `is_unique`, `note`) VALUES
  (4, 'sort_order', NULL, NULL, 'varchar', NULL, NULL, 'text', 'sort_order', NULL, NULL, 0, 1, NULL, 0, NULL);

INSERT INTO `catalog_eav_attribute` (`attribute_id`, `frontend_input_renderer`, `is_global`, `is_visible`, `is_searchable`, `is_filterable`, `is_comparable`,
                                     `is_visible_on_front`, `is_html_allowed_on_front`, `is_used_for_price_rules`, `is_filterable_in_search`, `used_in_product_listing`,
                                     `used_for_sort_by`, `is_configurable`, `apply_to`, `is_visible_in_advanced_search`, `position`, `is_wysiwyg_enabled`,
                                     `is_used_for_promo_rules`)
VALUES
  ((SELECT max(cast(attribute_id as unsigned)) from eav_attribute), NULL, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, NULL, 0, 0, 0, 0);

INSERT INTO `eav_attribute_label` ( `attribute_id`, `store_id`, `value`) VALUES
  ((SELECT max(cast(attribute_id as unsigned)) from eav_attribute), 1, 'sort_order');

-- unit
INSERT INTO `eav_attribute` ( `entity_type_id`, `attribute_code`, `attribute_model`, `backend_model`, `backend_type`, `backend_table`,
                              `frontend_model`, `frontend_input`, `frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
                              `default_value`, `is_unique`, `note`) VALUES
  (4, 'unit', NULL, NULL, 'varchar', NULL, NULL, 'text', 'unit', NULL, NULL, 0, 1, NULL, 0, NULL);

INSERT INTO `catalog_eav_attribute` (`attribute_id`, `frontend_input_renderer`, `is_global`, `is_visible`, `is_searchable`, `is_filterable`, `is_comparable`,
                                     `is_visible_on_front`, `is_html_allowed_on_front`, `is_used_for_price_rules`, `is_filterable_in_search`, `used_in_product_listing`,
                                     `used_for_sort_by`, `is_configurable`, `apply_to`, `is_visible_in_advanced_search`, `position`, `is_wysiwyg_enabled`,
                                     `is_used_for_promo_rules`)
VALUES
  ((SELECT max(cast(attribute_id as unsigned)) from eav_attribute), NULL, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, NULL, 0, 0, 0, 0);

INSERT INTO `eav_attribute_label` ( `attribute_id`, `store_id`, `value`) VALUES
  ((SELECT max(cast(attribute_id as unsigned)) from eav_attribute), 1, 'unit');

-- 20150807                添加Daemon_Order模块

-- 20150810 backend
create table custom_backend_permission(id int auto_increment primary key,
                                       code varchar(100),
                                       description varchar(500))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_backend_users(id int auto_increment primary key,
                                  user_name varchar(100),
                                  password varchar(100),
                                  permission int,CONSTRAINT `FK_ID` FOREIGN KEY (`permission`) REFERENCES `custom_backend_permission` (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into custom_backend_permission(code,description) values('admin','全局管理员权限');
insert into custom_backend_permission(code,description) values('nj_admin','南京管理员权限');
insert into custom_backend_permission(code,description) values('sh_admin','上海管理员权限');

insert into custom_backend_users(user_name,password,permission) values('admin',md5('youadmin'),1);
insert into custom_backend_users(user_name,password,permission) values('nj_admin',md5('nanjingadmin'),2);
insert into custom_backend_users(user_name,password,permission) values('sh_admin',md5('shanghaiyouadmin'),3);

