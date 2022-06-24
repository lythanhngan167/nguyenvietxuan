<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidator');
$model = $this->getModel();

// Get Parent Data
if(!empty($this->parent->id)){$id = $this->parent->id;}else {$id="";}
if(!empty($this->parent->user_id)){$user_id = $this->parent->user_id;}else {$user_id="";}
if(!empty($this->parent->name)){$name = $this->parent->name;}else {$name="";}
if(!empty($this->parent->chabima)){$chabima = $this->parent->chabima;}else {$chabima="";}
if(!empty($this->parent->email)){$email = $this->parent->email;}else {$email="";}
if(!empty($this->parent->churanita)){$password = $this->parent->churanita;}else {$password="";}
if(!empty($this->parent->roll)){$roll = $this->parent->roll;}else {$roll="";}
if(!empty($this->parent->student_id)){$student_id = $this->parent->student_id;$student_name = SmsHelper::getStudentname($student_id);}else {$student_id="";$student_name="";}
if(!empty($this->parent->photo)){
    $photo = $this->parent->photo;
    $path = "components/com_sms/photo/parents/";
}else {
	$path = "components/com_sms/photo/";
	$photo="photo.png";
}
    
// Set Field
$field_name       = SmsHelper::buildField(JText::_('LABEL_PARENT_NAME'),'input', 'name',$name , '','','required');
$field_chabima    = SmsHelper::buildField(JText::_('DEFAULT_USERNAME'),'input', 'chabima',$chabima ,'', '','','disabled="disabled"');
$field_email      = SmsHelper::buildField(JText::_('DEFAULT_EMAIL'),'input', 'email',$email ,'', '','','disabled="disabled"');
$field_churanita  = SmsHelper::buildField(JText::_('DEFAULT_PASSWORD'),'password', 'churanita',$password , '');
	
//student photo field
$file_button      = '<input  class="" type="file"   name="photo">';
$field_photo      = SmsHelper::buildField(JText::_('LABEL_PARENT_PHOTO'),'select', 'photo',$file_button , '');
$field_student_id = SmsHelper::buildField(JText::_('LABEL_PARENT_STUDENT'),'input', 'student_id',$student_name , '','','required','disabled="disabled"');
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
	            <form action="<?php echo JRoute::_('index.php?option=com_sms&view=editparent');?>" method="post" name="user-form" id="sms-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	                <div class="row-fluid">
	                    <div class="span12">
	                        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'profile')); ?>
	                            
	                            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'profile', JText::_('TAB_PARENT_PROFILE', true)); ?>
				                    <?php echo $field_name; ?>
					                <?php 
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
			                            echo SmsHelper::fieldshow($fid, $sid, $panel_id, $field->field_name,$field->type ,$field->required);
		                            }
		                            ?>
				                <?php echo JHtml::_('bootstrap.endTab'); ?>		
				                
				                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'account', JText::_('TAB_PARENT_ACCOUNT_DETAILS', true)); ?>
				                    <?php echo $field_chabima; ?>
				                    <?php echo $field_email; ?>
								    <?php echo $field_churanita; ?>
								    <input type="hidden" name="chabima" value="<?php echo $chabima; ?>" />
								    <input type="hidden" name="email" value="<?php echo $email; ?>" />
				                <?php echo JHtml::_('bootstrap.endTab'); ?>		
				
				                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'photo', JText::_('TAB_PARENT_PHOTO', true)); ?>
				                    <p ><img src="<?php echo $path.$photo; ?>" alt="" style="width: 200px;height: 236px;" /></p>
		                            <?php echo $field_photo; ?>
		                            <input type="hidden" name="old_photo" value="<?php echo $photo;?>" />
				                <?php echo JHtml::_('bootstrap.endTab'); ?>		
	 
	                        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
	                    </div>
		            </div>
									
					<div class="row-fluid info_box" style="margin-bottom: 20px;padding: 10px 0;margin-top: 20px;">
		                <div class="span12"><input type="submit" value="<?php echo JText::_('BTN_SAVE_CHANGE'); ?>" class="btn btn-small" style="width: 120px;margin-left: 10px;" /></div>
		            </div>
		              
	                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>" />
	                <input type="hidden" name="id" value="<?php echo $id;?>" />
	                <input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
	                <input type="hidden" name="controller" value="editparent" />
	                <input type="hidden" name="task" value="save" />
	                <?php echo JHtml::_('form.token'); ?>
	            </form>
	        </div>
	    </div>
	</div>
</div>
