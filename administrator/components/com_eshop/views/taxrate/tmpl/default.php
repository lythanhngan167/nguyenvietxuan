<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));; 	
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'taxrate.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.tax_name.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_TAX_NAME'); ?>");
				form.tax_name.focus();
				return;
			}
			if (form.tax_rate.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_TAX_RATE'); ?>");
				form.tax_rate.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_TAX_NAME'); ?>
		</div>
		<div class="controls">
			<input class="text-large" type="text" name="tax_name" id="tax_name" maxlength="255" value="<?php echo $this->item->tax_name; ?>" />
		</div>
	</div>	
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_TAX_RATE'); ?>
		</div>
		<div class="controls">
			<input class="text-large" type="text" name="tax_rate" id="tax_rate" maxlength="255" value="<?php echo $this->item->tax_rate; ?>" />
		</div>				
	</div>	
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_TAX_TYPE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['tax_type']; ?>
		</div>				
	</div>	
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_CUSTOMERGROUPS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['customergroup_id']; ?>
		</div>				
	</div>						
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_GEO_ZONE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['geozone_id']; ?>
		</div>				
	</div>				
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PUBLISHED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['published']; ?>
		</div>				
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<input type="hidden" name="task" value="" />
</form>