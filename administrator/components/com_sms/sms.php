<?php 
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_sms'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('SmsHelper', __DIR__ . '/helpers/sms.php');

// Get the current language code.
$lang = JFactory::getLanguage();
$code = $lang->getTag();
$lang->load('com_sms', JPATH_COMPONENT_ADMINISTRATOR, $code, true);
$lang->load('com_installer', dirname(JPATH_ADMINISTRATOR.'/language'), $code, true);
	
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_sms/css/sms.css');
$dir = $lang->get('rtl');
if($dir != 0) {
	$document->addStyleSheet(JURI::base().'components/com_sms/css/sms-rtl.css');
}
$document->addStyleSheet(JURI::base().'components/com_sms/font-awesome/css/font-awesome.min.css');


jimport('joomla.application.component.controller');
require_once( JPATH_COMPONENT.'/controller.php' );
$task = JRequest :: getCmd('task');
$c = '';
if (strstr($task, '.')) {
    $array = explode('.', $task);
    $c = $array[0];
    $task = $array[1];
} else {
    $c = JRequest :: getCmd('controller', 'sms');
    $task = JRequest :: getCmd('task', 'display');
}

if ($c != '') {
    $path = JPATH_COMPONENT . '/controllers/' . $c . '.php';
    jimport('joomla.filesystem.file');

    if (JFile :: exists($path)) {
        require_once ($path);
    } else {
        JError :: raiseError('500', JText :: _('Unknown controller: <br>' . $c . ':' . $path));
    }
}

$controllername = 'SmsController' . $c;
$controller = new $controllername();
$controller->execute($task);
$controller->redirect();