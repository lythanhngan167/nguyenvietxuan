<?php
/**
 * @package		sms offline plugin
 * @author 		zwebtheme http://www.zwebtheme.com
 * @copyright	Copyright (c) zwebtheme. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;
$message = $this->params->get('message');
$link = JRoute::_('index.php?option=com_sms&view=payments');
?>
<div class="sms-payment-form">

	<div class="offline_message">
		<b><?php echo $message; ?></b>
	</div>

    <a href="<?php echo $link; ?>" class="btn btn-primary" >Check Payment Status</a>
	
	
</div>
