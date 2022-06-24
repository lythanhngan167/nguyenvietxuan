<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


$user		= JFactory::getUser();
$loggeduser = JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=paytype');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
	

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" class=" left"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th class=" left"><?php echo JText::_('LABEL_PAYMENT_TYPE_TITLE'); ?></th>
				<th class="center"><?php echo JText::_('LABEL_PAYMENT_TYPE_FEE'); ?></th>
				<th width="5%" class=" center"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
			</tr>
		</thead>
		<tbody>
		  <?php foreach ($this->items as $i => $item) :
			$checked 	= JHTML::_('grid.id',   $i, $item->id );
			$canChange	= $user->authorise('core.edit.state', 'com_sms.subjects.'.$item->id);
		  //$published = JHtml::_('jgrid.published', $item->published, $i, '', $canChange, 'cb', '', '');
		  $link 		= JRoute::_( 'index.php?option=com_sms&view=paytype&task=edittype&cid[]='. $item->id );
		  ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $checked; ?></td>
				<td class="left"><a href="<?php echo $link; ?>"><?php echo $item->name;?></a></td>
				<td class="center"><?php echo $item->fee;?></td>
				<td class="center"><?php echo $item->id;?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
	
	<?php endif; ?>
	</div>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="paytype" />
	<?php echo JHtml::_('form.token'); ?>
</form>
