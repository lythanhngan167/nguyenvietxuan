<?php 
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

// No direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$lang = JFactory::getLanguage();
$dir = $lang->get('rtl');
if($dir == 0) {
    //do soemthing for ltl
	$document->addStyleSheet('components/com_sms/asset/css/bootstrap3.css');
	$document->addStyleSheet('components/com_sms/asset/css/sms_style.css');
}else{
    //do something rtl
	$document->addStyleSheet(JURI::base().'components/com_sms/asset/css/sms_style-rtl.css');
	$document->addStyleSheet('components/com_sms/asset/css/bootstrap3-rtl.css');
}
$document->addStyleSheet(JURI::base().'components/com_sms/asset/font-awesome/css/font-awesome.min.css');

// Get the current language code.
$code = $lang->getTag();
JFactory::getLanguage()->load('com_sms', JPATH_COMPONENT_ADMINISTRATOR, $code, true);
JLoader::register('SmsHelper', __DIR__ . '/helpers/sms.php');

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
	
?>
		
		
		