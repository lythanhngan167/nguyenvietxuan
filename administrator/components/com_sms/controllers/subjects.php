<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerSubjects extends SmsController
{
	
	/**
	** Set constructor
	**/
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	** Get Apply
	**/
	function apply(){
	    $model = $this->getModel('subjects');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_SAVE_ERROR' );
		}
        $link = 'index.php?option=com_sms&view=subjects&task=editsubject&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save 
	**/
	function save(){
		$model = $this->getModel('subjects');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_SAVE_ERROR' );
		}
		$link = 'index.php?option=com_sms&view=subjects';
		$this->setRedirect($link, $msg);
	}
	 
	 
	/**
	** Get Publish 
	**/
	function publish(){
	    $model = $this->getModel('subjects');
		if(!$model->toggle('subjects','id','published','1')) {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_PUBLISHED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_PUBLISHED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=subjects', $msg );
	}
	

	/**
	** Get unpublish 
	**/
	function unpublish(){
	    $model = $this->getModel('subjects');
		if(!$model->toggle('subjects','id','published','0')) {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_UNPUBLISHED_ERROR' );
		} else {
			$msg = JText::_( 'Data unpublished' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=subjects', $msg );
	}
	
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('subjects');
		if(!$model->delete()) {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_DELETED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_SUBJECT_DATA_DELETED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=subjects', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel()
	{
		$msg = JText::_( 'DEFAULT_CANCELL' );
		$this->setRedirect( 'index.php?option=com_sms&view=subjects', $msg );
	}

	
}
