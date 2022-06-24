/*--- UPDATE ASSETS RULES ---*/
UPDATE `#__assets`
SET `rules`='{"core.login.site":{"6":1,"2":1,"140001":1,"140002":1,"140003":1},"core.login.admin":{"6":1},"core.login.offline":{"6":1},"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
WHERE `id`=1; 

/*--- CREATE USER GROUP ---*/

/*-- DELETE GROUP --*/
DELETE FROM `#__usergroups` WHERE id = 140001;
DELETE FROM `#__usergroups` WHERE id = 140002;
DELETE FROM `#__usergroups` WHERE id = 140003;

INSERT INTO `#__usergroups` (`id`, `parent_id`, `lft`, `rgt`, `title`) VALUES
(140001, 1, 22, 23, 'Students'),
(140002, 1, 26, 27, 'Teachers'),
(140003, 1, 8, 9, 'Parents');

/*--- CREATE USER VIEW LABEL ---*/

/*-- DELETE VIEW LABEL --*/
DELETE FROM `#__viewlevels` WHERE id = 140004;
DELETE FROM `#__viewlevels` WHERE id = 140005;
DELETE FROM `#__viewlevels` WHERE id = 140006;

INSERT INTO `#__viewlevels` (`id`, `title`, `ordering`, `rules`) VALUES
(140004, 'Students', 0, '[140001]'),
(140005, 'Teachers', 0, '[140002]'),
(140006, 'Parents', 0, '[140003]');


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
  `admin` int(255) NOT NULL,
  `front` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*--- 01 ACADEMIC YEAR ---*/
DROP TABLE IF EXISTS `#__sms_academic_year`;

CREATE TABLE IF NOT EXISTS `#__sms_academic_year` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `year` varchar(255) COLLATE utf8_bin NOT NULL,
  `published` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin  ;

/*--- 02 ATTENDANCE ---*/
DROP TABLE IF EXISTS `#__sms_attendance`;

CREATE TABLE IF NOT EXISTS `#__sms_attendance` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `attendance_date` text COLLATE utf8_bin NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `section` varchar(255) COLLATE utf8_bin NOT NULL,
  `total_student` int(255) NOT NULL,
  `teacher` varchar(255) COLLATE utf8_bin NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;

/*--- 03 ATTENDANCE INFO ---*/
DROP TABLE IF EXISTS `#__sms_attendance_info`;

