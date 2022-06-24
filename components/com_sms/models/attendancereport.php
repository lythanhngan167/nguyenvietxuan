<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsModelAttendancereport extends JModelList
{
	
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
    }
	

	/**
	** Parent student List
	**/
	function getStudentList($pid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
             ->select($db->quoteName(array('id', 'name')))
             ->from($db->quoteName('#__sms_students'))
             ->order('id ASC');
				
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }



        // get 
        $student_data = SmsHelper::selectSingleData('student_id', 'sms_parents', 'id', $pid);
        $student_ids = explode(",", $student_data);


        $student_array = array();
        $student_array[] = array('value' => '', 'text' => JText::_(' -- Select Student -- '));
        foreach ($rows as $key=>$row) {
            if(in_array($row->id, $student_ids))
            {
            $student_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
            }else{
               	unset( $rows[ $key ] ); 
            }
        }
	    $student_list =  JHTML::_('select.genericList', $student_array, 'student', ' class="required  inputbox  "   ', 'value', 'text', '');
        return $student_list;
	}
	
	public static function getParentID($id){
	      $db = JFactory::getDBO();
			  $query_result = "SELECT id FROM `#__sms_parents` WHERE user_id = '".$id."'";
				$db->setQuery($query_result);
				$data = $db->loadResult();
				return $data;
	}
	
	
	
	public static function getStudentID($id){
	      $db = JFactory::getDBO();
			  $query_result = "SELECT id FROM `#__sms_students` WHERE user_id = '".$id."'";
				$db->setQuery($query_result);
				$data = $db->loadResult();
				return $data;
	}
	
	public static function getStudent($id)
	{
	 $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
             ->select($db->quoteName(array('id', 'class', 'section', 'year')))
             ->from($db->quoteName('#__sms_students'))
             ->where($db->quoteName('id') . ' = '. $db->quote($id))
             ->order('id ASC');
				
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
	}
	
	/**
	** Student Year
	**/
	public static function getAcademicYear($id){
	      $db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query
             ->select($db->quoteName(array('year')))
             ->from($db->quoteName('#__sms_academic_year'))
             ->where($db->quoteName('id') . ' = '. $db->quote($id));
				$db->setQuery($query);
				$data = $db->loadResult();
				return $data;
	}
	
	/**
	** Attendance display
	**/
	
	public static	function DisplayAttendance($year_id, $month, $student_id){

	    $student = self::getStudent($student_id);
	    $class = $student->class;
	    $section = $student->section;
		if(empty($year_id)){
			$year = self::getAcademicYear($student->year);
		
		}else{
			$year = self::getAcademicYear($year_id);
		}

		
	
	    $months = array( JText::_('COM_SMS_MONTH_JANUARY'), JText::_('COM_SMS_MONTH_FEBRUARY'), JText::_('COM_SMS_MONTH_MARCH'), JText::_('COM_SMS_MONTH_APRIL'),  JText::_('COM_SMS_MONTH_MAY'), JText::_('COM_SMS_MONTH_JUNE'), JText::_('COM_SMS_MONTH_JULY'), JText::_('COM_SMS_MONTH_AUGUST'), JText::_('COM_SMS_MONTH_SEPTEMBER'),JText::_('COM_SMS_MONTH_OCTOBER'), JText::_('COM_SMS_MONTH_NOVEMBER'),JText::_('COM_SMS_MONTH_DECEMBER'),);
					       
		$d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$month_val = $month-1;
		$month_title = $months[$month_val];
		$monthend = $d;
	    $monthstart = 1;
						
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_attendance'))
            ->where('YEAR(attendance_date) = '. $db->quote($year))
			->where('MONTH(attendance_date) = '. $db->quote($month))
			->where($db->quoteName('class') . ' = '. $db->quote($class))
			->where($db->quoteName('section') . ' = '. $db->quote($section))
            ->order('id ASC');
		$db->setQuery($query);
		$attendance_row = $db->loadObjectList();
		$total_class = count($attendance_row);
						
		$startdate = $year.'-'.$month.'-'.$monthstart;
		$enddate = $year.'-'.$month.'-'.$monthend;
						
		// Get Present Query
		$query_present = $db->getQuery(true);
		$query_present
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where("create_date BETWEEN '".$startdate." 00:00:01' AND '".$enddate." 23:59:59'")
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
			->where($db->quoteName('attend') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query_present);
		$present_row = $db->loadObjectList();
		$total_present = count($present_row);
						
						
		// Get Absent Query
		$query_absent = $db->getQuery(true);
		$query_absent
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where("create_date BETWEEN '".$startdate." 00:00:01' AND '".$enddate." 23:59:59'")
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
			->where($db->quoteName('attend') . ' = '. $db->quote('0'))
            ->order('id ASC');
        $db->setQuery($query_absent);
		$absent_row = $db->loadObjectList();
		$total_absent = count($absent_row);
						
		//$total_absent = ($total_class - $total_present);
						
		$show ='<table cellpadding="0" cellspacing="0" class="admin-table " id="admin-table" style="width: 100%;border: 1px solid #eee;background: #f5f5f5;" align="center" >';
		$show .='<tr>';
			$show .= '<td style="text-align: left;border: none;"><b>'.$month_title.'</b></td>';
			$show .= '<td style="border: none;"><i>'.JText::_('LABEL_ATTENDANCE_TOTAL_CLASS').': '.$total_class.' '.JText::_('DEFAULT_DAYS').'</i></td>';
			$show .= '<td style="border: none;"><i>'.JText::_('LABEL_ATTENDANCE_TOTAL_PRESENT').': '.$total_present.' '.JText::_('DEFAULT_DAYS').'</i></td>';
			$show .= '<td style="border: none;"><i>'.JText::_('LABEL_ATTENDANCE_TOTAL_ABSENT').': '.$total_absent.' '.JText::_('DEFAULT_DAYS').'</i></td>';
		$show .='</tr>';
		$show .='</table>';



		$show .='<table cellpadding="0" cellspacing="0" class="admin-table attendance-report" id="admin-table" style="width: 100%;margin-top: 0px;margin-bottom: 20px;" align="center" >';

		$show .='<tr>';
		$show .='<th>Day</th>';
		$show .='<th>Status</th>';
		$show .='<th>Entry By</th>';
		$show .='<th>Date & Time</th>';
		$show .='</tr>';
		
			for ($h = $monthstart; $h <= $monthend; $h++) {
				$show .='<tr>';
				$getattendanceD =  self::getAttendanceDay('DAY(attendance_date)',$year, $month , $h ,$class, $section);
				$attendane_id = self::getAttendanceDay('id',$year, $month , $h ,$class, $section);
				$student_attent = self::getAttentBy('attend', $student_id, $attendane_id);
				$create_date = self::getAttentBy('create_date', $student_id, $attendane_id);
				$atten_date = date( 'd-M-Y', strtotime($create_date));
				$atten_time = date( '(g:i A)', strtotime($create_date));

				$tuid = self::getAttentBy('entry_by', $student_id, $attendane_id);
				$teacher_name = SmsHelper::selectSingleData('name', 'sms_teachers', 'user_id', $tuid);

				if($student_attent==1){
					$pstatus = '<span style="color: green;font-weight: Bold;">'.JText::_('COM_SMS_LABEL_ATTENDANCE').'</span>';
					$pstatus_bg ='#ccffcc';
				}else{
                    $pstatus = '<span style="color: red;font-weight: Bold;">'.JText::_('COM_SMS_LABEL_ABSENT').'</span>';
                    $pstatus_bg ='#ffcccc';
				}

				if($getattendanceD==$h){
					$show .='<td style="background: '.$pstatus_bg.';" >'.$h.'</td>';
					$show .='<td style="background: '.$pstatus_bg.';" >'.$pstatus.'</td>';
					$show .='<td style="background: '.$pstatus_bg.';" >'.$teacher_name.'</td>';
					$show .='<td style="background: '.$pstatus_bg.';" >'.$atten_date.' '.$atten_time.'</td>';
				}else{
					$show .= '<td >'.$h.'</td>';
					$show .= '<td ></td>';
					$show .= '<td ></td>';
					$show .= '<td ></td>';
				}
				$show .='</tr>';
			} 
		
		/*							
		$show .='<tr>';
			for ($x = $monthstart; $x <= $monthend; $x++) {
				$getattendanceDd = self::getAttendanceDay('DAY(attendance_date)',$year, $month , $x ,$class, $section);
				$attendane_id = self::getAttendanceDay('id',$year, $month , $x ,$class, $section);
				$student_attent = self::getStudentAttent($student_id, $attendane_id);
				$create_date = self::getStudentTime($student_id, $attendane_id);
				$atten_time = date( 'g:i A', strtotime($create_date));
				if($getattendanceDd==$x){
					if($student_attent==1){
						$show .='<td style="background: #ccffcc ;" ><span style="color: green;font-weight: Bold;">P</span> </td>';
				    }else{
					    $show .='<td style="background: #ffcccc ;" ><span style="color: red;font-weight: Bold;">A</span></td>';}
				}else{
					$show .= '<td ></td>';
				}
			} 
												
		$show .='</tr>';
		*/
		$show .='</table>';
	return $show;
	}
	
	static function getAttendanceDay($select, $year, $month , $day ,$class, $section){
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
	static function getStudentAttent($field, $student_id, $attendane_id){
	    $db = JFactory::getDBO();
		$query_add_day = $db->getQuery(true);
		$query_add_day
            ->select('attend')
            ->from($db->quoteName('#__sms_attendance_info'))
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
			->where($db->quoteName('attendance_id') . ' = '. $db->quote($attendane_id));
		$db->setQuery($query_add_day);
		$data = $db->loadResult();
	    return $data;
	}

	static function getAttentBy($field, $student_id, $attendane_id){
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
	
	static function getStudentTime($student_id, $attendane_id){
	                     $db = JFactory::getDBO();
											 $query_add_day = $db->getQuery(true);
				                 $query_add_day
                         ->select('create_date')
                         ->from($db->quoteName('#__sms_attendance_info'))
												 ->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
						             ->where($db->quoteName('attendance_id') . ' = '. $db->quote($attendane_id));
				                 $db->setQuery($query_add_day);
										    $data = $db->loadResult();
	return $data;
	}
	
}
