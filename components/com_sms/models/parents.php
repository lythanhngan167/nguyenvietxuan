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

class SmsModelParents extends JModelList
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
	** Get Parent ID
	**/
	function getParentID($id){
	    $db = JFactory::getDBO();
	    $query_result = "SELECT id FROM `#__sms_parents` WHERE user_id = '".$id."'";
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Parent Student ID
	**/
	function getParentSID($id){
	    $db = JFactory::getDBO();
		$query_result = "SELECT student_id FROM `#__sms_parents` WHERE id = '".$id."'";
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	
	function getStudent($id){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id))
            ->order('id ASC');		 
        $db->setQuery($query);
        $rows = $db->loadObject();
		return $rows;
	}
	

    /**
    ** Get Today Attendance
    **/
	function todaytAttendance(){

		$user		= JFactory::getUser();
        $user_id = $user->id;
        
		$html ='<div class=" message message_box">';
		$html .='<h1>'.JText::_('LABEL_TODAY_ATTENDANCE_STATUS').':</h1>';

		$parent_id = self::getParentID($user_id);
        $student_data = SmsHelper::selectSingleData('student_id', 'sms_parents', 'id', $parent_id);
        $student_ids = explode(",", $student_data);

        $html .='<table class="message table-striped" style="margin-top: 0px;width: 100%;">';
        $html .='<tr>';
	    $html .='<th>Roll</th>';
	    $html .='<th>Name</th>';
	    $html .='<th>'.JText::_('LABEL_STATUS').'</th>';
	    $html .='<th>'.JText::_('LABEL_TEACHER').'</th>';
	    $html .='<th>'.JText::_('LABEL_DATE_TIME').'</th>';
        $html .='</tr>';
        foreach ($student_ids as $key => $sid) {
        	
    	    $roll = SmsHelper::getLoadResult('roll', 'sms_students','id',$sid);
    	    $name = SmsHelper::getLoadResult('name', 'sms_students','id',$sid);
			$class = SmsHelper::getLoadResult('class', 'sms_students','id',$sid);
			$section = SmsHelper::getLoadResult('section', 'sms_students','id',$sid);
			$cy = intVal(date('Y'));
			$cm = intVal(date('m'));
			$cd = intVal(date('d'));


			$getattendanceDd = self::getAttendanceDay('DAY(attendance_date)',$cy, $cm , $cd ,$class, $section);
			$attendane_id = self::getAttendanceDay('id',$cy, $cm , $cd ,$class, $section);
			$student_attent = self::getStudentAttent('attend',$sid, $attendane_id);
			$create_date = self::getStudentAttent('create_date',$sid, $attendane_id);
			$atten_time = date( 'd-m-Y g:i A', strtotime($create_date));

			$entry_by = self::getStudentAttent('entry_by',$sid, $attendane_id);
			$teacher_name = self::getTeachername($entry_by);

			if($student_attent==1){
				$attent_status ='<span style="color: green;font-weight: Bold;">'.JText::_('COM_SMS_LABEL_ATTENDANCE').'</span>';
			}else{
				$attent_status ='<span style="color: red;font-weight: Bold;">'.JText::_('COM_SMS_LABEL_ABSENT').'</span>';
			}

            $html .='<tr>';
		    $html .='<td>'.$roll.'</td>';
		    $html .='<td>'.$name.'</td>';
		    $html .='<td>'.$attent_status.'</td>';
		    $html .='<td>'.$teacher_name.'</td>';
		    $html .='<td>'.$atten_time.'</td>';
		    $html .='</tr>';
        }
        $html .='</table>';
					
		$link_attendance = JRoute::_( 'index.php?option=com_sms&view=attendancereport' );

		$html .='<p style="padding: 0 10px;text-align: right;"><a href="'.$link_attendance.'"> '.JText::_('LABEL_READ_MORE').'</a></p>';
		$html .='</div>';
        return $html;
	}

	/**
	** Get Attendance Day
	**/
	function getAttendanceDay($select, $year, $month , $day ,$class, $section){
	    $db = JFactory::getDBO();
		$query_add_day = $db->getQuery(true);
		$query_add_day
            ->select($select)
            ->from($db->quoteName('#__sms_attendance'))
            ->where('YEAR(attendance_date) = '. $db->quote($year))
			->where('MONTH(attendance_date) = '. $db->quote($month))
			->where('DAY(attendance_date) = '. $db->quote($day))
			->where($db->quoteName('class') . ' = '. $db->quote($class))
			->where($db->quoteName('section') . ' = '. $db->quote($section));
		$db->setQuery($query_add_day);
		$data = $db->loadResult();
	    return $data;
	}
	
	/**
	** Get Student Attent
	**/
	function getStudentAttent($field,$student_id, $attendane_id){
	    $db = JFactory::getDBO();
		$query_add_day = $db->getQuery(true);
		$query_add_day
            ->select($field)
            ->from($db->quoteName('#__sms_attendance_info'))
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
			->where($db->quoteName('attendance_id') . ' = '. $db->quote($attendane_id));
		$db->setQuery($query_add_day);
		$data = $db->loadResult();
	    return $data;
	}
	
	/**
	** Get Teacher Name
	**/
	function getTeachername($id){
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
	** Get Parent
	**/
	function getParent($id){
	    if($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('parents');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}

}
