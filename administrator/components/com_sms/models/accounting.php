<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;


class SmsModelAccounting extends JModelList
{
	
	/**
	** Constuctor
	**/
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
    }
	
	
	/**
	** Total Income 
	**/
	function getTotalIncome($year){
	    $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
	    $query
            ->select('sum(paid_ammount) as Total')
            ->from($db->quoteName('#__sms_payments'))
			->where('YEAR(create_date) = '. $db->quote($year))
            ->where($db->quoteName('status') . ' = '. $db->quote('1'));
		$db->setQuery($query);
        $result = $db->loadResult();
		return $result;
	}
	 
	/**
	** Get Income chart value
	**/
	function getTotalIncomebyMonth($month,$year){
	    $db    = JFactory::getDbo();
	    $query = $db->getQuery(true);
		$query
            ->select('sum(paid_ammount) as Total')
            ->from($db->quoteName('#__sms_payments'))
			->where('MONTH(create_date) = '. $db->quote($month))
			->where('YEAR(create_date) = '. $db->quote($year))
            ->where($db->quoteName('status') . ' = '. $db->quote('1'));
		$db->setQuery($query);
        $result = $db->loadResult();
		return $result;
	 }
	 
	/**
	** Total Expense 
	**/
	function getTotalExpense($year){
	    $db    = JFactory::getDbo();
	    $query = $db->getQuery(true);
		$query
            ->select('sum(ammount) as Total')
            ->from($db->quoteName('#__sms_expenses'))
			->where('YEAR(expense_date) = '. $db->quote($year));
		$db->setQuery($query);
        $result = $db->loadResult();
		return $result;
	}

	/**
	** Get Expense chart value
	**/
	function getTotalExpensebyMonth($month,$year){
	    $db    = JFactory::getDbo();
	    $query = $db->getQuery(true);
		$query
            ->select('sum(ammount) as Total')
            ->from($db->quoteName('#__sms_expenses'))
			->where('MONTH(expense_date) = '. $db->quote($month))
			->where('YEAR(expense_date) = '. $db->quote($year));
		$db->setQuery($query);
        $result = $db->loadResult();
		return $result;
	}
	
	
}
