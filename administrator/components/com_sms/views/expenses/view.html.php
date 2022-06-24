<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

// No direct access
defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewExpenses extends JViewLegacy
{
	
	/**
	** Display the view
	**/
	public function display($tpl = null){
	
		$task = JRequest::getWord('task');
	    switch ($task) {
	
			case'back':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=accounting';
				$app->redirect($link, '');
			break;
			
			case'backmonthly':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=income&task=monthly';
				$app->redirect($link, '');
			break;
			
			case'expensemonth':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=expensesmonth';
				$app->redirect($link, '');
			break;
			
			case'expenses':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=expenses';
				$app->redirect($link, '');
			break;
			
			case'yearly':
			    $model = $this->getModel();
				$monthly		=$model->yearlyList();
				$this->assignRef('items',		$monthly);
				$this->assignRef('year',		$year);
					
				SmsHelper::addSubmenu('accounting');
				JToolbarHelper::title(JText::_('LABEL_EXPENSE_YEAR_LIST'), 'pie');
				JToolbarHelper::custom('expenses', 'new.png', 'new.png',JText::_('BTN_EXPENSE_LIST'), false);
				JToolbarHelper::custom('expensemonth', 'new.png', 'new.png',JText::_('BTN_EXPENSE_MONTH'), false);
				JToolbarHelper::custom('yearly', 'new.png', 'new.png',JText::_('BTN_EXPENSE_YEAR'), false);
				JToolbarHelper::custom('back', 'list.png', 'new.png',JText::_('BTN_BACK_ACCOUNT'), false);
				JToolbarHelper::custom('exportyear', 'list.png', 'new.png',JText::_('DEFAULT_EXPORT'), false);
				$this->setLayout('yearly');
			break;
			
			case'monthly':
			    $model = $this->getModel();
				SmsHelper::addSubmenu('accounting');
				JToolbarHelper::title(JText::_('COM_SMS_LABEL_EXPENSE_MONTH_LIST'), 'pie');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('monthly_year_select');
			break;
			
			case'newexpense':
			case'editexpense':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$expense_cat = $model->getExpenseCat($id);
					$this->assignRef('expense_cat',	$expense_cat);
					$title ="EDIT";
				}else{$title ="NEW";}
				  
				JToolbarHelper::title(JText::_('LABEL_EXPENSE_'.$title.''), 'pie');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;
			
			case'exportexpense':
				$model = $this->getModel(); 
				$this->items = $this->get('Items');
			    $this->setLayout('default_xsl');
			break;
				
			case'exportyear':
				$model = $this->getModel(); 
				$monthly = $model->yearlyList();
				$this->items = $monthly;
			    $this->setLayout('yearly_xsl');
			break;
				
			default:
				//$model = $this->getModel();
		        $this->items = $this->get('Items');
				$this->pagination	= $this->get('Pagination');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
			       
				SmsHelper::addSubmenu('accounting');
				JToolbarHelper::title(JText::_('LABEL_EXPENSE_LIST'), 'pie');
				JToolbarHelper::custom('newexpense', 'new.png', 'new.png',JText::_('BTN_EXPENSE_NEW'), false);
				JToolbarHelper::editList('editexpense');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::custom('expensemonth', 'new.png', 'new.png',JText::_('BTN_EXPENSE_MONTH'), false);
				JToolbarHelper::custom('yearly', 'new.png', 'new.png',JText::_('BTN_EXPENSE_YEAR'), false);
				JToolbarHelper::custom('back', 'list.png', 'new.png',JText::_('BTN_BACK_ACCOUNT'), false);
				JToolbarHelper::custom('exportexpense', 'list.png', 'list.png',JText::_('DEFAULT_EXPORT'), false);
				$this->setLayout('default');
			break;
		}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
