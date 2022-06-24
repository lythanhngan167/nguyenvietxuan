<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewManageacademicyear extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) 
	    {
	        // get academic view
			case'back':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=academic';
				$app->redirect($link, '');
			break;
	
	        // get year from
			case'newyear':
			case'edityear':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$academic_year = $model->getYear($id);
					$this->assignRef('academic_year', $academic_year);
					$title ="Edit";
				}else{$title ="New";}
				JToolbarHelper::title(JText::_(''.$title.' Academic Year'), 'tree');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;

            // get year list
		    default:
		        $model = $this->getModel();
                $this->items		= $this->get('Items');
		        $this->pagination	= $this->get('Pagination');
	            SmsHelper::addSubmenu('academic');
				JToolbarHelper::title(JText::_('Academic Year List'), 'tree');
				JToolbarHelper::custom('newyear', 'new.png', 'new.png','New Year ', false);
				JToolbarHelper::editList('edityear');
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
