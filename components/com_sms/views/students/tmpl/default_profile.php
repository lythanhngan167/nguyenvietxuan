<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::_('behavior.framework');
$model = $this->getModel();
	 
// Collect Student Data
if(!empty($this->students->id)){$id = $this->students->id;}else {$id="";}
if(!empty($this->students->user_id)){$user_id = $this->students->user_id;}else {$user_id="";}
if(!empty($this->students->name)){$name = $this->students->name;}else {$name="";}
 
// Collect Academic info
if(!empty($this->students->class)){$class = $this->students->class;}else {$class="";}
if(!empty($this->students->roll)){$roll = $this->students->roll;}else {$roll="";}
if(!empty($this->students->division)){$division = $this->students->division;}else {$division="";}
if(!empty($this->students->section)){$section = $this->students->section;}else {$section="";}
if(!empty($this->students->year)){$year = $this->students->year;}else {$year="";}
 
// Student Avator
if(!empty($this->students->photo)){
    $path = "components/com_sms/photo/students/";
	$photo = $this->students->photo;
	$img_src = $path.$photo;
}else {
	$path = "components/com_sms/photo/";
	$photo="photo.png";
	$img_src = $path.$photo;
}

	// Display Profile
	$display_profile = '<div class="avator profile" style="background: #f5f5f5;padding: 20px 0;">
	                    <img src="'.$img_src.'" alt="'.$name.'" width="150px" height="150px" />
	                    </div>';

	$display_profile .= '<table class="admin-table profile" id="admin-table"  align="left">';
	//$display_profile .= '<caption>'.JText::_('TAB_STUDENT_ACADEMIC_INFO').'</caption>';
	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_STUDENT_NAME').': </td> 
	                    <td class="secound">'.$name.'</td>
	                    </tr>';

	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_STUDENT_ROLL').': </td> 
	                    <td class="secound">'.$roll.'</td>
	                    </tr>';

	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_STUDENT_CLASS').': </td> 
	                    <td class="secound">'.SmsHelper::getClassname($class).'</td>
	                    </tr>';

	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_STUDENT_SECTION').': </td> 
	                    <td class="secound">'.SmsHelper::getSectionname($section).'</td>
	                    </tr>';

	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_STUDENT_DIVISION').': </td> 
	                    <td class="secound">'.SmsHelper::getDivisionname($division).'</td>
	                    </tr>';

	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_STUDENT_YEAR').': </td> 
	                    <td class="secound">'.SmsHelper::getAcademicYear($year).'</td>
	                    </tr>';
	$display_profile .= '</table>';

	$display_profile .= '<table class="admin-table profile" id="admin-table"  align="left">';
	//$display_profile .= '<caption>'.JText::_('TAB_STUDENT_PROFILE').'</caption>';
	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_STUDENT_NAME').': </td> 
	                    <td class="secound">'.$name.'</td>
	                    </tr>';
	
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
		$display_profile .= SmsHelper::fieldBiodata($fid, $sid, $panel_id, $field->field_name,$field->type,$field->biodata);
	} // End Foreach
	$display_profile .= '</table>';
			
?>

<div id="com_sms" >
	<div class="container-fluid">
	    <div class="row">
	        <!-- Sidebar -->
		    <div class="col-xs-12 col-md-3" id="sms_leftbar">
				<?php echo $this->smshelper->profile(); ?>
				<?php echo $this->sidebar; ?>
			</div>

		    <!-- Student Profile -->
		    <div class="col-xs-12 col-md-9">
		        <div class="welcom_div">
			        <?php echo $display_profile; ?>
			    </div>
			</div>	
	    </div>
	</div>
</div>

