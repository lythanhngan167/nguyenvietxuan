<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidation');

	$model = $this->getModel();
	$user		= JFactory::getUser();
	$uid =$user->get('id');
	$group_title =  SmsHelper::checkGroup($uid);

	$app = JFactory::getApplication();
	$params = JComponentHelper::getParams('com_sms');
	$year_range_start = $params->get('year_range_start');
	$year_range_end = $params->get('year_range_end');
		
	if($this->group_title=="Teachers"){
		$disable ='';
		$class_id = SmsHelper::selectSingleData('class', 'sms_teachers', 'user_id', $uid);
		$class_field_for_teacher = '<input type="hidden" id="class_id" name="student_class" value="'.$class_id.'" />';
	}else{
		$disable ='disabled="disabled"';
		$class_field_for_teacher = '';
	}

	if(!empty($this->payment->id)){$id = $this->payment->id;}else {$id="";}
	if(!empty($this->payment->student_roll)){$student_roll = $this->payment->student_roll;}elseif(!empty($this->student_roll)){$student_roll = $this->student_roll;}else {$student_roll="";}
	if(!empty($this->payment->student_id)){$student_id = $this->payment->student_id;}elseif(!empty($this->student_id)){$student_id = $this->student_id;}else {$student_id="";}
	if(!empty($this->payment->month)){$month = $this->payment->month;}else {$month="";}
	if(!empty($this->payment->year)){$year = $this->payment->year;}else {$year="";}
	if(!empty($this->payment->paid_ammount)){$paid_ammount = $this->payment->paid_ammount;}else {$paid_ammount="";}
	if(!empty($this->payment->status)){$status = $this->payment->status;}else {$status="";}
	if(!empty($this->payment->comment)){$comment = $this->payment->comment;}else {$comment="";}
	if(!empty($this->payment->total_bill)){$total_bill = $this->payment->total_bill;}else {$total_bill="";}
	if(!empty($this->payment->due_ammount)){$due_ammount = $this->payment->due_ammount;}else {$due_ammount="";}
	if(!empty($this->payment->payment_method)){$payment_method = $this->payment->payment_method;}else {$payment_method="";}
 
	//Pay Month
	$months = array('', JText::_('COM_SMS_MONTH_JANUARY'), JText::_('COM_SMS_MONTH_FEBRUARY'), JText::_('COM_SMS_MONTH_MARCH'), JText::_('COM_SMS_MONTH_APRIL'),  JText::_('COM_SMS_MONTH_MAY'), JText::_('COM_SMS_MONTH_JUNE'), JText::_('COM_SMS_MONTH_JULY'), JText::_('COM_SMS_MONTH_AUGUST'), JText::_('COM_SMS_MONTH_SEPTEMBER'),JText::_('COM_SMS_MONTH_OCTOBER'), JText::_('COM_SMS_MONTH_NOVEMBER'),JText::_('COM_SMS_MONTH_DECEMBER'),);
	$pay_month ='<select id="select_month" name="month" required="required">';
	for($i = 1; $i <= 12; $i++) {
		if(!empty($month)){$isCurrentMonth="false";}else{$isCurrentMonth = ($i == intVal(date("m"))) ? 'true': 'false';}
		$monthName = $months[$i]; 
		if($isCurrentMonth=="true"){ $selected_month = 'selected="selected"'; }else{$selected_month = ''; } if($month==$i){$selected_month = 'selected="selected"';} 
	    $pay_month .='<option value="'.$i.'" '.$selected_month.' >'.$monthName.'</option>';
	}
	$pay_month .='</select>';
 
 
	//YEAR
	$pay_year ='<select id="select_month" name="year" required="required">';
	for($i = $year_range_start; $i <= $year_range_end; $i++) {
		if(!empty($year)){$isCurrentY="false"; }else{ $isCurrentY = ($i == intVal(date("Y"))) ? 'true': 'false';}
		if($isCurrentY=="true"){$selected_year = 'selected="selected"'; }else{$selected_year = ''; } if($year==$i){$selected_year = 'selected="selected"';}
	    $pay_year .='<option value="'.$i.'" '.$selected_year.' >'.$i.'</option>';						 
	}
	$pay_year .='</select>';


    if(!empty($this->parent_id)){
        $student = $model->getStudentList($this->parent_id);
        $student_list = SmsHelper::buildField(JText::_('LABEL_PARENT_SELECT_STUDENT'),'select', 'student',$student , '','','required');
	}
 
 
    $class_select = SmsHelper::getclassList($this->classid,'disabled="disabled"');
	$field_class = SmsHelper::buildField(JText::_('LABEL_STUDENT_CLASS'),'select', 'class',$class_select , '','','required');
		
	if($this->group_title=="Teachers"){
		// Section Filder
		$section_ids = explode(",", $this->sectionid); 
		$section_filter = '<select id="section_filter" name="student_section" required="required" >';
		$section_filter .= '<option value="" > Select Section </option>';
		foreach ($section_ids as $f=> $section_id) {
			$section_filter .= '<option value="'.$section_id.'"> '.SmsHelper::getSectionname($section_id).' </option>';
        }
		$section_filter .= '</select>';
		 
		$field_section = SmsHelper::buildField(JText::_('LABEL_STUDENT_SECTION'),'select', 'section',$section_filter , '','','required');
	}else{
		$section_select = SmsHelper::getsectionList($this->sectionid,$disable);
		$field_section = SmsHelper::buildField(JText::_('LABEL_STUDENT_SECTION'),'select', 'section',$section_select , '','','required');
	}
		
	$field_roll = SmsHelper::buildField(JText::_('LABEL_STUDENT_ROLL'),'input', 'roll',$student_roll , '','','required',$disable);
	

    // Set Payment Method
    $sms_payment_plugin_list = JPluginHelper::getPlugin('sms_payment');

    $payment_method_data = array();
    $payment_method_data[] = array('value' => '', 'text' => 'Payment Method');
    foreach ($sms_payment_plugin_list as $sms_payment_plugin) {
        $payment_method_data[] = array('value' => $sms_payment_plugin->name, 'text' => $sms_payment_plugin->name);
    }
	$payment_method_list =  JHTML::_('select.genericList', $payment_method_data, 'payment_method', 'class="required  inputbox  " required="required"  ', 'value', 'text',$payment_method);

	$field_paymethod = SmsHelper::buildField(JText::_('LABEL_PAYMENT_SELECT_METHOD'),'select', 'payment_method',$payment_method_list , '','','required');
    $field_pay_month = SmsHelper::buildField(JText::_('LABEL_PAYMENT_SELECT_MONTH'),'select','payment_month',$pay_month, '','','required');
 
    $field_pay_year = SmsHelper::buildField(JText::_('LABEL_PAYMENT_SELECT_YEAR'),'select', 'payment_year',$pay_year , '','','required');
	$field_paytype = SmsHelper::buildField(JText::_('LABEL_PAYMENT_SELECT_TYPE'),'select', 'pay_for_id',$this->paytype , '','','required');
    $field_pay_ammount = SmsHelper::buildField(JText::_('LABEL_PAYMENT_PAID_AMMOUNT'),'input', 'paid_ammount',$paid_ammount , '','','required');
 
    $comment_field = ' <textarea cols="" rows="" name="comment" class=" "  style="min-height: 20px;">'.$comment.'</textarea>';
    $field_comment = SmsHelper::buildField(JText::_('LABEL_PAYMENT_COMMENT'),'select', 'comment',$comment_field , '');
 
    //set css
    $document = JFactory::getDocument();
    $document->addStyleSheet('components/com_sms/asset/css/sumoselect.css');


    
