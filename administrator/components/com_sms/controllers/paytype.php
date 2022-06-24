<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerPaytype extends SmsController
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
	 * save a record (and redirect to main page)
	 * @return void
	 */
	 function apply(){
	  $model = $this->getModel('paytype');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'data Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
    $link = 'index.php?option=com_sms&view=paytype&task=edittype&cid[]='. $id;
		$this->setRedirect($link, $msg);
		
	
	 }
	 
	  function save(){
		$model = $this->getModel('paytype');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'data Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
		
		$link = 'index.php?option=com_sms&view=paytype';
		$this->setRedirect($link, $msg);
	
	 }
	 
	 
	
	
	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('paytype');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Data Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_sms&view=paytype', $msg );
	}
	
	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_sms&view=paytype', $msg );
	}

	
}
