<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::_('behavior.framework');
$model = $this->getModel();
$user = JFactory::getUser();
$user_id = $user->id;
?>

<div id="com_sms" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-3" id="sms_leftbar">
			    <?php echo $this->smshelper->profile(); ?>
			    <?php echo $this->sidebar; ?>
		    </div>
	 
	        <div class="col-xs-12 col-md-9">
				<div class="welcom_div">
		            <h1><?php echo JText::_('DEFAULT_WELCOME_BACK'); ?> <b style="color: green;"><?php echo $this->teacher->name; ?></b></h1>
		        </div>
		 
		        <div class=" message message_box">
		            <h1><?php echo JText::_('DEFAULT_LATEST_MESSAGE'); ?></h1>  
					<table class="message table-striped" style="margin-top: 0px;width: 100%;">
		            <tbody>
		                <?php foreach ($this->message as $i => $item) :
			            $link = JRoute::_( 'index.php?option=com_sms&view=message&task=messagedetails&mid='. $item->id );
						$message_id = $model->unreadMessageByid($item->id);
			            if(!empty($message_id)){
							if($item->sender_id != $user_id ){
								$total_un_read_message = $model->unreadMessageByid($item->id);
								$unread ='<b style="color: green;">('.$total_un_read_message.' '.JText::_('DEFAULT_UNREAD').')</b> ';
							}else{
								$total_un_read_message ="";
								$unread ='';
							}
						}else{
							$total_un_read_message ="";
							$unread ='';
						}
											
						if($item->recever_id==$user_id){
							$from_name = '<b>'.JText::_('DEFAULT_FORM').':</b> '.$model->senderName($item->sender_id);
						}else{
							$from_name = '<b>'.JText::_('DEFAULT_TO').':</b> '.$model->senderName($item->recever_id);
						}
		                ?>
			            <tr class="row<?php echo $i % 2; ?>">
			                <td class="left" width="200px" style="font-style: italic;"><?php echo $from_name; ?> </td>
				            <td class="left"><a href="<?php echo $link; ?>"><b><?php echo $item->subject; ?></b> : <?php echo substr($item->message,0,20) ;?> <?php echo $unread; ?></a> </td>
				            <td class="center" width="150px"><?php echo date( 'g:i A Y-m-d ', strtotime($item->date_time)); ?></td>
			            </tr>
			            <?php endforeach;  ?>
		            </tbody>
	                </table>
		        </div>
	        </div>
        </div>
    </div>
</div>
