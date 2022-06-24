<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerStudents extends SmsController
{
	
	/**
	** constructor
	**/
	function __construct(){
		parent::__construct();
	}
	
	/**
	** Get Apply
	**/
	function apply(){
	    $model = $this->getModel('students');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_STUDENT_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_STUDENT_DATA_SAVE_ERROR' );
		}
        $link = 'index.php?option=com_sms&view=students&task=editstudents&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('students');
		$id =$model->store();
		if (!empty($id)) {
			$msg = JText::_( 'LABEL_STUDENT_DATA_SAVE' );
		}else {
			$msg = JText::_( 'LABEL_STUDENT_DATA_SAVE_ERROR' );
		}
		$link = 'index.php?option=com_sms&view=students';
		$this->setRedirect($link, $msg);
    }
	 
	 
	/**
	** Get remove
	**/
	function remove(){
		$model = $this->getModel('students');
		if(!$model->delete()) {
			$msg = JText::_( 'LABEL_STUDENT_DATA_DELETED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_STUDENT_DATA_DELETED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=students', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'DEFAULT_CANCELL' );
		$this->setRedirect( 'index.php?option=com_sms&view=students', $msg );
	}
	
	
	/**
	** Get Save Comment
	**/
	function savecomment(){
		$cid = JRequest::getVar('cid');
		$roll = JRequest::getVar('roll');
		$eid = JRequest::getVar('eid');
		$tid = JRequest::getVar('tid');
		$comment = JRequest::getVar('comment');
		
		$model = $this->getModel('students');
		$id = $model->savecomment( $cid, $roll, $eid,  $tid, $comment);
		
		if (!empty($id)) {
			$comment = SmsHelper::selectSingleData('comments', 'sms_result_comments', 'id', $id);
			echo'<textarea cols="" rows="" id="comment" style="width: 98%;height: 100px;">'.$comment.'</textarea>';
		}else {
			echo '<p style="text-align: center;"><span id="meg" style=" background: red;color: #fff;padding: 3px 33px;">Error</span></p>';
		}
		
		JFactory::getApplication()->close();
	}

	
}
