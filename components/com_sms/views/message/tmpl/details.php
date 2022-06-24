<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
//JHtml::_('behavior.formvalidator');

$model = $this->getModel();
$user  = JFactory::getUser();

$message_id           = $this->message->id;
$message_recever_id   = $this->message->recever_id;
$message_sender_id    = $this->message->sender_id;
$message_recever_name = $this->message->recever_name;
$message_subject      = $this->message->subject;
$message_message      = $this->message->message;
$message_date_time    = $this->message->date_time;
 
 
//Message list
$messages       = $model->getMessageList($message_id);
$avator_message = $model->avator($message_sender_id);
?>

<style type="text/css">     
	.message_head {background: #f5f5f5;padding: 10px;display: inline-block;width: 96%;margin: 8px 0;}
	.message_head p {}
	.message {padding: 20px 10px;}
	.info-bar {display: inline-block;width: 100%;font-size: 12px;color: #666;}			
	.avator_meg, .message-body {display: inline-block;}
	.avator_meg {width: 10%;}
	.sender .avator_meg,.commone-avator {float: left;}
	.sender .info {float: left;}
	.sender .date-time, .sender .message-body {float: right;}
	.recever .avator_meg {float: right;text-align: right;}
	.recever .info {float: right;}
	.recever .date-time {float: left;}
	.message-body {width: 88%;background: #fff;padding: 1%;min-height: 50px;}
	.avator_m {margin: 0;}
	.megg{display: inline-block; width: 80%;}
	.date{display: inline-block; width: 20%;float: right; text-align: right;font-size: 11px;}
</style>


<div id="com_sms" >															
	<div class="container-fluid">
		<div class="row">
		    <div class="col-xs-12 col-md-3" id="sms_leftbar">
			    <?php echo $this->smshelper->profile(); ?>
			    <?php echo $this->sidebar; ?>
			</div>
			 
			<div class="col-xs-12 col-md-9">
			    <p class=""><b><?php echo JText::_('LABEL_MESSAGE_SUBJECT'); ?>: <?php echo $message_subject; ?></b></p>
				<div class="message_head">
					<div class="avator avator_m commone-avator">
						<img src="<?php echo $avator_message; ?>" alt="" style="width: 50px;height: 50px;" /> <br />
					</div>
					<div class="message-body" style="float: right;">
						<span class="date"><?php echo date( 'g:i A', strtotime($message_date_time)); ?> <br />
							<?php echo date( 'd M y', strtotime($message_date_time)); ?>
						</span>
						<span class="megg"><?php echo $message_message; ?></span>
					</div>
				</div>
			    <?php 
				$count = count($messages);
				if(!empty($count)){
					foreach($messages as $message){
						$reply_id         = $message->id;
						$reply_sender_id  = $message->sender_id;
						$reply_recever_id = $message->recever_id;
						$reply_message    = $message->message;
						$reply_date_time  = $message->date_time;
						$avator           = $model->avator($reply_sender_id);
										 
						if($message_sender_id ==$reply_sender_id){$add_class ='sender';}else{$add_class ='recever';}
						echo'<div class="message_head '.$add_class.'">';
						echo '<div class="avator avator_m avator_meg "><img src="'.$avator.'" alt="" style="width: 50px;height: 50px;" /></div>';
						echo'<div class="message-body"><span class="date">'. date( 'g:i A', strtotime($reply_date_time)).'<br />'. date( 'd M y', strtotime($reply_date_time)).'</span><span class="megg">'. $reply_message.'</span></div>';
						echo'</div>';
					}
				}
				?>
				<form action="<?php echo JRoute::_('index.php?option=com_sms&view=message&task=messagedetails&mid='.$message_id.'');?>" method="post">
				    <textarea name="message" cols="" rows="" style="min-height: 100px;width: 96%;margin-bottom: 15px;" placeholder="<?php echo JText::_('DEFAULT_REPLY'); ?>">
				    	
				    </textarea>
					<input type="submit" value="<?php echo JText::_('BTN_REPLY'); ?>" class="btn btn-small" />
			        <input type="hidden" name="message_id" value="<?php echo $message_id; ?>"  />
					<input type="hidden" name="sender_id" value="<?php echo $user->id; ?>"  />
					<input type="hidden" name="recever_id" value="<?php echo $message_sender_id; ?>" id="recever_id" />
			        <input type="hidden" name="controller" value="message" />
	                <input type="hidden" name="task" value="message_reply" />
	                <?php echo JHtml::_('form.token'); ?>
				</form>
			</div>
		</div>
	</div>
</div>
	



