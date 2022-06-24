<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

 //Collect Data
 if(!empty($this->subject->id)){$id = $this->subject->id;}else {$id="";}
 if(!empty($this->subject->subject_name)){$subject_name = $this->subject->subject_name;}else {$subject_name="";}
 if(!empty($this->subject->subject_shot_name)){$subject_shot_name = $this->subject->subject_shot_name;}else {$subject_shot_name="";}
 if(!empty($this->subject->subject_code)){$subject_code = $this->subject->subject_code;}else {$subject_code="";}
 if(!empty($this->subject->order_number)){$order_number = $this->subject->order_number;}else {$order_number="";}
 
 //Build Form
 $field_subject_name = SmsHelper::buildField(JText::_('LABEL_SUBJECT_NAME'),'input', 'subject_name',$subject_name , '','','required');
 $field_subject_shot_name = SmsHelper::buildField(JText::_('LABEL_SUBJECT_ST_NAME'),'input', 'subject_shot_name',$subject_shot_name , '','','');
 $field_subject_code = SmsHelper::buildField(JText::_('LABEL_SUBJECT_ST_CODE'),'input', 'subject_code',$subject_code , '','','');
 $field_order_number = SmsHelper::buildField(JText::_('LABEL_SUBJECT_ST_ORDER'),'input', 'order_number',$order_number , '','','');
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=subjects');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

 <?php echo $field_subject_name; ?>
 <?php echo $field_subject_shot_name; ?>
 <?php echo $field_subject_code; ?>
 <?php echo $field_order_number; ?>

<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="controller" value="subjects" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

