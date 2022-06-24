<?php 
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
$model = $this->getModel();
if(!empty($this->expense_cat->id)){$id = $this->expense_cat->id;}else {$id="";}
if(!empty($this->expense_cat->title)){$title = $this->expense_cat->title;}else {$title="";}
if(!empty($this->expense_cat->ammount)){$ammount = $this->expense_cat->ammount;}else {$ammount="";}
if(!empty($this->expense_cat->expense_date)){$expense_date = $this->expense_cat->expense_date;}else {$expense_date="";}
if(!empty($this->expense_cat->cat)){$cat = $this->expense_cat->cat;}else {$cat="";}
if(!empty($this->expense_cat->method)){$method = $this->expense_cat->method;}else {$method="";}
if(!empty($this->expense_cat->description)){$description = $this->expense_cat->description;}else {$description="";}
 
$user		= JFactory::getUser();
$uid = $user->id;
$category = $model->getcatList($cat);
 
?>
<style type="text/css">
#system-message-container {width: 100%;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=expenses');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">


<div class="row-fluid">
    <div class="span3">
	    <fieldset>
            <div class="control-group">
                <div class="control-label">
                    <label id="jname-lbl" class="hasTip required" title="" for="jname"><?php echo JText::_('LABEL_EXPENSE_TITLE'); ?>:<span class="star"> *</span></label>
                </div>
               <div class="controls">
                   <input id="jname" class="required" type="text"  required="required" size="30" value="<?php echo $title; ?>" name="title">
               </div>
            </div>
							 
			<div class="control-group">
                <div class="control-label">
                    <label id="jname-lbl" class="hasTip required" title="" for="jname"><?php echo JText::_('LABEL_EXPENSE_CATEGORY'); ?>:<span class="star"> *</span></label>
                </div>
                <div class="controls">
                    <?php echo $category; ?>
                </div>
            </div>
							 
			<div class="control-group">
                <div class="control-label">
                    <label id="jname-lbl" class="hasTip required" title="" for="jname"><?php echo JText::_('LABEL_EXPENSE_DESCRIPTION'); ?>:<span class="star"> *</span></label>
                </div>
                <div class="controls">
                    <textarea cols="" rows="" name="description" style="min-height: 100px;"><?php echo $description; ?></textarea>
                </div>
            </div>
							 
			<div class="control-group">
                <div class="control-label">
                    <label id="jname-lbl" class="hasTip required" title="" for="jname"><?php echo JText::_('LABEL_EXPENSE_AMMOUNT'); ?>:<span class="star"> *</span></label>
                </div>
                <div class="controls">
                    <input id="jname" class="required" type="text"  required="required" size="30" value="<?php echo $ammount; ?>" name="ammount">
                </div>
            </div>
							 
			<div class="control-group">
                <div class="control-label">
                    <label id="jname-lbl" class="hasTip required" title="" for="jname"><?php echo JText::_('LABEL_EXPENSE_METHOD'); ?>:<span class="star"> *</span></label>
                </div>
                <div class="controls">
                    <select name="method">
						<option value="Cash" <?php if($method=="Cash"){echo'selected="selected"';} ?>>Cash</option>
						<option value="Check" <?php if($method=="Cash"){echo'selected="selected"';} ?>>Check</option>
					</select>
                </div>
            </div>
							 
			<div class="control-group">
                <div class="control-label">
                    <label id="jname-lbl" class="hasTip required" title="" for="jname"><?php echo JText::_('LABEL_EXPENSE_DATE'); ?>:<span class="star"> *</span></label>
                </div>
                <div class="controls">
                    <?php echo JHTML::calendar($expense_date,'expense_date', 'expense_date', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','required'=>'"required"','class'=>' date-formp  validate[\'required\']',)); ?>
                </div>
            </div>
				
        </fieldset>
	</div>
</div>


<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="hidden" name="controller" value="expenses" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

