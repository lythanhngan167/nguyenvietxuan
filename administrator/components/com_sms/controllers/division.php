<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
class SmsControllerDivision extends SmsController
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
	** Get Apply
	**/
	function apply(){
	    $model = $this->getModel('division');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_DIVISION_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_DIVISION_DATA_SAVE_ERROR' );
		}
        $link = 'index.php?option=com_sms&view=division&task=editdivision&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('division');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_DIVISION_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_DIVISION_DATA_SAVE_ERROR' );
		}
		$link = 'index.php?option=com_sms&view=division';
		$this->setRedirect($link, $msg);
	}
	 
	 
	/**
	** Get published
	**/
	function publish(){
	    $model = $this->getModel('division');
		if(!$model->toggle('division','id','published','1')) {
			$msg = JText::_( 'LABEL_DIVISION_DATA_PUBLISHED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_DIVISION_DATA_PUBLISHED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=division', $msg );
	}
	
	
	/**
	** Get Unpublished
	**/
	function unpublish(){
	    $model = $this->getModel('division');
		if(!$model->toggle('division','id','published','0')) {
			$msg = JText::_( 'LABEL_DIVISION_DATA_UNPUBLISHED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_DIVISION_DATA_UNPUBLISHED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=division', $msg );
	}
	
	
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('division');
		if(!$model->delete()) {
			$msg = JText::_( 'LABEL_DIVISION_DATA_DELETED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_DIVISION_DATA_DELETED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=division', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel()
	{
		$msg = JText::_( 'DEFAULT_CANCELL' );
		$this->setRedirect( 'index.php?option=com_sms&view=division', $msg );
	}

	
}
