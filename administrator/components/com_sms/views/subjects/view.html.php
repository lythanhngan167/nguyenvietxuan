<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewSubjects extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$task = JRequest::getWord('task');
	    switch ($task) {
			case'newsubject':
			case'editsubject':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$subject		=$model->getSubject($id);
					$this->assignRef('subject',		$subject);
					$title ="EDIT";
				}else{$title ="NEW";}
				JToolbarHelper::title(JText::_('LABEL_SUBJECT_'.$title.''), 'paragraph-center');
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
				JToolbarHelper::title(JText::_('LABEL_SUBJECT_LIST'), 'paragraph-center');
				JToolbarHelper::custom('newsubject', 'new.png', 'new.png',JText::_('BTN_SUBJECT_NEW'), false);
				JToolbarHelper::editList('editsubject');
				JToolbarHelper::publishList('Publish');
				JToolbarHelper::unpublishList('Unpublish');
				JToolbarHelper::deleteList('delete');
				JToolbarHelper::custom('back', 'undo.png', 'undo.png','Back Academic ', false);
				$this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
}
