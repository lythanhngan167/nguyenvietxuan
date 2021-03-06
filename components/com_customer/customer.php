<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Customer', JPATH_COMPONENT);
JLoader::register('CustomerController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Customer');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
