<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();
class SmsViewEditParent extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
	    $model = $this->getModel('editparent');
	    $app   = JFactory::getApplication();
		$task  = JRequest::getWord('task');
		$user  = JFactory::getUser();
        $uid   = $user->get('id');
		if(!empty($uid)){
			$group_title =  SmsHelper::checkGroup($uid);
			if($group_title=="Parents"){
			
	            switch ($task) 
	            {
	
		            default:
						$parent_id = $model->getParentID($uid);
						$parent = $model->getParent($parent_id);
						$this->assignRef('parent',		$parent);
						SmsHelper::addSubmenu('editparent');
	                    $this->setLayout('form');
	                break;
	            }
	        }else{
				$mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
				$app->enqueueMessage($mge, 'warning');
                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
                $app->redirect($redirect_link);
			}		 
	    }else{
	        $mge = JText::_('COM_SMS_MESSAGE_PARENT_LOGIN_REQUIRED');
	        $app->enqueueMessage($mge, 'warning');
            $redirect_link = JRoute::_( 'index.php?option=com_sms&view=parents&task=parent_login' );
            $app->redirect($redirect_link);
	    }
		
		$this->smshelper = new SmsHelper;
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
