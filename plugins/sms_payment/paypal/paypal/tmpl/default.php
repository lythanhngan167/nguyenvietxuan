<?php
/**
 * @package		sms paypal plugin
 * @author 		zwebtheme http://www.zwebtheme.com
 * @copyright	Copyright (c) zwebtheme. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;
//data-digicom-task="formSubmit" for auto submit the form
$buy_image = $this->params->get('buy_image', 'https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-medium.png');
$preview_image = $this->params->get('preview_image');
?>
<div class="sms-payment-form">

	<div class="preview_image">
		<img src="<?php echo $preview_image; ?>" alt="" style="width: 300px" >
		<h4>Make payments with PayPal - it's fast, free and secure!</h4>
		<p>Please click below button to continue process.</p>
	</div>

	<form data-digicom-task="hide" data-digicom-id="paymentForm" action="<?php echo $vars->action_url ?>" class="form-horizontal" method="post">

		<input type="hidden" name="business" value="<?php echo $vars->business ?>" />
		<input type="hidden" name="custom" value="<?php echo $vars->order_id ?>" />

		<input type="hidden" name="item_name" value="<?php echo $vars->item_name ?>" />
		<input type="hidden" name="amount" value="<?php echo number_format( $vars->amount, 2); ?>" />

		<input type="hidden" name="return" value="<?php echo $vars->return; ?>" />
		<input type="hidden" name="cancel_return" value="<?php echo $vars->cancel_return; ?>" />
		<input type="hidden" name="notify_url" value="<?php echo $vars->notify_url; ?>" />
		<input type="hidden" name="currency_code" value="<?php echo $vars->currency_code; ?>" />
		<input type="hidden" name="no_note" value="1" />

		<input type="hidden" name="rm" value="2" />
		<input type="hidden" name="lc" value="<?php echo !empty($vars->country_code)?$vars->country_code:''; ?>" />
		
		<!--//_cart when manual calc and multiple items-->
		<input type="hidden" name="cmd" value="_xclick" />

		<div >
			<button type="submit" style="border: none;background: transparent;">
				<img src="<?php echo $buy_image; ?>" alt="Make payments with PayPal - it's fast, free and secure!" >
			</button>
			
		</div>

	</form>
	
</div>
