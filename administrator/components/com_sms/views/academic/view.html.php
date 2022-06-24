<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewAcademic extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$task = JRequest::getWord('task');
	    switch ($task) 
	    {
			// get promotion view
			case'managepromotion':
		        $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=promotion';
				$app->redirect($link, '');
		    break;
		    	
		    // get class view
			case'manageclass':
		        $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=class';
				$app->redirect($link, '');
		    break;
		    	
		    // get division view
			case'managedivision':
		        $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=division';
				$app->redirect($link, '');
		    break;
		    	
		    // get section view
			case'managesection':
		        $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=sections';
				$app->redirect($link, '');
		    break;
		    	
		    // get subject view
			case'managesubjects':
		        $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=subjects';
				$app->redirect($link, '');
		    break;
				
			// get academic year view
			case'manageacademicyear':
		        $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=manageacademicyear';
				$app->redirect($link, '');
		    break;
				
			default:
			    $model = $this->getModel();
	            SmsHelper::addSubmenu('academic');
				JToolbarHelper::title(JText::_('Academic Management'), 'screen');
				JToolbarHelper::custom('manageacademicyear', 'tag.png', 'new.png',JText::_('BTN_MANAGE_ACADEMIC_YEAR'), false);
				JToolbarHelper::custom('managesubjects', 'paragraph-center.png', 'paragraph-center.png',JText::_('MENU_SUBJECTS'), false);
				JToolbarHelper::custom('managesection', 'list.png', 'list.png',JText::_('MENU_SECTIONS'), false);
				JToolbarHelper::custom('managedivision', 'grid.png', 'grid.png',JText::_('MENU_DIVISION'), false);
				JToolbarHelper::custom('manageclass', 'tree.png', 'tree.png',JText::_('MENU_CLASS'), false);
				JToolbarHelper::custom('managepromotion', 'tag.png', 'tag.png',JText::_('MENU_PROMOTION'), false);
				JToolBarHelper::preferences('com_sms');
			    $this->setLayout('default');
		    break;
		}
	

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
