<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewAccounting extends JViewLegacy
{
	public function display($tpl = null)
	{
	    $task = JRequest::getWord('task');
	    switch ($task) {
			case'income':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=income';
				$app->redirect($link, '');
			break;
	
			case'expense':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=expenses';
				$app->redirect($link, '');
			break;
	
			case'expensecategory':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=expensecategory';
				$app->redirect($link, '');
			break;
	
		    default:
		        $model = $this->getModel();
                $this->items		= $this->get('Items');
		        $this->pagination	= $this->get('Pagination');
	            SmsHelper::addSubmenu('accounting');
				JToolbarHelper::title(JText::_('LABEL_ACCOUNTING_HEADER'), 'pie');
				JToolbarHelper::custom('income', 'list.png', 'new.png',JText::_('BTN_INCOME'), false);
				JToolbarHelper::custom('expense', 'list.png', 'new.png',JText::_('BTN_EXPENSE'), false);
				JToolbarHelper::custom('expensecategory', 'list.png', 'new.png',JText::_('BTN_EXPENSE_CATEGORY'), false);
			    $this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
		
}
