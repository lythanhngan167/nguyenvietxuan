
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
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');

$header_height = $params->get('header_height');
$footer_height = $params->get('footer_height');
        
//Collect Student Data
if(!empty($this->students->id)){$id = $this->students->id;}else {$id="";}
if(!empty($this->students->user_id)){$user_id = $this->students->user_id;}else {$user_id="";}
if(!empty($this->students->name)){$name = $this->students->name;}else {$name="";}
if(!empty($this->students->chabima)){$chabima = $this->students->chabima;}else {$chabima="";}
if(!empty($this->students->email)){$email = $this->students->email;}else {$email="";}
if(!empty($this->students->churanita)){$churanita = $this->students->churanita;}else {$churanita="";}

if(!empty($this->students->class)){$class = $this->students->class;}else {$class="";}
if(!empty($this->students->roll)){$roll = $this->students->roll;}else {$roll="";}
if(!empty($this->students->division)){$division = $this->students->division;}else {$division="";}
if(!empty($this->students->section)){$section = $this->students->section;}else {$section="";}
if(!empty($this->students->year)){$year = $this->students->year;}else {$year="";}
 
//Student Photo
if(!empty($this->students->photo)){
	$path = "../components/com_sms/photo/students/";
	$photo = $this->students->photo;
}else {
	$path = "../components/com_sms/photo/";
	$photo="photo.png";
}
 
    $pad_title = JText::_('LABEL_STUDENT_DETAILS');
    $padheader = SmsHelper::padHeader($pad_title, $class, '1');		
    $paddfooter = SmsHelper::padFooter();

		
    // Get Pad Body
	$student_info ='';	
    $student_info .='<div class="padd-body" style="padding: 10px;">';
    $student_info .='<table cellpadding="0" cellspacing="0" width="100%" class="" id="layout-table" style="border: 0px;margin: 0px 0;" >';
		$student_info .='<tr>';
			      
			//Student Academic details
			$student_info .='<td style="text-align: left;border: 0px;" width="70%" >';
			$student_info .='<h4 style="border: 0px 0;margin: 20px 0 0px 0;">'.JText::_('LABEL_STUDENT_ACADEMIC_INFO').'</h4>';
			$student_info .= '<table cellpadding="0" cellspacing="0"  width="100%" class="mark-table" id="admin-table"  style="border: 0px;margin: 20px 0;" >';
			$student_info .='<tr><td width="30%">'.JText::_('LABEL_STUDENT_ROLL').':</td> <td> '.$roll.'</td></tr>';  
			$student_info .='<tr><td>'.JText::_('LABEL_STUDENT_CLASS').':</td> <td> '.SmsHelper::getClassname($class).'</td></tr>'; 
			$student_info .='<tr><td> '.JText::_('LABEL_STUDENT_SECTION').':</td> <td> '.SmsHelper::getSectionname($section).'</td></tr>'; 
			$student_info .='<tr><td> '.JText::_('LABEL_STUDENT_DIVISION').':</td> <td> '.SmsHelper::getDivisionname($division).'</td></tr>';  
			$student_info .='<tr><td> '.JText::_('LABEL_STUDENT_YEAR').':</td> <td> '.SmsHelper::getAcademicYear($year).'</td></tr>'; 
			$student_info .='</table>';
			$student_info .=' </td>';
				
		    //Student Photo
		    $student_info .='<td style="text-align: center;border: 0px;padding: 0px 0;" width="30%" ><span style="display: block;float: right;height: auto;width: 100%;margin: 48px 0 0 0;"><img src="'.$path.$photo.'" alt="" style="width: 150px;height: 165px;" /> <p>'.$name.'</p></span></td>';
		
		$student_info .='</tr>';   
	$student_info .='</table>';
				
	//Student Profile Label
	$student_info .='<h4 style="border: 20px 0;margin: 40px 0 30px 0;">'.JText::_('LABEL_STUDENT_INFORMATION').'</h4>';
	   
	//Student Details Table
	$student_info .= '<table cellpadding="0" cellspacing="0" width="100%" class="mark-table" id="admin-table" style="border: 0px;margin: 0px 0;" >';
	$student_info .='<tr><td width="30%">'.JText::_('LABEL_STUDENT_NAME').':</td> <td> '.$name.'</td></tr>';  
				
		//Field Builder
		$sid = SmsHelper::getFieldSectionID('student');
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
    
// Start PDF Genareator
$file_name ='Student Detail_Class_'.SmsHelper::getClassname($class).'_roll_'.$this->students->roll.'_Name_'.$name;
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
    <div class="body">'.$student_info.'</div>
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
