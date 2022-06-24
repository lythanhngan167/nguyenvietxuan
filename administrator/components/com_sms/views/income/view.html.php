<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewIncome extends JViewLegacy
{
	
	/**
	** Display the view
	**/
	public function display($tpl = null){
	    $model = $this->getModel();
		$task = JRequest::getWord('task');
	    switch ($task) {
	
			case'back':
			    $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=accounting';
				$app->redirect($link, '');
			break;
			
			case'monthlydetails':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=incomemonth';
				$app->redirect($link, '');
			break;
			
			case'backincome':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=income';
				$app->redirect($link, '');
			break;
				
			case'yearly':
				$monthly = $model->yearlyList();
				$this->assignRef('items', $monthly);
				$this->assignRef('year', $year);
					
				SmsHelper::addSubmenu('accounting');
				JToolbarHelper::title(JText::_('LABEL_INCOME_YEAR_LIST'), 'pie');
				JToolbarHelper::custom('backincome', 'new.png', 'new.png',JText::_('BTN_INCOME_LIST') , false);
				JToolbarHelper::custom('monthlydetails', 'new.png', 'new.png',JText::_('BTN_INCOME_MONTHLY'), false);
				JToolbarHelper::custom('yearly', 'new.png', 'new.png',JText::_('BTN_INCOME_YEARLY'), false);
				JToolbarHelper::custom('back', 'list.png', 'list.png',JText::_('BTN_BACK_ACCOUNT'), false);
				JToolbarHelper::custom('exportyearly', 'list.png', 'list.png',JText::_('DEFAULT_EXPORT'), false);
				$this->setLayout('yearly');
			break;
			
			case'exportpayment':
				$this->items = $this->get('Items');
			    $this->setLayout('default_xsl');
			break;
				
			case'exportyearly':
				$monthly = $model->yearlyList();
				$this->items = $monthly;
			    $this->setLayout('yearly_xsl');
			break;
			 
			default:
		        $this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
			       
				SmsHelper::addSubmenu('accounting');
				JToolbarHelper::title(JText::_('LABEL_INCOME_LIST'), 'pie');
				JToolbarHelper::custom('monthlydetails', 'new.png', 'new.png',JText::_('BTN_INCOME_MONTHLY'), false);
				JToolbarHelper::custom('yearly', 'new.png', 'new.png',JText::_('BTN_INCOME_YEARLY') , false);
				JToolbarHelper::custom('back', 'list.png', 'list.png',JText::_('BTN_BACK_ACCOUNT'), false);
				JToolbarHelper::custom('exportpayment', 'list.png', 'list.png',JText::_('DEFAULT_EXPORT'), false);
				$this->setLayout('default');
			break;
		}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
