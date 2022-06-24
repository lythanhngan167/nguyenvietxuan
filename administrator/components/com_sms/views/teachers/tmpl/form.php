<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidator');
//JHtml::_('formbehavior.chosen', 'select');

$model = $this->getModel();

$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$teachers_account = $params->get('teachers_account');

//Data Collect
if(!empty($this->teacher->id)){$id = $this->teacher->id;}else {$id="";}
if(!empty($this->teacher->user_id)){$user_id = $this->teacher->user_id;}else {$user_id="";}
if(!empty($this->teacher->name)){$name = $this->teacher->name;}else {$name="";}

if(!empty($this->teacher->chabima)){$chabima = $this->teacher->chabima;}else {$chabima="";}
if(!empty($this->teacher->email)){$email = $this->teacher->email;}else {$email="";}
if(!empty($this->teacher->churanita)){$churanita = $this->teacher->churanita;}else {$churanita="";}

if(!empty($this->teacher->class)){$class = $this->teacher->class;}else {$class="";}
if(!empty($this->teacher->roll)){$roll = $this->teacher->roll;}else {$roll="";}
if(!empty($this->teacher->division)){$division = $this->teacher->division;}else {$division="";}
if(!empty($this->teacher->section)){$section = $this->teacher->section;}else {$section="";}
if(!empty($this->teacher->subject)){$subject = $this->teacher->subject;}else {$subject="";}

if(!empty($this->teacher->photo)){
	$photo = $this->teacher->photo;
	$path = "../components/com_sms/photo/teachers/";
}else {
	$path = "../components/com_sms/photo/";
	$photo="photo.png";
}
if(!empty($this->teacher->designation)){$designation = $this->teacher->designation;}else {$designation="";}
 
//cancel button script
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('user-form')))
		{
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	};
");
 

    //Set Form Field
	$field_name = SmsHelper::buildField(JText::_('LABEL_TEACHER_NAME'),'input', 'name',$name , '','','required');
	
	$class_select = $model->getclassList($class);
	$field_class = SmsHelper::buildField(JText::_('LABEL_TEACHER_CLASS'),'select', 'class',$class_select , '','','required');
	
	$section_select = $model->getsectionList($section);
	$field_section = SmsHelper::buildField(JText::_('LABEL_TEACHER_SECTION'),'select', 'section',$section_select , '','','required');
	
	$subject_select = $model->getsubjectList($subject);
	$field_subject = SmsHelper::buildField(JText::_('LABEL_TEACHER_SUBJECT'),'select', 'subject',$subject_select , '','','required');
	
	$field_chabima = SmsHelper::buildField(JText::_('DEFAULT_USERNAME'),'input', 'chabima',$chabima , '');
	$field_email = SmsHelper::buildField(JText::_('DEFAULT_EMAIL'),'input', 'email',$email , '');
	$field_password = SmsHelper::buildField(JText::_('DEFAULT_PASSWORD'),'password', 'churanita',$churanita , '');
	
	$field_designation = SmsHelper::buildField(JText::_('LABEL_TEACHER_DESIGNATION'),'input', 'designation',$designation , '');
	
	//teacher photo field
	$file_button = '<input  class="" type="file"   name="photo">';
	$field_photo = SmsHelper::buildField(JText::_('LABEL_TEACHER_PHOTO'),'select', 'photo',$file_button , '');
		
	//set css
	$document = JFactory::getDocument();
	$document->addStyleSheet('../administrator/components/com_sms/css/sumoselect.css');
?>

<script type="text/javascript" src="../administrator/components/com_sms/js/jquery.sumoselect.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        window.asd = jQuery('.classBox').SumoSelect({ csvDispCount: 3 });
        window.asd = jQuery('.sectionBox').SumoSelect({ csvDispCount: 3 });
        window.asd = jQuery('.subjectBox').SumoSelect({ csvDispCount: 3 });
    });
</script>

<style type="text/css">
	#system-message-container {width: 100%;}*/*/
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=teachers');?>" method="post" name="user-form" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data">

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
		<?php echo JHtml::_('bootstrap.endTab'); ?>	
		
		<?php if(!empty($teachers_account)): ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'account', JText::_('TAB_TEACHER_ACCOUNT_DETAILS', true)); ?>
	         <?php echo $field_chabima; ?>
	         <?php echo $field_email; ?>
	         <?php echo $field_password; ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>		
		<?php endif; ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'photo', JText::_('TAB_TEACHER_PHOTO', true)); ?>
		    <p><img src="<?php echo $path.$photo; ?>" alt="" style="width: 200px;height: 236px;" /></p>
            <?php echo $field_photo; ?>
            <input type="hidden" name="old_photo" value="<?php echo $photo;?>" />
		<?php echo JHtml::_('bootstrap.endTab'); ?>
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="cid" value="<?php echo $id;?>" />
	<input type="hidden" name="teacher_id" value="<?php echo $id;?>" />
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
	<input type="hidden" name="controller" value="teachers" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

