<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerExams extends SmsController
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
	    $model = $this->getModel('exams');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_EXAM_DATA_SAVE' );
		}else{
			$msg = JText::_( 'LABEL_EXAM_DATA_SAVE_ERROR' );
		}
        $link = 'index.php?option=com_sms&view=exams&task=editexam&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('exams');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_EXAM_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_EXAM_DATA_SAVE_ERROR' );
		}
		$link = 'index.php?option=com_sms&view=exams';
		$this->setRedirect($link, $msg);
	}
	 
	 
	/**
	** Get Publish 
	**/
	function publish(){
	    $model = $this->getModel('exams');
		if(!$model->toggle('exams','id','published','1')) {
			$msg = JText::_( 'LABEL_EXAM_DATA_PUBLISHED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_EXAM_DATA_PUBLISHED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=exams', $msg );
	}
	
	
	/**
	** Get unpublish 
	*/
	function unpublish(){
	    $model = $this->getModel('exams');
		if(!$model->toggle('exams','id','published','0')) {
			$msg = JText::_( 'LABEL_EXAM_DATA_UNPUBLISHED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_EXAM_DATA_UNPUBLISHED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=exams', $msg );
	}
	
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('exams');
		if(!$model->delete()) {
			$msg = JText::_( 'LABEL_EXAM_DATA_DELETED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_EXAM_DATA_DELETED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=exams', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'DEFAULT_CANCELL' );
		$this->setRedirect( 'index.php?option=com_sms&view=exams', $msg );
	}

	
}
