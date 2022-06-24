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
    $update_version = $model->getUpdateVersion();

    $activation  = SmsHelper::getActivation();
    if(!empty($activation)){
        $activation_count = count($activation);
    }else{
        $activation_count = 0;
    }
   
    

    if(!empty($activation_count)){
        $buyer = $activation->buyer_name;
        $purchase_code = $activation->p_code;
    }else{
        $buyer ='';
        $purchase_code = '';
    }
    
    $field_buyer = SmsHelper::buildField(JText::_('Envato Buyer Name'),'input', 'buyer',$buyer , '','','required');
    $field_purchase_code = SmsHelper::buildField(JText::_('Purchase Code'),'input', 'purchase_code',$purchase_code , '','','required');
  

    jimport( 'joomla.application.componenet.helper' );
    $module = JComponentHelper::getComponent('com_sms');
    $mid = $module->id;

?>

  
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=activation');?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" enctype="multipart/form-data">

	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
        <p style="color: gray;margin:15px 0;"> Please setting your application. enter valid buyer name & purchase code.</p>
	    <?php echo $field_buyer; ?>
        <?php echo $field_purchase_code; ?>
	
	    <p style="color: gray;margin:15px 0;"> Current Version <b><?php   echo $current_version = $model->getCurrentVersion();  ?></b></p>
        <p style="color: gray;margin:15px 0;"> New Version <b><?php if(empty($update_version)) {echo '<b style="color:#ccc;">Not Available</b>';}else{ echo '<b style="color:green;">'.$update_version.'</b>';}    ?></b></p>

            
 <fieldset class="adminform">
	<legend>Available updates</legend>
	<?php if (count($this->items)) : ?>
		<table class="table table-striped" >
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL',true); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th class="nowrap">
						<?php echo JText::_('COM_INSTALLER_HEADING_NAME'); ?>
					</th>
					<th class="nowrap">
						<?php echo JText::_('COM_INSTALLER_HEADING_INSTALLTYPE'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_INSTALLER_HEADING_TYPE'); ?>
					</th>
					<th width="10%" class="center">
						<?php echo JText::_('JVERSION'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_INSTALLER_HEADING_FOLDER'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_INSTALLER_HEADING_CLIENT'); ?>
					</th>
					<th width="25%">
						<?php echo JText::_('COM_INSTALLER_HEADING_DETAILSURL'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
				jimport( 'joomla.application.componenet.helper' );
				$module = JComponentHelper::getComponent('com_sms');
			
			foreach ($this->items as $i => $item) :
				if($module->id!=$item->extension_id)
					continue;
				
				$client = $item->client_id ? JText::_('JADMINISTRATOR') : JText::_('JSITE');
			?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<?php echo JHtml::_('grid.id', $i, $item->update_id); ?>
					</td>
					<td>
						<span class="editlinktip hasTooltip">
						<?php echo $this->escape($item->name); ?>
						</span>
					</td>
					<td class="center">
						<?php echo $item->extension_id ? JText::_('COM_INSTALLER_MSG_UPDATE_UPDATE') : JText::_('COM_INSTALLER_NEW_INSTALL') ?>
					</td>
					<td>
						<?php echo JText::_('COM_INSTALLER_TYPE_' . $item->type) ?>
					</td>
					<td class="center">
						<?php echo $item->version ?>
					</td>
					<td class="center">
						<?php echo @$item->folder != '' ? $item->folder : JText::_('COM_INSTALLER_TYPE_NONAPPLICABLE'); ?>
					</td>
					<td class="center">
						<?php echo $client; ?>
					</td>
					<td><?php echo $item->detailsurl ?>
						<?php if (isset($item->infourl)) : ?>
							<br />
							<a href="<?php echo $item->infourl; ?>" target="_blank">
							<?php echo $this->escape($item->infourl); ?>
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php else : ?>
			<div class="alert alert-info">
				<a class="close" data-dismiss="alert" href="javascript:void(0)">&times;</a>
				<?php echo JText::_('COM_INSTALLER_MSG_UPDATE_NOUPDATES'); ?>
			</div>
		<?php endif; ?>
		
	</fieldset>		
            
	</div>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="activation" />
	<?php echo JHtml::_('form.token'); ?>
</form>
