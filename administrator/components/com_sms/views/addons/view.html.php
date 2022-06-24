<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewAddons extends JViewLegacy
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
                $model = $this->getModel();
                $this->items		= $this->get('Items');
			    $this->pagination	= $this->get('Pagination');
			    SmsHelper::addSubmenu('addons');
				JToolbarHelper::title(JText::_('Addons'), 'options');	
				JToolbarHelper::publishList('Publish');
				JToolbarHelper::unpublishList('Unpublish');
				JToolbarHelper::deleteList('Are you sure want to delete the addon ?');
				$this->setLayout('default');
			break;
		}
		
        
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
