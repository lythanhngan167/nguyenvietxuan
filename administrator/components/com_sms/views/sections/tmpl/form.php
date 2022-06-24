<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

 //Collect Data
 if(!empty($this->section->id)){$id = $this->section->id;}else {$id="";}
 if(!empty($this->section->section_name)){$section_name = $this->section->section_name;}else {$section_name="";}
 
 //Build Form
 $field_section_name = SmsHelper::buildField(JText::_('LABEL_SECTION_NAME'),'input', 'section_name',$section_name , '','','required');
 
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=sections');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

<?php echo $field_section_name; ?>


<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="controller" value="sections" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

