<?php
/**
 * @package		sms paypal plugin
 * @author 		zwebtheme http://www.zwebtheme.com
 * @copyright	Copyright (c) zwebtheme. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;

require_once(dirname(__FILE__) . '/paypal/helper.php');

class  plgSms_paymentPaypal extends JPlugin
{

	

	function __construct($subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/*
	* method buildLayoutPath
	* @layout = ask for tmpl file name, default is default, but can be used others name
	* return propur file to take htmls
	*/
	function buildLayoutPath($layout)
	{
		if(empty($layout)) $layout = "default";
		$app = JFactory::getApplication();

		// core path
		$core_file 	= dirname(__FILE__) . '/' . $this->_name . '/tmpl/' . $layout . '.php';

		// override path from site active template
		$override	= JPATH_BASE .'/templates/' . $app->getTemplate() . '/html/plugins/' . $this->_type . '/' . $this->_name . '/' . $layout . '.php';

		if(JFile::exists($override)){
			$file = $override;
		}else{
  		$file =  $core_file;
		}

		return $file;
	}

	/*
	* method buildLayout
	* @vars = object with product, order, user info
	* @layout = tmpl name
	* Builds the layout to be shown, along with hidden fields.
	* @return html
	*/
	function buildLayout($vars, $layout = 'default' )
	{
		// Load the layout & push variables
		ob_start();
		$layout = $this->buildLayoutPath($layout);
		include($layout);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}




	/*
	* method onSMS_PaymentGetHTML
	* on transection process this function is being used to get html from component
	* @dependent : self::buildLayout()
	* @return html for view
	* @vars : passed from component, all info regarding payment n order
	*/
	function onSMS_PaymentGetHTML($vars, $pg_plugin)
	{
		if($pg_plugin != $this->_name) {
			return;
		}

		$secure_post 			= $this->params->get('secure_post');
		$sandbox 				= $this->params->get('sandbox');
		$vars->sandbox 			= $sandbox;
		$vars->action_url 		= plgSms_paymentPaypalHelper::buildPaymentSubmitUrl($secure_post , $sandbox);

		// If component does not provide cmd
		if (empty($vars->cmd)){
			$vars->cmd = '_xclick';
		}

		//Take this receiver email address from plugin if component not provided it
		if(empty($vars->business)) $vars->business = $this->params->get('business');
        $html = $this->buildLayout($vars);
		return $html;
	}
	
	

	/*
	* method onSMS_PaymentProcesspayment
	* used when we recieve payment from site or thurd party
	* @data : the necessary info recieved from form about payment
	* @return payment process final status
	*/
	function onSMS_PaymentProcesspayment($data, $pay_plugin)
	{
        if($pay_plugin != $this->_name) 
		{
			return;
		}

		$payer_email    = $data['payer_email'];
		$payer_id       = $data['payer_id'];
		$payer_status   = $data['payer_status'];
		$transaction_id = $data['txn_id'];
		$total_paid_amt = $data['mc_gross'];
		$payment_status = $data['payment_status'];
		$payment_type   = $data['payment_type'];
		$txn_type       = $data['txn_type'];
		$payment_date   = $data['payment_date'];
		$order_id       = $data['custom'];

		// Get store data insert
        $paypal_new = new stdClass();
        $paypal_new->payer_email    = $payer_email;
        $paypal_new->payer_id       = $payer_id;
        $paypal_new->payer_status   = $payer_status;
        $paypal_new->transaction_id = $transaction_id;
        $paypal_new->total_paid_amt = $total_paid_amt;
        $paypal_new->payment_status = $payment_status;
        $paypal_new->payment_type   = $payment_type;
        $paypal_new->txn_type       = $txn_type;
        $paypal_new->payment_date   = $payment_date;
        $paypal_new->order_id       = $order_id;
        JFactory::getDbo()->insertObject('#__sms_paypal', $paypal_new);

        if($payment_status == 'Completed'){
            
            // Get payment update
	        $pay_object = new stdClass();
	        $pay_object->id = $order_id;
	        $pay_object->status  = 1;
	        JFactory::getDbo()->updateObject('#__sms_payments', $pay_object, 'id');
        }


        $app = JFactory::getApplication();
        $mge = 'Your payment by paypal are successfully done. Transaction id: '.$transaction_id.' ';
        $app->enqueueMessage($mge, 'Message');
        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=payments' );
        $app->redirect($redirect_link);
        
		return true;
	}

    /**
    ** Get 
    **/
	function onSMS_PaymentTransaction($order_id, $pay_plugin)
	{
        if($pay_plugin != $this->_name) 
		{
			return;
		}

		// Get paypal info
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__sms_paypal'));
		$query->where($db->quoteName('order_id')." = ".$db->quote($order_id));
		$db->setQuery($query);
		$paypal = $db->loadObject();

		$transaction = '<table  width="100%" class="" id="admin-table" style="border: 1px;margin: 0px 0;" >';
		$transaction .='<tr>';
	    $transaction .='<td style="border: 0px;" width="50%" class="payment-to" > <b>Transaction Details</b> <br />';
		$transaction .='<span>Payer Email: '.$paypal->payer_email.'</span> <br />';
		$transaction .='<span>Payer Status: '.$paypal->payer_status.'</span> <br />';
		$transaction .='<span>Transaction ID: '.$paypal->transaction_id.'</span> <br />';
		$transaction .='<span>Payment Status: '.$paypal->payment_status.'</span> <br />';
		$transaction .='<span>Payment Date: '.$paypal->payment_date.'</span> <br />';
		$transaction .='</td>';
		$transaction .='</table>';
        return $transaction;
	}

	
}