CREATE TABLE IF NOT EXISTS `#__sms_attendance_info` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `entry_by` int(255) NOT NULL,
  `student_id` int(255) NOT NULL,
  `attend` int(255) NOT NULL,
  `attendance_id` int(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;



/*--- 04 CLASS ---*/
DROP TABLE IF EXISTS `#__sms_class`;

CREATE TABLE IF NOT EXISTS `#__sms_class` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `class_name` text COLLATE utf8_bin NOT NULL,
  `section` varchar(255) COLLATE utf8_bin NOT NULL,
  `division` varchar(255) COLLATE utf8_bin NOT NULL,
  `subjects` varchar(255) COLLATE utf8_bin NOT NULL,
  `grade_system` int(255) NOT NULL,
  `published` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 05 DIVISION ---*/
DROP TABLE IF EXISTS `#__sms_division`;

CREATE TABLE IF NOT EXISTS `#__sms_division` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `division_name` text COLLATE utf8_bin NOT NULL,
  `published` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 06 EXAMS ---*/
DROP TABLE IF EXISTS `#__sms_exams`;

CREATE TABLE IF NOT EXISTS `#__sms_exams` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `examdate` varchar(255) COLLATE utf8_bin NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  `published` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 07 EXAMS GRADE ---*/
DROP TABLE IF EXISTS `#__sms_exams_grade`;

CREATE TABLE IF NOT EXISTS `#__sms_exams_grade` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `category` int(255) NOT NULL,
  `name` longtext COLLATE utf8_bin NOT NULL,
  `grade_point` text COLLATE utf8_bin NOT NULL,
  `mark_from` int(255) NOT NULL,
  `mark_upto` int(255) NOT NULL,
  `comment` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;

INSERT INTO `#__sms_exams_grade` (`id`, `category`, `name`, `grade_point`, `mark_from`, `mark_upto`, `comment`) VALUES
(1, 1, 'A+', '5', 80, 100, 'Excellent'),
(2, 1, 'A', '4', 70, 79, 'Very Good'),
(3, 1, 'A-', '3.5', 60, 69, 'Good'),
(4, 1, 'B', '3', 50, 59, 'Okay'),
(5, 1, 'C', '2', 40, 49, 'Not good'),
(6, 1, 'D', '1', 33, 39, 'Pass'),
(7, 1, 'F', '0', 0, 32, 'Fail');


/*--- 08 EXAMS MARK ---*/
DROP TABLE IF EXISTS `#__sms_exams_mark`;

CREATE TABLE IF NOT EXISTS `#__sms_exams_mark` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `student_id` int(255) NOT NULL,
  `subject_id` int(255) NOT NULL,
  `class_id` int(255) NOT NULL,
  `exam_id` int(255) NOT NULL,
  `marks` int(255) NOT NULL,
  `mark_total` int(255) NOT NULL DEFAULT '100',
  `comment` longtext COLLATE utf8_bin NOT NULL,
  `roll` varchar(255) COLLATE utf8_bin NOT NULL,
  `year` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 09 EXPENSE CATEGORY ---*/
DROP TABLE IF EXISTS `#__sms_expense_category`;

CREATE TABLE IF NOT EXISTS `#__sms_expense_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


/*--- 10 EXPENSE  ---*/
DROP TABLE IF EXISTS `#__sms_expenses`;

CREATE TABLE IF NOT EXISTS `#__sms_expenses` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `cat` int(255) NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `ammount` int(255) NOT NULL,
  `method` varchar(255) COLLATE utf8_bin NOT NULL,
  `expense_date` text COLLATE utf8_bin NOT NULL,
  `uid` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 11 GRADE CATEGORY  ---*/
DROP TABLE IF EXISTS `#__sms_grade_category`;

CREATE TABLE IF NOT EXISTS `#__sms_grade_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `mark` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


INSERT INTO `#__sms_grade_category` (`id`, `name`, `mark`) VALUES
(1, 'System A (According 100 Mark)', 100);

/*--- 12 MESSAGE  ---*/
DROP TABLE IF EXISTS `#__sms_message`;

CREATE TABLE IF NOT EXISTS `#__sms_message` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sender_id` int(255) NOT NULL,
  `recever_name` text COLLATE utf8_bin NOT NULL,
  `recever_id` int(255) NOT NULL,
  `subject` text COLLATE utf8_bin NOT NULL,
  `message` longtext COLLATE utf8_bin NOT NULL,
  `status` int(255) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reply` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 13 MESSAGE REPLY  ---*/
DROP TABLE IF EXISTS `#__sms_message_reply`;

CREATE TABLE IF NOT EXISTS `#__sms_message_reply` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `message_id` int(255) NOT NULL,
  `sender_id` int(255) NOT NULL,
  `recever_id` int(255) NOT NULL,
  `message` longtext COLLATE utf8_bin NOT NULL,
  `status` int(255) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 14 PARENTS  ---*/
DROP TABLE IF EXISTS `#__sms_parents`;

CREATE TABLE IF NOT EXISTS `#__sms_parents` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `chabima` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `churanita` varchar(255) COLLATE utf8_bin NOT NULL,
  `mobile_no` varchar(255) COLLATE utf8_bin NOT NULL,
  `photo` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_id` int(255) NOT NULL,
  `student_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `admission_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_date` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;



/*--- 15 PAY TYPE  ---*/
DROP TABLE IF EXISTS `#__sms_pay_type`;

CREATE TABLE IF NOT EXISTS `#__sms_pay_type` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `fee` int(255) NOT NULL,
  `comment` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


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


/*--- 18 SECTIONS  ---*/
DROP TABLE IF EXISTS `#__sms_sections`;

CREATE TABLE IF NOT EXISTS `#__sms_sections` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `section_name` text COLLATE utf8_bin NOT NULL,
  `published` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 19 STUDENT YEAR  ---*/
DROP TABLE IF EXISTS `#__sms_student_year`;

CREATE TABLE IF NOT EXISTS `#__sms_student_year` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sid` int(255) NOT NULL,
  `class` int(255) NOT NULL,
  `roll` int(255) NOT NULL,
  `section` int(255) NOT NULL,
  `division` int(11) NOT NULL,
  `year` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 20 STUDENT   ---*/
DROP TABLE IF EXISTS `#__sms_students`;

CREATE TABLE IF NOT EXISTS `#__sms_students` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) COLLATE utf8_bin NOT NULL,
  `request_account` int(255) NOT NULL DEFAULT '0',
  `published` int(255) NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `chabima` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `churanita` varchar(255) COLLATE utf8_bin NOT NULL,
  `photo` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_id` int(255) NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `roll` varchar(255) COLLATE utf8_bin NOT NULL,
  `division` varchar(255) COLLATE utf8_bin NOT NULL,
  `section` varchar(255) COLLATE utf8_bin NOT NULL,
  `year` int(255) NOT NULL,
  `admission_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_date` text COLLATE utf8_bin NOT NULL,
  `transport_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin  ;



/*--- 21 SUBJECTS  ---*/
DROP TABLE IF EXISTS `#__sms_subjects`;

CREATE TABLE IF NOT EXISTS `#__sms_subjects` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `subject_name` text COLLATE utf8_bin NOT NULL,
  `subject_shot_name` text COLLATE utf8_bin NOT NULL,
  `subject_code` text COLLATE utf8_bin NOT NULL,
  `published` int(255) NOT NULL,
  `order_number` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;


/*--- 22 TEACHERS  ---*/
DROP TABLE IF EXISTS `#__sms_teachers`;

