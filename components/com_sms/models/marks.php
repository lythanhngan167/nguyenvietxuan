<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelMarks extends JModelList
{
	
	/**
	** Construct
	**/
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
    }
	
	/**
	** Exam List
	**/
	function getexamList(){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'name')))
            ->from($db->quoteName('#__sms_exams'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        $exam_array = array();
        $exam_array[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_EXAM'));
        foreach ($rows as $row) {
            $exam_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
        }

		$exam =  JHTML::_('select.genericList', $exam_array, 'exam', ' class="required  inputbox  "  ', 'value', 'text', '');
        return $exam;
	}
	
	
	/**
	** Student List for entry mark
	**/
	function getstudentList($eid, $cid,$section_id, $subjid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'name','roll','year')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('class') . ' = '. $db->quote($cid))
			->where($db->quoteName('section') . ' = '. $db->quote($section_id))
            ->order('roll ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
				
	    $mark_student = '<table class="admin-table" id="admin-table" style="width: 100%;margin-top: 50px;" align="center">';
		$mark_student .= '<tr>';
		$mark_student .= '<th style="width: 10%;">'.JText::_('LABEL_MARK_ROLL').'</th>';
		$mark_student .= '<th style="" >'.JText::_('LABEL_MARK_STUDENT_NAME').'</th>';
		$mark_student .= '<th style="width: 15%;">'.JText::_('LABEL_MARK_OBTAINED').'</th>';
		$mark_student .= '<th style="width: 15%;">'.JText::_('LABEL_MARK_COMMENT').'</th>';
		$mark_student .= '<th style="width: 15%;"></th>';
		$mark_student .= '</tr>';
		
		$mark_student .= '<input type="hidden" id="exam_id" name="exam_id" value="'.$eid.'" />';
		$mark_student .= '<input type="hidden" id="class_id" name="class_id" value="'.$cid.'" />';
		$mark_student .= '<input type="hidden" id="subject_id" name="subject_id" value="'.$subjid.'" />';
		
		// Script for save mark
		$mark_student .= '<script type="text/javascript">';
		$mark_student .= 'jQuery(document).ready(function () {';
		$mark_student .= 'var exam_id = jQuery("#exam_id").val();';
		$mark_student .= 'var class_id = jQuery("#class_id").val();';
		$mark_student .= 'var subject_id = jQuery("#subject_id").val();';

		//function make
		$mark_student .= 'function markSaving(exam_id,class_id,subject_id,roll,sid,year,mark,comment,order){';
		$url = "'index.php?option=com_sms&task=marks.savemark'";
		$loader_html = '<div class=\"loader\"></div>';
		$mark_student .= 'jQuery("#saving_"+ order).html("'.$loader_html.'");';
		$mark_student .= 'jQuery.post( '.$url.',{mark:mark,comment:comment,exam_id:exam_id,class_id:class_id,subject_id:subject_id,roll:roll,sid:sid,year:year}, function(data){';
		$mark_student .= 'if(data){ jQuery("#saving_"+ order).html(data); }';
		$mark_student .= '});';
		$mark_student .= '}';
			
		//function call
		$s =0;
		foreach ($rows as $row_s) {
		    $s++;
		    $mark_student .= 'jQuery( "#button_'.$s.'" ).click(function() {';
		    $mark_student .= 'markSaving(exam_id,class_id,subject_id,jQuery("#roll_'.$s.'").val(),jQuery("#sid_'.$s.'").val(),jQuery("#year_'.$s.'").val(),jQuery("#mark_'.$s.'").val(),jQuery("#comment_'.$s.'").val(),'.$s.')';
		    $mark_student .= '});';
		}
				 
		$mark_student .= '});';
		$mark_student .= '</script>';
			
			
		$i =0;
		foreach ($rows as $row) {
		    $i++;
		 
			$query_get_marks = "SELECT marks FROM `#__sms_exams_mark` WHERE exam_id = '".$eid."' AND class_id = '".$cid."' AND subject_id = '".$subjid."' AND student_id = '".$row->id."' ";
			$db->setQuery($query_get_marks);
			$marks_value =  $db->loadResult();
			
			$query_comment = "SELECT comment FROM `#__sms_exams_mark` WHERE exam_id = '".$eid."' AND class_id = '".$cid."' AND subject_id = '".$subjid."' AND student_id = '".$row->id."' ";
			$db->setQuery($query_comment);
			$comment =  $db->loadResult();
		 
		    //Hidden Value
		    $mark_student .= '<input type="hidden" id="roll_'.$i.'" name="roll" value="'.$row->roll.'" />';
			$mark_student .= '<input type="hidden" id="sid_'.$i.'" name="student_id" value="'.$row->id.'" />';
			$mark_student .= '<input type="hidden" id="year_'.$i.'" name="year" value="'.$row->year.'" />';
			
		    $mark_student .= '<tr>';
	        $mark_student .= '<td style="width: 10%;">'.$row->roll.'</td>';
			$mark_student .= '<td style="text-align: left;" >'.$row->name.'</td>';
			$mark_student .= '<td style="width: 15%;text-align: center;" >';
			$mark_student .= '<input type="text" class="mark-input" id="mark_'.$i.'" name="marks" value="'.$marks_value.'"  />';
			$mark_student .= '</td>';
			$mark_student .= '<td class="text-left" >';
			$mark_student .= '<input type="text"  class="mark-inputd" id="comment_'.$i.'"  name="comment" value="'.$comment.'"  />';
			$mark_student .= '</td>';
			$mark_student .= '<td style="width: 15%;text-align: center;" >';
			$mark_student .= '<div id="saving_'.$i.'" class="mark-result" ></div>';
			$mark_student .= '<input type="button" name="" id="button_'.$i.'" class="btn btn-small" value="Save" />';
			$mark_student .= '</td>';
			$mark_student .= '</tr>';
				
		}//End foreach
						
		$mark_student .= '</table>';
		return $mark_student;
	}
	
	
	
	/**
	** Save Mark
	**/
	function savemark($mark,$comment, $exam_id, $class_id, $subject_id, $student_id, $roll ,$year){
	    $db = JFactory::getDBO();
	    $query_check_mark = $db->getQuery(true);
		$query_check_mark
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_exams_mark'))
            ->where($db->quoteName('exam_id') . ' = '. $db->quote($exam_id))
			->where($db->quoteName('class_id') . ' = '. $db->quote($class_id))
			->where($db->quoteName('subject_id') . ' = '. $db->quote($subject_id))
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id));
		$db->setQuery($query_check_mark);
		$mark_id =  $db->loadResult();
	
	    $table = $this->getTable('marks');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if(!empty($mark_id)){$table->id = $mark_id;}
		$table->exam_id = $exam_id;
		$table->class_id = $class_id;
		$table->subject_id = $subject_id;
		$table->student_id = $student_id;
		$table->roll = $roll;
		$table->marks = $mark;
		$table->comment = $comment;
		$table->year = $year;
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	}
	
	

	
}
