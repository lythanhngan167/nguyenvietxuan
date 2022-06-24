<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();
class SmsViewMarks extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	    $model  = $this->getModel();
		$app    = JFactory::getApplication();
		$user	= JFactory::getUser();
        $uid    = $user->get('id');
		if(!empty($uid)){
			$group_title =  SmsHelper::checkGroup($uid);
			if( $group_title=="Teachers"){
			    $exam_list = $model->getexamList();
			    $this->assignRef('exam', $exam_list);
				SmsHelper::addSubmenu('marks');
				$this->setLayout('default');
	        }else{
				$mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
				$app->enqueueMessage($mge, 'warning');
                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
                $app->redirect($redirect_link);
			}
		       
					 
	    }else{
	        $mge = JText::_('COM_SMS_MESSAGE_TEACHER_LOGIN_REQUIRED');
	        $app->enqueueMessage($mge, 'warning');
            $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
            $app->redirect($redirect_link);
	    }
		
		$this->smshelper = new SmsHelper;
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
