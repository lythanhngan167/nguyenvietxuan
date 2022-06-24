<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
	$app        = JFactory::getApplication();
	$model      = $this->getModel('payments');
	$session 	= JFactory::getSession();
	$configs 	= JComponentHelper::getComponent('com_sms')->params;
	$input 		= $app->input;
	$method     = JRequest::getVar('method');


    //GET SCHOOLS DATA
	$params = JComponentHelper::getParams('com_sms');
	$schools_name = $params->get('schools_name');
	$schools_address = $params->get('schools_address');
	$schools_phone = $params->get('schools_phone');
	$schools_email = $params->get('schools_email');
	$schools_website = $params->get('schools_web');

 
	if(!empty($this->payment->id)){$id = $this->payment->id;}else {$id="";}
	if(!empty($this->payment->pay_for_id)){$pay_for_id = $this->payment->pay_for_id;}else {$pay_for_id="";}
	if(!empty($this->payment->payment_method)){$payment_method = $this->payment->payment_method;}else {$payment_method="";}
	if(!empty($this->payment->paid_ammount)){$paid_ammount = $this->payment->paid_ammount;}else {$paid_ammount="";}
	if(!empty($this->payment->total_bill)){$total_bill = $this->payment->total_bill;}else {$total_bill="";}
	if(!empty($this->payment->due_ammount)){$due_ammount = $this->payment->due_ammount;}else {$due_ammount="";}
 
    $invID = str_pad($id, 10, '0', STR_PAD_LEFT);
	
    

 
?>


<div id="com_sms" >
    <div class="container-fluid">
        <div class="row">
        	<div class="col-xs-12 col-md-3" id="sms_leftbar">
			    <?php echo $this->smshelper->profile(); ?>
			    <?php echo $this->sidebar; ?>
			</div>
            <div class="col-xs-12 col-md-9">
		     
            <?php 

            if(empty($payment_method)){
				$pay_plugin = $configs->get('default_payment','offline');
			}else{
				$pay_plugin = $payment_method;
			}

            if($method == 'processPayment'){
                $post 		= $input->post->getArray();
                // add post field exception
		        if( !count($post) ) $post = @file_get_contents('php://input');	

		        JPluginHelper::importPlugin('sms_payment', $pay_plugin);
			    $dispatcher = JDispatcher::getInstance();

			    $html = $dispatcher->trigger('onSMS_PaymentProcesspayment', array($post, $pay_plugin));
			    echo $html[0];

            }else{
                
				

				$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

				$cancel_link = $actual_link;
				$url = $actual_link.'?method=processPayment&processor='.$pay_plugin;

                $vars 				 = new stdClass();
				$vars->order_id 	 = $id;
				$vars->user_id 		 = JFactory::getUser()->id;
				$vars->item_name 	 = '';

                $items = explode(',', $pay_for_id);
				foreach ($items as $key => $value){
					
					$vars->item_name .= SmsHelper::selectSingleData('name', 'sms_pay_type', 'id', $value) . ', ';
				}

				$vars->item_name = substr($vars->item_name, 0, strlen($vars->item_name)-2);

				$vars->cancel_return = $cancel_link;

				//processPayment
				$vars->return = $vars->notify_url = $vars->url = $url;
				$vars->currency_code = $configs->get('currency','USD');
				$vars->amount = $paid_ammount;
				

				JPluginHelper::importPlugin('sms_payment', $pay_plugin);
			    $dispatcher = JDispatcher::getInstance();

			    $html = $dispatcher->trigger('onSMS_PaymentGetHTML', array($vars, $pay_plugin));

                if (!isset($html[0])){
					$html[0] = '';
				}

				echo $html[0];

			}

            ?>
            </div> 
        </div>   
    </div> 
</div>    

