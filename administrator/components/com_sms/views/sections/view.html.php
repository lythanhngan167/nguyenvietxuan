<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewSections extends JViewLegacy
{
	
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
	
			case'newsection':
			case'editsection':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$section		=$model->getSection($id);
					$this->assignRef('section',		$section);
					$title ="EDIT";
				}else{$title ="NEW";}
				JToolbarHelper::title(JText::_('LABEL_SECTION_'.$title.''), 'tree');
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
				JToolbarHelper::title(JText::_('LABEL_SECTION_LIST'), 'tree');
				JToolbarHelper::custom('newsection', 'new.png', 'new.png',JText::_('BTN_SECTION_NEW'), false);
				JToolbarHelper::editList('editsection');
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
