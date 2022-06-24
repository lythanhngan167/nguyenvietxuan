<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;


class SmsModelAttendance extends JModelList
{
	
	/**
	** constructor
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
	** Get Old Attendance Info Data
	**/
	function getOldatt($aid, $student_id,$field){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array($field)))
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where($db->quoteName('attendance_id') . ' = '. $db->quote($aid))
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	** Get Student  List
	**/
	function getStudentList($class, $section){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('class') . ' = '. $db->quote($class))
			->where($db->quoteName('section') . ' = '. $db->quote($section))
            ->order('roll ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Get Total Present
	**/
	function totalPresent($aid){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where($db->quoteName('attendance_id') . ' = '. $db->quote($aid))
			->where($db->quoteName('attend') . ' = '. $db->quote('1'));
		$db->setQuery($query_result);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Get Total Absent
	**/
	function totalAbsent($aid){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where($db->quoteName('attendance_id') . ' = '. $db->quote($aid))
			->where($db->quoteName('attend') . ' = '. $db->quote('0'));
		$db->setQuery($query_result);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	
	/**
	** Get Attendance 
	**/
	function getAttendance($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('attendance');
			$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
    /**
	** Attantdance List
	**/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_attendance'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'desc'); 
		
		// Filter by Class.
		$classId = $this->getState('filter.class_id');
		if (is_numeric($classId)){
			$query->where('class = ' . $db->quote($classId));
		}
		
		// Filter by Section.
		$sectionId = $this->getState('filter.section_id');
		if (is_numeric($sectionId)){
			$query->where('section = ' . $db->quote($sectionId));
		}
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)){
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(attendance_date LIKE ' . $search . ' )');
		}	
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Save Attendance
	**/
	function saveattend($aid, $user_id, $date, $class, $section){
	    $db    = JFactory::getDbo();
		date_default_timezone_set('Asia/Kolkata');
		$date_update   = date('Y-m-d H:i:s');
		$current_time  = date('H:i:s');
		$create_date   = date( 'Y-m-d H:i:s', strtotime($date.''.$current_time));
		$user		   = JFactory::getUser();
	    $user_id       = $user->id;
				
		// Student List
		$query_students = $db->getQuery(true);
		$query_students
            ->select('*')
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('class') . ' = '. $db->quote($class))
			->where($db->quoteName('section') . ' = '. $db->quote($section))
            ->order('roll ASC');
        $db->setQuery($query_students);
        $student_rows = $db->loadObjectList();
		$total_student = count($student_rows);
		
	    $table = $this->getTable('attendance');
		$data = JRequest::get( 'post' );
		
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if($aid){$table->id = $aid;}
			$table->teacher = $user_id;
			$table->attendance_date = $date;
			$table->class = $class;
			$table->section = $section;
			$table->total_student = $total_student;
			$table->create_date = $create_date;
			$table->update_date = $date_update;
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		
		//Save to attendance info
		if(!empty($total_student) && empty($aid)){
		    foreach($student_rows as $student){
				$table_info = $this->getTable('attendanceinfo');
				$table_info->attendance_id = $id;
		        $table_info->student_id = $student->id;
		        $table_info->attend = 0;
				$table_info->entry_by = $user_id;
				$table_info->create_date = $create_date;
		        if (!$table_info->store()){
				    $this->setError($user->getError());
			        return false;
		        }
			}//end foreach
		}
		
		return $id;
	}
  
	/**
	** Save Attendance info
	**/
	function savestatus($aid, $sid, $status){
	    date_default_timezone_set('Asia/Kolkata');
	    $date = date('Y-m-d H:i:s');
	    $user		= JFactory::getUser();
	    $user_id = $user->id;
	    $table = $this->getTable('attendanceinfo');
		$db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where($db->quoteName('attendance_id') . ' = '. $db->quote($aid))
			->where($db->quoteName('student_id') . ' = '. $db->quote($sid));
		$db->setQuery($query_result);
		$id = $db->loadResult();
		
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if(!empty($id)){$table->id = $id;}
		$table->attendance_id = $aid;
		$table->student_id = $sid;
		$table->attend = $status;
		$table->update_date = $date;
		$table->entry_by = $user_id;
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
	    $id = $table->id;
		return $id;
	}
  
	

	
}
