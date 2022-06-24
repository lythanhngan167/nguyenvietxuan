<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidation');
if(!empty($this->grade->id)){$id = $this->grade->id;}else {$id="";}
if(!empty($this->grade->name)){$name = $this->grade->name;}else {$name="";}
if(!empty($this->grade->grade_point)){$grade_point = $this->grade->grade_point;}else {$grade_point="";}
if(!empty($this->grade->mark_from)){$mark_from = $this->grade->mark_from;}else {$mark_from="";}
if(!empty($this->grade->mark_upto)){$mark_upto = $this->grade->mark_upto;}else {$mark_upto="";}
if(!empty($this->grade->comment)){$comment = $this->grade->comment;}else {$comment="";}

$field_name = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_NAME'),'input', 'name',$name , '','','required');
$grade_category = $this->gradecategory;
$field_category = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_CATEGORY'),'select', 'class',$grade_category , '','','required');
$field_point = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_POINT'),'input', 'grade_point',$grade_point , '','','required');
$field_mark_from = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_MARK_FORM'),'input', 'mark_from',$mark_from , '','','required');
$field_mark_upto = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_MARK_UPTO'),'input', 'mark_upto',$mark_upto , '','','required');
$field_comment = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_COMMENT'),'input', 'comment',$comment , '');
 
 
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
");
?>

<style type="text/css">
#system-message-container {width: 100%;}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=grade');?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<?php echo $field_name; ?>
	<?php echo $field_category; ?>
	<?php echo $field_point; ?>
	<?php echo $field_mark_from; ?>
	<?php echo $field_mark_upto; ?>
	<?php echo $field_comment; ?>
	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="controller" value="grade" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

