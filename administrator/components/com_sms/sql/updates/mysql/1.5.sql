
CREATE TABLE IF NOT EXISTS `#__sms_addons` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `alias` varchar(255) NOT NULL,
  `is_install` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `active_code` text NOT NULL,
  `product_id` text NOT NULL,
  `version` text NOT NULL,
  `icon` text NOT NULL,
  `addon_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*--- 16 PAYMENTS  ---*/
DROP TABLE IF EXISTS `#__sms_payments`;

CREATE TABLE IF NOT EXISTS `#__sms_payments` (
   `id` int(255) NOT NULL AUTO_INCREMENT,
  `student_id` int(255) NOT NULL,
  `student_class` int(255) NOT NULL,
  `student_section` int(255) NOT NULL,
  `student_roll` varchar(255) COLLATE utf8_bin NOT NULL,
  `payment_method` varchar(255) COLLATE utf8_bin NOT NULL,
  `month` varchar(255) COLLATE utf8_bin NOT NULL,
  `year` varchar(255) COLLATE utf8_bin NOT NULL,
  `pay_for_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `total_bill` varchar(255) COLLATE utf8_bin NOT NULL,
  `paid_ammount` int(255) NOT NULL,
  `due_ammount` varchar(255) COLLATE utf8_bin NOT NULL,
  `status` int(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` text COLLATE utf8_bin NOT NULL,
  `uid` int(255) NOT NULL,
  `txn_id` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 17 PAYMENTS  ---*/
CREATE TABLE IF NOT EXISTS `#__sms_paypal` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `payer_email` text NOT NULL,
  `payer_id` text NOT NULL,
  `payer_status` text NOT NULL,
  `transaction_id` text NOT NULL,
  `total_paid_amt` text NOT NULL,
  `payment_status` text NOT NULL,
  `payment_type` text NOT NULL,
  `txn_type` text NOT NULL,
  `payment_date` text NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


