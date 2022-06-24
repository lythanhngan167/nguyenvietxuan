<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewClass extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
	
			//Get Subject Management
			case'getsubject':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=subjects';
				$app->redirect($link, '');
			break;
	
			//Get Section Management
			case'getsection':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=sections';
				$app->redirect($link, '');
			break;
	
			//Get Division Management
			case'getdivisionn':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=division';
				$app->redirect($link, '');
			break;
	
			//Get Grade System Management
			case'getgrade':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=grade';
				$app->redirect($link, '');
			break;
	
			case'newclass':
			case'editclass':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$class		=$model->getClass($id);
					$this->assignRef('class',		$class);
					$title ="EDIT";
					
					$cid = $class->grade_system;
					$division_list = $model->getdivisionList($id);
					$this->assignRef('division', $division_list);  
					
					$section_list = $model->getsectionList($id);
					$this->assignRef('section', $section_list); 
					
					$subject_list = $model->getsubjectList($id);
					$this->assignRef('subject', $subject_list);  
					
				}else{$title ="NEW";$cid='';}
					
				$gradecategory		=$model->getGcategoryList($cid);
				$this->assignRef('gradecategory',		$gradecategory);
				JToolbarHelper::title(JText::_('LABEL_CLASS_'.$title.''), 'folder');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;

			case'back':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=academic';
				$app->redirect($link, '');
			break;

		    default:
			    $model = $this->getModel();
	            $this->items		= $this->get('Items');
			    $this->pagination	= $this->get('Pagination');
				SmsHelper::addSubmenu('academic');
				JToolbarHelper::title(JText::_('LABEL_CLASS_LIST'), 'folder');
				JToolbarHelper::custom('newclass', 'new.png', 'new.png',JText::_('BTN_CLASS_NEW'), false);
				JToolbarHelper::editList('editclass');
				JToolbarHelper::publishList('Publish');
				JToolbarHelper::unpublishList('Unpublish');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::custom('getsubject', 'options.png', 'new.png',JText::_('BTN_SUBJECT_MANAGE'), false);
				JToolbarHelper::custom('getsection', 'options.png', 'new.png',JText::_('BTN_SECTION_MANAGE'), false);
				JToolbarHelper::custom('getdivisionn', 'options.png', 'new.png',JText::_('BTN_DIVISION_MANAGE'), false);
				JToolbarHelper::custom('getgrade', 'options.png', 'options.png',JText::_('BTN_GRADE_SYSTEM'), false);
	            JToolbarHelper::custom('back', 'undo.png', 'undo.png','Back Academic ', false);
				$this->setLayout('default');
		    break;
		}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
