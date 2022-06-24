<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewExpenseCategory extends JViewLegacy
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
			
			case'newexcat':
			case'editexcat':
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id    =(int)$array[0];
				if(!empty($id)){
					$expense_cat = $model->getExpenseCat($id);
					$this->assignRef('expense_cat',		$expense_cat);
					$title ="EDIT";
				}else{$title ="NEW";}
				  
				JToolbarHelper::title(JText::_('LABEL_EXPENSE_CATEGORY_'.$title.''), 'pie');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;

			default:
		        $this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');
			    SmsHelper::addSubmenu('accounting');
				JToolbarHelper::title(JText::_('LABEL_EXPENSE_CATEGORY_LIST'), 'pie');
				JToolbarHelper::custom('newexcat', 'new.png', 'new.png',JText::_('BTN_EXPEN_CATEGORY_NEW'), false);
				JToolbarHelper::editList('editexcat');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::custom('back', 'list.png', 'new.png',JText::_('BTN_BACK_ACCOUNT'), false);
			    $this->setLayout('default');
			break;
		}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
}
