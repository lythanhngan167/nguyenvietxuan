<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

 //Collect Data
 if(!empty($this->addon->id)){$id = $this->addon->id;}else {$id="";}
 if(!empty($this->addon->active_code)){$active_code = $this->addon->active_code;}else {$active_code="";}
 
 //Build Form
 $field_active_code = SmsHelper::buildField(JText::_('Active Code'),'input', 'active_code',$active_code , '','','required');
 
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=addons');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

<?php echo $field_active_code; ?>

<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="controller" value="addons" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

