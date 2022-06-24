<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewGradeCategory extends JViewLegacy
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
				$link = 'index.php?option=com_sms&view=grade';
				$app->redirect($link, '');
			break;
		
			case'newgradecat':
			case'editgradecat':
				JRequest::setVar('hidemainmenu', 1);
			    $array = JRequest::getVar('cid',  0, '', 'array');
	            $id =(int)$array[0];
				if(!empty($id)){
					$grade = $model->getGrade($id);
					$title ="EDIT";
					$this->assignRef('grade', $grade);
				}else{
					$title ="NEW";
				}
				
				JToolbarHelper::title(JText::_('LABEL_EXAM_GRADE_CATEGORY_'.$title.''), 'bookmark');
				JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
			    JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;

			default:
	            $this->items		= $this->get('Items');
			    $this->pagination	= $this->get('Pagination');
				$this->state = $this->get('State');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
		        SmsHelper::addSubmenu('grade');
				JToolbarHelper::title(JText::_('LABEL_EXAM_GRADE_CATEGORY_LIST'), 'bookmark');
				JToolbarHelper::custom('newgradecat', 'new.png', 'new.png',JText::_('BTN_EXAM_GRADE_CATEGORY_NEW'), false);
				JToolbarHelper::editList('editgradecat');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::custom('back', 'list.png', 'new.png',JText::_('BTN_BACK_GRADE'), false);
				$this->setLayout('default');
		    break;
	
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
