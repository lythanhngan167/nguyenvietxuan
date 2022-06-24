<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();
if(!defined('DS')) define('DS', '/');

class SmsViewLanguages extends JViewLegacy
{
	
	public function display($tpl = null){
	
		$task = JRequest::getWord('task');
		switch ($task) {
			
			case'newlanguage':
				JToolbarHelper::title(JText::_('LANG_NEW'), 'grid');
				JToolbarHelper::apply('store');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('create');
			break;
			
			case'editlanguage':
			    $app =JFactory::getApplication();
				$code = JRequest::getString('code');
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =$array[0];
				if(!empty($id)){
					$code = $id;
				}
					
				if(empty($code)){
					$app->enqueueMessage(JFactory::getApplication()->enqueueMessage(JText::_('LNG_CODE_NOT_SPECIFIED'), 'error'));
					return;
				}
			
				$file = new stdClass();
				$file->name = $code;
				
				$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'.com_sms.ini';
				$customPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'-custom.com_sms.ini';
				
				$file->path = $path;
				$file->customPath = $customPath;

				jimport('joomla.filesystem.file');
				$showLatest = true;
				$loadLatest = false;

				if(JFile::exists($path)){
					$file->content = JFile::read($path);
					if(empty($file->content)){
						$app->enqueueMessage('File not found : '.$path);
					}
				}else{
					$loadLatest = true;
					$file->content = JFile::read(JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.com_sms.ini');
				}

				if(JFile::exists($customPath)) {
					$file->custom_content = JFile::read($customPath);
					if(empty($file->custom_content)) {
						$app->enqueueMessage('File not found : '.$customPath);
					}
				}else{
					$file->custom_content = " ";
				}

				$this->assignRef('file',$file);
			    JToolbarHelper::title(JText::_('LABEL_LANG'), 'options');
				JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('default_language');
			break;

			default:
				$model = $this->getModel();
		        $this->languages = $this->get('Languages');
			    SmsHelper::addSubmenu('languages');
				JToolbarHelper::title(JText::_('LANG_LIST'), 'options');
				JToolbarHelper::custom('newlanguage', 'new.png', 'new.png',JText::_('LANG_NEW'), false);
				JToolbarHelper::editList('editlanguage');
				JToolbarHelper::deleteList('delete');
				$this->setLayout('default');
			break;
		}
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	
	
}
