<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewPaytype extends JViewLegacy
{
	
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	switch ($task) {
	
	case'back':
	    $app = JFactory::getApplication();
			$link = 'index.php?option=com_sms&view=payments';
			$app->redirect($link, '');
	
	break;
	
	case'newtype':
	case'edittype':
	    $model = $this->getModel();
			JRequest::setVar('hidemainmenu', 1);
		  $array = JRequest::getVar('cid',  0, '', 'array');
      $id =(int)$array[0];
			if(!empty($id)){
			$paytype		=$model->getPaytype($id);
			$this->assignRef('paytype',		$paytype);
			$title ="EDIT";
			}else{$title ="NEW";}
		  JToolbarHelper::title(JText::_('LABEL_PAYMENT_TYPE_'.$title.''), 'credit');
			JToolbarHelper::apply('apply');
			JToolbarHelper::save('save');
		  JToolbarHelper::cancel('cancel');
			$this->setLayout('form');
		break;
		default:
		       $model = $this->getModel();
           $this->items		= $this->get('Items');
		       $this->pagination	= $this->get('Pagination');
				   SmsHelper::addSubmenu('payments');
					 JToolbarHelper::title(JText::_('LABEL_PAYMENT_TYPE_LIST'), 'credit');
					 JToolbarHelper::custom('newtype', 'new.png', 'new.png',JText::_('BTN_PAYMENT_TYPE_NEW'), false);
					 JToolbarHelper::editList('edittype');
					 JToolbarHelper::deleteList('delete');
					 JToolbarHelper::custom('back', 'new.png', 'new.png',JText::_('BTN_BACK_PAYMENT_PAGE'), false);
				   $this->setLayout('default');
	    break;
	}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
