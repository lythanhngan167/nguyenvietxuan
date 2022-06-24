<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
    
    $model = $this->getModel();
		
    $user		= JFactory::getUser();
    $uid =$user->get('id');
	$group_title =  SmsHelper::checkGroup($uid);

	$cy = intVal(date('Y'));
	$cm = intVal(date('m'));
		
	if(!empty($this->parent_id)){
        $student = $model->getStudentList($this->parent_id);
        $student_list = SmsHelper::buildField(JText::_('LABEL_PARENT_SELECT_STUDENT'),'select', 'student',$student , '','','required');
	}else{
        
        $display_report = $model->DisplayAttendance( $this->student->year, $cm, $this->student->id).'</br>';
	}

    $year_id = SmsHelper::selectSingleData('id', 'sms_academic_year', 'year', $cy);
	$year_field = SmsHelper::getyearList($year_id);
	$year_list = SmsHelper::buildField(JText::_('LABEL_STUDENT_YEAR'),'select', 'year',$year_field , '','','required');


    // Month Select Field
    // Month Select Field
    $months = array('', JText::_('COM_SMS_MONTH_JANUARY'), JText::_('COM_SMS_MONTH_FEBRUARY'), JText::_('COM_SMS_MONTH_MARCH'), JText::_('COM_SMS_MONTH_APRIL'),  JText::_('COM_SMS_MONTH_MAY'), JText::_('COM_SMS_MONTH_JUNE'), JText::_('COM_SMS_MONTH_JULY'), JText::_('COM_SMS_MONTH_AUGUST'), JText::_('COM_SMS_MONTH_SEPTEMBER'),JText::_('COM_SMS_MONTH_OCTOBER'), JText::_('COM_SMS_MONTH_NOVEMBER'),JText::_('COM_SMS_MONTH_DECEMBER'),);
    $month_field ='<select id="month" name="month" required="required">';
	for($i = 1; $i <= 12; $i++) {
	$isCurrentMonth = ($i == intVal(date("m"))) ? 'true': 'false';
	$monthName = $months[$i];
	if($isCurrentMonth=="true"){ $selected_month = 'selected="selected"'; }else{$selected_month = ''; } 
	$month_field .='<option value="'.$i.'" '.$selected_month.' >'.$monthName.'</option>';
	}
	$month_field .='</select>';
    $month_list = SmsHelper::buildField(JText::_('LABEL_SELECT_MONTH'),'select', 'payment_month',$month_field , '','','required');
 
    $loader_html = '<div class=\"loader\"></div>';	
?>

<script type="text/javascript">
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		document.getElementById("print").style.visibility = "hidden";
		window.print();
		document.body.innerHTML = originalContents;
		document.location.reload();
	}
</script>


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
						<form action="" class="form-horizontal well">
							<?php if(!empty($this->parent_id)){ echo $student_list; } ?>
							<?php echo $month_list; ?>
						    <?php echo $year_list; ?>
						</form>
					</div>
				</div>
			
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<div id="printableArea" >
						    <?php 
							if(empty($this->parent_id)){
								echo '<input type="hidden" id="student" name="student" value="'.$this->student->id.'">';
							}
							?>
						    <div id="result">
								<?php 
								if(empty($this->parent_id)){
									echo $display_report;
							    }
								?>
							</div>
						</div>
					</div>
				</div>
		    </div>
        </div>
    </div>
</div>	
	
	
<script type="text/javascript">
    jQuery(document).ready(function () {

        function desplyresult(){
			var sid = jQuery("#student").val();
			var year = jQuery("#year").val();
			var month = jQuery("#month").val();
			jQuery("#result").html("<?php echo $loader_html; ?>");
			jQuery.post( 'index.php?option=com_sms&task=attendancereport.getattendancereport',{sid:sid,year:year,month:month}, function(data){
				if(data){ jQuery("#result").html(data); }
            });
		}

        jQuery( "#student" ).change(function() { desplyresult() });
        jQuery( "#year" ).change(function() { desplyresult() });
		jQuery( "#month" ).change(function() { desplyresult() });
				
				
    });//End doc
</script>