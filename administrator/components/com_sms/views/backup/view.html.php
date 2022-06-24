<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewBackup extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {

			case'addactive':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$addon = $model->getAddon($id);
					$this->assignRef('addon', $addon);
					$title ="Edit addon active code";
				}else{
					$title ="Add addon active code";
				}
				JToolbarHelper::title($title, 'grid');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;
	
			default:
			    SmsHelper::addSubmenu('backup');
				JToolbarHelper::title(JText::_('Backup & Restore'), 'options');	
				$this->setLayout('default');
			break;
		}
		
        
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
