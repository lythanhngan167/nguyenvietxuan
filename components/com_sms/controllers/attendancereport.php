<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsControllerAttendancereport extends SmsController
{
	
	function __construct()
	{
		parent::__construct();

	}
	
	/**
	** Get Attendance Report
	**/
	function getattendancereport(){
		$sid = JRequest::getVar('sid');
		$year = JRequest::getVar('year');
		$month = JRequest::getVar('month');
		$model = $this->getModel('attendancereport');
		if(!empty($sid) && !empty($year) && !empty($month)){
		    $return_value = $model->DisplayAttendance( $year, $month, $sid);
		    echo $return_value;
		}else{
			if(empty($sid)){
				echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select student !</div>';
			}elseif(empty($month)){
                echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select month !</div>';
			}elseif (empty($year)) {
				echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select year !</div>';
			}
		 
		}
		JFactory::getApplication()->close();
	}
	
	
}
