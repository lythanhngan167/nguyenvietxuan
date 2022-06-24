<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerAttendance extends SmsController
{
	
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

	}
	
	
	
	/**
	** Get Save
	**/
	function saveattend(){
		$aid     = JRequest::getVar('aid');
		$user_id = JRequest::getVar('tid');
		$date    = JRequest::getVar('date');
		$class   = JRequest::getVar('class');
		$section = JRequest::getVar('section');
		$model   = $this->getModel('attendance');
		$id      = $model->saveattend($aid, $user_id, $date, $class, $section);
	    if (!empty($id)) {
			$msg = JText::_( 'Successfully saved!' );
		}else {
			$msg = JText::_( 'Error Saving Data' );
		}
		
		$link 	 = JRoute::_( 'index.php?option=com_sms&view=attendance&task=editattend&cid='. $id );
		$this->setRedirect($link, $msg);
	}
	
	/**
	** Get Save Status
	**/
	function savestatus(){
		$aid    = JRequest::getVar('aid');
		$sid    = JRequest::getVar('sid');
		$status = JRequest::getVar('status');

	    if($status=="true"){
		    $status_value ="1";
	    }
	 
	    if($status=="false"){
		    $status_value ="0";
	    }
	 
	    $model = $this->getModel('attendance');
	    $id = $model->savestatus($aid, $sid, $status_value);
	    if (!empty($id)) {
			echo ' ';
		}else {
			echo 'Error';
		}
		
		JFactory::getApplication()->close();
	}
	
	
	
	
}
