<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<style type="text/css">
#system-message-container {width: 100% !important;} 
</style>

<div id="acy_content">
	<form action="<?php echo JRoute::_('index.php?option=com_sms');?>" method="post" name="adminForm" id="item-form">
		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_LANGUAGE'); ?></legend>
			<input 
				type		= "text"
				name		= "code"
				id		 	= "code"
				placeholder = "en-GB"
				maxlength 	= "5"
				class       = 'input_txt validate[required]'
			/>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('LNG_LANGUAGE_FILE',true) ;?></legend>
			<textarea rows="30" name="content" id="translation" class="input_txt validate[required]" style="width:100%;max-width:95%;" 
			placeholder='LNG_NAME="Name" LNG_SURNAME="Surname"'></textarea>
		</fieldset>
		<div class="clr"></div>
		<input type="hidden" name="option" value="com_sms" />
		<input type="hidden" name="task" value="languages.store" />
		<input type="hidden" name="controller" value="languages"/>
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>