<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewPromotion extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
	
			case'getback':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=academic';
				$app->redirect($link, '');
			break;
		
			case'fixpromotion':
			    SmsHelper::addSubmenu('students');
				JToolbarHelper::title(JText::_('Fix Promotion'), 'star');
	            JToolbarHelper::custom('fixissue', 'new.png', 'new.png','Fix issue', false);
				JToolbarHelper::custom('back', 'refresh.png', 'refresh.png','Back to promotion ', false);
			    $this->setLayout('fixpromotion');
			break;
		
			case'export':
			    $exam_id = JRequest::getVar('exam');
		        $class_id = JRequest::getVar('class');
		        $division_id = JRequest::getVar('division');
		        $section_id = JRequest::getVar('section');
		        $subject_id = JRequest::getVar('subjects');
		 
			    $model = $this->getModel(); 
				$row	= $model->getMarkListExcel($exam_id, $class_id,$section_id, $subject_id,$division_id);
				$this->assignRef('items',		$row);
		        $this->setLayout('mark_xsl');
			break;

		    default:
		        SmsHelper::addSubmenu('academic');
				JToolbarHelper::title(JText::_('Promotion'), 'star');
				JToolbarHelper::custom('fixissue', 'new.png', 'new.png','Fix issue', false);
                JToolbarHelper::custom('getback', 'undo.png', 'new.png',JText::_('Back Academic'), false);
				$this->setLayout('default');
	        break;
	
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
}
