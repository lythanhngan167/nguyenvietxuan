<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();
class SmsViewAttendancereport extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
	    $app = JFactory::getApplication();
		$task = JRequest::getWord('task');
		
		$user		= JFactory::getUser();
        $uid =$user->get('id');
		if(!empty($uid)){
			$group_title =  SmsHelper::checkGroup($uid);
			if($group_title=="Parents" || $group_title=="Students"){
			
				switch ($task) 
				{
	
		            default:
		                SmsHelper::addSubmenu('attendancereport');
		                $model = $this->getModel();
					 
					    if($group_title=="Parents" || $group_title=="Students" ){
					        $parent_id = $model->getParentID($uid);
					 
					        if(!empty($parent_id)){
					            $this->assignRef('parent_id', $parent_id);
					        }else{
					            $student_id = $model->getStudentID($uid);
					            $student = $model->getStudent($student_id);
					            $this->assignRef('student', $student);
					        }
					    }
					 
				    $this->setLayout('default');
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
            $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
            $app->redirect($redirect_link);
	    }
		
		$this->smshelper = new SmsHelper;
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
