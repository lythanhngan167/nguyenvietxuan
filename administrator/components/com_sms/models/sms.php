<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelSms extends JModelList
{
	
	/**
	** TOTAL PRESENT
	**/
	function totalPresent($date){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where('DATE(create_date) = ' . $db->quote($date))
			->where($db->quoteName('attend') . ' = '. $db->quote('1'));
		$db->setQuery($query_result);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Get today present
	**/
	function getTodayPresent($year, $month, $day){
	    $db = JFactory::getDBO();
		$query_grade = "SELECT * FROM `#__sms_attendance_info` WHERE YEAR(create_date) = '".$year."' AND MONTH(create_date) = '".$month."' AND DAY(create_date) = '".$day."' AND attend = 1 ";
        $query_grade.=" ORDER BY id asc ";
        $db->setQuery($query_grade);
        $data = $db->loadObjectList();
		return $data;
	}
	
	
	/**
	** Get Total Student
	**/
	function getTotalStudents(){
	    $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__sms_students`  ";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		$total = count( $rows);
       return $total;
	}
	
	/**
	** Get Total Teacher
	**/
	function getTotalTeachers(){
	    $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__sms_teachers`  ";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		$total = count( $rows);
       return $total;
	}
	
	/**
	** Get Total Parent
	**/
	function getTotalParents(){
	    $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__sms_parents`  ";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		$total = count( $rows);
       return $total;
	}
		
	
}
