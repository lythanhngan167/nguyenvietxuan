<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelIncome extends JModelList
{
	
	/**
	** Constructor
	**/
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
	** Paid For
	**/
	function getPaidFor($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_pay_type'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Paid By
	**/
	function getPaidBy($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_pay_method'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	** Income List
	**/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_payments'));
		$query->where('status = 1');
		
		// Filter by id.
		$pId = $this->getState('filter.search');
		if (is_numeric($pId)){
			$query->where('id = ' . $db->quote($pId));
		}
		
		// Filter by Month.
		$monthId = $this->getState('filter.month_id');
		if (is_numeric($monthId)){
			$query->where('MONTH(create_date) = ' . $db->quote($monthId));
		}
		
		// Filter by Year.
		$yearId = $this->getState('filter.year_id');
		if (is_numeric($yearId)){
			$query->where('YEAR(create_date) = ' . $db->quote($yearId));
		}
		
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
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

	
	/**
	** Yearly Income List
	**/
	function yearlyList(){
	    $db    = JFactory::getDbo();
	    $sql = "SELECT p.month as Month,p.year,  p.create_date, p.payment_method, p.pay_for_id, p.id, sum(p.paid_ammount) as Total ".
					       "FROM #__sms_payments as p WHERE p.status = '1'".
								 "GROUP BY p.year ".
                 "ORDER BY p.year ASC";
        $db->setQuery($sql);
        $result = $db->loadObjectList();
		return $result;
	 }
	
	
	
}
