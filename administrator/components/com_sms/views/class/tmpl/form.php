<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

//Collect Data
if(!empty($this->class->id)){$id = $this->class->id;}else {$id="";}
if(!empty($this->class->class_name)){$class_name = $this->class->class_name;}else {$class_name="";}

//Build Form
$field_class_name = SmsHelper::buildField(JText::_('LABEL_CLASS_NAME'),'input', 'class_name',$class_name , '','','required');

$field_grade_category = SmsHelper::buildField(JText::_('DEFAULT_GRADE_SYSTEM'),'select', 'grade_category',$this->gradecategory , '','','required');

//set css
$document = JFactory::getDocument();
$document->addStyleSheet('../administrator/components/com_sms/css/sumoselect.css');

?>
<script type="text/javascript" src="../administrator/components/com_sms/js/jquery.sumoselect.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function () {
	    window.asd = jQuery('.divisionBox').SumoSelect({ csvDispCount: 3 });
		window.asd = jQuery('.sectionBox').SumoSelect({ csvDispCount: 3 });
		window.asd = jQuery('.subjectBox').SumoSelect({ csvDispCount: 3 });
	});
</script>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=class');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <?php echo $field_class_name; ?>
	<?php echo $field_grade_category; ?>

    <?php  if(!empty($id)){ ?>
    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'division')); ?>
	
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'division', JText::_('DEFAULT_DIVISION', true)); ?>
			<?php echo $field_division = SmsHelper::buildField('DEFAULT_DIVISION','select', 'division',$this->division , '','','required'); ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>	
			
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'section', JText::_('DEFAULT_SECTION', true)); ?>
			<?php echo $field_section = SmsHelper::buildField('DEFAULT_SECTION','select', 'section',$this->section , '','','required'); ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>	
			
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'subjects', JText::_('DEFAULT_SUBJECT', true)); ?>
			<?php echo $field_subjects = SmsHelper::buildField('DEFAULT_SUBJECT','select', 'subjects',$this->subject , '','','required'); ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>	

    <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    <?php } ?>
			
	 
<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="controller" value="class" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>


 
 
 
 
 