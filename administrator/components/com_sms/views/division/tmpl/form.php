<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

 //Collect Data
 if(!empty($this->division->id)){$id = $this->division->id;}else {$id="";}
 if(!empty($this->division->division_name)){$division_name = $this->division->division_name;}else {$division_name="";}
 
 //Build Form
 $field_division_name = SmsHelper::buildField(JText::_('LABEL_DIVISION_NAME'),'input', 'division_name',$division_name , '','','required');
 
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=division');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

<?php echo $field_division_name; ?>

<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="controller" value="division" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

