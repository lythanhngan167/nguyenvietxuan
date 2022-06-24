<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');

// Get SMS config info
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');

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
	         
?>

<script type="text/javascript">
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	document.getElementById("print").style.visibility = "hidden";
	window.print();
	document.body.innerHTML = originalContents;
	document.location.reload();
}
</script>

<style >
    .container-main {padding: 50px 100px;background: #666;}
    #printableArea {background: #fff;}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=students');?>" method="post" name="adminForm" id="adminForm">
	
	<div id="printableArea" >
		<div  style="padding: 0px;">
	    <?php 
	    // Get Pad Header Info
		$pad_title = JText::_('LABEL_STUDENT_ATTENDANCE_REPORT').' - '.$academic_year;
	    $padheader = SmsHelper::padHeader($pad_title, $class);	

	    // Get Header
	    $attent_html = $padheader;
	    
		// Get Body Content
		$attent_html .='<div class="row-fluid">';
	    $attent_html .='<div class="span12">';
		$attent_html .='<table  class="admin-table" id="admin-table2" style="width: 100%;margin-top: 10px;margin-bottom: 20px;" align="center" >';
		$attent_html .='<tr>';
		$attent_html .='<td style="text-align: left;"><b>'.JText::_('LABEL_STUDENT_NAME').':</b> '.$name.'</td>';
		$attent_html .='<td style="text-align: right;"> <b>'.JText::_('LABEL_STUDENT_ROLL').': </b>'.$roll.'</td>';
		$attent_html .='</tr>';
		$attent_html .='</table>';
						 
			 $months = array( JText::_('COM_SMS_MONTH_JANUARY'), JText::_('COM_SMS_MONTH_FEBRUARY'), JText::_('COM_SMS_MONTH_MARCH'), JText::_('COM_SMS_MONTH_APRIL'),  JText::_('COM_SMS_MONTH_MAY'), JText::_('COM_SMS_MONTH_JUNE'), JText::_('COM_SMS_MONTH_JULY'), JText::_('COM_SMS_MONTH_AUGUST'), JText::_('COM_SMS_MONTH_SEPTEMBER'),JText::_('COM_SMS_MONTH_OCTOBER'), JText::_('COM_SMS_MONTH_NOVEMBER'),JText::_('COM_SMS_MONTH_DECEMBER'),);
			 $i = 0;
			 foreach($months as $month){
			 $i++;
			 $d=cal_days_in_month(CAL_GREGORIAN,$i,$academic_year);
			 $attent_html .= SmsHelper::DisplayAttendance($month, $academic_year, $i, $student_id, $class, $section,  $d).'</br>';
			 }
					
		$attent_html .='</div>';
	    $attent_html .='</div>';

	    // Get footer
	    $padfooter = SmsHelper::padFooter();	
	    $attent_html .= $padfooter;

	    echo $attent_html;
	    ?>

		</div>
	</div>
	
	<input type="hidden" name="cid" value="<?php echo $id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="students" />
	<?php echo JHtml::_('form.token'); ?>
</form>
