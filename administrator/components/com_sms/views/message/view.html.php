<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewMessage extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	    $task = JRequest::getWord('task');
	    switch ($task) {
	        case'messagedetails':
	            $model = $this->getModel();
		        $array = JRequest::getVar('mid',  0, '', 'array');
                $id =(int)$array[0];
				$message		=$model->getMessage($id);
				$this->assignRef('message',		$message);
				SmsHelper::addSubmenu('message');
				JToolbarHelper::title(JText::_('LABEL_MESSAGE_DETAILS'), 'mail-2');
				JToolbarHelper::custom('newmessage', 'new.png', 'new.png','New Message ', false);
				JToolbarHelper::custom('apply', 'new.png', 'new.png','Send ', false);
				JToolbarHelper::custom('save', 'new.png', 'new.png','Send & Close ', false);
				JToolbarHelper::cancel('cancel');
				$this->setLayout('details');
			break;
	
			case'newmessage':
			    $model = $this->getModel();
				$array = JRequest::getVar('aid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$attendance		=$model->getAttendance($id);
					$this->assignRef('attendance',		$attendance);
					$student_class_id =$attendance->class;
					$student_section_id =$attendance->section;
				}else{
					$student_class_id ="";
					$student_section_id ="";
				}
				SmsHelper::addSubmenu('message');
				JToolbarHelper::title(JText::_('LABEL_MESSAGE_NEW'), 'mail-2');
				JToolbarHelper::custom('sendmessage', 'new.png', 'new.png','Send Message ', false);
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;

		    default:
		        $model = $this->getModel();
                $this->items		 = $this->get('Items');
		        $this->pagination	 = $this->get('Pagination');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
	            SmsHelper::addSubmenu('message');
				JToolbarHelper::title(JText::_('LABEL_MESSAGE_LIST'), 'mail-2');
				JToolbarHelper::custom('newmessage', 'new.png', 'new.png',JText::_('BTN_NEW_MESSAGE'), false);
				JToolbarHelper::deleteList('delete');
				$this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
