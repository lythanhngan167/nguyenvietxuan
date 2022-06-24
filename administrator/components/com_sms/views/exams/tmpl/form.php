<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

if(!empty($this->exam->id)){$id = $this->exam->id;}else {$id="";}
if(!empty($this->exam->name)){$name = $this->exam->name;}else {$name="";}
if(!empty($this->exam->examdate)){$examdate = $this->exam->examdate;}else {$examdate="";}
if(!empty($this->exam->comment)){$comment = $this->exam->comment;}else {$comment="";}
if(!empty($this->exam->published)){$published = $this->exam->published;}else {$published="";}
 
//setting form field
$field_name = SmsHelper::buildField(JText::_('LABEL_EXAM_NAME'),'input', 'name',$name , '','','required');
$date = JHTML::calendar($examdate,'examdate', 'examdate', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','required'=>'"required"','class'=>' date-formp  validate[\'required\']',));
$field_examdate = SmsHelper::buildField(JText::_('LABEL_EXAM_DATE'),'select', 'examdate',$date , '','','required');

if($published==1){$yes_v = 'selected="selected"';}else{$yes_v ='';}
if($published==0){$no_v = 'selected="selected"';}else{$no_v ='';}
$published_option ='<select name="published"><option value="1" '.$yes_v.' >Yes</option><option value="0" '.$no_v.'>No</option></select>';
$field_published = SmsHelper::buildField(JText::_('DEFAULT_PUBLISHED'),'select', 'published',$published_option , '');
?>

<style type="text/css">
#system-message-container {width: 100%;}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=exams');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <?php echo $field_name; ?>
	<?php echo $field_examdate; ?>
	<?php echo $field_published; ?>

	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="controller" value="exams" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