?>
<script type="text/javascript" src="components/com_sms/asset/js/jquery.sumoselect.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        window.asd = jQuery('.payment_type').SumoSelect({ csvDispCount: 3 });
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        //###################################### GET SECTION
		jQuery( "#student_class" ).change(function() {
		    var val = jQuery("#student_class").val();
		    jQuery("#section_div").html("Loading ...");
			jQuery.post( 'index.php?option=com_sms&task=payments.getsection',{val:val}, function(data){
				if(data){ jQuery("#section_div").html(data); }
            });
	    });
				
		jQuery( "#pay_for_id" ).change(function() {
		    var val = jQuery("#pay_for_id").val();
			jQuery("#bill").html("Loading ...");
		    jQuery.post( 'index.php?option=com_sms&task=payments.bill',{val:val}, function(data){
			    if(data){ jQuery("#bill").html(data); }
            });
			jQuery.post( 'index.php?option=com_sms&task=payments.due',{val:val}, function(data){
			    if(data){ jQuery("#due").html(data); }
            });		
        });
		
		jQuery( "#jform_paid_ammount" ).keyup(function() {
		    var totalbill = jQuery("#total_bill_value").val();
			var paid      = jQuery("#jform_paid_ammount").val();
			var due       = Number( Number(totalbill) - Number(paid)).toFixed(2); 
			var due_html ='<p style="color: red;font-size: 150%;" id="total_bill"> ' + due + '</p><input type="hidden" name="due_ammount" value="' + due + '" />'
		    jQuery("#due").html("Loading ...");
			jQuery("#due").html(due_html); 
        });
		
		//###################################### ROLL CHECKING
		jQuery( "#student_roll" ).keyup(function() {
		    var val = jQuery("#student_roll").val();
			var student_class = jQuery("#class_id").val();
			var student_section = jQuery("#section_filter").val();
		    jQuery("#roll_checking").html("Loading ...");
			jQuery.post( 'index.php?option=com_sms&task=payments.checkroll',{val:val,student_class:student_class,student_section:student_section}, function(data){
			    if(data){ jQuery("#roll_checking").html(data); }
            });
        });
						
	});//End doc
