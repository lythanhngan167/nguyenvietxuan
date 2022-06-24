<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewSms extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$task = JRequest::getWord('task');
	    switch ($task) {
			case'student':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=students';
			    $app->redirect($link, '');
	        break;
			
			case'teacher':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=teachers';
			    $app->redirect($link, '');
	        break;
			
			case'parent':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=parents';
			    $app->redirect($link, '');
	        break;
			
			case'payment':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=payments';
			    $app->redirect($link, '');
	        break;
			
			case'notice':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=notice';
			    $app->redirect($link, '');
	        break;
			
			case'message':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=message';
			    $app->redirect($link, '');
	        break;
			
			case'accounting':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=accounting';
			    $app->redirect($link, '');
	        break;
			
			default:
		        $model = $this->getModel();
                SmsHelper::addSubmenu('sms');
			    JToolbarHelper::title(JText::_('Schools Management System'), 'home');
				JToolBarHelper::preferences('com_sms');
				$this->setLayout('default');
	        break;
		}
	
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
