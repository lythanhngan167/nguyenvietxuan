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
class SmsViewStudents extends JViewLegacy
{

	public function display($tpl = null)
	{
	    $app              = JFactory::getApplication();
		$params           = JComponentHelper::getParams('com_sms');
	    $model            = $this->getModel('students');
		$user	          = JFactory::getUser();
        $uid              = $user->get( 'id' );
		$group_title      = SmsHelper::checkGroup($uid);
        $students_account = $params->get('students_account');
		$task             = JRequest::getWord('task');
		switch ($task) {
	
			case'profile':
				if(!empty($students_account)){
					if(!empty($uid)){ 
						if($group_title=="Students"){
					        $student_id = SmsHelper::selectSingleData('id', 'sms_students', 'user_id', $uid);
							$student = $model->getStudent($student_id);
						    $this->assignRef('students',		$student);
							SmsHelper::addSubmenu('profile');
			                $this->setLayout('default_profile');
						}else if($group_title=="Parents"){
							$mge = JText::_('COM_SMS_MESSAGE_PARENT_AREA_ONLY');
							$app->enqueueMessage($mge, 'warning');
		                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=parents' );
		                    $app->redirect($redirect_link);
						}else if($group_title=="Teachers"){
							$mge = JText::_('COM_SMS_MESSAGE_TEACHER_AREA_ONLY');
							$app->enqueueMessage($mge, 'warning');
		                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=teachers' );
		                    $app->redirect($redirect_link);
						}else{
							$mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
							$app->enqueueMessage($mge, 'warning');
		                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
		                    $app->redirect($redirect_link);
						}
							 
					}else{
						$mge = JText::_('COM_SMS_MESSAGE_STUDENT_LOGIN_REQUIRED');
						$app->enqueueMessage($mge, 'warning');
		                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=slogin' );
		                $app->redirect($redirect_link);		 
					}
				}else{
					$mge = JText::_('COM_SMS_MESSAGE_STUDENT_NOT_ALLOW');
					$app->enqueueMessage($mge, 'warning');
		            $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
		            $app->redirect($redirect_link);
				}
			break;
	
		    default:
		        
				if(!empty($students_account)){
					if(!empty($uid)){ 
					    if($group_title=="Students"){
				     
							$student_id = SmsHelper::selectSingleData('id', 'sms_students', 'user_id', $uid);
							$student = $model->getStudent($student_id);
							$this->assignRef('student',		$student);
							
							//LATEST MESSAGE
							$latest_message = $model->getLatestMessage($uid);
							$this->assignRef('message',		$latest_message);
							
							SmsHelper::addSubmenu('students');
		                    $this->setLayout('default');
							
						}else if($group_title=="Parents"){
						    $mge = JText::_('COM_SMS_MESSAGE_PARENT_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=parents' );
	                        $app->redirect($redirect_link);
						}else if($group_title=="Teachers"){
						    $mge = JText::_('COM_SMS_MESSAGE_TEACHER_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=teachers' );
	                        $app->redirect($redirect_link);
						}else{
						    $mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
	                        $app->redirect($redirect_link);
						}
						 
				    }else{
						$mge = JText::_('COM_SMS_MESSAGE_STUDENT_LOGIN_REQUIRED');
						$app->enqueueMessage($mge, 'warning');
	                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=slogin' );
	                    $app->redirect($redirect_link);
				    }
				}else{
				    $mge = JText::_('COM_SMS_MESSAGE_STUDENT_NOT_ALLOW');
				    $app->enqueueMessage($mge, 'warning');
	                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
	                $app->redirect($redirect_link);
				}
			break;
		}
		
		$this->smshelper = new SmsHelper;
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
