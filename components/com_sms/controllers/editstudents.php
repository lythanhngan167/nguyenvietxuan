<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsControllerEditstudents extends JControllerLegacy
{

	function __construct(){
		parent::__construct();
	}
	
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('editstudents');
		$id =$model->store();
		if (!empty($id)) {
			$msg = JText::_( 'Successfully Saved!' );
		}else {
			$msg = JText::_( 'Error Saving Data' );
		}
		$link = JRoute::_('index.php?option=com_sms&view=editstudents');
		$this->setRedirect($link, $msg);
	}
	
	
	
}
