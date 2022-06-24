<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewStudents extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
	        case'result':
		        JRequest::setVar('hidemainmenu', 1);
			    $roll = JRequest::getVar('roll');
				$exam_id = JRequest::getVar('exam');
				$class = JRequest::getVar('class');

				// Get Yser
				$year_id = JRequest::getVar('year');
				if(!empty($year_id)){
					$year = SmsHelper::getAcademicYear($year_id);
				}else{
					$year = date("Y");
				}
		
			    $model = $this->getModel();
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				
				if(!empty($id)){
		            $students = $model->getStudents($id);
			        $class = $students->class;
		            $roll = $students->roll;
				}else{
		            $class = $class;
		            $roll = $roll;
				}
			    
				$this->assignRef('class', $class);
				$this->assignRef('roll', $roll);
					
				// Get exam
				if(empty($exam_id)){
		            $exam_id = $model->getExamID($year);
				}else{
		            $exam_id = $exam_id;
				}

				$this->assignRef('exam_id', $exam_id);  
				  
				JToolbarHelper::title(JText::_('LABEL_STUDENT_RESULT'), 'user');
				//SmsHelper::addSubmenu('students');
				JToolbarHelper::custom('getback', 'undo.png', 'new.png','Back ', false);
				JToolbarHelper::custom('resultpdf', 'attachment.png', 'new.png','PDF ', false);
				$this->setLayout('result');
			break;
	
			case'resultpdf':
			    $roll = JRequest::getVar('roll');
				$exam_id = JRequest::getVar('exam');
				$class = JRequest::getVar('class');

				// Get Yser
				$year_id = JRequest::getVar('year');
				if(!empty($year_id)){
					$year = SmsHelper::getAcademicYear($year_id);
				}else{
					$year = date("Y");
				}
				
			    $model = $this->getModel();
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				
				if(!empty($id)){
		            $students = $model->getStudents($id);
			        $class = $students->class;
		            $roll = $students->roll;
				}else{
		            $class = $class;
		            $roll = $roll;
				}
			    
				$this->assignRef('class', $class);
				$this->assignRef('roll', $roll);
					
				// Get exam
				if(empty($exam_id)){
		            $exam_id = $model->getExamID($year);
				}else{
		            $exam_id = $exam_id;
				}

				$this->assignRef('exam_id', $exam_id);  
					
				$this->setLayout('result_pdf');
			break;
				
			case'attendance':
			    $model = $this->getModel();
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
					
				$students		=$model->getStudents($id);
				$this->assignRef('students',		$students);
				$student_class_id =$students->class;
				$student_section_id =$students->section;
				$student_division_id =$students->division;
				JToolbarHelper::title(JText::_('LABEL_STUDENT_ATTENDANCE_DETAILS'), 'pencil');
			    JToolbarHelper::custom('getback', 'undo.png', 'new.png','Back ', false);
				JToolbarHelper::custom('attenpdf', 'attachment.png', 'new.png','PDF ', false);
				JToolbarHelper::custom('manageacademic', 'options.png', 'new.png',JText::_('BTN_MANAGE_ACADEMIC_YEAR'), false);
				JToolbarHelper::custom('getattendence', 'options.png', 'new.png',JText::_('BTN_MANAGE_ATTENDANCE'), false);
				$this->setLayout('attendance');
			break;
			
			case'attenpdf':
			    $model = $this->getModel();
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				$students		=$model->getStudents($id);
				$this->assignRef('students',		$students);
				$student_class_id =$students->class;
				$student_section_id =$students->section;
				$student_division_id =$students->division;
				$this->setLayout('attendance_pdf');
			break;
			
		    case'backattendance':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=students';
				$app->redirect($link, '');
			break;
	
            case'promotion':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=promotion';
			    $app->redirect($link, '');
	        break;
            
	        case'manageacademic':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=manageacademicyear';
			    $app->redirect($link, '');
	        break;
	
	        case'getback':
	            $app = JFactory::getApplication();
			    $link = 'index.php?option=com_sms&view=students';
			    $app->redirect($link, '');
	        break;
	
			case'newstudents':
			case'editstudents':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$students		=$model->getStudents($id);
					$this->assignRef('students',		$students);
					$title ="EDIT";
					
					$transport_id =$students->transport_id;
					$student_class_id =$students->class;
					$student_section_id =$students->section;
					$student_year_id =$students->year;
					$student_division_id =$students->division;
				}else{
					$title ="NEW";
					$student_class_id ="";
					$student_section_id ="";
					$student_division_id ="";
					$transport_id ='';
				}
				
				
				JToolbarHelper::title(JText::_('LABEL_STUDENT_'.$title.''), 'user');
				JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				if(!empty($id)){
				JToolbarHelper::custom('details', 'new.png', 'new.png',JText::_('BTN_STUDENT_BIODATA'), false);
				}
				$this->setLayout('form');
			break;
			
			case'details':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$students		=$model->getStudents($id);
					$this->assignRef('students',		$students);
					$student_class_id =$students->class;
					$student_section_id =$students->section;
					$student_year_id =$students->year;
					$student_division_id =$students->division;
				}
					
				JToolbarHelper::title(JText::_('BTN_STUDENT_BIODATA'), 'user');
				JToolbarHelper::custom('getback', 'undo.png', 'new.png',JText::_('DEFAULT_BACK'), false);
				JToolbarHelper::custom('detailspdf', 'attachment.png', 'new.png',JText::_('DEFAULT_PDF'), false);
		        JToolbarHelper::custom('idcard', 'attachment.png', 'new.png',JText::_('ID Card'), false);
				$this->setLayout('details');
			break;
		            
		    case'idcard':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$students		=$model->getStudents($id);
					$this->assignRef('students',		$students);
					$student_class_id =$students->class;
					$student_section_id =$students->section;
					$student_year_id =$students->year;
					$student_division_id =$students->division;
				}
				JToolbarHelper::title(JText::_('Student ID Card'), 'user');
				JToolbarHelper::custom('getback', 'undo.png', 'new.png',JText::_('DEFAULT_BACK'), false);
				$this->setLayout('idcard');
			break;
			
			case'detailspdf':
			    $model = $this->getModel();
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$students		=$model->getStudents($id);
					$this->assignRef('students',		$students);
					$student_class_id =$students->class;
					$student_section_id =$students->section;
					$student_year_id =$students->year;
					$student_division_id =$students->division;
				}
				$this->setLayout('details_pdf');
			break;
			
			case'modal':
		        $model = $this->getModel();
                $this->items		= $this->get('Items');
		        $this->pagination	= $this->get('Pagination');
			    $this->state = $this->get('State');
			    $this->filterForm    = $this->get('FilterForm');
			    $this->activeFilters = $this->get('ActiveFilters');
                $this->setLayout('modal');
		    break;
		            
			default:
				$model = $this->getModel();
		        $this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');
				$this->state = $this->get('State');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
                SmsHelper::addSubmenu('students');
				JToolbarHelper::title(JText::_('LABEL_STUDENT_LIST'), 'users');
				JToolbarHelper::custom('newstudents', 'new.png', 'new.png',JText::_('BTN_STUDENT_NEW'), false);
				JToolbarHelper::editList('editstudents');
				JToolbarHelper::publishList('Publish');
				JToolbarHelper::unpublishList('Unpublish');
				JToolbarHelper::deleteList('Are you sure want to delete ?');
				JToolbarHelper::custom('manageacademic', 'options.png', 'new.png',JText::_('BTN_MANAGE_ACADEMIC_YEAR'), false);
				JToolbarHelper::custom('getattendence', 'options.png', 'new.png',JText::_('BTN_MANAGE_ATTENDANCE'), false);
		        JToolbarHelper::custom('promotion', 'options.png', 'new.png',JText::_('BTN_MANAGE_PROMOTION'), false);
				$this->setLayout('default');
			break;
			
		}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
