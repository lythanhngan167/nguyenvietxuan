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
class SmsViewParents extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$app             = JFactory::getApplication();
	    $model           = $this->getModel('parents');
		$user		     = JFactory::getUser();
	    $uid             = $user->get( 'id' );
		$group_title     = SmsHelper::checkGroup($uid);
		$params          = JComponentHelper::getParams('com_sms');
	    $parents_account = $params->get('parents_account');
		$task            = JRequest::getWord('task');
		switch ($task) 
		{
			case'studentprofile':
				if(!empty($parents_account)){
					if(!empty($uid)){ 
					    if($group_title=="Parents"){

					    	$parent_id = $model->getParentID($uid);
							$parent = $model->getParent($parent_id);

							if(!empty($parent->student_id)){
								$student_ids = explode(",",$parent->student_id); 
							}else {
								$student_ids=""; 
							}

					        $array = JRequest::getVar('cid',  0, '', 'array');
					        $id = (int)$array[0];
							if(!empty($id)){

								if (in_array($id, $student_ids)){
								    $student = $model->getStudent($id);
								    $this->assignRef('students', $student);
								    SmsHelper::addSubmenu('profile');
						            $this->setLayout('default_student_profile');
								}else{
								    $mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
									$app->enqueueMessage($mge, 'warning');
			                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
			                        $app->redirect($redirect_link);
								} 
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
	                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=plogin' );
	                    $app->redirect($redirect_link);		 
				    }

				}else{
				    $mge = JText::_('COM_SMS_MESSAGE_PARENT_NOT_ALLOW');
				    $app->enqueueMessage($mge, 'warning');
	                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
	                $app->redirect($redirect_link);
				}
			break;
	
		    case'profile':
				if(!empty($parents_account)){
				
					if(!empty($uid)){ 
					    if($group_title=="Parents"){
							$this->model = $this->getModel();
							$parent_id = $model->getParentID($uid);
							$parent = $model->getParent($parent_id);
							$this->assignRef('parent',		$parent);
							SmsHelper::addSubmenu('profile');
		                    $this->setLayout('default_profile');
						}else if($group_title=="Students"){
						    $mge = JText::_('COM_SMS_MESSAGE_STUDENT_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students' );
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
						$mge = JText::_('COM_SMS_MESSAGE_PARENT_LOGIN_REQUIRED');
						$app->enqueueMessage($mge, 'warning');
	                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=plogin' );
	                    $app->redirect($redirect_link);		 
				    }
				}else{
				    $mge = JText::_('COM_SMS_MESSAGE_PARENT_NOT_ALLOW');
				    $app->enqueueMessage($mge, 'warning');
	                $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
	                $app->redirect($redirect_link);
				}
			break;
	
		    default:
				if(!empty($parents_account)){
				
					if(!empty($uid)){ 
					    if($group_title=="Parents"){
							$this->model = $this->getModel();
							$parent_id = $model->getParentID($uid);
							$parent = $model->getParent($parent_id);
							$this->assignRef('parent',		$parent);
							
							//LATEST MESSAGE
							$latest_message = $model->getLatestMessage($uid);
							$this->assignRef('message',		$latest_message);
							
							SmsHelper::addSubmenu('parents');
		                    $this->setLayout('default');
						}else if($group_title=="Students"){
						    $mge = JText::_('COM_SMS_MESSAGE_STUDENT_AREA_ONLY');
						    $app->enqueueMessage($mge, 'warning');
	                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students' );
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
						$mge = JText::_('COM_SMS_MESSAGE_PARENT_LOGIN_REQUIRED');
						$app->enqueueMessage($mge, 'warning');
	                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=plogin' );
	                    $app->redirect($redirect_link); 
				    }
				}else{
				    $mge = JText::_('COM_SMS_MESSAGE_PARENT_NOT_ALLOW');
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
