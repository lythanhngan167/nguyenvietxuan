<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelIncomemonth extends JModelList
{
	
	
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }
	
	/**
	** Income List
	**/
	protected function getListQuery(){
	    $year =date("Y");
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
            ->select('MONTH(create_date) as Month, YEAR(create_date) as year, create_date, payment_method, pay_for_id, id, sum(paid_ammount) as Total')
            ->from($db->quoteName('#__sms_payments'))
            ->where($db->quoteName('status') . ' = '. $db->quote('1'))
			->group('MONTH(create_date)')
		->order('MONTH(create_date) ASC');
		
		// Filter by Year.
		$yearId = $this->getState('list.year_id');
		if (is_numeric($yearId)){
			$query->where('YEAR(create_date) = ' . $db->quote($yearId));
		}else{
		    $query->where('YEAR(create_date) = '. $db->quote($year));
		}
		return $query;
	}
	
	/**
	** Income populate state
	**/
	protected function populateState($ordering = null, $direction = null){
	    // Initialise variables.
	    $app = JFactory::getApplication('administrator');
	    $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
	    $this->setState('filter.search', $search);
	    //Takes care of states: list. limit / start / ordering / direction
	    parent::populateState('id', 'asc');
    }

}
