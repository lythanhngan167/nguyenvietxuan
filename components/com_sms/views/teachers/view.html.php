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
class SmsViewTeachers extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
		switch ($task) 
		{
			
			case'profile':
			    $model = $this->getModel('teachers');
				$user		= JFactory::getUser();
		        $uid =$user->get( 'id' );
				$group_title = $model->checkGroup($uid);
				$app = JFactory::getApplication();
				$params = JComponentHelper::getParams('com_sms');
		        $teachers_account = $params->get('teachers_account');
				if(!empty($teachers_account)){
				
					if(!empty($uid)){ 
					    if($group_title=="Teachers"){
				            SmsHelper::addSubmenu('profile');
							$model = $this->getModel('teachers');
							$teacher_id = $model->getTeacherID($uid);
							$teacher = $model->getTeacher($teacher_id);
							$this->assignRef('teacher',	$teacher);
		                    $this->setLayout('default_profile');
						}else if($group_title=="Parents"){
						    $mge = JText::_('COM_SMS_MESSAGE_PARENT_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=parents' );
	                        $app->redirect($redirect_link);
						}else if($group_title=="Students"){
						    $mge = JText::_('COM_SMS_MESSAGE_STUDENT_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students' );
	                        $app->redirect($redirect_link);
						}else{
							$mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
							$app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
	                        $app->redirect($redirect_link);
						}
				    }else{
						$mge = JText::_('COM_SMS_MESSAGE_TEACHER_LOGIN_REQUIRED');
						$app->enqueueMessage($mge, 'warning');
	                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=tlogin' );
	                    $app->redirect($redirect_link);		 
				    }
				}else{
				    $mge = JText::_('COM_SMS_MESSAGE_TEACHER_NOT_ALLOW');
				    $app->enqueueMessage($mge, 'warning');
	                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
	                $app->redirect($redirect_link);
				}
			break;
	
		    default:
			    $model = $this->getModel('teachers');
				$user		= JFactory::getUser();
	            $uid =$user->get( 'id' );
				$group_title = $model->checkGroup($uid);
				$app = JFactory::getApplication();
				
				$params = JComponentHelper::getParams('com_sms');
	            $teachers_account = $params->get('teachers_account');
				if(!empty($teachers_account)){
				
					if(!empty($uid)){ 
					    if($group_title=="Teachers"){
				            SmsHelper::addSubmenu('teachers');
							$model = $this->getModel('teachers');
							$teacher_id = $model->getTeacherID($uid);
							$teacher = $model->getTeacher($teacher_id);
							$this->assignRef('teacher',		$teacher);
							
							//LATEST MESSAGE
							$latest_message = $model->getLatestMessage($uid);
							$this->assignRef('message',	$latest_message);
		                    $this->setLayout('default');
						}else if($group_title=="Parents"){
						    $mge = JText::_('COM_SMS_MESSAGE_PARENT_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=parents' );
	                        $app->redirect($redirect_link);
						}else if($group_title=="Students"){
						    $mge = JText::_('COM_SMS_MESSAGE_STUDENT_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students' );
	                        $app->redirect($redirect_link);
						}else{
							$mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
							$app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
	                        $app->redirect($redirect_link);
						}
				    }else{
						$mge = JText::_('COM_SMS_MESSAGE_TEACHER_LOGIN_REQUIRED');
						$app->enqueueMessage($mge, 'warning');
	                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=tlogin' );
	                    $app->redirect($redirect_link);
				    }
				}else{
				    $mge = JText::_('COM_SMS_MESSAGE_TEACHER_NOT_ALLOW');
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
