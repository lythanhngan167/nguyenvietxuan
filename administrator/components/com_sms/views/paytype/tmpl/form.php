<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

 if(!empty($this->paytype->id)){$id = $this->paytype->id;}else {$id="";}
 if(!empty($this->paytype->name)){$name = $this->paytype->name;}else {$name="";}
 if(!empty($this->paytype->fee)){$fee = $this->paytype->fee;}else {$fee="";}
 if(!empty($this->paytype->comment)){$comment = $this->paytype->comment;}else {$comment="";}
 
 $field_name = SmsHelper::buildField(JText::_('LABEL_PAYMENT_TYPE_TITLE'),'input', 'name',$name , '','','required');
 $field_fee = SmsHelper::buildField(JText::_('LABEL_PAYMENT_TYPE_FEE'),'input', 'fee',$fee , '','','required');
 
 $comment_field = ' <textarea cols="" rows="" name="comment" class=" "  style="min-height: 20px;">'.$comment.'</textarea>';
 $field_comment = SmsHelper::buildField(JText::_('LABEL_PAYMENT_COMMENT'),'select', 'comment',$comment_field , '');
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=paytype');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

  <?php echo $field_name; ?>
	<?php echo $field_fee; ?>
	<?php echo $field_comment; ?>

<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="controller" value="paytype" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

