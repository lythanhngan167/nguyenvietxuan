<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerExpenseCategory extends SmsController
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
	    $model = $this->getModel('expensecategory');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'Successfully saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
        $link = 'index.php?option=com_sms&view=expensecategory&task=editexcat&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('expensecategory');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'Successfully saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
		$link = 'index.php?option=com_sms&view=expensecategory';
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('expensecategory');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Data Deleted' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=expensecategory', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_sms&view=expensecategory', $msg );
	}

	
}
