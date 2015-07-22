--create custom table for promotion
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

--select sql
select * from custom_promotions cp,
		      salesrule_coupon sc
where cp.coupon_id = sc.coupon_id
  and phone = '15151834774';

update salesrule_coupon set times_used = times_used + 1 where coupon_id = 17;
update salesrule set times_used = times_used + 1 where rule_id = (select rule_id from salesrule_coupon where couple_id = 17);
insert into salesrule_coupon_usage values (17,1,1);
insert into salesrule_customer(rule_id,customer_id,times_used) values(2,1,1);