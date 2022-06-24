<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidation');
$model = $this->getModel();
$db = JFactory::getDBO();

//GET SCHOOLS DATA
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');

$header_height = $params->get('header_height');
$footer_height = $params->get('footer_height');

$user		= JFactory::getUser();
$uid =$user->get( 'id' );

 
// Get Style code
$style ='<style type="text/css">
	.odd-1{background: #eaf9f9;border: 1px solid #7cd5d5 !important;}
	.odd-2{background: #ccffcc;border: 1px solid #5ead5e !important;}

	.fail {color: red;}
	.abb-value {
	    display: block;
	    border: medium none !important;
	    line-height: 32px !important;
	    margin: 0;
	    position: absolute;
	    min-width: 40px;
	 }

	.bn-1 {border-bottom: 1px solid #eaf9f9 !important;}
	.bn-2 {border-bottom: 1px solid #ccffcc  !important;}

	.oddhead-1{background: #bbe8e8 !important;border: 1px solid #7cd5d5 !important;}
	.oddhead-2{background: #baefba !important;border: 1px solid #5ead5e !important;}

	.rf {width: 45px;}
	.rfd {width: 45px;}
	.mark-table {font-size: 15px;}
	.mark-table th {line-height: 14px;}
	.mark-table td {line-height: 14px;font-size: 15px; padding: 0;}
	.information-div h3,
	.information-div p {text-align: center;}
	.student_info_table {font-size: 16px;font-style: italic;line-height: 30px;}
	#comment {box-shadow: none; border-radius: 0px;font-style: italic;}
	
    
</style>';

    //COMMON VARIABLE
    $db = JFactory::getDBO();
    $total_grade_point	=	0;
    $total_marks		=	0;
    $total_subjects		=	0;
    
    $class = $this->class;
    $roll = $this->roll;
    $exam_id = $this->exam_id;
    $exam_name = SmsHelper::selectSingleData('name', 'sms_exams', 'id', $exam_id);

    $student_name = SmsHelper::getStudentData('name', $class, $roll);

    $comment = $model->getComment( $class, $roll, $exam_id,  $uid);
        
	//Get grade system from class
	$grade_system_id = $model->getGradeSystem($class);
														
	//get grade list 
	$grade_items = $model->getGradeList($grade_system_id);
	$grade_rows = $grade_items;
				
    //GET SUBJECT LIST
	$query_subject_list = "SELECT subjects FROM `#__sms_class` WHERE id = '".$class."' ";
	$db->setQuery($query_subject_list);
	$subject_list_data = $db->loadResult();
	$subject_list = explode(",", $subject_list_data);
	$total_subject = count($subject_list);
				
	// Get Pad Header Info
	$pad_title = JText::_('LABEL_STUDENT_RESULT_CAPTION');
    $padheader = SmsHelper::padHeader($pad_title, $class, '1');		
    $paddfooter = SmsHelper::padFooter();
				
	//Student Information
    $student_info = '<p style="text-align:center;margin-top:-15px;"><b> '.JText::_('DEFAULT_EXAM').': '.$exam_name.'</b></p>'; 
	$student_info .= '<table cellpadding="0" cellspacing="0" width="100%" class="student_info_table" id="admin-table" style="border: 0px;margin: 0px 0;" >';
	$student_info .='<tr>';
    $student_info .='<td style="text-align: left;border: 0px;padding-left:0;" width="20%" ><b> '.JText::_('LABEL_STUDENT_NAME').': </b>'.$student_name.' </td>';
	$student_info .='<td style="text-align: right;border: 0px;" width="20%" ><b> '.JText::_('LABEL_STUDENT_ROLL').':</b> '.$this->roll.'</td>';
	$student_info .='</tr>';   
	$student_info .='</table>';

    // Get Pad Body
	$result_display ='<div class="padd-body" style="padding: 10px;">';
	$result_display .= $student_info;
	$result_display .= '<table cellpadding="0" cellspacing="0" width="100%" class="mark-table " id="admin-table" style="border: 0px;" >';
	$result_display .= '<tr>'; 
	$result_display .= '<td style="border: 0px;padding-bottom: 2px; padding:0;">'; 
	
	$result_display .= '<table cellpadding="0" cellspacing="0" width="100%" class="mark-table" id="admin-table"  >';
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
			    $subject_name = $model->getSubjectname($subject);
			    $marks = $model->getMark('marks', $exam_id, $class, $subject, $roll);
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
				$result_display .= '<td class="text-left">'.$subject_name.'</td>';
				$result_display .= '<td class="text-center">'.$marks.'</td>';
				$result_display .= '<td class="text-center">'.$gpa_ok.'</td>';
				$result_display .= '<td class="text-center">'.$gp_comment.'</td>';
				$result_display .= '</tr>'; 
			}
			$result_display .= '</table>';  
					
	//Resultsheet Footer
	$result_display .= '<table cellpadding="0" cellspacing="0" width="100%" class=" none-border-table"  style="border: 0px;margin: 0px 0;" >';
	$result_display .= '<tr>'; 
	$result_display .= '<td class="text-left" style="padding-left:0;padding-top: 15px;"><b> '.JText::_('LABEL_STUDENT_RESULT_TOTAL_MARK').': '.$tearm_total_mark.'</b></td>';
	
	//calculate Tearm GPA
	$total_subject = count($subject_list);
	$tearm_gp = round($tearm_total_gp / $total_subject);
	
	$result_display .= '<td class="text-center" style="padding-top: 15px;text-align:right;"><b> '.JText::_('LABEL_STUDENT_RESULT_GPA').' : '.$tearm_gp.'</b></td>';
	$result_display .= '</tr>'; 
	$result_display .= '</table>';  
	
	$result_display .= '</td>';
	$result_display .= '</tr>'; 
			
	$result_display .= '</table>';  
	$result_display .= '</div>';  

	$result_display .= '<div  style="padding: 10px;">';
	$result_display .= '<p style="margin-top: 10px;margin-bottom:0;"><b>'.JText::_('LABEL_STUDENT_RESULT_TEACHER_COMMENT').':</b></p>';
	$result_display .= '<p style="font-style:italic;">'.$comment.'</p>';
    $result_display .= '</div>';

    			
// Start PDF Genareator
$file_name ='Student Marksheet_Class_'.SmsHelper::getClassname($class).'_roll_'.$roll;
$file_name_clean = str_replace(" ", "_", $file_name);
$file_name_clean = strtolower($file_name_clean);

$pdf_ready ='
    <style>
    html { margin: '.$header_height.' 0px '.$footer_height.' 0px; padding: .0in;}
    #header,#footer { width: 100%; text-align: center; position: fixed;}
    #header {  top: -'.$header_height.';}
    #footer {  bottom: 0%;}
    .pagenum:before { content: counter(page);}
    </style>
  
    <div id="header">'.$padheader.'</div>
    <div class="body">'.$result_display.'</div>
    <div id="footer">'.$paddfooter.'</div>';

 $html = SmsHelper::buildPDFHTML($pdf_ready);
 
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
	
	
 



	
	

