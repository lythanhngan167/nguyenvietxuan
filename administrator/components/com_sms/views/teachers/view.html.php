<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewTeachers extends JViewLegacy
{
	public function display($tpl = null)
	{
	    $task = JRequest::getWord('task');
	    switch ($task) {
			case'newteacher':
			case'editteacher':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
					if(!empty($id)){
					$teacher		=$model->getTeacher($id);
					$this->assignRef('teacher',		$teacher);
					$title ="EDIT";
					
					$teacher_class_id =$teacher->class;
					$teacher_section_id =$teacher->section;
					$teacher_subject_id =$teacher->subject;
				}else{
					$title ="NEW";
					$teacher_class_id ="";
					$teacher_section_id ="";
					$teacher_subject_id ="";
				}
				JToolbarHelper::title(JText::_('LABEL_TEACHER_'.$title.''), 'user');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				if(!empty($id)){
				JToolbarHelper::custom('details', 'new.png', 'new.png','View BioData ', false);
				}
				$this->setLayout('form');
			break;
		
			case'getback':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=teachers';
				$app->redirect($link, '');
			break;
		
			case'details':
		        $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
			    $array = JRequest::getVar('cid',  0, '', 'array');
	            $id =(int)$array[0];
				if(!empty($id)){
					$teacher		=$model->getTeacher($id);
					$this->assignRef('teacher',		$teacher);
					$teacher_class_id =$teacher->class;
					$teacher_section_id =$teacher->section;
					$teacher_subject_id =$teacher->subject;
				}
				
				JToolbarHelper::title(JText::_('LABEL_TEACHER_BIODATA'), 'user');
				JToolbarHelper::custom('getback', 'undo.png', 'new.png',JText::_('DEFAULT_BACK'), false);
				JToolbarHelper::custom('detailspdf', 'attachment.png', 'new.png',JText::_('DEFAULT_PDF'), false);
				$this->setLayout('details');
			break;
		
			case'detailspdf':
		        $model = $this->getModel();
			    $array = JRequest::getVar('cid',  0, '', 'array');
	            $id =(int)$array[0];
				if(!empty($id)){
					$teacher		= $model->getTeacher($id);
					$this->assignRef('teacher',		$teacher);
					$teacher_class_id = $teacher->class;
					$teacher_section_id = $teacher->section;
					$teacher_subject_id = $teacher->subject;
				}
				$this->setLayout('details_pdf');
			break;
		
			case'sendemail_form':
			    $input = JFactory::getApplication()->input;
			    $input->set('hidemainmenu', true);
				JToolbarHelper::title(JText::_('LABEL_TEACHER_SEND_EMAIL'), 'pencil');
				JToolbarHelper::custom('teachers.sendemail', 'new.png', 'new.png',JText::_('BTN_TEACHER_SEND'), false);
				JToolbarHelper::cancel('cancel');
			    $this->setLayout('default_sendemail');
			break;
		
			case'sendsms_form':
			    $input = JFactory::getApplication()->input;
			    $input->set('hidemainmenu', true);
				JToolbarHelper::title(JText::_('Send SMS'), 'pencil');
				JToolbarHelper::custom('teachers.sendsms', 'new.png', 'new.png','Send ', false);
				JToolbarHelper::cancel('cancel');
			    $this->setLayout('default_sendsms');
			break;
		
		    default:
		        $bar = JToolBar::getInstance('toolbar');
		        $model = $this->getModel();
                $this->items		= $this->get('Items');
		        $this->pagination	= $this->get('Pagination');
				$this->state = $this->get('State');
				$this->filterForm    = $this->get('FilterForm');
			    $this->activeFilters = $this->get('ActiveFilters');
	            SmsHelper::addSubmenu('teachers');
				JToolbarHelper::title(JText::_('LABEL_TEACHER_LIST'), 'users');
				JToolbarHelper::custom('newteacher', 'new.png', 'new.png',JText::_('BTN_TEACHER_NEW'), false);
				JToolbarHelper::editList('editteacher');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::divider();
				$dhtml = '<button  onclick="if (document.adminForm.boxchecked.value==0){alert(\''.JText::_('LABEL_TEACHER_SELSCT_FROM_LIST').'\');}else{Joomla.submitbutton(\'sendemail_form\') }" class="btn btn-small">
                    <i class="icon-mail" title="'.JText::_('BTN_TEACHER_SEND_EMAIL').'"></i>'.JText::_('BTN_TEACHER_SEND_EMAIL').'</button>';
                $bar->appendButton('Custom', $dhtml, 'companies.sendemail');
		        $this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
