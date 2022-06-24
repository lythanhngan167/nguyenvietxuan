<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewExams extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
			case'examgrade':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=grade';
				$app->redirect($link, '');
			break;
	
			case'examtearm':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=examtearm';
				$app->redirect($link, '');
			break;
	
			case'newexam':
			case'editexam':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$exam		=$model->getExam($id);
					$this->assignRef('exam',		$exam);
					$title ="EDIT";
				}else{$title ="NEW";}
				JToolbarHelper::title(JText::_('LABEL_EXAM_'.$title.''), 'book');
				JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;

		    default:
		        $model = $this->getModel();
                $this->items		= $this->get('Items');
		        $this->pagination	= $this->get('Pagination');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
	            SmsHelper::addSubmenu('exams');
				JToolbarHelper::title(JText::_('LABEL_EXAM_LIST'), 'pencil-2');
				JToolbarHelper::custom('newexam', 'new.png', 'new.png',JText::_('BTN_EXAM_NEW'), false);
				JToolbarHelper::editList('editexam');
				JToolbarHelper::publishList('Publish');
				JToolbarHelper::unpublishList('Unpublish');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::custom('examgrade', 'new.png', 'new.png',JText::_('BTN_EXAM_GRADE'), false);
				$this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
