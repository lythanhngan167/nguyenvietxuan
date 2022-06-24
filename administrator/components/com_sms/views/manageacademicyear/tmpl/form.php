  <?php 
 /**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
  
  defined('_JEXEC') or die('Restricted access'); 

  if(!empty($this->academic_year->id)){$id = $this->academic_year->id;}else {$id="";}
  if(!empty($this->academic_year->year)){$year = $this->academic_year->year;}else {$year="";}
 
  ?>

  <form action="<?php echo JRoute::_('index.php?option=com_sms&view=manageacademicyear');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
  <div class="row-fluid">
      <div class="span3">
	        <fieldset>
              <div class="control-group">
                   <div class="control-label">
                       <label id="jsection_name-lbl" class="hasTip required" title="" for="jsection_name">Year:<span class="star"> *</span></label>
                   </div>
                   <div class="controls">
                       <input id="jsection_name" class="required" type="text" aria-required="true" required="required" size="30" value="<?php echo $year; ?>" name="year">
                   </div>
               </div>
          </fieldset>
	    </div>
  </div>
  <input type="hidden" name="id" value="<?php echo $id;?>" />
  <input type="hidden" name="controller" value="manageacademicyear" />
  <input type="hidden" name="task" value="" />
  <?php echo JHtml::_('form.token'); ?>
  </form>

