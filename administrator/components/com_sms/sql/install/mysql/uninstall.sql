
DROP TABLE IF EXISTS `#__sms_addons`;
/*--- 01 ACADEMIC YEAR ---*/
DROP TABLE IF EXISTS `#__sms_academic_year`;

/*--- 02 ATTENDANCE ---*/
DROP TABLE IF EXISTS `#__sms_attendance`;

/*--- 03 ATTENDANCE INFO ---*/
DROP TABLE IF EXISTS `#__sms_attendance_info`;

/*--- 04 CLASS ---*/
DROP TABLE IF EXISTS `#__sms_class`;

/*--- 05 DIVISION ---*/
DROP TABLE IF EXISTS `#__sms_division`;

/*--- 06 EXAMS ---*/
DROP TABLE IF EXISTS `#__sms_exams`;

/*--- 07 EXAMS GRADE ---*/
DROP TABLE IF EXISTS `#__sms_exams_grade`;

/*--- 08 EXAMS MARK ---*/
DROP TABLE IF EXISTS `#__sms_exams_mark`;

/*--- 09 EXPENSE CATEGORY ---*/
DROP TABLE IF EXISTS `#__sms_expense_category`;

/*--- 10 EXPENSE  ---*/
DROP TABLE IF EXISTS `#__sms_expenses`;

/*--- 11 GRADE CATEGORY  ---*/
DROP TABLE IF EXISTS `#__sms_grade_category`;

/*--- 12 MESSAGE  ---*/
DROP TABLE IF EXISTS `#__sms_message`;

/*--- 13 MESSAGE REPLY  ---*/
DROP TABLE IF EXISTS `#__sms_message_reply`;

/*--- 14 PARENTS  ---*/
DROP TABLE IF EXISTS `#__sms_parents`;

/*--- 15 PAY TYPE  ---*/
DROP TABLE IF EXISTS `#__sms_pay_type`;

/*--- 16 PAYMENTS  ---*/
DROP TABLE IF EXISTS `#__sms_payments`;

/*--- 17 PAYMENTS  ---*/
DROP TABLE IF EXISTS `#__sms_paypal`;

/*--- 18 SECTIONS  ---*/
DROP TABLE IF EXISTS `#__sms_sections`;

/*--- 19 STUDENT YEAR  ---*/
DROP TABLE IF EXISTS `#__sms_student_year`;

/*--- 20 STUDENT YEAR  ---*/
DROP TABLE IF EXISTS `#__sms_students`;

/*--- 21 SUBJECTS  ---*/
DROP TABLE IF EXISTS `#__sms_subjects`;

/*--- 22 TEACHERS  ---*/
DROP TABLE IF EXISTS `#__sms_teachers`;

/*--- 23 FIELDS  ---*/
DROP TABLE IF EXISTS `#__sms_fields`;

/*--- 24 FIELDS DATA ---*/
DROP TABLE IF EXISTS `#__sms_fields_data`;

/*--- 25 FIELDS SECTION ---*/
DROP TABLE IF EXISTS `#__sms_fields_section`;

/*--- 26 FIELDS TYPE ---*/
DROP TABLE IF EXISTS `#__sms_fields_type`;

/*--- 27 ACTIVATION ---*/
DROP TABLE IF EXISTS `#__sms_activation`;

/*--- 28 RESULT COMMENT ---*/
DROP TABLE IF EXISTS `#__sms_result_comments`;

/*-- DELETE VIEW LABEL --*/
DELETE FROM `#__viewlevels` WHERE id = 140004;
DELETE FROM `#__viewlevels` WHERE id = 140005;
DELETE FROM `#__viewlevels` WHERE id = 140006;

/*-- DELETE GROUP --*/
DELETE FROM `#__usergroups` WHERE id = 140001;
DELETE FROM `#__usergroups` WHERE id = 140002;
DELETE FROM `#__usergroups` WHERE id = 140003;

