<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerManageacademicyear extends SmsController
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
	** Get Apply button 
	**/
	function apply(){
	    $model = $this->getModel('manageacademicyear');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'data Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
        $link = 'index.php?option=com_sms&view=manageacademicyear&task=edityear&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save button
	**/
	function save(){
		$model = $this->getModel('manageacademicyear');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'data Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
		$link = 'index.php?option=com_sms&view=manageacademicyear';
		$this->setRedirect($link, $msg);
	}
	 
	 
	/**
	** Get Publish button
	**/
	function publish(){
	    $model = $this->getModel('manageacademicyear');
		 if(!$model->toggle('manageacademicyear','id','published','1')) {
			$msg = JText::_( 'The data not published !' );
		} else {
			$msg = JText::_( 'Data published' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=manageacademicyear', $msg );
	}
	
	
	/**
	** Get Unpublish button
	**/
	function unpublish(){
	    $model = $this->getModel('manageacademicyear');
		 if(!$model->toggle('manageacademicyear','id','published','0')) {
			$msg = JText::_( 'The data not unpublished !' );
		}else{
			$msg = JText::_( 'Data unpublished' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=manageacademicyear', $msg );
	}
	
	/**
	** Get Remove Button
	**/
	function remove(){
		$model = $this->getModel('manageacademicyear');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Data Deleted' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=manageacademicyear', $msg );
	}
	
	/**
	** Get Cancel Button
	**/
	function cancel(){
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_sms&view=manageacademicyear', $msg );
	}

	
}
