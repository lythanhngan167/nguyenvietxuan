<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsModelResult extends JModelList
{
	
    function __construct()
    {
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
            ->select($db->quoteName(array('id', 'roll', 'name')))
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
            $student_array[] = array('value' => $row->roll, 'text' => JText::_(' '.$row->name));
            }else{
               	unset( $rows[ $key ] ); 
            }
        }
	    $student_list =  JHTML::_('select.genericList', $student_array, 'jform_roll', ' class="required  inputbox  "   ', 'value', 'text', '');
        return $student_list;
	}
	
	/**
	** Get Grade System
	**/
	function getGradeSystem($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('grade_system')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Grade List
	**/
	function getGradeList($cid){
	    $db = JFactory::getDBO();
        $query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_exams_grade'))
            ->where($db->quoteName('category') . ' = '. $db->quote($cid))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
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
	** Get Parent
	**/
	function getParent($id){
	    $db = JFactory::getDBO();
	    $query_result = "SELECT student_id FROM `#__sms_parents` WHERE id = '".$id."'";
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Student ID
	**/
	function getStudentID($id){
	    $db = JFactory::getDBO();
		$query_result = "SELECT id FROM `#__sms_students` WHERE user_id = '".$id."'";
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Student
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
	
	
	/**
	** Get Result
	**/
	function Result($exam_id, $class_id, $roll){
	     
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_sms');
		$schools_name = $params->get('schools_name');
		$schools_address = $params->get('schools_address');
		$schools_phone = $params->get('schools_phone');
		$schools_email = $params->get('schools_email');
		$schools_website = $params->get('schools_web');

		$user		= JFactory::getUser();
        $uid =$user->get( 'id' );
        $group_title =  SmsHelper::checkGroup($uid);

	    if(empty($class_id)){
	    	$class_id = SmsHelper::selectSingleData('class', 'sms_students', 'roll', $roll);
	    }
	
		//COMMON VARIABLE
        $db = JFactory::getDBO();
        $total_grade_point	=	0;
        $total_marks		=	0;
        $total_subjects		=	0;
        $class_id = $class_id;
        $roll_number = $roll;
		$exam_id = $exam_id;
				
		//GET Student ID
		$student_id = SmsHelper::selectSingleData('id', 'sms_students', 'roll', $roll);
		$student_name = SmsHelper::getStudentname($student_id);
				
		//Header Information
		$onclick_link ="'printableArea'";
        $header_con ='<p style="text-align: center;"><input type="button" id="print" onclick="printDiv('.$onclick_link.')" class="btn btn-small"  style="border: none;margin-left: 10px;" value="Print" /> </p>';
	  
			$header  = '<div class="information-div">';
			$header .= '<h3> '.$schools_name.'</h3>';
			$header .= '<p> Student Marksheet</p>';
			$header .= '<p> '.JText::_('COM_SMS_LABEL_CLASS').' - '.SmsHelper::getClassname($class_id).' </p>';
			$header .= $header_con;
			$header .= '</div>';
				
			//Student Information
			$student_info = '<table  width="100%" class="" id="result-table" style="border: 0px;margin: 0px 0;" >';
			$student_info .='<tr>';
			$student_info .='<td style="text-align: left;border: 0px;" width="20%" > '.$student_name.' </td>';
		    $student_info .='<td style="text-align: right;border: 0px;" width="20%" > '.JText::_('COM_SMS_LABEL_ROLL').': '.$roll_number.'</td>';
			$student_info .='</tr>';   
			$student_info .='</table>';
				
				
			$grade_system_id = $this->getGradeSystem($class_id);
																	
			//get grade list 
			$grade_items = $this->getGradeList($grade_system_id);
			$grade_rows = $grade_items;
				
            //GET SUBJECT LIST
			$query_subject_list = "SELECT subjects FROM `#__sms_class` WHERE id = '".$class_id."' ";
			$db->setQuery($query_subject_list);
			$subject_list_data = $db->loadResult();
			$subject_list = explode(",", $subject_list_data);
			$total_subject = count($subject_list);
				
			//GET EXAM NAME
			$query_exam_name = "SELECT name FROM `#__sms_exams` WHERE id = '".$exam_id."'";
			$db->setQuery($query_exam_name);
			$exam_name = $db->loadResult();
				
			//Result Display 
			$result_display = '<table  width="100%" class="mark-table " id="result-table" style="border: 0px;" >';
				
				$result_display .= '<tr>'; 
				$result_display .= '<td style="border: 0px;padding-bottom: 20px;">'; 
				$result_display .= '<p><b> '.JText::_('DEFAULT_EXAM').': '.$exam_name.'</b></p>'; 
					$result_display .= '<table  width="100%" class="mark-table" id="admin-table" >';
						//Head
						$result_display .= '<tr>'; 
						$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_SUBJECT').'</th>';
						$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_OBTAIN_MARK').'</th>';
						$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_GRADE').'</th>';
						$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_GRADE_COMMENT').'</th>';
						$result_display .= '</tr>'; 
						$tearm_total_mark=0;
						$tearm_total_gp =0;
						foreach ($subject_list as $j=>$subject) {
						    $subject_name = $this->getSubjectname($subject);
						    $marks = $this->getMark('marks', $exam_id, $class_id, $subject, $student_id);
						   
						    if(!empty($marks)){
						        $tearm_total_mark += $marks;
						    }

						    //grade system
						    $gp =0;
						    $gpa =0;
							$gpcomment =0;
						    foreach ($grade_rows as $grade_row) {
								if ($marks >= $grade_row->mark_from && $marks <= $grade_row->mark_upto){
                                    $gp = $grade_row->grade_point;
									$gpa = $grade_row->name;
									$gpcomment = $grade_row->comment;
                                }
							}
														
							//total tearm gp
							$tearm_total_gp += $gp;
							//ignore empty comment
							if(!empty($gpcomment)){$gp_comment = $gpcomment;}else{$gp_comment ='';}
							//ignore empty GPA
							if(!empty($gpa)){$gpa_ok = $gpa;}else{$gpa_ok ='';}
							
						$result_display .= '<tr>'; 
						$result_display .= '<td class="text-left" style="text-align:left;">'.$subject_name.'</td>';
						$result_display .= '<td class="text-center">'.$marks.'</td>';
						$result_display .= '<td class="text-center">'.$gpa_ok.'</td>';
						$result_display .= '<td class="text-center">'.$gp_comment.'</td>';
						$result_display .= '</tr>'; 

						} // end loop
					$result_display .= '</table>';  
					
					//Resultsheet Footer
					$result_display .= '<table  width="100%" class=" none-border-table"  style="border: 0px;margin: 0px 0;" >';
					$result_display .= '<tr>'; 
					$result_display .= '<td class="text-left" style="padding-left: 0;"><b> '.JText::_('LABEL_STUDENT_RESULT_TOTAL_MARK').': '.$tearm_total_mark.'</b></td>';
					
					//calculate Tearm GPA
					$total_subject = count($subject_list);
					$tearm_gp = round($tearm_total_gp / $total_subject);
					
					$result_display .= '<td class="text-center" style="text-align:right;"><b> '.JText::_('LABEL_STUDENT_RESULT_GPA').' : '.$tearm_gp.'</b></td>';
					$result_display .= '</tr>'; 
					$result_display .= '</table>';  
					
					$result_display .= '</td>';
					$result_display .= '</tr>'; 
					
				 
				$result_display .= '</table>';  

				$result_display .= '<div>'; 
				$comment = self::getComment( $class_id, $roll, $exam_id,  '');  
				$result_display .= '<p style="margin:0;"><b>'.JText::_('LABEL_STUDENT_RESULT_TEACHER_COMMENT').'</b></p>';  

				    if($group_title=="Teachers"){
                        
                        // Get Comment Form
                        $cooment_form ='<div id="comment_result">';
		                $cooment_form .='<textarea cols="" rows="" id="comment" style="width: 99%;height: 100px;">'.$comment.'</textarea>';
		                $cooment_form .='</div>';
		                $cooment_form .='<input type="hidden" name="class_id" id="class" value="'.$class_id.'" />';
		                $cooment_form .='<input type="hidden" name="roll_number" id="roll" value="'.$roll.'" />';
		                $cooment_form .='<input type="hidden" name="exam_id" id="eid" value="'.$exam_id.'" />';
		                $cooment_form .='<input type="hidden" name="teacher_id" id="tid" value="'.$uid.'" />';
		                $cooment_form .='<button id="save" class="btn btn-default" style="margin-top:10px;">Save</button>';

		                $loader_html = '<div class=\"loader\"></div>';
		                $cooment_form .='<script type="text/javascript">
										function savecomment(){
										var cid = jQuery("#class").val();
										var roll = jQuery("#roll").val();
										var eid = jQuery("#eid").val();
										var tid = jQuery("#tid").val();
										var comment = jQuery("#comment").val();
										jQuery("#comment_result").html("'.$loader_html.'");
											jQuery.post( "index.php?option=com_sms&task=result.savecomment",{cid:cid,roll:roll,eid:eid,tid:tid, comment:comment}, function(data){
											if(data){ jQuery("#comment_result").html(data); }
										});
										}		
										jQuery( "#save" ).click(function() { savecomment(); });	
										</script>';

                        $result_display .= $cooment_form;
				    }else{
				    	$result_display .= '<p><i>'.$comment.'</i></p>'; 
				    }
				
				$result_display .= '</div>'; 
				
				if(!empty($tearm_total_mark)){
				//$result_sheet = $header;
				//$result_sheet .= $student_info;
				$result_sheet = $result_display;
				}else{
				$result_sheet = 0;
				}
		return $result_sheet;
	}


	/**
	** Get Comment
	**/
	function getComment( $cid, $roll, $eid,  $tid){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('comments')))
            ->from($db->quoteName('#__sms_result_comments'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll))
			->where($db->quoteName('eid') . ' = '. $db->quote($eid))
			->where($db->quoteName('class') . ' = '. $db->quote($cid));
		
		$db->setQuery($query);
		$comment =  $db->loadResult();
        return $comment;
	}

	/**
	** Save Comment
	**/
	function savecomment( $cid, $roll, $eid,  $tid, $comment){
	    $db = JFactory::getDBO();
	    $query_check_comment = $db->getQuery(true);
		$query_check_comment
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_result_comments'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll))
			->where($db->quoteName('eid') . ' = '. $db->quote($eid))
			->where($db->quoteName('class') . ' = '. $db->quote($cid));
		$db->setQuery($query_check_comment);
		$comment_id =  $db->loadResult();
	
	    $table = $this->getTable('comment');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if(!empty($comment_id)){$table->id = $comment_id;}
		$table->roll = $roll;
		$table->class = $cid;
		$table->eid = $eid;
		$table->comments = $comment;
		$table->tid = $tid;
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	}

	
	/**
	** Get Mark
	**/
	function getMark($field, $exam_id, $class_id, $subject_id, $sid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array($field)))
            ->from($db->quoteName('#__sms_exams_mark'))
            ->where($db->quoteName('exam_id') . ' = '. $db->quote($exam_id))
			->where($db->quoteName('class_id') . ' = '. $db->quote($class_id))
			->where($db->quoteName('subject_id') . ' = '. $db->quote($subject_id))
			->where($db->quoteName('student_id') . ' = '. $db->quote($sid));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Subject Name
	**/
	function getSubjectname($id){
	    $db = JFactory::getDBO();
		$query_subject_name = "SELECT subject_name FROM `#__sms_subjects` WHERE id = '".$id."'";
		$db->setQuery($query_subject_name);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	** Exam List
	**/
	function getexamList(){
	    $db = JFactory::getDBO();
        $query = "SELECT id,name FROM `#__sms_exams` ";
        $query.=" ORDER BY id asc ";
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
		$exam =  JHTML::_('select.genericList', $exam_array, 'exam', ' class="required  inputbox  " required="required"  ', 'value', 'text', '');
        return $exam;
	}
	
	/**
	** Class List
	**/
	function getclassList(){
	    $db = JFactory::getDBO();
        $query = "SELECT id,class_name FROM `#__sms_class` WHERE published = 1";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $class_array = array();
        $onclick_manage_subject = "javascript: formget(this.form, 'index.php?option=com_sms&controller=marks&task=getsubjectlist&format=raw');";
				
		$class_array[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_CLASS'));
        foreach ($rows as $row) {
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
        }
		$class =  JHTML::_('select.genericList', $class_array, 'class', ' class="required  inputbox  " required="required"   ', 'value', 'text', '');
        return $class;
	}
	
}
