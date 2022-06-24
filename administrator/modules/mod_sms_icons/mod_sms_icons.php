<?php
/**
 * @package	Schools Management System !
 * @author	zwebtheme.com
 * @copyright	(C) zwebtheme. All rights reserved.
 */

//no direct accees
defined ('_JEXEC') or die ('restricted access');

$mod_name = 'mod_sms_icons';

$document 	= JFactory::getDocument();
$input 		= JFactory::getApplication()->input;

$document->addStyleSheet(JURI::base(true).'/modules/'.$mod_name.'/tmpl/css/sms-style.css');
$document->addStyleSheet(JURI::base().'components/com_sms/font-awesome/css/font-awesome.min.css');

require JModuleHelper::getLayoutPath($mod_name,$params->get('layout','default'));