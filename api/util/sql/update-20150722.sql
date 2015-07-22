--create errorcode table
CREATE TABLE IF NOT EXISTS `custom_errorcode` (
  `errorcode_id` int(10) NOT NULL AUTO_INCREMENT,
  `errorcode_number` int(10) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` varchar(200),
  PRIMARY KEY(`errorcode_id`),
  UNIQUE KEY (`errorcode_number`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;