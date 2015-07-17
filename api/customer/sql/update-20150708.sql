--Add new attribute to custoemr (phone,birthday,sex,image,nickname)
INSERT INTO `eav_attribute`(`entity_type_id`, `attribute_code`, `attribute_model`,
`backend_model`, `backend_type`, `backend_table`, `frontend_model`, `frontend_input`,
`frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`,
`default_value`, `is_unique`, `note`)
VALUES
(1,'phone',NUll,NUll,'varchar',NULL,NULL,'text','Phone Number',NULL,NULL,0,0,NULL,0,NULL),
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

--demo record
INSERT INTO `customer_entity_varchar`(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
VALUES
(1,132,1,'15151834774'),
(1,133,1,str_to_date('1990-1-1 00:00:00','%Y-%m-%d %H:%i:%s')),
(1,134,1,'1'),
(1,135,1,'/image/a.jpg'),
(1,136,1,'Daemon');

--get phone,birthday,sex,image,nickname
--get customer entity id
select cev.entity_id from customer_entity_varchar cev,
                          eav_attribute ea
                    where cev.attribute_id = ea.attribute_id
                      and ea.attribute_code = 'phone'
                      and cev.value = '12345678902';

--get birthday
select cev.value from customer_entity_varchar cev,
                      eav_attribute ea
                where cev.attribute_id = ea.attribute_id
                  and ea.attribute_code = 'birthday'
                  and cev.entity_id = 13;
--get sex
select cev.value from customer_entity_varchar cev,
                      eav_attribute ea
                where cev.attribute_id = ea.attribute_id
                  and ea.attribute_code = 'sex'
                  and cev.entity_id = 13;
--get image
select cev.value from customer_entity_varchar cev,
                      eav_attribute ea
                where cev.attribute_id = ea.attribute_id
                  and ea.attribute_code = 'myimage'
                  and cev.entity_id = 13;
--get nickname
select cev.value from customer_entity_varchar cev,
                      eav_attribute ea
                where cev.attribute_id = ea.attribute_id
                  and ea.attribute_code = 'image'
                  and cev.entity_id = 13;