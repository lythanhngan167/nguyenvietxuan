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

//Data Collect
if(!empty($this->teacher->id)){$id = $this->teacher->id;}else {$id="";}
if(!empty($this->teacher->user_id)){$user_id = $this->teacher->user_id;}else {$user_id="";}
if(!empty($this->teacher->name)){$name = $this->teacher->name;}else {$name="";}
if(!empty($this->teacher->designation)){$designation = $this->teacher->designation;}else {$designation="";}
if(!empty($this->teacher->class)){$class = $this->teacher->class;}else {$class="";}
if(!empty($this->teacher->division)){$division = $this->teacher->division;}else {$division="";}
if(!empty($this->teacher->section)){$section = $this->teacher->section;}else {$section="";}
if(!empty($this->teacher->subject)){$subject = $this->teacher->subject;}else {$subject="";}

if(!empty($this->teacher->photo)){
	$path = "components/com_sms/photo/teachers/";
	$photo = $this->teacher->photo;
	$img_src = $path.$photo;
}else {
	$path = "components/com_sms/photo/";
	$photo="photo.png";
	$img_src = $path.$photo;
}
    $display_profile = '<div class="avator" style="background: #f5f5f5;padding: 20px 0;">
                        <img src="'.$img_src.'" alt="'.$name.'" width="150px" height="150px" />
                        </div>';
     
		 
	$display_profile .= '<table class="admin-table profile" id="admin-table"  align="left">';
	//$display_profile .= '<caption>'.JText::_('TAB_TEACHER_ACADEMIC_INFO').'</caption>';
	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_TEACHER_NAME').': </td> 
	                    <td class="secound">'.$name.'</td>
	                    </tr>';
	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_TEACHER_DESIGNATION').': </td> 
	                    <td class="secound">'.$designation.'</td>
	                    </tr>';

	// Display Subject
	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_TEACHER_SUBJECT').': </td> 
	                    <td class="secound">';
					    $subject_ids = explode(",", $subject);
					    $count_subject = count($subject_ids);
					    foreach ($subject_ids as $sub=> $subject_id) {
			                $display_profile .= SmsHelper::getSubjectname($subject_id);
					        if ($sub < ($count_subject - 1)) {
				                $display_profile .= ', ';
			                }
			            }
	$display_profile .='</td></tr>';

    // Display Class
	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_TEACHER_CLASS').': </td> 
	                    <td class="secound">';
				        $class_ids = explode(",", $class);
					    $count_class = count($class_ids);
					    foreach ($class_ids as $c=> $class_id) {
			                $display_profile .= SmsHelper::getClassname($class_id);
					        if ($c < ($count_class - 1)) {
				                $display_profile .= ', ';
			                }
			            }
	$display_profile .='</td></tr>';

	// Display Section
	$display_profile .= '<tr>
	                    <td class="first" > '.JText::_('LABEL_TEACHER_SECTION').': </td> 
	                    <td class="secound">';
				        $section_ids = explode(",", $section);
					    $count_section = count($section_ids);
					    foreach ($section_ids as $s=> $section_id) {
			                $display_profile .= SmsHelper::getSectionname($section_id);
					        if ($s < ($count_section - 1)) {
				                $display_profile .= ', ';
			                }
			            }
	$display_profile .='</td></tr>';
    $display_profile .= '</table>';
		 
	$display_profile .= '<table class="admin-table profile" id="admin-table"  align="left">';
	//$display_profile .= '<caption>'.JText::_('TAB_TEACHER_PROFILE').'</caption>';
	$display_profile .= '<tr>
	                    <td class="first"> '.JText::_('LABEL_TEACHER_NAME').': </td> 
	                    <td class="secound">'.$name.'</td>
	                    </tr>';
			
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
		$display_profile .= SmsHelper::fieldBiodata($fid, $sid, $panel_id, $field->field_name,$field->type,$field->biodata);
	}
	$display_profile .= '</table>';
			
?>
<div id="com_sms" >
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-xs-12 col-md-3" id="sms_leftbar">
				<?php echo $this->smshelper->profile(); ?>
				<?php echo $this->sidebar; ?>
			</div>
		 
		    <div class="col-xs-12 col-md-9">
			    <div class="welcom_div">
			        <?php echo $display_profile; ?>
			    </div>
		    </div>	
	    </div>
	</div>
</div>
