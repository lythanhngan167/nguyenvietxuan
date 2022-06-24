<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
$model = $this->getModel();
JHtml::_('behavior.formvalidator');

// Get Teacher Data
if(!empty($this->teacher->id)){$id = $this->teacher->id;}else {$id="";}
if(!empty($this->teacher->user_id)){$user_id = $this->teacher->user_id;}else {$user_id="";}
if(!empty($this->teacher->name)){$name = $this->teacher->name;}else {$name="";}
if(!empty($this->teacher->chabima)){$chabima = $this->teacher->chabima;}else {$chabima="";}
if(!empty($this->teacher->email)){$email = $this->teacher->email;}else {$email="";}
if(!empty($this->teacher->churanita)){$password = $this->teacher->churanita;}else {$password="";}
if(!empty($this->teacher->class)){$class = $this->teacher->class;}else {$class="";}
if(!empty($this->teacher->roll)){$roll = $this->teacher->roll;}else {$roll="";}
if(!empty($this->teacher->division)){$division = $this->teacher->division;}else {$division="";}
if(!empty($this->teacher->section)){$section = $this->teacher->section;}else {$section="";}
if(!empty($this->teacher->subject)){$subject = $this->teacher->subject;}else {$subject="";}
if(!empty($this->teacher->photo)){
    $photo = $this->teacher->photo;
    $path = "components/com_sms/photo/teachers/";
}else {
	$path = "components/com_sms/photo/";
	$photo="photo.png";
}
if(!empty($this->teacher->designation)){$designation = $this->teacher->designation;}else {$designation="";}
 
//Set Form Field
$field_name        = SmsHelper::buildField(JText::_('LABEL_TEACHER_NAME'),'input', 'name',$name , '','','required');
$class_select      = SmsHelper::getclassList($class,'disabled="disabled"');
$field_class       = SmsHelper::buildField(JText::_('LABEL_TEACHER_CLASS'),'select', 'class',$class_select , '','','required');
$section_select    = SmsHelper::getsectionList($section,'disabled="disabled"');
$field_section     = SmsHelper::buildField(JText::_('LABEL_TEACHER_SECTION'),'select', 'section',$section_select , '','','required');
$subject_select    = SmsHelper::getsubjectList($subject,'disabled="disabled"');
$field_subject     = SmsHelper::buildField(JText::_('LABEL_TEACHER_SUBJECT'),'select', 'subject',$subject_select , '','','required');
$field_chabima     = SmsHelper::buildField(JText::_('DEFAULT_USERNAME'),'input', 'chabima',$chabima , '','','','disabled="disabled"');
$field_email       = SmsHelper::buildField(JText::_('DEFAULT_EMAIL'),'input', 'email',$email , '','','','disabled="disabled"');
$field_password    = SmsHelper::buildField(JText::_('DEFAULT_PASSWORD'),'password', 'churanita',$password , '');
$field_designation = SmsHelper::buildField(JText::_('LABEL_TEACHER_DESIGNATION'),'input', 'designation',$designation , '','','','disabled="disabled"');

//teacher photo field
$file_button       = '<input  class="" type="file"   name="photo">';
$field_photo       = SmsHelper::buildField(JText::_('LABEL_TEACHER_PHOTO'),'select', 'photo',$file_button , '');

?>

<style type="text/css">
	#com_sms .form-control{width: auto;}
</style>

<div id="com_sms" >
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-xs-12 col-md-3" id="sms_leftbar">
		        <?php echo $this->smshelper->profile(); ?>
		        <?php echo $this->sidebar; ?>
		    </div>

		    <div class="col-xs-12 col-md-9">
	            <form action="<?php echo JRoute::_('index.php?option=com_sms&view=editteachers');?>" method="post" name="user-form" id="sms-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	                <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'profile')); ?>

	                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'profile', JText::_('TAB_TEACHER_PROFILE', true)); ?>
					    <?php echo $field_name; ?>
						<?php 
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
		                    echo SmsHelper::fieldshow($fid, $sid, $panel_id, $field->field_name,$field->type ,$field->required);
	                    }
	                    ?>
				    <?php echo JHtml::_('bootstrap.endTab'); ?>		
				
				    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'academic', JText::_('TAB_TEACHER_ACADEMIC_INFO', true)); ?>
				        <?php echo $field_designation; ?>
						<?php echo $field_class; ?>
					    <?php echo $field_section; ?>
					    <?php echo $field_subject; ?>
		                <input type="hidden" name="class" value="<?php echo $this->teacher->class; ?>" />
		                <input type="hidden" name="section" value="<?php echo $this->teacher->section; ?>" />
		                <input type="hidden" name="subject" value="<?php echo $this->teacher->subject; ?>" />
				    <?php echo JHtml::_('bootstrap.endTab'); ?>	
												 
				    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'account', JText::_('TAB_TEACHER_ACCOUNT_DETAILS', true)); ?>
				        <?php echo $field_chabima; ?>
				        <?php echo $field_email; ?>
				        <?php echo $field_password; ?>                   
						<input type="hidden" name="chabima" value="<?php echo $chabima; ?>" />
						<input type="hidden" name="email" value="<?php echo $email; ?>" />
					<?php echo JHtml::_('bootstrap.endTab'); ?>		
				
				    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'photo', JText::_('TAB_TEACHER_PHOTO', true)); ?>
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
	            <input type="hidden" name="controller" value="editteachers" />
	            <input type="hidden" name="task" value="save" />
	            <?php echo JHtml::_('form.token'); ?>
	            </form>
	        </div>
	    </div>
	</div>
</div>

