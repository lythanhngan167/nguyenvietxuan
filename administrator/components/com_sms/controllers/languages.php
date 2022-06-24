<?php
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerLanguages extends SmsController
{
	
	function __construct() {
        parent::__construct();
    }

    /**
    ** Get Apply
    **/
    function apply() {
        $code = JRequest::getString('code');
        $model = $this->getModel('languages');
        $msg = $model->saveLanguage();
        $link = 'index.php?option=com_sms&view=languages&task=editlanguage&code='.$code;
        $this->setRedirect($link, $msg);
    }

    /**
    ** Get Save
    **/
    function save() {
        $model = $this->getModel('languages');
        $msg = $model->saveLanguage();
        $link = 'index.php?option=com_sms&view=languages';
        $this->setRedirect($link, $msg);
    }

    /**
    ** Get Store
    **/
    function store() {
        $code = JRequest::getString('code');
        $content = JRequest::getString('content');
        $model = $this->getModel('languages');

        if(empty($code) || empty($content)) {
            if (empty($content)) { $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_CONTENT_CANNOT_BE_BLANK'), 'error'); }
            if (empty($code)) { $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_CODE_NOT_SPECIFIED'), 'error'); }
            $this->setRedirect('index.php?option=com_sms&view=languages&task=newlanguage', $msg);
        } else {
            $msg = $model->createLanguage();
            $link = 'index.php?option=com_sms&view=languages';
            $this->setRedirect($link, $msg);
        }
    }

    /**
    ** Get Remove
    **/
    function remove() {
        // Check for request forgeries
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model = $this->getModel('languages');
        $app =JFactory::getApplication();
        
        // Get items to remove from the request.
        $codes = JRequest::getVar('cid', array(), '', 'array');
        
        if (!is_array($codes) || count($codes) < 1) {
            $msg = $app->enqueueMessage(JText::_('LNG_NO_LANGUAGES_SELECTED'), 'error');
            $link = 'index.php?option=com_sms&view=languages';
            $this->setRedirect($link, $msg);
        }
        else{
            foreach($codes as $code){
                $msg = $model->deleteFolder($code);
            }
            $link = 'index.php?option=com_sms&view=languages';
            $this->setRedirect($link, $msg);
        }
    }

    /**
    ** Get Cancel
    **/
    function cancel() {
        $msg = JText::_('LNG_OPERATION_CANCELLED',true);
        $this->setRedirect( 'index.php?option=com_sms&view=languages', $msg );
    }

	
}
