<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewFields extends JViewLegacy
{
	
	public function display($tpl = null){
	
		$task = JRequest::getWord('task');
		switch ($task) {
			case'newfield':
			case'editfield':
		        $model = $this->getModel();
				JRequest::setVar('fields', 1);
			    $array = JRequest::getVar('cid',  0, '', 'array');
	            $id =(int)$array[0];
				if(!empty($id)){
					$field		=$model->getField($id);
					$this->assignRef('field',		$field);
					$title ="EDIT";
				}else{$title ="NEW";}
				JToolbarHelper::title(JText::_('LABEL_FIELD_'.$title.''), 'options');
				JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
			    JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
		    break;

	        default:
			    $model = $this->getModel();
	            $this->items = $this->get('Items');
			    $this->pagination = $this->get('Pagination');
				$this->state = $this->get('State');
				$this->filterForm = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
				SmsHelper::addSubmenu('fields');
				JToolbarHelper::title(JText::_('LABEL_FIELDS_LIST'), 'options');
				JToolbarHelper::custom('newfield', 'new.png', 'new.png',JText::_('BTN_FIELDS_NEW'), false);
				JToolbarHelper::editList('editfield');
				JToolbarHelper::publishList('Publish');
				JToolbarHelper::unpublishList('Unpublish');
				JToolbarHelper::deleteList('delete');
				$this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
