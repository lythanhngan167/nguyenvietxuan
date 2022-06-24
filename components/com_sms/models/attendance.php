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

class SmsModelAttendance extends JModelList
{
	
    /**
    ** Construct
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
	** Get Class Name
	**/
	function getClassname($id){
		$data = SmsHelper::selectSingleData('class_name', 'sms_class', 'id', $id);
	    return $data;
	}
	
	/**
	** Get Teacher Name
	**/
	function getTeachername($id){
		$data = SmsHelper::selectSingleData('name', 'users', 'id', $id);
	    return $data;
	}
	
	/**
	** Get Section Name
	**/
	function getSectionname($id){
		$data = SmsHelper::selectSingleData('section_name', 'sms_sections', 'id', $id);
	    return $data;
	}
	
	/**
	** Get Old Attendance Info Data
	**/
	function getOldatt($aid, $student_id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('attend')))
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where($db->quoteName('attendance_id') . ' = '. $db->quote($aid))
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	** Student  List
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
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where($db->quoteName('attendance_id') . ' = '. $db->quote($aid))
			->where($db->quoteName('attend') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Get Total Absent
	**/
	function totalAbsent($aid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where($db->quoteName('attendance_id') . ' = '. $db->quote($aid))
			->where($db->quoteName('attend') . ' = '. $db->quote('0'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Class List
	**/
	function getclassList($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'class_name')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');	
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        // get user
        $user		= JFactory::getUser();
        $userid = $user->get( 'id' ); 
        $class_data = SmsHelper::selectSingleData('class', 'sms_teachers', 'user_id', $userid);
        $class = explode(",", $class_data);

        $class_array = array();
        $class_array[] = array('value' => '', 'text' => JText::_(' -- Select Class -- '));
        foreach ($rows as $key=>$row) {
            if(in_array($row->id, $class)){
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
            }else{
               	unset( $rows[ $key ] ); 
            }
        }
	    $class =  JHTML::_('select.genericList', $class_array, 'class', ' class="required  inputbox  "   ', 'value', 'text', $id);
        return $class;
	}
	
	/**
	** Section List
	**/
	function getsectionList($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'section_name')))
            ->from($db->quoteName('#__sms_sections'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        // get user
        $user		= JFactory::getUser();
        $userid = $user->get( 'id' ); 
        $section_data = SmsHelper::selectSingleData('section', 'sms_teachers', 'user_id', $userid);
        $section = explode(",", $section_data);

        $sections = array();
        $sections[] = array('value' => '', 'text' => JText::_(' -- Select Section -- '));
        foreach ($rows as $key=>$row) {
            if(in_array($row->id, $section)){
                $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
            }else{
               	unset( $rows[ $key ] ); 
            }
        }
		$section =  JHTML::_('select.genericList', $sections, 'section', 'class=" required inputbox  " required="required" ', 'value', 'text', $id);
        return $section;
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
	** Attendance List
	**/
	 protected function getListQuery(){
	    $user		= JFactory::getUser();
        $userid = $user->get( 'id' ); 
		
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_attendance'));
		//$query->where('teacher = ' . $db->quote($user_id));
		$query->where('teacher = ' . $db->quote($userid));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'desc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Save Attendance
	**/
	function saveattend($aid, $user_id, $date, $class, $section){
	    $db    = JFactory::getDbo();
		    
		date_default_timezone_set('Asia/Kolkata');
		$date_update = date('Y-m-d H:i:s');
		$current_time = date('H:i:s');
		$create_date = date( 'Y-m-d H:i:s', strtotime($date.''.$current_time));
				
		$user		= JFactory::getUser();
	    $user_id = $user->id;
				
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
		if (!$table->store())
		{
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
	    $query_result = "SELECT id FROM `#__sms_attendance_info` WHERE attendance_id = '".$aid."' AND student_id = '".$sid."'";
		$db->setQuery($query_result);
		$id = $db->loadResult();
		
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if(!empty($id)){$table->id = $id;}
		$table->attendance_id = $aid;
		$table->student_id    = $sid;
		$table->attend        = $status;
		$table->update_date   = $date;
		$table->entry_by      = $user_id;
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	 }
  
	

	
	
}
