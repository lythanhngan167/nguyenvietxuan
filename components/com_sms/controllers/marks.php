<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerMarks extends SmsController
{
	
	function __construct()
	{
		parent::__construct();

	}
	
	/**
	** Save Mark
	**/
	function savemark(){
		$mark = JRequest::getVar('mark');
		$comment = JRequest::getVar('comment');
		$exam_id = JRequest::getVar('exam_id');
		$class_id = JRequest::getVar('class_id');
		$subject_id = JRequest::getVar('subject_id');
		$student_id = JRequest::getVar('sid');
		$year = JRequest::getVar('year');
		$roll = JRequest::getVar('roll');
		
		$model = $this->getModel('marks');
		$id = $model->savemark( $mark,$comment, $exam_id,  $class_id, $subject_id, $student_id, $roll,$year );
		
		if (!empty($id)) {
			echo ' ';
		}else {
			echo '<p style="text-align: center;"><span id="meg" style=" background: red;color: #fff;padding: 3px 33px;">Error</span></p>';
		}
		
		JFactory::getApplication()->close();
	}
	
	
	 
	/**
	** Student List
	**/
	function getstudentlist(){
		$exam_id    = JRequest::getVar('exam');
		$class_id   = JRequest::getVar('classid');
		$section_id = JRequest::getVar('section');
		$subject_id = JRequest::getVar('subject');
		$model      = $this->getModel('marks');
	 
	    if(!empty($exam_id) && !empty($class_id) && !empty($section_id) && !empty($subject_id)){
		    echo $model->getstudentList($exam_id, $class_id,$section_id, $subject_id);
		}else{
		    if(empty($exam_id)){
	            echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam ! </div>';}
	        else if(empty($class_id)){
	            echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select class ! </div>';}
	        else if(empty($section_id)){
	            echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select section ! </div>';}
	        else if(empty($subject_id)){
	            echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select subject ! </div>';
	        }else{
		        echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam & section !</div>';
	        }
	    }
	    JFactory::getApplication()->close();
	}
	 
	 
	 
}
