<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidator');
$model = $this->getModel();

// Get Student Data
if(!empty($this->students->id)){$id = $this->students->id;}else {$id="";}
if(!empty($this->students->user_id)){$user_id = $this->students->user_id;}else {$user_id="";}
if(!empty($this->students->name)){$name = $this->students->name;}else {$name="";}
if(!empty($this->students->chabima)){$chabima = $this->students->chabima;}else {$chabima="";}
if(!empty($this->students->email)){$email = $this->students->email;}else {$email="";}
if(!empty($this->students->churanita)){$churanita = $this->students->churanita;}else {$churanita="";}
if(!empty($this->students->class)){$class = $this->students->class;}else {$class="";}
if(!empty($this->students->roll)){$roll = $this->students->roll;}else {$roll="";}
if(!empty($this->students->division)){$division = $this->students->division;}else {$division="";}
if(!empty($this->students->year)){$year = $this->students->year;}else {$year="";}
if(!empty($this->students->section)){$section = $this->students->section;}else {$section="";}
//if(!empty($this->students->transport_id)){$transport_id = $this->students->transport_id;}else {$transport_id="";}
if(!empty($this->students->photo)){
    $path = "components/com_sms/photo/students/";
	$photo = $this->students->photo;
}else {
	$path = "components/com_sms/photo/";
	$photo="photo.png";
}
 
// Set Field   
$field_name       = SmsHelper::buildField(JText::_('LABEL_STUDENT_NAME'),'input', 'name',$name , '','','required');
$class_select     = SmsHelper::getclassList($class,'disabled="disabled"');
$field_class      = SmsHelper::buildField(JText::_('LABEL_STUDENT_CLASS'),'select', 'class',$class_select , '','','required');
$field_roll       = SmsHelper::buildField(JText::_('LABEL_STUDENT_ROLL'),'input', 'roll',$roll , '','','required','disabled="disabled"');
$section_select   = SmsHelper::getsectionList($section,'disabled="disabled"');
$field_section    = SmsHelper::buildField(JText::_('LABEL_STUDENT_SECTION'),'select', 'section',$section_select , '','','required');
$division_select  = SmsHelper::getdivisionList($division,'disabled="disabled"');
$field_division   = SmsHelper::buildField(JText::_('LABEL_STUDENT_DIVISION'),'select', 'division',$division_select , '','','required');
$year_select      = SmsHelper::getyearList($year,'disabled="disabled"');
$field_year       = SmsHelper::buildField(JText::_('LABEL_STUDENT_YEAR'),'select', 'year',$year_select , '','','required');
//$transport_select = $this->transport_list;
//$field_transport  = SmsHelper::buildField(JText::_('LABEL_STUDENT_TRANSPORT'),'select', 'transport',$transport_select , '','','');
$field_chabima    = SmsHelper::buildField(JText::_('DEFAULT_USERNAME'),'input', 'chabima',$chabima ,'', '','','disabled="disabled"');
$field_email      = SmsHelper::buildField(JText::_('DEFAULT_EMAIL'),'input', 'email',$email ,'', '','','disabled="disabled"');
$field_churanita  = SmsHelper::buildField(JText::_('DEFAULT_PASSWORD'),'password', 'churanita',$churanita , '');

//student photo field
$file_button = '<input  class="" type="file"   name="photo">';
$field_photo = SmsHelper::buildField(JText::_('LABEL_STUDENT_PHOTO'),'select', 'photo',$file_button , '');
		
?>

<style type="text/css">
	#com_sms .form-control{width: auto;}
	#com_sms button[disabled],
	#com_sms select[disabled], 
	#com_sms input[disabled]{background: #eee;}
</style>

<div id="com_sms" >
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-xs-12 col-md-3" id="sms_leftbar">
				<?php echo $this->smshelper->profile(); ?>
				<?php echo $this->sidebar; ?>
		    </div>
		    <div class="col-xs-12 col-md-9">
		        <form action="<?php echo JRoute::_('index.php?option=com_sms&view=editstudents');?>" method="post" name="user-form" id="sms-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'profile')); ?>

	                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'profile', JText::_('TAB_STUDENT_PROFILE', true)); ?>
					    <?php echo $field_name; ?>
						<?php 
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
					        echo SmsHelper::fieldshow($fid, $sid, $panel_id, $field->field_name,$field->type ,$field->required);
					    }
					    ?>
					<?php echo JHtml::_('bootstrap.endTab'); ?>	
				
				    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'academic', JText::_('TAB_STUDENT_ACADEMIC_INFO', true)); ?>	
				        <?php echo $field_class; ?>
						<?php echo $field_roll; ?>
					    <?php echo $field_section; ?>
					    <?php echo $field_division; ?>
						<?php echo $field_year; ?>
						
						<input type="hidden" name="class" value="<?php echo $this->students->class;?>" />
						<input type="hidden" name="roll" value="<?php echo $this->students->roll;?>" />
						<input type="hidden" name="section" value="<?php echo $this->students->section;?>" />
						<input type="hidden" name="division" value="<?php echo $this->students->division;?>" />
						<input type="hidden" name="year" value="<?php echo $this->students->year;?>" />
						<input type="hidden" name="transport_id" value="<?php echo $this->students->transport_id;?>" />
					<?php echo JHtml::_('bootstrap.endTab'); ?>	
				
				    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'account', JText::_('TAB_STUDENT_ACCOUNT_DETAILS', true)); ?>
				        <?php echo $field_chabima; ?>
						<?php echo $field_email; ?>
						<?php echo $field_churanita; ?>
						<input type="hidden" name="chabima" value="<?php echo $chabima; ?>" />
						<input type="hidden" name="email" value="<?php echo $email; ?>" />
					<?php echo JHtml::_('bootstrap.endTab'); ?>		

				    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'photo', JText::_('TAB_STUDENT_PHOTO', true)); ?>
				        <p ><img src="<?php echo $path.$photo; ?>" alt="" style="width: 200px;height: 236px;" /></p>
		                <?php echo $field_photo; ?>
		                <input type="hidden" name="old_photo" value="<?php echo $photo;?>" />
					<?php echo JHtml::_('bootstrap.endTab'); ?>	

	                <?php echo JHtml::_('bootstrap.endTabSet'); ?>
		            <div class=" info_box" style="margin-bottom: 20px;padding: 10px 0;margin-top: 20px;">
			            <input type="submit" value="<?php echo JText::_('BTN_SAVE_CHANGE'); ?>" class="btn btn-small" style="width: 120px;margin-left: 10px;" />
			        </div>

		            <input type="hidden" name="id" value="<?php echo $id;?>" />
		            <input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
	                <input type="hidden" name="controller" value="editstudents" />
	                <input type="hidden" name="task" value="save" />
	                <?php echo JHtml::_('form.token'); ?>
		        </form>
		    </div>
	    </div>
	</div>
</div>