</script>
<style type="text/css">
    select,input[type="text"], .SlectBox {
	    width: 170px;
    } 
    .SumoSelect,
    .SlectBox{width: 100%;}
</style>

<div id="com_sms" >
	<div class="container-fluid">
		<div class="row">
		    <div class="col-xs-12 col-md-3" id="sms_leftbar">
				<?php echo $this->smshelper->profile(); ?>
				<?php echo $this->sidebar; ?>
			</div>
			 
			<div class="col-xs-12 col-md-9">
			    <div class="row ">
					<div class="col-xs-12 col-md-12 ">
					    <div class="row-fluid info_box" style="margin-bottom: 20px;padding: 10px 0;">
							<h1 class="title"><span class="fa fa-money"></span> <?php echo JText::_('LABEL_STUDENT_PAY_FORM'); ?></h1>
						</div>
					</div>
				</div>

		       <form action="<?php echo JRoute::_('index.php?option=com_sms&view=payments');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

		       <div class="row">
					<div class="col-xs-12 col-md-12">
					<?php 
		            if($group_title=="Parents"){
		                echo $student_list;
		            }elseif($group_title=="Students"){
					    echo $field_class;
					    echo $field_section; 
		            }else{
		            	echo $field_section; 
		            ?>
		            <div class="control-group">
		                <div class="control-label">
		                    <label id="jgrade_point-lbl" class="hasTip required" title="" for="student_roll"><?php echo JText::_('LABEL_STUDENT_ROLL'); ?>:<span class="star"> *</span></label>
		                </div>
	                    <div class="controls">  
	                        <input id="student_roll" class="required" type="text" <?php echo $disable; ?> required="required" size="30" value="<?php echo $student_roll; ?>" name="student_roll">
	                        <div id="roll_checking" style="margin-top: 10px;">
	                       	    <input type="hidden" id="student_id" name="student_id" value="<?php echo $student_id; ?>" />
	                        </div>
						</div>
		            </div>
		            <?php
		            }
		            ?>
						
				    <?php echo $field_pay_month; ?>
				    <?php echo $field_pay_year; ?>

					<?php echo $field_paytype ?>
					    <div class="control-group">
		                    <div class="control-label">
		                        <label id="jgrade_point-lbl" class="hasTip required" title="" for="jgrade_point"><?php echo JText::_('LABEL_PAYMENT_TOTAL_BILL'); ?>:<span class="star"> *</span></label>
		                    </div>
		                    <div class="controls">
								<div id="bill" style="margin-top: 10px;">
									<p style="color: green;font-size: 150%;" id="total_bill"><?php echo $total_bill; ?></p>
										<input type="hidden" id="total_bill_value" name="total_bill" value="<?php echo $total_bill; ?>" />
						        </div>
							</div>
		                </div>
									
						<?php echo $field_pay_ammount; ?>

						<div class="control-group">
		                    <div class="control-label">
		                        <label id="jgrade_point-lbl" class="hasTip required" title="" for="jgrade_point"><?php echo JText::_('LABEL_PAYMENT_DUE_AMMOUNT'); ?>:<span class="star"> *</span></label>
		                    </div>
		                    <div class="controls">
								<div id="due" style="margin-top: 10px;">
									<p style="color: red;font-size: 150%;" id="due_bill"><?php echo $due_ammount; ?></p>
									<input type="hidden" name="due_ammount" value="<?php echo $due_ammount; ?>" />
								</div>
							</div>
		                </div>

		                <?php echo $field_paymethod; ?>
									
					</div>
				</div>
		        
		    <div class="row-fluid info_box" style="margin-bottom: 20px;padding: 10px 0;">
			   <div class="span12"><input type="submit" value="<?php echo JText::_('BTN_SUBMIT'); ?>" class="btn" style="width: 120px;margin-left: 10px;" /></div>
			 </div>


		    <?php if($this->group_title=="Students"){ ?>
		    <input type="hidden" name="student_roll" value="<?php echo $student_roll; ?>" />
		    <?php } ?>

		    <?php if($this->group_title=="Teachers"){ echo $class_field_for_teacher; } ?>
			<input type="hidden" name="status" value="0" />
			<input type="hidden" name="uid" value="<?php echo $user->id;?>" />
			<input type="hidden" name="id" value="<?php echo $id;?>" />
			<input type="hidden" name="controller" value="payments" />
			<input type="hidden" name="task" value="save" />
			<?php echo JHtml::_('form.token'); ?>
			</form>
		    </div>
		</div>
	</div>
</div>