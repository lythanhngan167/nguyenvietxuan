<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewExpensesmonth extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	switch ($task) {
	
	case'back':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=accounting';
			$app->redirect($link, '');
	
	break;
	
	case'yearly':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=expenses&task=yearly';
			$app->redirect($link, '');
	
	break;
	
	case'expense':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=expenses';
			$app->redirect($link, '');
	
	break;
	
	case'monthly':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=expensesmonth';
			$app->redirect($link, '');
	
	break;
	
	case'exportmonth':
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
					 JToolbarHelper::title(JText::_('LABEL_EXPENSE_MONTH_LIST'), 'pie');
					 
					 JToolbarHelper::custom('expense', 'new.png', 'new.png',JText::_('BTN_EXPENSE_LIST'), false);
					 JToolbarHelper::custom('monthly', 'new.png', 'new.png',JText::_('BTN_EXPENSE_MONTH'), false);
					 JToolbarHelper::custom('yearly', 'new.png', 'new.png',JText::_('BTN_EXPENSE_YEAR'), false);
					 JToolbarHelper::custom('back', 'list.png', 'new.png',JText::_('BTN_BACK_ACCOUNT'), false);
					 JToolbarHelper::custom('exportmonth', 'list.png', 'new.png',JText::_('DEFAULT_EXPORT'), false);
				   $this->setLayout('default');
	    break;
	
	}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
