<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class SmsModelStudents extends JModelList
{
	
	/**
	** Get Constuctor
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
	** Get Unread Message By
	**/
	function unreadMessageByid($id){

		$db = JFactory::getDBO();

		// Get total message 
		$query_m = $db->getQuery(true);
		$query_m
            ->select('*')
            ->from($db->quoteName('#__sms_message'))
            ->where($db->quoteName('status') . ' = '. $db->quote('0'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query_m);
		$rows_m = $db->loadObjectList();
		$total_m = count($rows_m);

	    // Get Reply total
	    $query_r = $db->getQuery(true);
		$query_r
            ->select('*')
            ->from($db->quoteName('#__sms_message_reply'))
            ->where($db->quoteName('status') . ' = '. $db->quote('0'))
            ->where($db->quoteName('message_id') . ' = '. $db->quote($id));
		$db->setQuery($query_r);
		$rows_r = $db->loadObjectList();
		$total_r = count($rows_r);
		
		// Get Total		
		$total = round($total_m + $total_r);
        return $total;
	}
	
	/**
	** Sender Name
	**/
	function senderName($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Latest Message
	**/
	function getLatestMessage($sid){
		$db = JFactory::getDBO();
        $query = "SELECT * FROM `#__sms_message` WHERE recever_id = '".$sid."' OR sender_id = '".$sid."'";
        $query.=" ORDER BY id desc LIMIT 0 ,5 ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		return  $rows;
	}
	
	
	/**
	** Get Student by ID
	**/
	function getStudent($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('students');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	
	
}
