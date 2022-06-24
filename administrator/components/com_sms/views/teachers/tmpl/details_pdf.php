<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

//GET SCHOOLS DATA
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');

//Data Collect
if(!empty($this->teacher->id)){$id = $this->teacher->id;}else {$id="";}
if(!empty($this->teacher->user_id)){$user_id = $this->teacher->user_id;}else {$user_id="";}
if(!empty($this->teacher->name)){$name = $this->teacher->name;}else {$name="";}

if(!empty($this->teacher->class)){$class = $this->teacher->class;}else {$class="";}
if(!empty($this->teacher->division)){$division = $this->teacher->division;}else {$division="";}
if(!empty($this->teacher->section)){$section = $this->teacher->section;}else {$section="";}
if(!empty($this->teacher->subject)){$subject = $this->teacher->subject;}else {$subject="";}

if(!empty($this->teacher->designation)){$designation = $this->teacher->designation;}else {$designation="";}

if(!empty($this->teacher->photo)){
	$photo = $this->teacher->photo;
	$path = "../components/com_sms/photo/teachers/";
}else {
	$path = "../components/com_sms/photo/";
	$photo="photo.png";
}
								
        $pad_title = JText::_('LABEL_TEACHER_DETAILS');
		$student_info ='';	
    
        // Get Pad Header Info if show print button put blank. 0 = Yes | 1 = No
        $student_info .= SmsHelper::padHeader($pad_title, '', '1');		
    
        // Get Pad Body
        $student_info .='<div class="padd-body" style="padding: 10px;">';
        
			  $student_info .='<table cellpadding="0" cellspacing="0" width="100%" class="" id="layout-table" style="border: 0px;margin: 0px 0;" >';
				$student_info .='<tr>';
			      
				    //Student Academic details
				    $student_info .='<td style="text-align: left;border: 0px;" width="70%" >';
				    $student_info .='<h4 style="border: 0px 0;margin: 20px 0 0px 0;">'.JText::_('TAB_TEACHER_ACADEMIC_INFO').'</h4>';
					 $student_info .= '<table cellpadding="0" cellspacing="0"  width="100%" class="mark-table" id="admin-table" style="border: 0px;margin: 10px 0 10px 0;" >';
					 $student_info .='<tr><td> '.JText::_('LABEL_TEACHER_DESIGNATION').':</td> <td> '.$designation.'</td></tr>'; 
					 // Set Teacher Subjects
                    $student_info .='<tr><td> '.JText::_('LABEL_TEACHER_SUBJECT').':</td> <td> ';
     
                                    $subject_ids = explode(",", $subject);
				                    $count_subject = count($subject_ids);
                                    foreach ($subject_ids as $s=> $subject_id) {
                                    $student_info .= SmsHelper::getSubjectname($subject_id);
                        
				                    if ($s < ($count_subject - 1)) {
                                    $student_info .= ', ';
                                    }
                                    }
                     $student_info .='</td></tr>';  
     
                     // Set Teacher Class
					 $student_info .='<tr><td> '.JText::_('LABEL_TEACHER_CLASS').':</td> <td> ';
                                    $class_ids = explode(",", $class);
				                    $count_class = count($class_ids);
                                    foreach ($class_ids as $c=> $class_id) {
                                    $student_info .= SmsHelper::getClassname($class_id);
                        
				                    if ($c < ($count_class - 1)) {
                                    $student_info .= ', ';
                                    }
                                    }
                    $student_info .='</td></tr>'; 
     
                     // Set Teacher Section
					 $student_info .='<tr><td> '.JText::_('LABEL_TEACHER_SECTION').':</td> <td> ';
                                    $section_ids = explode(",", $section);
				                    $count_section = count($section_ids);
                                    foreach ($section_ids as $sc=> $section_id) {
                                    $student_info .= SmsHelper::getSectionname($section_id);
                        
				                    if ($sc < ($count_section - 1)) {
                                    $student_info .= ', ';
                                    }
                                    }
                     $student_info .= '</td></tr>';  
					 $student_info .='</table>';
				    $student_info .=' </td>';
				
				    //Student Photo
				    $student_info .='<td style="text-align: center;border: 0px; " width="30%"  ><span style="display: block;float: right;height: auto;width: 100%;margin: 48px 0 0 0;"><img src="'.$path.$photo.'" alt="" style="width: 150px;height: 165px;margin-top: 0px;" /> <p>'.$name.'</p></span></td>';
				    
				
				$student_info .='</tr>';   
				$student_info .='</table>';
				
				$student_info .='<h4 style="border: 0px 0;margin: 40px 0 10px 0;">'.JText::_('LABEL_TEACHER_INFORMATION').'</h4>';
				   $student_info .= '<table  width="100%" cellpadding="0" cellspacing="0" class="mark-table" id="admin-table" style="border: 0px;margin: 0px 0;" >';
					 $student_info .='<tr><td> '.JText::_('LABEL_TEACHER_NAME').':</td> <td> '.$name.'</td></tr>';  
					 //Field Builder
				   $sid = SmsHelper::getFieldSectionID('teacher');
				   $fields = SmsHelper::getFieldList($sid);
				   $total_field = count($fields);
				 
				   $f=0;
				   foreach($fields as $field){
				   $f++;
				   $fid = $field->id;
				   $sid = $sid;
				   $panel_id = $id;
				   $student_info .= SmsHelper::fieldBiodata($fid, $sid, $panel_id, $field->field_name,$field->type,$field->biodata);
				   }
					 $student_info .='</table>';
        $student_info .='</div>';
        // End Pad Body
    
        // Get Pad Footer Info
	    $student_info .= SmsHelper::padFooter();			
			
 // Start PDF Genareator
 $file_name ='Teacher Detail_Class_'.SmsHelper::getClassname($class).'_Name_'.$name;
 $file_name_clean = str_replace(" ", "_", $file_name);
 $file_name_clean = strtolower($file_name_clean);
 
 $html = SmsHelper::buildPDFHTML($student_info);
 require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/pdf/dompdf/autoload.inc.php' );
 use Dompdf\Dompdf;
 $dompdf = new Dompdf();
 $dompdf->loadHtml($html);
 $pdf_orientation = $params->get('pdf_orientation');
 $pdf_paper_size = $params->get('pdf_paper_size');
 $dompdf->setPaper($pdf_paper_size, $pdf_orientation);
 $dompdf->render();
 $dompdf->stream($file_name_clean);
 exit;
 ?>

