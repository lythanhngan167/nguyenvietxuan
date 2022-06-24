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
 
	if(!empty($this->parent->id)){$id = $this->parent->id;}else {$id="";}
	if(!empty($this->parent->name)){$name = $this->parent->name;}else {$name="";}
	if(!empty($this->parent->roll)){$roll = $this->parent->roll;}else {$roll="";}
	
    // Parent Students
	if(!empty($this->parent->student_id)){
		$student_id = $this->parent->student_id; 
		$student_name = SmsHelper::getStudents($student_id, 'getStudentName');
	}else {
		$student_id=""; $student_name="";
	}
 
    // Parent Avatar 
    if(!empty($this->parent->photo)){
        $path = "components/com_sms/photo/parents/";
		$photo = $this->parent->photo;
		$img_src = $path.$photo;
	}else {
		$path = "components/com_sms/photo/";
		$photo="photo.png";
		$img_src = $path.$photo;
	}

    // Display Profile info
    $display_profile = '<div class="avator profile" style="background: #f5f5f5;padding: 20px 0;">
                        <img src="'.$img_src.'" alt="'.$name.'" width="150px" height="150px" />
                        </div>';

    $display_profile .= '<table class="admin-table profile" id="admin-table"  align="left">';
	$display_profile .= '<tr>
	                    <td class="first"> '.JText::_('LABEL_PARENT_NAME').': </td> 
	                    <td class="secound">'.$name.'</td>
	                    </tr>';

	$display_profile .= '<tr>
	                    <td class="first"> '.JText::_('LABEL_PARENT_STUDENT_NAME').': </td> 
	                    <td class="secound">'.$student_name.'</td>
	                    </tr>';
			
	//Field Builder
	$sid = SmsHelper::getFieldSectionID('parent'); 
	$fields = SmsHelper::getFieldList($sid);
	$total_field = count($fields);
	$f=0;
	foreach($fields as $field){
		$f++;
		$fid = $field->id;
		$sid = $sid;
		$panel_id = $id;
		$display_profile .= SmsHelper::fieldBiodata($fid, $sid, $panel_id, $field->field_name,$field->type,$field->biodata);
	} // End foreach
			
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

		    <!-- Parent Profile -->
		    <div class="col-xs-12 col-md-9">
		        <div class="welcom_div">
			        <?php echo $display_profile; ?>
			    </div>
		    </div>
					
	    </div>
	</div>
</div>

