<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=languages');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
	
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width=""  class=""><?php echo JText::_('LNG_NAME',true); ?></th>
				<th width="5%" class="hidden-phone"><?php echo JText::_('LNG_ID',true); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php
			$k = 0;
			for($i = 0,$a = count($this->languages);$i<$a;$i++) {
				$row = $this->languages[$i]; ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $i + 1; ?></td>
					<td class="hidden-phone" align=center>
						<?php echo JHtml::_('grid.id', $i, $row->language); ?>
					</td>
					<td align="center">
                        <a  href="<?php echo JRoute::_( 'index.php?option=com_sms&view=languages&task=editlanguage&code='.$row->language )?>"><?php echo $row->name; ?></a>
                    </td>
					<td align="center"><?php echo $row->language; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
			} ?>

		</tbody>
		
	</div>
	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="languages" />
	<?php echo JHtml::_('form.token'); ?>
</form>
