<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
class SmsControllerExpenses extends SmsController
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
	    $model = $this->getModel('expenses');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'Successfully saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
        $link = 'index.php?option=com_sms&view=expenses&task=editexpense&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('expenses');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'Successfully saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
		$link = 'index.php?option=com_sms&view=expenses';
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('expenses');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Data Deleted' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=expenses', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_sms&view=expenses', $msg );
	}

	
}
