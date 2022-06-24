<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelPromotion extends JModelList
{
	
	/**
	** Get constructor
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
	** Get Class Name by id
	**/
	function getClassname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('class_name')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Promotion data
	**/
	function getPromoData($select_field,$year_field,$class_field,$division_field, $year, $class, $division, $sid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array($select_field)))
            ->from($db->quoteName('#__sms_student_year'))
			->where($db->quoteName('sid') . ' = '. $db->quote($sid))
            ->where($db->quoteName($year_field) . ' = '. $db->quote($year))
			->where($db->quoteName($class_field) . ' = '. $db->quote($class))
			->where($db->quoteName($division_field) . ' = '. $db->quote($division));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
    
    /**
    ** Get exit data
    **/
    function getExitData($select_field, $year, $sid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array($select_field)))
            ->from($db->quoteName('#__sms_student_year'))
			->where($db->quoteName('sid') . ' = '. $db->quote($sid))
            ->where($db->quoteName('year') . ' = '. $db->quote($year));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	

	/**
	** Get Section by id
	**/
	function getSection($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('section_name')))
            ->from($db->quoteName('#__sms_sections'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Exam name
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
	** Get Student Name
	**/
	function getStudentName($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
    
	
	/**
	** Get Section Name
	**/
	function getSectionname($id){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('section_name')))
            ->from($db->quoteName('#__sms_sections'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Division Name
	**/
	function getDivisionname($id){
	    $db = JFactory::getDBO();
	    $query_result = "SELECT division_name FROM `#__sms_division` WHERE id = '".$id."'";
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Exam List
	**/
	function getexamList($id){
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
        $exam_array[] = array('value' => '', 'text' => JText::_(' -- Select Exam -- '));
        foreach ($rows as $row) {
            $exam_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
        }
	    $exam =  JHTML::_('select.genericList', $exam_array, 'exam', ' class="required  inputbox  "  ', 'value', 'text', $id);
        return $exam;
	}
	
	/**
	** Class List
	**/
	function getclassList($id,$id_name){
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
        $class_array[] = array('value' => '', 'text' => JText::_(' -- Select -- '));
        foreach ($rows as $row) {
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
        }
		$class =  JHTML::_('select.genericList', $class_array, $id_name, ' class="required  inputbox  "   ', 'value', 'text', $id);
       return $class;
	}
	
	
	/**
	** Section List from class
	**/
	function sectionList($class_id){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('section')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($class_id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		$section_value = explode(",", $data);
		return $section_value;
	}
	
	/**
	** Get Section List
	**/
	function getsectionList($id,$id_name){
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
        $sections[] = array('value' => '', 'text' => JText::_(' -- Select -- '));
        foreach ($rows as $row) {
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
        }
	    $section =  JHTML::_('select.genericList', $sections, $id_name, 'class=" required inputbox  " required="required"  ', 'value', 'text', $id);
        return $section;
	}
	
	
	/**
	** Division List
	**/
	function getdivisionList($id,$id_name){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'division_name')))
            ->from($db->quoteName('#__sms_division'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $divisions = array();
        $divisions[] = array('value' => '', 'text' => JText::_(' -- Select -- '));
        foreach ($rows as $row) {
            $divisions[] = array('value' => $row->id, 'text' => JText::_(' '.$row->division_name));
        }
				
		$onclick_manage_subject = "javascript: formget(this.form, 'index.php?option=com_sms&controller=marks&task=getsubjectlist&format=raw');";
	    $division =  JHTML::_('select.genericList', $divisions, $id_name, 'class=" inputbox  " onchange="'.$onclick_manage_subject.'"  ', 'value', 'text',$id);
        return $division;
	}
	
	/**
	** Subject List
	**/
	function getFourthSubject($id,$optional_id_name){
	    $db = JFactory::getDBO();
        $query = "SELECT id,subject_name,for_class,division FROM `#__sms_subjects` WHERE published = 1 AND fourth_subject = 1 ";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $divisions = array();
        $divisions[] = array('value' => '', 'text' => JText::_(' -- Select -- '));
        foreach ($rows as $row) {
            $divisions[] = array('value' => $row->id, 'text' => $row->subject_name);
        }
		$division =  JHTML::_('select.genericList', $divisions, $optional_id_name, 'class="  inputbox  " ', 'value', 'text',$id);
        return $division;
	}
	
	/**
	** Get Subject by didvision
	**/
	function getSubjectByDivision($division,$compulsory_subject,$id_name){
	    if(empty($id)){$id=0;}
	    $db = JFactory::getDBO();
        $query = "SELECT id,subject_name,for_class,division FROM `#__sms_subjects` WHERE published = 1 AND division = ".$division." ";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $divisions = array();
        
			if($compulsory_subject){
			$select_value = explode(",", $compulsory_subject);
			}else{
			$select_value = "";
			}
				
		//$divisions[] = array('value' => '', 'text' => JText::_(' -- Select Subject -- '));
        foreach ($rows as $row) {
            $divisions[] = array('value' => $row->id, 'text' => JText::_(' '.$row->subject_name.''));
        }
		$division =  JHTML::_('select.genericList', $divisions, $id_name, 'multiple="multiple" class=" classBox inputbox  " ', 'value', 'text',$select_value);
        return $division;
	}
	
    
    /**
	** Fix issue
	**/
	function fix($cid){
	    $db = JFactory::getDBO();
        $query = "SELECT *  FROM `#__sms_students`   ";
        $query.=" ORDER BY roll asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        foreach($rows as $row){
            $student_id = $row->id;
            $class = $row->class;
            $roll = $row->roll;
            $division  = $row->division;
            $section  = $row->section;
            $year  = $row->year;
            
            
            
            $exit_id = $this->getPromoData('id', 'year', 'class', 'division', $year, $class, $division, $student_id);
            if(empty($exit_id)){
            $query_insert = $db->getQuery(true);
            $columns = array('sid', 'class', 'roll', 'section', 'division', 'year');
            $values = array($db->quote($student_id), $db->quote($class), $db->quote($roll), $db->quote($section), $db->quote($division), $db->quote($year));
            $query_insert
                ->insert($db->quoteName('#__sms_student_year'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query_insert);
            $db->execute();
               
            }
            
        }
        
        return 1;
    }
    
    
	/**
	** Student List
	**/
	function getstudentList($year, $cid, $division_id){
	    $db = JFactory::getDBO();
        $query = "SELECT *  FROM `#__sms_student_year` WHERE class = '".$cid."' AND division = '".$division_id."' AND year = '".$year."'  ";
        $query.=" ORDER BY roll asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		
	    $mark_student = '<table class="admin-table" id="admin-table" style="width: 100%;margin-top: 50px;" align="center">';
			$mark_student .= '<tr>';
			$mark_student .= '<th style="width: 6%;">Roll</th>';
			$mark_student .= '<th >Student Name</th>';
			$mark_student .= '<th style="width: 10%;">New Roll</th>';
			$mark_student .= '<th style="width: 10%;">New Class</th>';
			$mark_student .= '<th style="width: 10%;">New Division</th>';
			$mark_student .= '<th style="width: 10%;">New Section</th>';
			$mark_student .= '<th style="width: 9%;">New Year</th>';
			$mark_student .= '<th style="width: 15%;"></th>';
			$mark_student .= '</tr>';
			
			//$mark_student .= '<input type="hidden" id="class_id" name="class_id" value="'.$cid.'" />';
			
			// Script for save mark
            $mark_student .= '<script type="text/javascript" src="../administrator/components/com_sms/js/jquery.sumoselect.js"></script>';

			$mark_student .= '<script type="text/javascript">';
            $mark_student .= 'jQuery(document).ready(function () { window.asd = jQuery(".classBox").SumoSelect({ csvDispCount: 3 });';
			     
            //function make
            $mark_student .= 'function promoSaving(sid, newroll, newclass, newdivision, newsection, newyear,exit_id,order){';
            $url = "'index.php?option=com_sms&task=promotion.savepromo'";
            $mark_student .= 'jQuery("#saving_"+ order).html("Saving ...");';
					
            $mark_student .= 'jQuery.post( '.$url.',{sid:sid,newroll:newroll,newclass:newclass,newdivision:newdivision,newsection:newsection,newyear:newyear,exit_id:exit_id}, function(data){';
            $mark_student .= 'if(data){ var JSONObject = JSON.parse(data);console.log(JSONObject);  jQuery("#saving_"+ order).html(JSONObject[0]);jQuery("#promo_id_"+ order).val(JSONObject[1]);}';
            $mark_student .= '});';
            $mark_student .= '}';
			
			     //function call
			     $s =0;
			     foreach ($rows as $row_s) {
			     $s++;
			     $mark_student .= 'jQuery( "#button_'.$s.'" ).click(function() {';
			     $mark_student .= 'promoSaving(jQuery("#sid_'.$s.'").val(),jQuery("#new_roll_'.$s.'").val(),jQuery("#class_'.$s.'").val(),jQuery("#division_'.$s.'").val(),jQuery("#section_'.$s.'").val(),jQuery("#year_'.$s.'").val(), jQuery("#exit_id_'.$s.'").val(),'.$s.')';
			     $mark_student .= '});';
			     }
					 
            $mark_student .= '});';
			$mark_student .= '</script>';
			
            $i =0;
            foreach ($rows as $row) {
            $i++;
			 
            $next_year = (($year)+1);
            $old_year = $row->year;
            
            // Exit Data
            $exit_id = $this->getExitData('id', $next_year, $row->sid);
               
            $old_roll = $this->getPromoData('roll','year','class','division',$year, $cid, $division_id, $row->sid);
               
            $exit_class = $this->getExitData('class', $next_year, $row->sid);
            $exit_division = $this->getExitData('division', $next_year, $row->sid);
            $exit_section = $this->getExitData('section', $next_year, $row->sid);
            $exit_year = $this->getExitData('year', $next_year, $row->sid);
            $exit_roll = $this->getExitData('roll', $next_year, $row->sid);
			
            // Get Class List
            $class_id_name ='class_'.$i;
            $class_list = $this->getclassList($exit_class, $class_id_name);
			 
            // Get Division List
            $division_id_name ='division_'.$i;
            $division_list = $this->getdivisionList($exit_division , $division_id_name);
			 
            // Get Section List
            $section_id_name ='section_'.$i;
            $section_list = $this->getsectionList($exit_section, $section_id_name);
			 
            // Get Year List
                /*
            $year_list ='<select id="year_'.$i.'" name="year_'.$i.'" required="required">';
                for($y = 2015; $y <= 2050; $y++) {
				    if(!empty($exit_year)){$isCurrentY = ($y == intVal($exit_year)) ? 'true': 'false';
                                           }else{
										   $isCurrentY = ($y == intVal(date("Y"))) ? 'true': 'false';
										   }
				    if($isCurrentY=="true"){ $selected = 'selected="selected"'; }else{ echo $selected = ''; } 
			        $year_list .='<option value="'.$y.'" '.$selected.' >'.$y.'</option>';
				}  
			$year_list .='</select>';
            */
               
            if(!empty($exit_year)){
                $CurrentY_id = $exit_year;
            }else{
				$isCurrentY =  intVal(date("Y"));
                $CurrentY_id = SmsHelper::getYear('id', 'year', $isCurrentY);
			}
            
            $year_id="_".$i;
            $year_list = SmsHelper::getyearList($CurrentY_id, $year_id);
                
			//Hidden Value for exit data
            $mark_student .= '<input type="hidden" id="exit_id_'.$i.'" name="exit_id_'.$i.'" value="'.$exit_id.'" />';
            $mark_student .= '<input type="hidden" id="sid_'.$i.'" name="student_id" value="'.$row->sid.'" />';
            
			$mark_student .= '<tr>';
            $mark_student .= '<td style="width: 6%;">'.$row->roll.'</td>';
            $mark_student .= '<td style="text-align: left;width:15%;" >'.$this->getStudentName($row->sid).'</td>';
            $mark_student .= '<td style="width: 10%;text-align: center;" >';
            $mark_student .= '<input type="text" class="" style="width: 80px;" id="new_roll_'.$i.'" name="roll" value="'.$exit_roll.'"  />';
            $mark_student .= '</td>';
            $mark_student .= '<td style="width: 10%;text-align: center;" >'.$class_list.'</td>';
            $mark_student .= '<td style="width: 10%;text-align: center;" >'.$division_list.'</td>';
            $mark_student .= '<td style="width: 10%;text-align: center;" >'.$section_list.'</td>';
            $mark_student .= '<td style="width: 9%;text-align: center;" >'.$year_list.'</td>';
            $mark_student .= '<td style="width: 15%;text-align: center;" >';
            $mark_student .= '<div id="saving_'.$i.'"></div>';
            $mark_student .= '<input type="button" name="" id="button_'.$i.'" class="btn btn-success" style="margin-left: 10px;" value="Save" />';
            $mark_student .= '</td>';
            $mark_student .= '</tr>';
					
            } // End Foreach
				
            $mark_student .= '</table>';
			
		if(!empty($rows)){
	        return $mark_student;
		}else{
			return 0;
		}
	}
	
	
	
	/**
	** Get save promotion data
	**/
	function savepromo( $sid, $new_year, $new_class, $new_division, $new_section, $new_roll, $exit_id ){
	   
        if(empty($exit_id)){
            $exit_id_checked = $this->getPromoData('id','year','class','division',$new_year, $new_class, $new_division, $sid);
            if(empty($exit_id_checked)){
                $exit_id = $exit_id;
            }else{
                $exit_id = $exit_id_checked;
            }
        }else{
            $exit_id = $exit_id;
        }
        
        
	    $table = $this->getTable('promo');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if(!empty($exit_id)){$table->id = $exit_id;}
		$table->sid = $sid;
		$table->class = $new_class;
		$table->roll = $new_roll;
		$table->section = $new_section;
		$table->division = $new_division;
		$table->year = $new_year;
       
        
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
        
        // update student table
        $object = new stdClass();
        $object->id = $sid;
        $object->class = $new_class;
        $object->roll = $new_roll;
        $object->division = $new_division;
        $object->section = $new_section;
        $object->year = $new_year;
        $result = JFactory::getDbo()->updateObject('#__sms_students', $object, 'id');
        
		return $id;
	}
	
	
}
