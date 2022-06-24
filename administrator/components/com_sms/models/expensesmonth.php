<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelExpensesmonth extends JModelList
{
	
	
function __construct()
  {
        parent::__construct();
 
        $mainframe = JFactory::getApplication();
 
       
  }
	
	/**
	 ** Expense Monthly List 
	 ***/
	 protected function getListQuery()
	{
		$year =date("Y");
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
             ->select('MONTH(expense_date) as Month, YEAR(expense_date) as year, sum(ammount) as Total')
             ->from($db->quoteName('#__sms_expenses'))
						 ->group('MONTH(expense_date)')
						 ->order('MONTH(expense_date) ASC');
		
		// Filter by Year.
		$yearId = $this->getState('list.year_id');
		if (is_numeric($yearId))
		{
			$query->where('YEAR(expense_date) = ' . $db->quote($yearId));
		}else{
		 $query->where('YEAR(expense_date) = '. $db->quote($year));
		}
		
		
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null)
  {
   // Initialise variables.
   $app = JFactory::getApplication('administrator');
   $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
   $this->setState('filter.search', $search);
   //Takes care of states: list. limit / start / ordering / direction
   parent::populateState('id', 'asc');
   }

}
