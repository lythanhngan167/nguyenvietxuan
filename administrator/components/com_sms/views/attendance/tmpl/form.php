<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');
$user		= JFactory::getUser();
 
//Collect Data
if(!empty($this->attendance->id)){$id = $this->attendance->id;}else {$id="";}
if(!empty($this->attendance->teacher)){$teacher = $this->attendance->teacher;}else {$teacher="";}
if(!empty($this->attendance->attendance_date)){$attendance_date = $this->attendance->attendance_date;}else {$attendance_date="";}
if(!empty($this->attendance->class)){$class = $this->attendance->class;}else {$class="";}
if(!empty($this->attendance->section)){$section = $this->attendance->section;}else {$section="";}

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	};
");

$model = $this->getModel();
$student_rows = $model->getStudentList($class, $section);
?>
 
<style type="text/css">
#system-message-container {width: 100%;}
 
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=attendance');?>" method="post" name="user-form" id="item-form" class="form-validate form-horizontal" enctype="multipart/form-data">
    <?php 
    $date = JHTML::calendar($attendance_date,'date', 'date', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','required'=>'"required"','class'=>' date-formp  validate[\'required\']',));
    $field_date = SmsHelper::buildField(JText::_('LABEL_ATTENDANCE_SELECT_DATE'),'select', 'date',$date , '','','required');
	$field_class = SmsHelper::buildField(JText::_('LABEL_ATTENDANCE_SELECT_CLASS'),'select', 'class',$this->class , '','','required');
	$field_section = SmsHelper::buildField(JText::_('LABEL_ATTENDANCE_SELECT_SECTION'),'select', 'section',$this->section , '','','required');
    echo $field_date;
	echo $field_class; 
	echo $field_section; 
	?>
	<input type="hidden" name="tid" value="<?php echo $user->id;?>" />
	<input type="hidden" name="aid" value="<?php echo $id; ?>" />

	<input type="hidden" name="controller" value="attendance" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>


<?php 
if(count($student_rows)){ 
    if(count($student_rows)){
		
		// Script for save mark
        $attentdance_display = '<script type="text/javascript">';
        $attentdance_display .= 'jQuery(document).ready(function () {';
     
		//function make
        $attentdance_display .= 'function attentSaving(aid,sid,status,order){';
        $url = "'index.php?option=com_sms&controller=attendance&task=savestatus'";
        $attentdance_display .= 'jQuery("#saving_"+ order).html("Saving ...");';
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
		$attentdance_display .='<th>'.JText::_('LABEL_ATTENDANCE_ROLL').'</th>';
		$attentdance_display .='<th>'.JText::_('LABEL_ATTENDANCE_STUDENT_NAME').'</th>';
		$attentdance_display .='<th>'.JText::_('LABEL_ATTENDANCE_PRESENT_STATUS').'</th>';
		$attentdance_display .='<th>'.JText::_('LABEL_ATTENDANCE_ENTRY_BY').'</th>';
		$attentdance_display .='<th>'.JText::_('LABEL_ATTENDANCE_CRATE').'</th>';
		$attentdance_display .='<th>'.JText::_('LABEL_ATTENDANCE_LAST_UPDATE').'</th>';
		$attentdance_display .='</tr>';

        $i = 0;
        foreach ($student_rows as $row) {
            $i++;
		    $entry_by_uid = $model->getOldatt($id, $row->id,'entry_by');
		    $entry_by = $model->getTeachername($entry_by_uid);
		 
		    $entry_date = $model->getOldatt($id, $row->id,'create_date');
		    $create_date = date( 'Y-m-d g:i A', strtotime($entry_date));
		 
		    $entry_update_date = $model->getOldatt($id, $row->id,'update_date');
		    if(!empty($entry_update_date)){
		        $update_date = date( 'Y-m-d g:i A', strtotime($entry_update_date));
		    }else{
		        $update_date  ='';
		    }
		 
		    $attentdance_display .='<tr>';
		    $attentdance_display .='<td>'.$row->roll.'</td>';
		    $attentdance_display .='<td style="text-align: left;" >'.$row->name.'</td>';
		    $attentdance_display .='<td align="center">';
					 	
			//Check old data
			$check_value = $model->getOldatt($id, $row->id,'attend');
			if(empty($check_value)){
			    $checked ="";
			}else{
			    $checked ='checked="checked"';
			}
			$attentdance_display .='<div id="saving_'.$i.'"></div>';
			$attentdance_display .='<input id="button_'.$i.'" type="checkbox" name="status" value="0" '.$checked.'  /></td>';
			$attentdance_display .='<input id="aid_'.$i.'" type="hidden" name="aid"  value="'.$id.'" />';
			$attentdance_display .='<input id="sid_'.$i.'" type="hidden" name="sid" value="'.$row->id.'" />';
			
			$attentdance_display .='<td style="text-align: center;" >'.$entry_by.'</td>';
			$attentdance_display .='<td style="text-align: center;" >'.$create_date.'</td>';
			$attentdance_display .='<td style="text-align: center;" >'.$update_date.'</td>';
		    $attentdance_display .='</tr>';
        }
		$attentdance_display .='</table>';
		echo $attentdance_display;
	} 
} 
?>


