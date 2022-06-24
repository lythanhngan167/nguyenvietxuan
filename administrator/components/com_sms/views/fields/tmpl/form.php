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
$model = $this->getModel();

//Collect Data
if(!empty($this->field->id)){$id = $this->field->id;}else {$id="";}
if(!empty($this->field->field_name)){$field_name = $this->field->field_name;}else {$field_name="";}
if(!empty($this->field->type)){$type = $this->field->type;}else {$type="";}
if(!empty($this->field->section)){$section = $this->field->section;}else {$section="";}
if(!empty($this->field->field_order)){$field_order = $this->field->field_order;}else {$field_order="";}

// Code for Published
if(isset($this->field->published)){if(!empty($this->field->published)){ $published= 'checked="checked"';$unpublished = ''; }else {$published= '';$unpublished = 'checked="checked"'; }}else{$published= 'checked="checked"';$unpublished = '';}

// Code for required
if(isset($this->field->required)){if(!empty($this->field->required)){ $required = 'checked="checked"'; $unrequired=""; }else {$required = ''; $unrequired='checked="checked"'; }}else{$required = ''; $unrequired='checked="checked"';}

if(!empty($this->field->option_param)){$option_param = $this->field->option_param;}else {$option_param="";}
 
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	};
");

 
//Build Form
$field_division_name = SmsHelper::buildField(JText::_('LABEL_FIELD_NAME'),'input', 'field_name',$field_name , '','','required');

$field_field_order = SmsHelper::buildField(JText::_('LABEL_FIELD_ORDER'),'input', 'field_order',$field_order , '','','');

$type_select = $model->getTypeList($type);
$field_type = SmsHelper::buildField(JText::_('LABEL_FIELD_TYPE'),'select', 'type',$type_select , '','','required');

$section_select = $model->getSectionList($section);
$field_section = SmsHelper::buildField(JText::_('LABEL_FIELD_SECTION'),'select', 'section',$section_select , '','','required');

//Published
$published_show ='<fieldset id="published" class="btn-group btn-group-yesno radio">';
$published_show .='<input id="published0" name="published" value="1"  '.$published.'  type="radio">';
$published_show .='<label class="btn " for="published0"> Yes </label>';
$published_show .='<input id="published1" name="published" value="0" '.$unpublished.'  type="radio">';
$published_show .='<label class="btn" for="published1"> No </label>';
$published_show .='</fieldset>';

$field_published = SmsHelper::buildField(JText::_('DEFAULT_PUBLISHED'),'select', 'published',$published_show , '','','');

//Required
$required_show ='<fieldset id="required" class="btn-group btn-group-yesno radio">';
$required_show .='<input id="required0" name="required" value="1" '.$required.'  type="radio">';
$required_show .='<label class="btn " for="required0"> Yes </label>';
$required_show .='<input id="required1" name="required" value="0" '.$unrequired.' type="radio">';
$required_show .='<label class="btn" for="required1"> No </label>';
$required_show .='</fieldset>';

$field_required = SmsHelper::buildField(JText::_('DEFAULT_REQUIRED'),'select', 'required',$required_show , '','','');

//Option field
$option_field = ' <textarea cols="" rows="" name="option_param" class=" "  style="min-height: 100px;">'.$option_param.'</textarea> '.JText::_('LABEL_OPTION_HINTS');
$field_option = SmsHelper::buildField(JText::_('LABEL_OPTION_VALUE'),'select', 'option_param',$option_field , '');

//Display on profile
if(isset($this->field->profile)){if(!empty($this->field->profile)){ $profile= 'checked="checked"';$unprofile = ''; }else {$profile= '';$unprofile = 'checked="checked"'; }}else{$profile= 'checked="checked"';$unprofile = '';}

$display_on_profile ='<fieldset id="profile" class="btn-group btn-group-yesno radio">';
$display_on_profile .='<input id="profile0" name="profile" value="1"  '.$profile.'  type="radio">';
$display_on_profile .='<label class="btn " for="profile0"> Yes </label>';
$display_on_profile .='<input id="profile1" name="profile" value="0" '.$unprofile.'  type="radio">';
$display_on_profile .='<label class="btn" for="profile1"> No </label>';
$display_on_profile .='</fieldset>';

$field_profile = SmsHelper::buildField(JText::_('LABEL_DISPLAY_ON_PROFILE'),'select', 'profile',$display_on_profile , '','','');

//Display on list
if(isset($this->field->list)){if(!empty($this->field->list)){ $list = 'checked="checked"'; $unlist=""; }else {$list = ''; $unlist='checked="checked"'; }}else{$list = ''; $unlist='checked="checked"';}

$display_on_list ='<fieldset id="list" class="btn-group btn-group-yesno radio">';
$display_on_list .='<input id="list0" name="list" value="1"  '.$list.'  type="radio">';
$display_on_list .='<label class="btn " for="list0"> Yes </label>';
$display_on_list .='<input id="list1" name="list" value="0" '.$unlist.'  type="radio">';
$display_on_list .='<label class="btn" for="list1"> No </label>';
$display_on_list .='</fieldset>';

$field_list = SmsHelper::buildField(JText::_('LABEL_DISPLAY_ON_LIST'),'select', 'list',$display_on_list , '','','');

//Display on biodata
if(isset($this->field->biodata)){if(!empty($this->field->biodata)){ $biodata = 'checked="checked"'; $unbiodata=""; }else {$biodata = ''; $unbiodata='checked="checked"'; }}else{$biodata = ''; $unbiodata='checked="checked"';}

$display_on_biodata ='<fieldset id="biodata" class="btn-group btn-group-yesno radio">';
$display_on_biodata .='<input id="biodata0" name="biodata" value="1"  '.$biodata.'  type="radio">';
$display_on_biodata .='<label class="btn " for="biodata0"> Yes </label>';
$display_on_biodata .='<input id="biodata1" name="biodata" value="0" '.$unbiodata.'  type="radio">';
$display_on_biodata .='<label class="btn" for="biodata1"> No </label>';
$display_on_biodata .='</fieldset>';

$field_biodata = SmsHelper::buildField(JText::_('LABEL_DISPLAY_ON_BIODATA'),'select', 'biodata',$display_on_biodata , '','','');
 
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=fields');?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal">

<?php echo $field_division_name; ?>
<?php echo $field_type; ?>
<?php echo $field_section; ?>
<?php echo $field_published; ?>
<?php echo $field_required; ?>
<?php echo $field_field_order; ?>
<?php echo $field_option; ?>
<?php echo  $field_profile; ?>
<?php echo $field_list; ?>
<?php echo $field_biodata; ?>


<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="controller" value="fields" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

