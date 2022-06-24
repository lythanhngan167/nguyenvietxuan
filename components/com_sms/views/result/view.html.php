<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();
class SmsViewResult extends JViewLegacy
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
			if($group_title=="Parents" || $group_title=="Students" || $group_title=="Teachers"){
			
	
		        SmsHelper::addSubmenu('result');
		        $model = $this->getModel();
				if($group_title=="Parents" || $group_title=="Students" ){
					$parent_id = $model->getParentID($uid);
		            $parent = $model->getParent($parent_id);
					 
					if(!empty($parent_id)){
					    $this->assignRef('parent_id', $parent_id);
					    $student_id = $parent;
					}else{
					      $student_id = $model->getStudentID($uid);
				    }
		        
					$student = $model->getStudent($student_id);
					$this->assignRef('student', $student);
			    }
					 
                $exam_list = $model->getexamList();
			    $this->assignRef('exam', $exam_list);
	            $this->setLayout('default');
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
