<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidator');

$user		= JFactory::getUser();

if(!empty($this->attendance->id)){$id = $this->attendance->id;}else {$id="";}
if(!empty($this->attendance->teacher)){$teacher = $this->attendance->teacher;}else {$teacher="";}
if(!empty($this->attendance->attendance_date)){$attendance_date = $this->attendance->attendance_date;}else {$attendance_date="";}
if(!empty($this->attendance->class)){$class = $this->attendance->class;}else {$class="";}
if(!empty($this->attendance->section)){$section = $this->attendance->section;}else {$section="";}

$model = $this->getModel();
$student_rows = $model->getStudentList($class, $section);
		
    
 ?>
 
<div id="com_sms" >
<div class="container-fluid">
<div class="row">
   <div class="col-xs-12 col-md-3" id="sms_leftbar">
	 <?php echo $this->smshelper->profile(); ?>
	 <?php echo $this->sidebar; ?>
	 </div>
	 
	 <div class="col-xs-12 col-md-9">
	 
	 <div class="row">
			   <div class="col-xs-12 col-md-12">
				     <form action="<?php echo JRoute::_('index.php?option=com_sms&view=attendance');?>" method="post" name="user-form" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data">
                  <table  class="message table-striped" id="admin-table" style="width: 100%;margin-top: 0px;" align="center" >
			               <tr>
				                <th><?php echo JText::_('LABEL_ATTENDANCE_SELECT_DATE'); ?></th>
						            <th><?php echo JText::_('DEFAULT_CLASS'); ?></th>
						            <th><?php echo JText::_('DEFAULT_SECTION'); ?></th>
						            <th>&nbsp;</th>
				             </tr>
				             <tr>
				                <td width="30%"><?php echo JHTML::calendar($attendance_date,'date', 'date', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','required'=>'"required"','class'=>' date-formp  validate[\'required\']',)); ?></td>
				                <td><?php echo $this->class; ?></td>
						        <td><?php echo $this->section; ?></td>
						        <td><input type="submit" value="<?php echo JText::_('BTN_MANAGE_ATTENDANCE'); ?>" class="btn btn-small" /> </td>
				             </tr>
			            </table>
						<input type="hidden" name="tid" value="<?php echo $user->id;?>" />
			            <input type="hidden" name="aid" value="<?php echo $id; ?>" />
		              <input type="hidden" name="controller" value="attendance" />
                  <input type="hidden" name="task" value="saveattend" />
                  <?php echo JHtml::_('form.token'); ?>
             </form>
				 </div>
	 </div>
	 
	 <div class="row">
			   <div class="col-xs-12 col-md-12">
				     <?php if(count($student_rows)){
			  
				     // Script for save mark
			       $attentdance_display = '<script type="text/javascript">';
			       $attentdance_display .= 'jQuery(document).ready(function () {';
			     
					   //function make
			       $attentdance_display .= 'function attentSaving(aid,sid,status,order){';
			       $url = "'index.php?option=com_sms&controller=attendance&task=savestatus'";
			       $loader_html = '<div class=\"loader\"></div>';
			       $attentdance_display .= 'jQuery("#saving_"+ order).html("'.$loader_html.'");';
			       $attentdance_display .= 'jQuery.post( '.$url.',{aid:aid,sid:sid,status:status}, function(data){';
			       $attentdance_display .= 'if(data){ jQuery("#saving_"+ order).html(data); }';
             $attentdance_display .= '});';
			       $attentdance_display .= '}';
			
			       //function call
			       $s =0;
			       foreach ($student_rows as $row_s) {
			       $s++;
			       $attentdance_display .= 'jQuery( "#button_'.$s.'" ).click(function() {';
			       $attentdance_display .= 'attentSaving(jQuery("#aid_'.$s.'").val(),jQuery("#sid_'.$s.'").val(),jQuery("#button_'.$s.'").is(":checked"),'.$s.')';
			       $attentdance_display .= '});';
			       }
					 
			       $attentdance_display .= '});';
			       $attentdance_display .= '</script>';
			
			       $attentdance_display .='<table class="admin-table" id="admin-table" style="width: 100%;margin-top: 10px;" align="center">';
					   $attentdance_display .='<tr>';
						 $attentdance_display .='<th>'.JText::_('LABEL_STUDENT_ROLL').'</th>';
						 $attentdance_display .='<th>'.JText::_('LABEL_STUDENT_NAME').'</th>';
						 $attentdance_display .='<th>'.JText::_('LABEL_PRESENT_STATUS').'</th>';
						 $attentdance_display .='</tr>';
			
			       $i = 0;
			       foreach ($student_rows as $row) {
			       $i++;
						 $attentdance_display .='<tr>';
						 $attentdance_display .='<td>'.$row->roll.'</td>';
						 $attentdance_display .='<td style="text-align: left;" >'.$row->name.'</td>';
						 $attentdance_display .='<td>';
						 	
								//Check old data
								$check_value = $model->getOldatt($id, $row->id);
								if(empty($check_value)){
								$checked ="";
								}else{
								$checked ='checked="checked"';
								}
								
								$attentdance_display .='
	<div class="onoffswitch">
		<input id="button_'.$i.'" class="onoffswitch-checkbox" type="checkbox" name="status" value="0" '.$checked.'  />
    
	    <label class="onoffswitch-label" for="button_'.$i.'">
	        <span class="onoffswitch-inner"></span>
	        <span class="onoffswitch-switch"></span>
	    </label>
    </div>';
								$attentdance_display .='<div id="saving_'.$i.'" class="attendance-button-result" ></div>';
								$attentdance_display .='</td>';

								$attentdance_display .='<input id="aid_'.$i.'" type="hidden" name="aid"  value="'.$id.'" />';
								$attentdance_display .='<input id="sid_'.$i.'" type="hidden" name="sid" value="'.$row->id.'" />';
								
						 $attentdance_display .='</tr>';
             }
				     $attentdance_display .='</table>';
			
			       echo $attentdance_display;
			       }else{
			       	echo JText::_('JGLOBAL_NO_MATCHING_RESULTS');
			       	} ?>
				 </div>
	 </div>


</div>
</div>
</div>
</div>





             



