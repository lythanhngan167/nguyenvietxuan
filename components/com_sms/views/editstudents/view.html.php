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

class SmsViewEditstudents extends JViewLegacy
{

	public function display($tpl = null)
	{
		$model  = $this->getModel('editstudents');
		$app    = JFactory::getApplication();
		$task   = JRequest::getWord('task');
		$user	= JFactory::getUser();
	    $uid    = $user->get('id');
		if(!empty($uid)){
			$group_title =  SmsHelper::checkGroup($uid);
			if( $group_title=="Students" ){
	
		        switch ($task) 
		        {
		            default:
			            $student_id          = $model->getStudentID($uid);
						$student             = $model->getStudent($student_id);
						
						$student_class_id    = $student->class;
				        $student_section_id  = $student->section;
				        $student_division_id = $student->division;
					    $transport_id        = $student->transport_id;
						$student_year_id     = $student->year;
						$this->assignRef('students', $student);
						SmsHelper::addSubmenu('editstudents');
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
            $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students&task=student_login' );
            $app->redirect($redirect_link);
	    }
		
		$this->smshelper = new SmsHelper;
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
