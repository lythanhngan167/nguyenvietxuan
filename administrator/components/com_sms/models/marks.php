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
	
	
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication(); 
    }
	
	/**
	** Get Section List
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
        $sections = array();
        $sections[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_SECTION'));
        foreach ($rows as $row) {
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
        }
		$section =  JHTML::_('select.genericList', $sections, 'section', 'class=" required inputbox  " required="required" onchange="desplyStudentList();" ', 'value', 'text', $id);
        return $section;
	}
	
	/**
	** Get Exam Name
	**/
	function getExamname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_exams'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Subject Name
	**/
	function getSubjectname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('subject_name')))
            ->from($db->quoteName('#__sms_subjects'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Mark
	**/
	function getInMark($field,$eid,$cid,$subjid,$id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array($field)))
            ->from($db->quoteName('#__sms_exams_mark'))
			->where($db->quoteName('exam_id') . ' = '. $db->quote($eid))
			->where($db->quoteName('class_id') . ' = '. $db->quote($cid))
			->where($db->quoteName('subject_id') . ' = '. $db->quote($subjid))
            ->where($db->quoteName('student_id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Exam List
	**/
	function getexamList($id=0){
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
		$exam =  JHTML::_('select.genericList', $exam_array, 'exam', ' class="required  inputbox  "  ', 'value', 'text', $id);
        return $exam;
	}
	
	/**
	** Get Student ID
	**/
	function getStudentID($roll){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Student Year
	**/
	function getStudentYear($roll){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('year')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Mark List for out excel
	**/
	function getMarkListExcel($eid, $cid,$sid, $subjid){
	    $db = JFactory::getDBO();
        $query = "SELECT id,name, roll,year FROM `#__sms_students` WHERE class = '".$cid."' AND section = '".$sid."' ";
        $query.=" ORDER BY roll asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		return $rows;
	}
	
	
	/**
	** Subject List
	**/
	function getsubjectList($class_id,$id=0){
        $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('subjects')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($class_id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		$subject_ids = explode(",", $data);
				
        $subjects = array();
		$subjects[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_SUBJECT'));
        foreach ($subject_ids as $row) {
			$query_subject_name = $db->getQuery(true);
		    $query_subject_name
                ->select($db->quoteName(array('subject_name')))
                ->from($db->quoteName('#__sms_subjects'))
                ->where($db->quoteName('id') . ' = '. $db->quote($row));
			$db->setQuery($query_subject_name);
		    $subject_name = $db->loadResult();
            $subjects[] = array('value' => $row, 'text' => JText::_(' '.$subject_name));
        }
		$subject =  JHTML::_('select.genericList', $subjects, 'subjects', ' class=" inputbox  "  onchange="desplyStudentList();"  ', 'value', 'text', $id);
        return $subject;
	}
	
	/**
	** Student List
	***/
	function getstudentList($eid, $cid, $section_id, $subjid){
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
				
		//upload xcxl file
		$mark_student = '<table class="admin-table" id="admin-table" style="width: 90%;margin-top: 50px;background: #f5f5f5;" align="center">';
			$mark_student .= '<tr>';
			    $mark_student .= '<td>';
				
				$mark_student .= '<form action="" method="post" name="user-form"   enctype="multipart/form-data">';
				$mark_student .= '<input type="file" name="spreadsheet"/>';
				$mark_student .= '<input type="submit" value="Upload" id="upload" class="btn btn-default" />';
				$mark_student .= '<input type="hidden" name="task" value="importcsv"  />';
				$mark_student .= '<input type="hidden" name="controller" value="marks" />';
				$mark_student .= '<input type="hidden" name="exam_id" value="'.$eid.'" />';
				$mark_student .= '<input type="hidden" name="class_id" value="'.$cid.'" />';
				$mark_student .= '<input type="hidden" name="subject_id" value="'.$subjid.'" />';
				$mark_student .= '<input type="hidden" name="section_id" value="'.$section_id.'" />';
				$mark_student .= '</form>';
				$mark_student .= ' <p class="help-block" style="margin-top: 10px;">Only Excel File Import. Excel file Must have headers as follows:<b style="color: red;"> Roll, Marks, Comment</b> </p></td>';
			$mark_student .= '</tr>';
	    $mark_student .= '</table>';
					
	    $mark_student .= '<table class="admin-table" id="admin-table" style="width: 90%;margin-top: 50px;" align="center">';
			$mark_student .= '<tr>';
			$mark_student .= '<th style="width: 6%;">'.JText::_('LABEL_MARK_ROLL').'</th>';
			$mark_student .= '<th style="width: 30%;" >'.JText::_('LABEL_MARK_STUDENT_NAME').'</th>';
			$mark_student .= '<th style="width: 15%;">'.JText::_('LABEL_MARK_OBTAINED').'</th>';
			$mark_student .= '<th >'.JText::_('LABEL_MARK_COMMENT').'</th>';
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
			     $mark_student .= 'jQuery("#saving_"+ order).html("Saving ...");';
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
						$mark_student .= '<div id="saving_'.$i.'"></div>';
						$mark_student .= '<input type="button" name="" id="button_'.$i.'" class="btn btn-small" value="Save" />';
						$mark_student .= '</td>';
						
				$mark_student .= '</tr>';
					
			}//End foreach
				
			$mark_student .= '</table>';
			
			if (empty($rows)) {
			$msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').'</div>';
			return $msg;
			}else{
			return $mark_student;
			}
	}
	
	/**
	** Mark List
	***/
	function getMarkList($eid, $cid, $section_id, $subjid){
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
				
	    $mark_student = '<table class="admin-table" id="admin-table" style="margin-top: 50px;" align="center">';
			$mark_student .= '<tr>';
			$mark_student .= '<th style="width: 6%;">'.JText::_('LABEL_MARK_ROLL').'</th>';
			$mark_student .= '<th style="width: 30%;" >'.JText::_('LABEL_MARK_STUDENT_NAME').'</th>';
			$mark_student .= '<th style="width: 15%;">'.JText::_('LABEL_MARK_OBTAINED').'</th>';
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
			     $mark_student .= 'jQuery("#saving_"+ order).html("Saving ...");';
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
						$mark_student .= '<td style="width: 15%;text-align: center;" >'.$marks_value.'</td>';
						
						
				$mark_student .= '</tr>';
					
			}//End foreach
				
				$mark_student .= '</table>';
				
				if (empty($rows)) {
				$msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').'</div>';
				return $msg;
				}else{
				return $mark_student;
				}
				
	   
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
        $class_array = array();
        $class_array[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_CLASS'));
        foreach ($rows as $row) {
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
        }
		$class =  JHTML::_('select.genericList', $class_array, 'class', ' class="required  inputbox  "   ', 'value', 'text', $id);
        return $class;
	}
	
	
}
