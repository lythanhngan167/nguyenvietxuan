<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
 
	//Get School Data
	$app = JFactory::getApplication();
	$params = JComponentHelper::getParams('com_sms');
	$schools_name = $params->get('schools_name');
	$schools_address = $params->get('schools_address');
	$schools_phone = $params->get('schools_phone');
	$schools_email = $params->get('schools_email');
	$schools_website = $params->get('schools_web');
 
	$model = $this->getModel('payments');
	if(!empty($this->payment->id)){$id = $this->payment->id;}else {$id="";}
	if(!empty($this->payment->student_class)){$student_class = $this->payment->student_class;}else {$student_class="";}
	if(!empty($this->payment->student_section)){$student_section = $this->payment->student_section;}else {$student_section="";}
	if(!empty($this->payment->student_roll)){$student_roll = $this->payment->student_roll;}else {$student_roll="";}
	if(!empty($this->payment->payment_method)){$payment_method = $this->payment->payment_method;}else {$payment_method="";}
	if(!empty($this->payment->month)){$month = $this->payment->month;}else {$month="";}
	if(!empty($this->payment->year)){$year = $this->payment->year;}else {$year="";}
	if(!empty($this->payment->pay_for_id)){$pay_for_id = $this->payment->pay_for_id;}else {$pay_for_id="";}
	if(!empty($this->payment->paid_ammount)){$paid_ammount = $this->payment->paid_ammount;}else {$paid_ammount="";}
	if(!empty($this->payment->status)){$status = $this->payment->status;}else {$status="";}
	if(!empty($this->payment->create_date)){$create_date = $this->payment->create_date;}else {$create_date="";}
	if(!empty($this->payment->comment)){$comment = $this->payment->comment;}else {$comment="";}
	if(!empty($this->payment->total_bill)){$total_bill = $this->payment->total_bill;}else {$total_bill="";}
	if(!empty($this->payment->due_ammount)){$due_ammount = $this->payment->due_ammount;}else {$due_ammount="";}
 
    $invID = str_pad($id, 10, '0', STR_PAD_LEFT);
	$issue_date = date( 'Y-m-d', strtotime($create_date));
	$status_text = array('Pending','Paid','Un Paid','Cancel','Under Review');
	if(empty($status)){$status_value ='0';}else{$status_value =$status;}
	$paid_status = $status_text[$status_value];
	
	//due ammount
	$due = number_format(($total_bill)-($paid_ammount), 2);
				
	$style ='<style type="text/css">
		#admin-table {font-size: 12px;}
		#admin-table tr td {font-size: 12px;}
		.mark-table tr td,
		.mark-table tr th{border: 1px solid #666;}
		.mark-table tr th{background: #f5f5f5;}
	</style>';


    //Header Information
	$header  = '<h4 class="" style="text-align: center;margin-bottom: 3px;" >'.$schools_name.'</h4>';
	$header .='<p style="text-align: center;"><b>'.JText::_('LABEL_PAYMENT_STUDENT_PAYMENT_INVOICE').'</b></p>';
	$header .= '<table  width="100%" class="" id="admin-table" style="border: 0px;margin: 0px 0;" cellpadding="0" cellspacing="0" >';
	$header .='<tr>';
	$header .='<td style="border: 0px;" width="50%" class="payment-to" > ';
	$header .= '<h4 class="" >'.JText::_('LABEL_PAYMENT_INVOICE').': '.$invID.'</h4>';
	$header .='</td>';
	$header .='<td style="border: 0px;" width="50%" class="bill-to" > ';
	$header .='<span> '.JText::_('LABEL_PAYMENT_ISSUE').': '.$issue_date.'</span> <br />';
	$header .='<span>'.JText::_('LABEL_PAYMENT_STATUS').': '.$paid_status.'</span> <br />';
	$header .='<span>'.JText::_('LABEL_PAYMENT_PAY_BY').': '.$payment_method.'</span> <br />';
	$header .='</td>';
	$header .='</tr>';   
	$header .='</table>';
				
				
	//Student Information
	$bill_info = '<table  width="100%" class="" id="admin-table" style="border: 1px;margin: 0px 0;" cellpadding="0" cellspacing="0" >';
	$bill_info .='<tr>';
	$bill_info .='<td style="border: 0px;" width="50%" class="payment-to" > <b>'.JText::_('LABEL_PAYMENT_INVOICE_PAYMENT_TO').'</b> <br />';
	$bill_info .='<span>'.$schools_name.'</span> <br />';
	$bill_info .='<span>'.$schools_address.'</span> <br />';
	$bill_info .='<span>'.$schools_phone.'</span> <br />';
	$bill_info .='</td>';
	$bill_info .='<td style="border: 0px;" width="50%" class="bill-to" > <b>'.JText::_('LABEL_PAYMENT_INVOICE_BILL_TO').'</b> <br />';
	$bill_info .='<span>'.$this->student_name.'</span> <br />';
	$bill_info .='<span> '.JText::_('LABEL_STUDENT_ROLL').' - '.$student_roll.'</span> <br />';
	$bill_info .='<span> '.JText::_('LABEL_STUDENT_CLASS').' - '.SmsHelper::getClassname($student_class).'</span> <br />';
	$bill_info .='</td>';
	$bill_info .='</tr>';   
	$bill_info .='</table>';

	$invoice_table = $style;
	$invoice_table .= $header;
	$invoice_table .= $bill_info;

	$invoice_table .= '<table  width="100%" class="mark-table" id="admin-table" cellpadding="0" cellspacing="0" >';
	$invoice_table .= '<tr>'; 
	$invoice_table .= '<th style="text-align: center;">#</th>';
	$invoice_table .= '<th style="text-align: center;">'.JText::_('LABEL_PAYMENT_INVOICE_FEES_TYPE').'</th>';
	$invoice_table .= '<th style="text-align: center;">'.JText::_('LABEL_PAYMENT_INVOICE_TOTAL').'</th>';
	$invoice_table .= '</tr>'; 
					
	$i=0;
	$pay_type = explode(",", $pay_for_id);
	foreach($pay_type as $item){
		$i++;
		$payment_type_name = $model->getPayammount($item,'name');
		$payment_type_fee = $model->getPayammount($item,'fee');
		$invoice_table .= '<tr>'; 
		$invoice_table .= '<td align="center"  style="width: 10%;padding: 30px;;">'.$i.'</td>';
		$invoice_table .= '<td style="padding: 30px; "  class="fee-td">'.$payment_type_name.'</td>';
		$invoice_table .= '<td align="right" style="width: 25%;padding: 30px;text-align: right; ">'.SmsHelper::getCurrency($payment_type_fee).'</td>';
		$invoice_table .= '</tr>'; 
	}
				
	$invoice_table .= '<tr>'; 
	$invoice_table .= '<td align="center"  style="width: 10%;padding:10px 30px;border-right: 0 none; "></td>';
	$invoice_table .= '<td align="right" style="padding:10px 30px; border-left: 0 none;" class="fee-td"> <span>'.JText::_('LABEL_PAYMENT_INVOICE_SUB_TOTAL').': </span></td>';
	$invoice_table .= '<td align="right" style="width: 25%;padding:10px 30px; text-align: right;">'.SmsHelper::getCurrency($total_bill).'</td>';
	$invoice_table .= '</tr>'; 
	$invoice_table .= '<tr>'; 
	$invoice_table .= '<td align="center"  style="width: 10%;padding:10px 30px;border-right: 0 none; "></td>';
	$invoice_table .= '<td align="right" style="padding:10px 30px;border-left: 0 none; " class="fee-td"> <span>'.JText::_('LABEL_PAYMENT_INVOICE_PAID').': </span></td>';
	$invoice_table .= '<td align="right" style="width: 25%;padding:10px 30px; text-align: right;">'.SmsHelper::getCurrency($paid_ammount).'</td>';
	$invoice_table .= '</tr>'; 
	$invoice_table .= '<tr>'; 
	$invoice_table .= '<td align="center"  style="width: 10%;padding:10px 30px;border-right: 0 none; "></td>';
	$invoice_table .= '<td align="right" style="padding:10px 30px;border-left: 0 none; " class="fee-td"> <span>'.JText::_('LABEL_PAYMENT_INVOICE_DUE_AMMOUNT').': </span></td>';
	$invoice_table .= '<td align="right" style="width: 25%;padding:10px 30px; text-align: right;">'.SmsHelper::getCurrency($due).'</td>';
	$invoice_table .= '</tr>'; 
    $invoice_table .='</table>';

    //Transaction Information
	if($payment_method != "offline"){
		JPluginHelper::importPlugin('sms_payment', $payment_method);
	    $dispatcher = JDispatcher::getInstance();
	    $html = $dispatcher->trigger('onSMS_PaymentTransaction', array($id, $payment_method));
	    $invoice_table .= $html[0];
    }else{
    	$revier_name = SmsHelper::selectSingleData('name', 'users', 'id', $uid);
    	$review ='<br>
    	<p>Review by '.$revier_name.'</p>
    	<p>Review Comment: '.$comment.'</p>';
    	$invoice_table .= $review;
    }
				
	// Start PDF Genareator
	$file_name ='Student Payment Invoice_'.$invID;
	$file_name_clean = str_replace(" ", "_", $file_name);
	$file_name_clean = strtolower($file_name_clean);

	$html = SmsHelper::buildPDFHTML($invoice_table);
	require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/pdf/dompdf/autoload.inc.php' );
	use Dompdf\Dompdf;
	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$pdf_orientation = $params->get('pdf_orientation');
	$pdf_paper_size = $params->get('pdf_paper_size');
	$dompdf->setPaper($pdf_paper_size, $pdf_orientation);
	$dompdf->render();
	$dompdf->stream($file_name_clean);
	exit;
?>


