
CREATE TABLE IF NOT EXISTS `#__sms_activation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `buyer_name` varchar(1800) NOT NULL,
  `domain_name` varchar(1800) NOT NULL,
  `p_code` varchar(1800) NOT NULL,
  `a_code` varchar(1800) NOT NULL,
  `ag_code` varchar(1800) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

