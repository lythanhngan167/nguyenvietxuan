<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die('Restricted access'); 

$model = $this->getModel();
$user		= JFactory::getUser();

$message_id = $this->message->id;
$message_recever_id = $this->message->recever_id;
$message_sender_id = $this->message->sender_id;
$message_recever_name = $this->message->recever_name;
$message_subject = $this->message->subject;
$message_message = $this->message->message;
$message_date_time = $this->message->date_time;
 
//Message list
$messages = $model->getMessageList($message_id);
$avator_message = $model->avator($message_sender_id);
?>

<style type="text/css">                   
	.message_head {background: #f5f5f5;padding: 10px;display: inline-block;width: 96%;margin: 8px 0;}
	.message_head p {}
	.message {padding: 20px 10px;}
	
	.info-bar {display: inline-block;width: 100%;font-size: 12px;color: #666;}
														
	.avator, .message-body {display: inline-block;}
	.avator {width: 10%;}
	.sender .avator,.commone-avator {float: left;}
	.sender .info {float: left;}
	.sender .date-time {float: right;}
	
	.recever .avator {float: right;text-align: right;}
	.recever .info {float: right;}
	.recever .date-time {float: left;}
	.message-body {width: 88%;background: #fff;padding: 1%;}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=message');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
															
		<p class=""> <b><?php echo JText::_('LABEL_MESSAGE_SUBJECT'); ?>: <?php echo $message_subject; ?></b></p>
		<div class="message_head">
		    <p class=""> <b><?php echo $model->senderName($message_sender_id); ?></b> to <i><?php  echo $model->senderName($message_recever_id); ?></i>  <b style="float: right;font-style: italic;font-weight: normal;"><?php echo date( 'd-M-Y g:i A', strtotime($message_date_time)); ?></b></p>
			<div class="avator commone-avator"><img src="<?php echo $avator_message; ?>" alt="" style="width: 50px;height: 50px;" /></div>
			<div class="message-body">
				<span><?php echo $message_message; ?></span>
			</div>
		</div>
		            
		<?php 
		$count = count($messages);
		if(!empty($count)){
			foreach($messages as $message){
				$reply_id = $message->id;
				$reply_sender_id = $message->sender_id;
				$reply_recever_id = $message->recever_id;
				$reply_message = $message->message;
				$reply_date_time = $message->date_time;
				$avator = $model->avator($reply_sender_id);
				if($message_sender_id ==$reply_sender_id){$add_class ='sender';}else{$add_class ='recever';}
									
				echo'<div class="message_head '.$add_class.'">';
				echo'<div class="info-bar"> <span class="info"><b>'.$model->senderName($reply_sender_id).'</b> to <i>'.$model->senderName($reply_recever_id).'</i> </span> <b class="date-time" style="font-style: italic;font-weight: normal;">'. date( 'd-M-Y g:i A', strtotime($reply_date_time)).'</b></div>';
				echo '<div class="avator "><img src="'.$avator.'" alt="" style="width: 50px;height: 50px;" /></div>';
				echo'<div class="message-body"><span>'. $reply_message.'</span></div>';
				echo'</div>';
			}
		}
		?>
		<textarea name="message" cols="" rows="" style="min-height: 100px;width: 98%;" placeholder="Reply"></textarea>
										
		<input type="hidden" name="message_id" value="<?php echo $message_id; ?>"  />
		<input type="hidden" name="sender_id" value="<?php echo $user->id; ?>"  />
		<input type="hidden" name="recever_id" value="<?php echo $message_sender_id; ?>" id="recever_id" />
		<input type="hidden" name="controller" value="message" />
         
	    </div>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
	
	



