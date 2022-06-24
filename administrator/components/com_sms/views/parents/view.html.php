<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewParents extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
			case'newparent':
			case'editparent':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$parents = $model->getParents($id);
					$this->assignRef('students', $parents);
					$title ="EDIT";
				}else{
					$title ="NEW";
				}
				JToolbarHelper::title(JText::_('LABEL_PARENT_'.$title.''), 'user');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;
			default:
				$model = $this->getModel();
		        $this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');
				$this->state = $this->get('State');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
			    SmsHelper::addSubmenu('parents');
				JToolbarHelper::title(JText::_('LABEL_PARENT_LIST'), 'users');
				JToolbarHelper::custom('newparent', 'new.png', 'new.png',JText::_('BTN_PARENT_NEW'), false);
				JToolbarHelper::editList('editparent');
				JToolbarHelper::deleteList('delete');
				$this->setLayout('default');
			break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
