<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
//JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.formvalidation');
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('exportForm')))
		{
			Joomla.submitform(task, document.getElementById('exportForm'));
		}
	};

	
");


?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=teachers');?>" method="post" name="adminForm" id="exportForm" class="form-validate form-horizontal" enctype="multipart/form-data">
		
		
		
		<div class="control-group">
        <div class="control-label">
            <label id="jform_to-lbl" class="required " for="jform_to" aria-invalid="true">SMS To:<span class="star"> *</span></label>
        </div>
        <div class="controls">
        <?php 
				
				
				
			 $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		   $model = $this->getModel();
		   $resultstr = array();
       foreach ($cids as $cid) {
		           $data = $model->getStudents($cid);
		           $resultstr[] = $data->mobile_no;
       }
        echo $recipient_value = implode(",",$resultstr);
			  ?>
				<input type="hidden" id="jform_to"  name="mobile_no" value="<?php echo $recipient_value; ?>" />
        </div>
    </div>
		
		
		
		
		<div class="control-group">
        <div class="control-label">
            <label id="jform_message-lbl" class="required " for="jform_message" aria-invalid="true">Message:<span class="star"> *</span></label>
        </div>
        <div class="controls">
        
        <textarea cols="" rows="" name="message" maxlength="160" id="jform_message" class="required " required="required" aria-required="true" aria-invalid="true" style="height: 100px;width: 95%;"></textarea>
				
				</div>
    </div>
		
		
		
		
<input type="hidden" name="option"	value="com_sms" />
<input type="hidden" name="controller" value="teachers" />
<input type="hidden" name="task" value="" />
			 
			 <?php echo JHTML::_( 'form.token' ); ?> 

		</form>
	