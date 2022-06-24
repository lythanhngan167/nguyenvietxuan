<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access');
?>

<style type="text/css">
#system-message-container {width: 100% !important;} 
</style>

<div id="language-content">
	<form action="<?php echo JRoute::_('index.php?option=com_sms') ?>" method="post" name="adminForm"  id="adminForm" autocomplete="off">
		
		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_FILE',true).' : '.$this->file->name;?></legend>
			<textarea rows="30" name="content" id="translation" style="width:100%;max-width:95%;"><?php echo $this->file->content;?></textarea>
		</fieldset>

		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_FILE_CUSTOM',true); ?></legend>
			<textarea rows="18" name="custom_content" id="translation" style="width:100%;max-width:95%;"><?php echo $this->file->custom_content;?></textarea>
		</fieldset>

		<div class="clr"></div>
		<input type="hidden" name="option" value="com_sms" />
		<input type="hidden" id="task" name="task" value=""/>
		<input type="hidden" name="controller" value="languages"/>
		<input type="hidden" id="code" name="code" value="<?php echo $this->file->name?>"/>
		<input type="hidden" name="ctrl" value="file" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