CREATE TABLE IF NOT EXISTS `#__sms_teachers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `chabima` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `churanita` varchar(255) COLLATE utf8_bin NOT NULL,
  `photo` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_id` int(255) NOT NULL,
  `designation` text COLLATE utf8_bin NOT NULL,
  `class` varchar(255) COLLATE utf8_bin NOT NULL,
  `section` varchar(255) COLLATE utf8_bin NOT NULL,
  `subject` varchar(255) COLLATE utf8_bin NOT NULL,
  `admission_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_date` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;



/*--- 23 FIELDS  ---*/
DROP TABLE IF EXISTS `#__sms_fields`;

CREATE TABLE IF NOT EXISTS `#__sms_fields` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(255) NOT NULL,
  `published` int(255) NOT NULL DEFAULT '1',
  `type` int(255) NOT NULL,
  `required` int(255) NOT NULL,
  `section` int(255) NOT NULL,
  `option_param` varchar(1800) NOT NULL,
  `field_order` int(255) NOT NULL,
  `profile` int(255) NOT NULL,
  `list` int(255) NOT NULL,
  `biodata` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*---  FIELDS INSERT  ---*/
INSERT INTO `#__sms_fields` (`id`, `field_name`, `published`, `type`, `required`, `section`, `option_param`, `field_order`, `profile`, `list`, `biodata`) VALUES
(1, 'Father''s Name', 1, 1, 1, 1, '', 1, 1, 0, 1),
(5, 'Address', 1, 2, 1, 1, '', 7, 1, 0, 1),
(7, 'Gender', 1, 4, 1, 1, 'male=Male,\r\nfemale=Female', 6, 0, 0, 0),
(9, 'Father''s Name', 1, 1, 1, 2, '', 1, 1, 0, 1),
(12, 'Father''s Name', 1, 1, 1, 3, '', 1, 1, 0, 1),
(13, 'Gender', 1, 4, 1, 3, 'male=Male,\r\nfemale=Female', 5, 1, 0, 1),
(14, 'Birthday', 1, 6, 1, 3, '', 6, 1, 0, 1),
(15, 'Mother''s Name', 1, 1, 1, 1, '', 2, 1, 0, 1),
(16, 'Religion', 1, 1, 1, 1, '', 3, 1, 0, 1),
(17, 'Blood Group', 1, 1, 0, 1, '', 4, 1, 0, 1),
(18, 'Birthday', 1, 6, 1, 1, '', 5, 1, 0, 1),
(19, 'Mother''s Name', 1, 1, 1, 2, '', 2, 1, 0, 1),
(20, 'Religion', 1, 1, 0, 2, '', 3, 1, 0, 1),
(21, 'Blood Group', 1, 1, 0, 2, '', 4, 1, 0, 1),
(22, 'Birthday', 1, 6, 1, 2, '', 5, 1, 0, 1),
(23, 'Address', 1, 2, 1, 2, '', 7, 1, 0, 1),
(24, 'Mother''s Name', 1, 1, 1, 3, '', 2, 1, 0, 1),
(25, 'Religion', 1, 1, 0, 3, '', 3, 1, 0, 1),
(26, 'Blood Group', 1, 1, 0, 3, '', 4, 1, 0, 1),
(27, 'Address', 1, 2, 1, 3, '', 7, 1, 0, 1),
(28, 'Gender', 1, 4, 1, 2, 'male=Male,\r\nfemale=Female', 6, 1, 0, 1);

/*--- 24 FIELDS DATA ---*/
DROP TABLE IF EXISTS `#__sms_fields_data`;

CREATE TABLE IF NOT EXISTS `#__sms_fields_data` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fid` int(255) NOT NULL,
  `sid` int(255) NOT NULL,
  `data` varchar(1800) NOT NULL,
  `panel_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

/*--- 25 FIELDS SECTION ---*/
DROP TABLE IF EXISTS `#__sms_fields_section`;

CREATE TABLE IF NOT EXISTS `#__sms_fields_section` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

/*--- 32 FIELDS SECTION INSERT ---*/
INSERT INTO `#__sms_fields_section` (`id`, `name`) VALUES
(1, 'student'),
(2, 'teacher'),
(3, 'parent');

/*--- 33 FIELDS TYPE ---*/
DROP TABLE IF EXISTS `#__sms_fields_type`;

CREATE TABLE IF NOT EXISTS `#__sms_fields_type` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*--- 33 FIELDS INSERT ---*/
INSERT INTO `#__sms_fields_type` (`id`, `type`) VALUES
(1, 'Input Box'),
(2, 'Text Area'),
(3, 'Check Box'),
(4, 'Radio Box'),
(5, 'Select Box'),
(6, 'datepicker');

/*--- 34 ACTIVATION ---*/
DROP TABLE IF EXISTS `#__sms_activation`;

CREATE TABLE IF NOT EXISTS `#__sms_activation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `buyer_name` varchar(1800) NOT NULL,
  `domain_name` varchar(1800) NOT NULL,
  `p_code` varchar(1800) NOT NULL,
  `a_code` varchar(1800) NOT NULL,
  `ag_code` varchar(1800) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*--- 35 RESULT COMMENT ---*/
DROP TABLE IF EXISTS `#__sms_result_comments`;

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