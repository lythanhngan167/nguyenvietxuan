<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TableActivation extends JTable
{
	/**
	** Constructor
	**/
	public function __construct(&$db){
		parent::__construct('#__sms_activation', 'id', $db);
	}
}