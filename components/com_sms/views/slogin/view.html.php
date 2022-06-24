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
class SmsViewSLogin extends JViewLegacy
{

	public function display($tpl = null)
	{
		$app              = JFactory::getApplication();
		$user	          = JFactory::getUser();
        $uid              = $user->get( 'id' );
        if(!empty($uid)){
	        $group_title      = SmsHelper::checkGroup($uid);
	        if($group_title=="Students"){
	        	//$mge = JText::_('COM_SMS_MESSAGE_STUDENT_AREA_ONLY');
				//$app->enqueueMessage($mge, 'warning');
	        	$redirect_link = JRoute::_( 'index.php?option=com_sms&view=students' );
		        $app->redirect($redirect_link);
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
	        }
        }
	    $this->setLayout('default');
		parent::display($tpl);
	}

}
