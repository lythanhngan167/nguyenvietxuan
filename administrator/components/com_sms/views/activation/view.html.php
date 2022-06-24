<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewActivation extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
	
		$task = JRequest::getWord('task');
	    switch ($task) {
	
			
			default:
                $model = $this->getModel();
		        $this->items		= $this->get('Items');
				$this->pagination	= $this->get('Pagination');
		        $update_version = $model->getUpdateVersion();
			    SmsHelper::addSubmenu('activation');
				JToolbarHelper::title(JText::_('Activation & Update'), 'options');
				JToolbarHelper::apply('apply');		
		        if(!empty($update_version)) {
		            JToolbarHelper::custom('update', 'upload', 'upload', 'Update', true, false);
		        }       
		        JToolbarHelper::custom('find', 'refresh', 'refresh', 'COM_INSTALLER_TOOLBAR_FIND_UPDATES', false, false);
				$this->setLayout('default');
			break;
		}
		
        
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
