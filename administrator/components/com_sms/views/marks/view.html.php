<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewMarks extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$task = JRequest::getWord('task');
	    switch ($task) {
			case'back':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=marks';
				$app->redirect($link, '');
			break;
	        case'exportmark':
		        $model = $this->getModel();
                $exam_list = $model->getexamList();
			    $this->assignRef('exam', $exam_list);
				SmsHelper::addSubmenu('marks');
				JToolbarHelper::title(JText::_('LABEL_MARK_EXPORT'), 'star');
				JToolbarHelper::custom('back', 'new.png', 'new.png',JText::_('BTN_MANAGE_MARK'), false);
				JToolbarHelper::custom('export', 'user.png', 'new.png',JText::_('BTN_EXPORT_EXCEL'), false);
		        $this->setLayout('exportmark');
		    break;
		
		    case'export':
		        $exam_id = JRequest::getVar('exam');
	            $class_id = JRequest::getVar('class');
	            $section_id = JRequest::getVar('section');
	            $subject_id = JRequest::getVar('subject');
		        $model = $this->getModel(); 
			    $row = $model->getMarkListExcel($exam_id, $class_id,$section_id, $subject_id);
			    $this->assignRef('items',		$row);
	            $this->setLayout('mark_xsl');
		    break;

		    default:
		        $model = $this->getModel();
                $exam_list = $model->getexamList();
			    $this->assignRef('exam', $exam_list);
				SmsHelper::addSubmenu('marks');
				JToolbarHelper::title(JText::_('LABEL_MARK_PAGE'), 'star');
				JToolbarHelper::custom('exportmark', 'new.png', 'new.png',JText::_('BTN_MARK_EXPORT'), false);
				$this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
}
