<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

$model    = $this->getModel();
$user	  = JFactory::getUser();
$user_id  = $user->id;
$link_new = JRoute::_( 'index.php?option=com_sms&view=message&task=newmessage' );

?>
<div id="com_sms" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-3" id="sms_leftbar">
		        <?php echo $this->smshelper->profile(); ?>
		        <?php echo $this->sidebar; ?>
	        </div>
	 
	        <div class="col-xs-12 col-md-9">
	            <form action="<?php echo JRoute::_('index.php?option=com_sms&view=message');?>" method="post">
	                
	                <table class="message table-striped" style="margin-top: 0px;margin-bottom: 20px;">
			            <thead>
			            <tr>
				            <th width="1%" class="nowrap left"><?php //echo JHtml::_('grid.checkall'); ?></th>
				            <th  class=" left">
				            	<a href="<?php echo $link_new; ?>" class="btn btn-small"><?php echo JText::_('BTN_NEW_MESSAGE'); ?></a> 
				            	<input type="submit" id="delete_message" value="<?php echo JText::_('BTN_DELETE_MESSAGE'); ?>" class="btn btn-small" />  
				            </th>
				            <th  class=" center">&nbsp;</th>
			            </tr>
		                </thead>
		            </table>
		
		            <?php if (empty($this->items)) : ?>
			            <div class="alert alert-no-items">
				            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			            </div>
		            <?php else : ?>
	
	                <table class="message table-striped" style="margin-top: 20px;width: 100%;">
		                <tbody>
		                <?php foreach ($this->items as $i => $item) :
			            $checked 	= JHTML::_('grid.id',   $i, $item->id );
		                $link 		= JRoute::_( 'index.php?option=com_sms&view=message&task=messagedetails&mid='. $item->id );
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
			                <td class="left" width="15px"><?php echo $checked; ?></td>
				            <td class="left" width="200px" style="font-style: italic;"><?php echo $from_name; ?></td>
				            <td class="left">
				            	<a href="<?php echo $link; ?>">
				            		<b><?php echo $item->subject; ?></b> : 
				            		<?php echo substr($item->message,0,20) ;?> <?php echo $unread; ?>
				            	</a> 
				            </td>
				            <td class="center" width="150px"><?php echo date( 'g:i A Y-m-d ', strtotime($item->date_time)); ?></td>
			            </tr>
			            <?php endforeach;  ?>
		                </tbody>
		
			            <tfoot>
							<tr>
								<td colspan="10">
									<?php echo $this->pagination->getListFooter(); ?>
								</td>
							</tr>
						</tfoot>
					</table>
	                <?php endif;?>
	
		            <input type="hidden" name="controller" value="message" />
                    <input type="hidden" id="task" name="task" value=""  />
                    <?php echo JHtml::_('form.token'); ?>
		        </form>
	        </div>
        </div>
    </div>
</div>	
	
<script type="text/javascript">
	jQuery( "#delete_message" ).click(function() {
	    jQuery("#task").val('delete');
	});
</script>
