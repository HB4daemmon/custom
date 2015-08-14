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
drop table custom_backend_users;
drop table custom_backend_assign;
drop table custom_backend_permission;
drop table custom_app_permission;
drop table custom_backend_role;


create table custom_backend_permission(id int auto_increment primary key,
                                       permission varchar(100) NOT NULL,
                                       description varchar(500) NOT NULL,
                                       enable_flag smallint(1) NOT NULL)
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_app_permission(id int auto_increment primary key,
                                       permission varchar(100) NOT NULL,
                                       description varchar(500) NOT NULL,
                                       enable_flag smallint(1) NOT NULL)
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_backend_role(id int auto_increment primary key,
                                       role varchar(100) NOT NULL,
                                       description varchar(500) NOT NULL,
                                       enable_flag smallint(1) NOT NULL,
                                       category varchar(50) NOT NULL)
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_backend_assign(role_id int NOT NULL,
                                     permission_id int NOT NULL,
                                     note varchar(500),
                                     enable_flag smallint(1) NOT NULL,
  CONSTRAINT `FK_RESIGN_1` FOREIGN KEY (`role_id`) REFERENCES `custom_backend_role` (`id`),
  CONSTRAINT `FK_RESIGN_2` FOREIGN KEY (`permission_id`) REFERENCES `custom_backend_permission` (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_backend_users(id int auto_increment primary key,
                                  user_name varchar(100) NOT NULL,
                                  password varchar(100) NOT NULL,
                                  role int NOT NULL,
                                  enable_flag smallint(1) NOT NULL,
  CONSTRAINT `FK_USER_1` FOREIGN KEY (`role`) REFERENCES `custom_backend_role` (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
Alter table custom_backend_users add unique(user_name);

insert into custom_backend_permission(permission,description,enable_flag) values('old_oneyuan','1元购推广统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('old_coupon','优惠码推广统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('old_sku','SKU商品销售量统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('old_order','订单统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('old_amount','总数统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('old_inventory','库存管理',1);
insert into custom_backend_permission(permission,description,enable_flag) values('new_nj_order','南京订单统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('new_sh_order','上海订单统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('new_nj_amount','南京总数统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('new_sh_amount','上海总数统计',1);
insert into custom_backend_permission(permission,description,enable_flag) values('daily_nj_purchase','南京采购单',1);
insert into custom_backend_permission(permission,description,enable_flag) values('daily_nj_shipment','南京配送单',1);
insert into custom_backend_permission(permission,description,enable_flag) values('daily_sh_purchase','上海采购单',1);
insert into custom_backend_permission(permission,description,enable_flag) values('daily_sh_shipment','上海配送单',1);
insert into custom_backend_permission(permission,description,enable_flag) values('daily_refund','退货单',1);
insert into custom_backend_permission(permission,description,enable_flag) values('daily_chart','趋势表',1);

insert into custom_app_permission(permission,description,enable_flag) values('manager','经理',1);
insert into custom_app_permission(permission,description,enable_flag) values('promoter','推广',1);

insert into custom_backend_role(role,description,enable_flag,category) values ('admin','领导层',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('manager','经理',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('nj_header','南京分拣中心Head',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('nj_purchase','南京分拣中心采购',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('nj_pack','南京分拣中心包装员',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('sh_header','上海分拣中心Head',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('sh_purchase','上海分拣中心采购',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('sh_pack','上海分拣中心包装员',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('purchase_header','采购经理',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('nj_main_driver','南京干线司机',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('sh_main_driver','上海干线司机',1,'backend');
insert into custom_backend_role(role,description,enable_flag,category) values ('app_manager','内部APP经理',1,'app');
insert into custom_backend_role(role,description,enable_flag,category) values ('app_promoter','内部APP推广',1,'app');


insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,1,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,2,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,3,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,4,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,5,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,6,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,7,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,8,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,9,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,10,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,11,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,12,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,13,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,14,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,15,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(1,16,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,1,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,2,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,3,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,4,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,5,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,6,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,7,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,8,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,9,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,10,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,11,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,12,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,13,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,14,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,15,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(2,16,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,1,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,2,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,4,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,5,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,6,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,7,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,9,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,11,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,12,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(3,15,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(4,11,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(4,12,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(5,7,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(5,9,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(5,11,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(5,12,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,1,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,2,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,4,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,5,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,6,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,8,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,10,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,13,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,14,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(6,15,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(7,13,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(7,14,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(8,8,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(8,10,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(8,13,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(8,14,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(9,11,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(9,13,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(10,7,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(10,9,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(10,11,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(10,12,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(10,15,1);

insert into custom_backend_assign(role_id,permission_id,enable_flag) values(11,5,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(11,6,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(11,8,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(11,10,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(11,13,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(11,14,1);
insert into custom_backend_assign(role_id,permission_id,enable_flag) values(11,15,1);

insert into custom_backend_users(user_name,password,role,enable_flag) values('admin',md5('888888'),1,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('liukaijie',md5('888888'),1,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('wangjunpeng',md5('888888'),1,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('chengdong',md5('888888'),1,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('yushouhua',md5('888888'),1,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('zhaoyuhan',md5('888888'),1,1);

insert into custom_backend_users(user_name,password,role,enable_flag) values('caishunjie',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('xiaoshanshan',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('limengfei',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('yangguanqun',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('zhangquan',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('zhoujiwen',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('fanghuayuan',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('xuhao',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('xushengyang',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('liang',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('zhengmingxing',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('wanghandong',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('xujilun',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('pengfei',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('shachun',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('liuyunfeng',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('shaoxiaoze',md5('888888'),2,1);
insert into custom_backend_users(user_name,password,role,enable_flag) values('mengzhuo',md5('888888'),2,1);

insert into custom_backend_users(user_name,password,role,enable_flag) values('test1',md5('888888'),3,1);

insert into custom_backend_users(user_name,password,role,enable_flag) values('yaochenlu',md5('888888'),4,1);

insert into custom_backend_users(user_name,password,role,enable_flag) values('wangjuan',md5('888888'),5,1);

insert into custom_backend_users(user_name,password,role,enable_flag) values('wangtao',md5('888888'),10,1);

insert into custom_backend_users(user_name,password,role,enable_flag) values('lixiuxin',md5('888888'),11,1);

-- create table 20150812
drop table custom_backend_users;
drop table custom_backend_assign;
drop table custom_backend_permission;
drop table custom_backend_role;


create table custom_backend_permission(id int auto_increment primary key,
                                       permission_code varchar(100) NOT NULL,
                                       description varchar(500) NOT NULL,
                                       upper_permission_code varchar(100) NOT NULL,
                                       enable_flag smallint(1) NOT NULL)
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_backend_role(id int auto_increment primary key,
                                 role_code varchar(100) NOT NULL,
                                 description varchar(500) NOT NULL,
                                 enable_flag smallint(1) NOT NULL,
                                 category varchar(50) NOT NULL)
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_backend_assign(role_id int NOT NULL,
                                   permission_id int NOT NULL,
                                   note varchar(500),
                                   enable_flag smallint(1) NOT NULL,
  CONSTRAINT `FK_RESIGN_1` FOREIGN KEY (`role_id`) REFERENCES `custom_backend_role` (`id`),
  CONSTRAINT `FK_RESIGN_2` FOREIGN KEY (`permission_id`) REFERENCES `custom_backend_permission` (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
create table custom_backend_users(id int auto_increment primary key,
                                  user_code varchar(20) NOT NULL,
                                  user_name varchar(100) NOT NULL,
                                  nick_name varchar(100),
                                  password varchar(100) NOT NULL,
                                  backend_role_id int,
                                  app_role_id int,
                                  enable_flag smallint(1) NOT NULL,
  CONSTRAINT `FK_USER_1` FOREIGN KEY (`backend_role_id`) REFERENCES `custom_backend_role` (`id`),
  CONSTRAINT `FK_USER_2` FOREIGN KEY (`app_role_id`) REFERENCES `custom_backend_role` (`id`))
  ENGINE=InnoDB DEFAULT CHARSET=utf8;
Alter table custom_backend_users add unique(user_code);