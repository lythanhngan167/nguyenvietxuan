<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewMessage extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$model = $this->getModel();
	    $app   = JFactory::getApplication();
		$task  = JRequest::getWord('task');
		$user  = JFactory::getUser();
        $uid   = $user->get('id');
		if(!empty($uid)){
			$group_title =  SmsHelper::checkGroup($uid);
			if($group_title=="Parents" || $group_title=="Students" || $group_title=="Teachers"){
		
	            switch ($task) 
	            {
	                case'newmessage':
			            SmsHelper::addSubmenu('message');
		                $this->setLayout('form');
		            break;
	
	                case'messagedetails':
			            $array = JRequest::getVar('mid',  0, '', 'array');
                        $id =(int)$array[0];
			
			            //Update status
			            $update_status =$model->updateStatus($id);
			            if($update_status){}
			            $message		=$model->getMessage($id);
			            $this->assignRef('message',		$message);
			            SmsHelper::addSubmenu('message');
		                $this->setLayout('details');
		            break;
	
		            default:
		                $this->items		= $this->get('Items');
		                $this->pagination	= $this->get('Pagination');
				        $this->setLayout('default');	
				        SmsHelper::addSubmenu('message');				 
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
