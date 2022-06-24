<?php 
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die('Restricted access'); 

 if(!empty($this->expense_cat->id)){$id = $this->expense_cat->id;}else {$id="";}
 if(!empty($this->expense_cat->name)){$name = $this->expense_cat->name;}else {$name="";}
 
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=expensecategory');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

    <div class="row-fluid">
        <div class="span3">
	        <fieldset>
                <div class="control-group">
                    <div class="control-label">
                        <label id="jname-lbl" class="hasTip required" title="" for="jname"><?php echo JText::_('LABEL_EXPENSE_CATEGORY_NAME'); ?>:<span class="star"> *</span></label>
                    </div>
                    <div class="controls">
                       <input id="jname" class="required" type="text"  required="required" size="30" value="<?php echo $name; ?>" name="name">
                    </div>
                </div>
            </fieldset>
	    </div>
    </div>

	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="controller" value="expensecategory" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

