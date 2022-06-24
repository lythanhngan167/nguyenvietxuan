<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.modal');
JHtml::_('script', JUri::root() . 'media/jui/js/fielduser.min.js'); 

$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$parents_account = $params->get('parents_account');

//Collect data
if(!empty($this->students->id)){$id = $this->students->id;}else {$id="";}
if(!empty($this->students->user_id)){$user_id = $this->students->user_id;}else {$user_id="";}
if(!empty($this->students->name)){$name = $this->students->name;}else {$name="";}
if(!empty($this->students->chabima)){$chabima = $this->students->chabima;}else {$chabima="";}
if(!empty($this->students->email)){$email = $this->students->email;}else {$email="";}
if(!empty($this->students->churanita)){$churanita = $this->students->churanita;}else {$churanita="";}

if(!empty($this->students->roll)){$roll = $this->students->roll;}else {$roll="";}
if(!empty($this->students->student_id)){
 $student_id = $this->students->student_id; 
}else {
 $student_id=""; 
}
 
if(!empty($this->students->mobile_no)){$mobile_no = $this->students->mobile_no;}else {$mobile_no="";}
if(!empty($this->students->photo)){
	$photo = $this->students->photo;
	$path = "../components/com_sms/photo/parents/";
}else {
	$path = "../components/com_sms/photo/";
	$photo="photo.png";
}
 
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('user-form')))
		{
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	};
");


    //Build Input form field
	$field_name = SmsHelper::buildField(JText::_('LABEL_PARENT_NAME'),'input', 'name',$name, '','','required');
		
	// SmsHelper::getStudentname($student_ids['0'])
    $student_ids = explode(",", $student_id);
    //echo $student_ids['0'];

    if(!empty($student_ids['0'])){$student_id_01 = $student_ids['0']; }else{$student_id_01 ='';}
    $student_01_filed = SmsHelper::getModalparent('student_01', 'student_id[]', $student_id_01, 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_01 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_01'),'select', 'customer_select',$student_01_filed , '');

    if(!empty($student_ids['1'])){$student_id_02 = $student_ids['1']; }else{$student_id_02 ='';}
    $student_02_filed = SmsHelper::getModalparent('student_02', 'student_id[]', $student_id_02, 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_02 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_02'),'select', 'customer_select',$student_02_filed , '');

    if(!empty($student_ids['2'])){$student_id_03 = $student_ids['2']; }else{$student_id_03 ='';}
    $student_03_filed = SmsHelper::getModalparent('student_03', 'student_id[]', $student_id_03 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_03 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_03'),'select', 'customer_select',$student_03_filed , '');

    if(!empty($student_ids['3'])){$student_id_04 = $student_ids['3']; }else{$student_id_04 ='';}
    $student_04_filed = SmsHelper::getModalparent('student_04', 'student_id[]', $student_id_04 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_04 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_04'),'select', 'customer_select',$student_04_filed , '');

    if(!empty($student_ids['4'])){$student_id_05 = $student_ids['4']; }else{$student_id_05 ='';}
    $student_05_filed = SmsHelper::getModalparent('student_05', 'student_id[]', $student_id_05 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_05 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_05'),'select', 'customer_select',$student_05_filed , '');

    if(!empty($student_ids['5'])){$student_id_06 = $student_ids['5']; }else{$student_id_06 ='';}
    $student_06_filed = SmsHelper::getModalparent('student_06', 'student_id[]', $student_id_06 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_06 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_06'),'select', 'customer_select',$student_06_filed , '');

    if(!empty($student_ids['6'])){$student_id_07 = $student_ids['6']; }else{$student_id_07 ='';}
    $student_07_filed = SmsHelper::getModalparent('student_07', 'student_id[]', $student_id_07 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_07 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_07'),'select', 'customer_select',$student_07_filed , '');

    if(!empty($student_ids['7'])){$student_id_08 = $student_ids['7']; }else{$student_id_08 ='';}
    $student_08_filed = SmsHelper::getModalparent('student_08', 'student_id[]', $student_id_08 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_08 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_08'),'select', 'customer_select',$student_08_filed , '');

    if(!empty($student_ids['8'])){$student_id_09 = $student_ids['8']; }else{$student_id_09 ='';}
    $student_09_filed = SmsHelper::getModalparent('student_09', 'student_id[]', $student_id_09 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_09 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_09'),'select', 'customer_select',$student_09_filed , '');

    if(!empty($student_ids['9'])){$student_id_10 = $student_ids['9']; }else{$student_id_10 ='';}
    $student_10_filed = SmsHelper::getModalparent('student_10', 'student_id[]', $student_id_10 , 'LABEL_PARENT_SELECT_STUDENT');
	$field_student_10 = SmsHelper::buildField(JText::_('LABEL_PARENT_CHILD_10'),'select', 'customer_select',$student_10_filed , '');
    
	$field_roll = SmsHelper::buildField(JText::_('COM_SMS_LABEL_ROLL'),'input', 'roll',$roll , '','','required');
	$field_student_id = SmsHelper::buildField(JText::_('COM_SMS_LABEL_PARENT_STUDENT_ID'),'input', 'student_id',$student_id , '','','required');
	
	$field_chabima = SmsHelper::buildField(JText::_('DEFAULT_USERNAME'),'input', 'chabima',$chabima , '');
	$field_email = SmsHelper::buildField(JText::_('DEFAULT_EMAIL'),'input', 'email',$email , '');
	$field_password = SmsHelper::buildField(JText::_('DEFAULT_PASSWORD'),'password', 'churanita',$churanita , '');
	
	//parent photo field
	$file_button = '<input  class="" type="file"   name="photo">';
	$field_photo = SmsHelper::buildField(JText::_('LABEL_PARENT_PHOTO'),'select', 'photo',$file_button , '');
 ?>

<style type="text/css">
#system-message-container {width: 100%;}
</style>


<form action="<?php echo JRoute::_('index.php?option=com_sms&view=parents');?>" method="post" name="user-form" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data">


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
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'academic', JText::_("TAB_PARENT_STUDENT_INFO", true)); ?>
	        <?php echo $field_student_01; ?>
            <?php echo $field_student_02; ?>
            <?php echo $field_student_03; ?>
            <?php echo $field_student_04; ?>
            <?php echo $field_student_05; ?>
            <?php echo $field_student_06; ?>
            <?php echo $field_student_07; ?>
            <?php echo $field_student_08; ?>
            <?php echo $field_student_09; ?>
            <?php echo $field_student_10; ?>
        <?php echo JHtml::_('bootstrap.endTab'); ?>	
			
		<?php if(!empty($parents_account)): ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'account', JText::_('TAB_PARENT_ACCOUNT_DETAILS', true)); ?>
		         <?php echo $field_chabima; ?>
		         <?php echo $field_email; ?>
		         <?php echo $field_password; ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>		
		<?php endif; ?>
			
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'photo', JText::_('TAB_PARENT_PHOTO', true)); ?>
		    <p ><img src="<?php echo $path.$photo; ?>" alt="" style="width: 200px;height: 236px;" /></p>
            <?php echo $field_photo; ?>
            <input type="hidden" name="old_photo" value="<?php echo $photo;?>" />
		<?php echo JHtml::_('bootstrap.endTab'); ?>		
 
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="parent_id" value="<?php echo $id;?>" />
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
	<input type="hidden" name="controller" value="parents" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

