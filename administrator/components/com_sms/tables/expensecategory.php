<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class TableExpenseCategory extends JTable
{
	
	var $id = null;

	/**
	** Constructor
	**/
	public function __construct(&$db){
		parent::__construct('#__sms_expense_category', 'id', $db);
	}
}