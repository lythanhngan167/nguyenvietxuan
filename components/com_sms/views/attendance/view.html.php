<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();
class SmsViewAttendance extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model       = $this->getModel();
	    $user		 = JFactory::getUser();
		$uid         = $user->id;
		if(empty($uid)){ 
		    $mge = JText::_('COM_SMS_MESSAGE_TEACHER_LOGIN_REQUIRED');
			$app->enqueueMessage($mge, 'warning');
            $redirect_link = JRoute::_( 'index.php?option=com_sms&view=tlogin' );
            $app->redirect($redirect_link);		 
	    }
		$group_title = SmsHelper::checkGroup($uid);
		$task        = JRequest::getWord('task');
	    switch ($task) 
	    {
	
			case'newattend':
			case'editattend':

			    if($group_title=="Teachers"){
				    $array = JRequest::getVar('cid',  0, '', 'array');
			        $id =(int)$array[0];
			
					if(!empty($id)){
						$attendance = $model->getAttendance($id);
						$this->assignRef('attendance', $attendance);
						$class = $attendance->class;
						$section = $attendance->section;
					}else{
						$class ='';
						$section ='';
					}
						
					$class_list = $model->getclassList($class);
					$this->assignRef('class', $class_list);
						
					$section_list = $model->getsectionList($section);
					$this->assignRef('section', $section_list);
						
					SmsHelper::addSubmenu('attendance');
					$this->setLayout('form');
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
			break;
	
			default:
				SmsHelper::addSubmenu('attendance');
				$model = $this->getModel();
		        $this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');
			    $this->setLayout('default');
			break;
	    }
		
		$this->smshelper = new SmsHelper;
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
}
