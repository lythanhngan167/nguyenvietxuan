<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
$app = JFactory::getApplication();
$active = $app->getMenu()->getActive();
  
//Return URL
$return_url = JRoute::_('index.php?option=com_sms&view=parents');
$return =base64_encode($return_url); 

//SET FORM FIELD
$field_username = SmsHelper::buildField(JText::_('DEFAULT_USERNAME'),'input', 'username','' , '','','required');
$field_password = SmsHelper::buildField(JText::_('DEFAULT_PASSWORD'),'password', 'password','' , '','','required');
$remember = '<label class="checkbox"><input id="remember" class="inputbox" type="checkbox" value="yes" name="remember"> '.JText::_('DEFAULT_REMEMBER_ME').'</label>';
$field_remember = SmsHelper::buildField('','select', 'remember',$remember , '','','');

$button = '<button class="btn btn-small" type="submit">'.JText::_('JLOGIN').'</button>';
$field_button = SmsHelper::buildField('','select', 'button',$button , '','','');
?>

<div id="com_sms" >
	<div class="login">
		<form id="sms-login" action="<?php echo JRoute::_('index.php?option=com_sms&task=sms.login'); ?>" method="post" class="form-validate form-horizontal ">
	        <h1 class="login_h1_title"> <?php echo JText::_('COM_SMS_PARENT_LOGIN_TITLE');?> </h1>
			<fieldset class="login-fieldset">
				<?php echo $field_username; ?>
				<?php echo $field_password; ?>
				<?php echo $field_remember; ?>
				<?php echo $field_button; ?>
	        </fieldset>
			<input type="hidden" name="option" value="com_sms" />
			<input type="hidden" name="task" value="sms.login" />
			<input type="hidden" name="controller" value="sms" />
			<input type="hidden" name="return" value="<?php echo $return; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>
