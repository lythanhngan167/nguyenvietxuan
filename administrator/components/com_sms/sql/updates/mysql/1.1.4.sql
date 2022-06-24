
/*--- 01 CHANGE #__SMS_TEACHER "student_id" to VARCHAR  ---*/
ALTER TABLE `#__sms_parents` CHANGE `student_id` `student_id` VARCHAR(255) NOT NULL;

/* --- Add Result comment ---*/
CREATE TABLE IF NOT EXISTS `#__sms_result_comments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `roll` varchar(255) COLLATE utf8_bin NOT NULL,
  `class` int(255) NOT NULL,
  `eid` int(255) NOT NULL,
  `comments` text COLLATE utf8_bin NOT NULL,
  `tid` int(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


