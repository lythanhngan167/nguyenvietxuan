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

class SmsModelCommon extends JModelList
{
	
function __construct()
  {
        parent::__construct();
 
        $mainframe = JFactory::getApplication();
 
       
  }
	
	
	function unreadMessage($uid){
	   
		    $db = JFactory::getDBO();
       
				
        $query_m = "SELECT * FROM #__sms_message m WHERE  m.status=0  and m.recever_id='".$uid."' ";
        $db->setQuery($query_m);
        $rows_m = $db->loadObjectList();
				$total_m = count($rows_m);
				
				$query_r = "SELECT * FROM #__sms_message_reply r WHERE  r.status=0  and r.recever_id='".$uid."' ";
        $db->setQuery($query_r);
        $rows_r = $db->loadObjectList();
				$total_r = count($rows_r);
				
				$total = round($total_m + $total_r);
				
       return $total;
	
	}
	
	function checkGroup($id){
	                                $db =JFactory::getDBO();
																	$check_group= "SELECT group_id FROM #__user_usergroup_map WHERE user_id= '$id' ";
                                  $db->setQuery( $check_group);
	                                $user_group_id= $db->loadResult(); 
																	
																	$check_group_title= "SELECT title FROM #__usergroups WHERE id= '$user_group_id' ";
                                  $db->setQuery( $check_group_title);
	                                $title= $db->loadResult(); 
																	return  $title;
	}
	
	
	
	
	
	
						

	
	
}
