<?php
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');

// Get SMS Config Info
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');

$header_height = $params->get('header_height');
$footer_height = $params->get('footer_height');

// Get Student Info
if(!empty($this->students->id)){$id = $this->students->id;}else {$id="";}
if(!empty($this->students->name)){$name = $this->students->name;}else {$name="";}
if(!empty($this->students->class)){$class = $this->students->class;}else {$class="";}
if(!empty($this->students->roll)){$roll = $this->students->roll;}else {$roll="";}
if(!empty($this->students->division)){$division = $this->students->division;}else {$division="";}
if(!empty($this->students->section)){$section = $this->students->section;}else {$section="";}
if(!empty($this->students->year)){$year = $this->students->year;}else {$year="";}

$academic_year = SmsHelper::getAcademicYear($year);
$student_id = $id;

// Get CSS Code
$style ='<style type="text/css">
#admin-table tr td{font-size: 12px;padding: 5px 2px;text-align: center;}
#admin-table tr th{font-size: 12px;padding: 5px 2px;}
</style>';

//Set Style
$attendance_pdf = $style;

// Get Pad Header Info
$pad_title = JText::_('LABEL_STUDENT_ATTENDANCE_REPORT').' - '.$academic_year;
$padheader = SmsHelper::padHeader($pad_title, $class, '1');   
$paddfooter = SmsHelper::padFooter();

// Set student info
$attendance_pdf .='<table  class="admin-table" id="admin-table2" style="width: 100%;margin-top: 10px;margin-bottom: 20px;" align="center" >';
$attendance_pdf .='<tr>';
$attendance_pdf .='<td style="text-align: left;"><b>'.JText::_('LABEL_STUDENT_NAME').':</b> '.$name.'</td>';
$attendance_pdf .='<td style="text-align: right;"> <b>'.JText::_('LABEL_STUDENT_ROLL').': </b>'.$roll.'</td>';
$attendance_pdf .='</tr>';
$attendance_pdf .='</table>';

// Set Attendance Data				 
$months = array( JText::_('COM_SMS_MONTH_JANUARY'), JText::_('COM_SMS_MONTH_FEBRUARY'), JText::_('COM_SMS_MONTH_MARCH'), JText::_('COM_SMS_MONTH_APRIL'),  JText::_('COM_SMS_MONTH_MAY'), JText::_('COM_SMS_MONTH_JUNE'), JText::_('COM_SMS_MONTH_JULY'), JText::_('COM_SMS_MONTH_AUGUST'), JText::_('COM_SMS_MONTH_SEPTEMBER'),JText::_('COM_SMS_MONTH_OCTOBER'), JText::_('COM_SMS_MONTH_NOVEMBER'),JText::_('COM_SMS_MONTH_DECEMBER'),);
$i = 0;
foreach($months as $month){
  $i++;
  $d=cal_days_in_month(CAL_GREGORIAN,$i,$academic_year);
  $attendance_pdf .= SmsHelper::DisplayAttendance($month, $academic_year, $i, $student_id, $class, $section,  $d).'</br>';
}
	 
// Start PDF Genareator
$file_name ='Attendance Report_'.$academic_year.'_Class_'.SmsHelper::getClassname($class).'_roll_'.$roll;
$file_name_clean = str_replace(" ", "_", $file_name);
$file_name_clean = strtolower($file_name_clean);

$pdf_ready ='
<style>
  html { margin: '.$header_height.' 0px '.$footer_height.' 0px; padding: .0in;}
  #header,#footer { width: 100%; text-align: center; position: fixed;}
  #header {  top: -'.$header_height.';}
  #footer {  bottom: 0px;}
  .pagenum:before { content: counter(page);}
</style>

<div id="header">'.$padheader.'</div>
<div class="body">'.$attendance_pdf.'</div>
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
						
				