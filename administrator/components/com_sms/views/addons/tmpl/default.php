<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	};
");

$model = $this->getModel();
$user = JFactory::getUser(); 

?>

  
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=addons');?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" enctype="multipart/form-data">

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
				<th width="1%" class="nowrap left"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th  class="nowrap left">Addon Title</th>
				<th  class="center">Active Code</th>
				<th  class=" center">Product ID</th>
				<th width="5%" class="nowrap center"><?php echo JText::_('DEFAULT_PUBLISHED'); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php foreach ($this->items as $i => $item) :
		  	$checked 	= JHTML::_('grid.id',   $i, $item->id );
			$canChange	= $user->authorise('core.edit.state', 'com_sms.addons.'.$item->id);
		    $published  = JHtml::_('jgrid.published', $item->status, $i, '', $canChange, 'cb', '', '');
		    $link 		= JRoute::_( 'index.php?option=com_sms&view=addons&task=addactive&cid[]='. $item->id );
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
                <td class="left"><?php echo $checked; ?></td>
				<td class="left"><?php echo $item->name;?> <?php echo $item->version;?> <br> <?php echo $item->desc;?></td>
				<td class="center">
					<?php 
					if(empty($item->active_code)){
                        echo '<a href="'.$link.'">Add addon active code</a>';
					}else{
						echo '<a href="'.$link.'">'.$item->active_code.'</a>';
					}
				    ?>
				</td>
				<td class="center"><?php echo $item->product_id;?></td>
				<td class="center"><?php echo $published;?></td>
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

    <input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="addons" />
	<?php echo JHtml::_('form.token'); ?>
	
        
</form>

<form action="" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Upload & Install sms addon</legend>
		<input type="file" name="install_file">
	    <input type="submit" name="" value="Install" class="btn btn-parimary">
	</fieldset>

	<input type="hidden" name="task" value="getInstall" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="addons" />
	<?php echo JHtml::_('form.token'); ?>
	
</form>

    
	
