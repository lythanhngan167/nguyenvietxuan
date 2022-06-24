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
if(!empty($this->grade->mark)){$mark = $this->grade->mark;}else {$mark="";}
 
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	};
");
 
$field_category_name = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_CATEGORY_NAME'),'input', 'name',$name , '','','required');
$field_category_mark = SmsHelper::buildField(JText::_('LABEL_EXAM_GRADE_CATEGORY_MARK'),'input', 'mark',$mark , '','','required');
 
?>

<style type="text/css">
#system-message-container {width: 100%;}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=grade');?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal">
	<?php echo $field_category_name; ?>
	<?php echo $field_category_mark; ?>

	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="controller" value="gradecategory" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

