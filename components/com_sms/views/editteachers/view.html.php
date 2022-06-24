<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */


defined('_JEXEC') or die;
SmsHelper::valid();
jimport('joomla.application.component.view');

class SmsViewEditTeachers extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$model = $this->getModel();
		$app   = JFactory::getApplication();
		$user  = JFactory::getUser();
	    $uid   = $user->get('id');
	    $task  = JRequest::getWord('task');
		if(!empty($uid)){
			$group_title =  SmsHelper::checkGroup($uid);
			if( $group_title=="Teachers" ){
	
		        switch ($task) 
		        {
		            default:
				        SmsHelper::addSubmenu('editteachers');
						$id      = $model->getTeacherID($uid);
				        $teacher = $model->getTeacher($id);
				        $this->assignRef('teacher',		$teacher);
		                $this->setLayout('editteacher');
			        break;
			    }
		    }else{
				$mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
				$app->enqueueMessage($mge, 'warning');
                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
                $app->redirect($redirect_link);
			}
		      		 
	    }else{
	        $mge = JText::_('COM_SMS_MESSAGE_STUDENT_LOGIN_REQUIRED');
	        $app->enqueueMessage($mge, 'warning');
            $redirect_link = JRoute::_( 'index.php?option=com_sms&view=teachers&task=tlogin' );
            $app->redirect($redirect_link);
	    }
		
		$this->smshelper = new SmsHelper;
		$this->sidebar   = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
