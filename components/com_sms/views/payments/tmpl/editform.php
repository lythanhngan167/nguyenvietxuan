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
	}else{
		$disable ='disabled="disabled"';
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
	if(!empty($this->payment->pay_for_id)){$pay_for_id = $this->payment->pay_for_id;}else {$pay_for_id="";}
 
	$comment_field = ' <textarea cols="" rows="" name="comment" class=" "  style="min-height: 20px;">'.$comment.'</textarea>';
    $field_comment = SmsHelper::buildField(JText::_('LABEL_PAYMENT_COMMENT'),'select', 'comment',$comment_field , '');

    // Get Student Name by id
    $student_name = SmsHelper::getStudentname($student_id);

    $items = explode(',', $pay_for_id);
    $item_name = '';
	foreach ($items as $key => $value){
		$item_name .= SmsHelper::selectSingleData('name', 'sms_pay_type', 'id', $value);
		if(!empty($key)){
			$item_name .= ', ';
		}
	}
?>

<style type="text/css">
	.payment-details-list{padding-bottom: 20px;}
	.payment-details-list p{margin-bottom: 2px;}
	.payment-details-list p label{
		display: inline-block;
		width: 214px;
        text-align: right;
        padding-right: 10px;
	}
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
							<h1 class="title"><b style="color: green;"><?php echo $student_name; ?></b> Payment Details</h1>
						</div>
					</div>
				</div>

		        <form action="<?php echo JRoute::_('index.php?option=com_sms&view=payments');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

		            <div class="row">
					    <div class="col-xs-12 col-md-12 payment-details-list">
					
					        <p><label>Student Name: </label> <?php echo $student_name; ?></p>
					        <p><label>Student Roll: </label> <?php echo $student_roll; ?></p>
					        <p><label>Month: </label> <?php echo $month; ?></p>
					        <p><label>Year: </label> <?php echo $year; ?></p>
					        <p><label>Pay Item: </label> <?php echo $item_name; ?></p>
					        <p><label>Total Bill: </label> <?php echo SmsHelper::getCurrency($total_bill); ?></p>
					        <p><label>Paid: </label> <?php echo SmsHelper::getCurrency($paid_ammount); ?></p>
					        <p><label>Due: </label> <?php echo SmsHelper::getCurrency($due_ammount); ?></p>
					        <p><label>Payment Method: </label> <?php echo $payment_method; ?></p>
									
					    </div>
				    </div>

				    <div class="control-group">
	                    <div class="control-label">
	                        <label id="jmark_upto-lbl" class="hasTip required" title="" for="jmark_upto"><?php echo JText::_('LABEL_PAYMENT_STATUS'); ?>:<span class="star"> *</span></label>
	                    </div>
	                    <div class="controls">
	                        <select name="status">
								<option value="0" <?php if($status=="0"){echo $selected = 'selected="selected"';} ?>><?php echo JText::_('COM_SMS_LABEL_STATUS_PENDING'); ?> </option>
								<option value="1"  <?php if($status=="1"){echo $selected = 'selected="selected"';} ?>><?php echo JText::_('COM_SMS_LABEL_STATUS_PAID'); ?> </option>
								<option value="2"  <?php if($status=="2"){echo $selected = 'selected="selected"';} ?>><?php echo JText::_('COM_SMS_LABEL_STATUS_UN_PAID'); ?> </option>
								<option value="3"  <?php if($status=="3"){echo $selected = 'selected="selected"';} ?>><?php echo JText::_('COM_SMS_LABEL_STATUS_CANCEL'); ?> </option>
								<option value="4"  <?php if($status=="4"){echo $selected = 'selected="selected"';} ?>><?php echo JText::_('COM_SMS_LABEL_STATUS_UNDER_REVIEW'); ?> </option>
							</select>
						</div>
                    </div>

                    <?php echo $field_comment; ?>
		        
		            <div class="row-fluid info_box" style="margin-bottom: 20px;padding: 10px 0;">
			            <div class="span12">
			            	<input type="submit" value="<?php echo JText::_('BTN_SUBMIT'); ?>" class="btn" style="width: 120px;margin-left: 10px;" />
			            </div>
			        </div>

				<input type="hidden" name="student_roll" value="<?php echo $student_roll; ?>" />
				<input type="hidden" name="month" value="<?php echo $month; ?>" />
				<input type="hidden" name="year" value="<?php echo $year; ?>" />
				<input type="hidden" name="pay_for_id" value="<?php echo $pay_for_id; ?>" />
				<input type="hidden" name="total_bill" value="<?php echo $total_bill; ?>" />
				<input type="hidden" name="paid_ammount" value="<?php echo $paid_ammount; ?>" />
				<input type="hidden" name="due_ammount" value="<?php echo $due_ammount; ?>" />
				<input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>" />

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