<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerParents extends SmsController
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
	    $model = $this->getModel('parents');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_PARENT_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_PARENT_DATA_SAVE_ERROR' );
		}
        $link = 'index.php?option=com_sms&view=parents&task=editparent&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('parents');
		$id =$model->store();
		if (!empty($id)) {
			$msg = JText::_( 'LABEL_PARENT_DATA_SAVE' );
		}else {
			$msg = JText::_( 'LABEL_PARENT_DATA_SAVE_ERROR' );
		}
		$link = 'index.php?option=com_sms&view=parents';
		$this->setRedirect($link, $msg);
	 }
	 
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('parents');
		if(!$model->delete()) {
			$msg = JText::_( 'LABEL_PARENT_DATA_DELETED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_PARENT_DATA_DELETED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=parents', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'DEFAULT_CANCELL' );
		$this->setRedirect( 'index.php?option=com_sms&view=parents', $msg );
	}

	
}
