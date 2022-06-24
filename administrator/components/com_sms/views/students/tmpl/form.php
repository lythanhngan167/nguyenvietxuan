<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$students_account = $params->get('students_account');

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
 
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	};
");

    //setting form field
	$field_name = SmsHelper::buildField(JText::_('LABEL_STUDENT_NAME'),'input', 'name',$name , '','','required');
	$field_roll = SmsHelper::buildField(JText::_('LABEL_STUDENT_ROLL'),'input', 'roll',$roll , '','','required');
	
	$class_select = SmsHelper::getclassList($class);
	$field_class = SmsHelper::buildField(JText::_('LABEL_STUDENT_CLASS'),'select', 'class',$class_select , '','','required');
	
	
	$section_select = SmsHelper::getsectionList($section);
	$field_section = SmsHelper::buildField(JText::_('LABEL_STUDENT_SECTION'),'select', 'section',$section_select , '','','required');
	
	$year_select = SmsHelper::getyearList($year);
	$field_year = SmsHelper::buildField(JText::_('LABEL_STUDENT_YEAR'),'select', 'year',$year_select , '','','required');
	
	$division_select = SmsHelper::getdivisionList($division);
	$field_division = SmsHelper::buildField(JText::_('LABEL_STUDENT_DIVISION'),'select', 'division',$division_select , '','','required');
	
	
	$field_chabima = SmsHelper::buildField(JText::_('DEFAULT_USERNAME'),'input', 'chabima',$chabima , '');
	$field_email = SmsHelper::buildField(JText::_('DEFAULT_EMAIL'),'input', 'email',$email , '');
	$field_churanita = SmsHelper::buildField(JText::_('DEFAULT_PASSWORD'),'password', 'churanita',$churanita , '');
	
	//student photo field
	$file_button = '<input  class="" type="file"   name="photo">';
	$field_photo = SmsHelper::buildField(JText::_('LABEL_STUDENT_PHOTO'),'select', 'photo',$file_button , '');
		
    ?>

<style type="text/css">
#system-message-container {width: 100%;}
</style>


<form action="<?php echo JRoute::_('index.php?option=com_sms&view=students');?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal" enctype="multipart/form-data">

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
		<?php echo JHtml::_('bootstrap.endTab'); ?>	
			
		<?php if(!empty($students_account)): ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'account', JText::_('TAB_STUDENT_ACCOUNT_DETAILS', true)); ?>
			<?php echo $field_chabima; ?>
			<?php echo $field_email; ?>
			<?php echo $field_churanita; ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>		
		<?php endif; ?>
	
	    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'photo', JText::_('TAB_STUDENT_PHOTO', true)); ?>
			<p><img src="<?php echo $path.$photo; ?>" alt="" style="width: 200px;height: 236px;" /></p>
	        <?php echo $field_photo; ?>
	        <input type="hidden" name="old_photo" value="<?php echo $photo;?>" />
		<?php echo JHtml::_('bootstrap.endTab'); ?>	
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="cid" value="<?php echo $id;?>" />
	<input type="hidden" name="student_id" value="<?php echo $id;?>" />
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
	<input type="hidden" name="controller" value="students" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

