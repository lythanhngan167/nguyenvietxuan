<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewGrade extends JViewLegacy
{
	
	public function display($tpl = null){
	
		$task = JRequest::getWord('task');
		switch ($task) {
		
		    case'back':
		        $app  = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=exams';
				$app->redirect($link, '');
		    break;

		    case'gradecategory':
			    $app  = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=gradecategory';
				$app->redirect($link, '');
			break;
		
			case'newgrade':
			case'editgrade':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
			    $array = JRequest::getVar('cid',  0, '', 'array');
	            $id =(int)$array[0];
				if(!empty($id)){
					$grade = $model->getGrade($id);
					$title = "EDIT";
					$cid   = $grade->category;
					$this->assignRef('grade', $grade);
				}else{
					$title ="NEW"; 
					$cid   ='';
				}
			  
			    JToolbarHelper::title(JText::_(''.$title.' Grade'), 'bookmark');
				JToolbarHelper::title(JText::_('LABEL_EXAM_GRADE_'.$title.''), 'bookmark');
				
				$gradecategory = $model->getGcategoryList($cid);
				$this->assignRef('gradecategory', $gradecategory);
		    
				JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
			    JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;

			default:
				$model               = $this->getModel();
	            $this->items		 = $this->get('Items');
			    $this->pagination	 = $this->get('Pagination');
			    $this->state         = $this->get('State');
				$this->filterForm    = $this->get('FilterForm');
			    $this->activeFilters = $this->get('ActiveFilters');
			    SmsHelper::addSubmenu('grade');
				JToolbarHelper::title(JText::_('LABEL_EXAM_GRADE_LIST'), 'bookmark');
				JToolbarHelper::custom('newgrade', 'new.png', 'new.png','New Grade ', false);
				JToolbarHelper::editList('editgrade');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::custom('gradecategory', 'new.png', 'new.png',JText::_('BTN_GRADE_CATEGORY'), false);
				JToolbarHelper::custom('back', 'list.png', 'new.png',JText::_('BTN_BACK_EXAM'), false);
			    $this->setLayout('default');
		    break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
