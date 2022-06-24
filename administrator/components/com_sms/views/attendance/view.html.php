<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewAttendance extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
			case'newattend':
			case'editattend':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$attendance		=$model->getAttendance($id);
					$this->assignRef('attendance',		$attendance);
					$title ="EDIT";
					$student_class_id =$attendance->class;
					$student_section_id =$attendance->section;
				}else{
					$title ="NEW";
					$student_class_id ="";
					$student_section_id ="";
				}
				$class_list = SmsHelper::getclassList($student_class_id);
				$this->assignRef('class', $class_list);
				$section_list = SmsHelper::getsectionList($student_section_id);
				$this->assignRef('section', $section_list);
				JToolbarHelper::title(JText::_('LABEL_ATTENDANCE_'.$title.''), 'pencil');
			    JToolbarHelper::custom('saveattend', 'new.png', 'list.png','Take/View Attendance ', false);
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;
		    default:
		        $model = $this->getModel();
                $this->items		= $this->get('Items');
		        $this->pagination	= $this->get('Pagination');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
				SmsHelper::addSubmenu('attendance');
				JToolbarHelper::title(JText::_('LABEL_ATTENDANCE_LIST'), 'pencil');
				JToolbarHelper::custom('newattend', 'new.png', 'new.png',JText::_('BTN_ATTENDANCE_NEW'), false);
				JToolbarHelper::editList('editattend');
				$this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
