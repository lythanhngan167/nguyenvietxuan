<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewIncomemonth extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	switch ($task) {
	
	case'backacc':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=accounting';
			$app->redirect($link, '');
	
	break;
	
	case'back':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=income';
			$app->redirect($link, '');
	
	break;
	
	case'monthlydetails':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=incomemonth';
			$app->redirect($link, '');
	
	break;
	
	case'yearly':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=income&task=yearly';
			$app->redirect($link, '');
	
	break;
	
	case'exportmonthly':
		  $model = $this->getModel(); 
			$this->items		= $this->get('Items');
	    $this->setLayout('default_xsl');
		break;
	
		default:
		
		       $model = $this->getModel();
           $this->items		= $this->get('Items');
		       $this->pagination	= $this->get('Pagination');
					 $this->filterForm    = $this->get('FilterForm');
				   $this->activeFilters = $this->get('ActiveFilters');
	       
				   SmsHelper::addSubmenu('accounting');
					 JToolbarHelper::title(JText::_('LABEL_INCOME_MONTH_LIST'), 'pie');
					 JToolbarHelper::custom('back', 'new.png', 'new.png',JText::_('BTN_INCOME_LIST'), false);
					 JToolbarHelper::custom('monthlydetails', 'new.png', 'new.png',JText::_('BTN_INCOME_MONTHLY'), false);
					 JToolbarHelper::custom('yearly', 'new.png', 'new.png',JText::_('BTN_INCOME_YEARLY'), false);
					 JToolbarHelper::custom('backacc', 'list.png', 'list.png',JText::_('BTN_BACK_ACCOUNT'), false);
					 JToolbarHelper::custom('exportmonthly', 'list.png', 'list.png',JText::_('DEFAULT_EXPORT'), false);
					 
				   $this->setLayout('default');
	    break;
	
	}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
