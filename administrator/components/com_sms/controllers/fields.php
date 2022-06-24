<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerFields extends SmsController
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
	    $model = $this->getModel('fields');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'data Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
        $link = 'index.php?option=com_sms&view=fields&task=editfield&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('fields');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'data Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
		$link = 'index.php?option=com_sms&view=fields';
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Publish
	**/
	function publish(){
	    $model = $this->getModel('fields');
		if(!$model->toggle('fields','id','published','1')) {
			$msg = JText::_( 'The data not published !' );
		} else {
			$msg = JText::_( 'Data published' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=fields', $msg );
	}
	
	
	/**
	** Get Unpublish
	**/
	function unpublish(){
	    $model = $this->getModel('fields');
		if(!$model->toggle('fields','id','published','0')) {
			$msg = JText::_( 'The data not unpublished !' );
		} else {
			$msg = JText::_( 'Data unpublished' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=fields', $msg );
	}
	
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('fields');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Data Deleted' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=fields', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_sms&view=fields', $msg );
	}

	
}
