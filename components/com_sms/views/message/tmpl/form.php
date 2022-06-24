<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidator');

$user		 = JFactory::getUser();
$uid         = $user->get('id');
$group_title =  SmsHelper::checkGroup($uid);

$loader_html = '<div class=\"loader\"></div>';
?>

<style type="text/css">
	#searchbox { position: relative;}
	#message_suggestions{ position: absolute; z-index: 999999999;margin:-10px 0 0 0px;padding: 0; width:20%; display:none;border: 1px solid #ccc;background: #f5f5f5; }
	.searchresult_ajax:hover {background: #ccc;}
	#to_selector {font-family: arial !important;font-size: 14px !important;color: #666 !important;}
	#teacher, #student, #parent, #subject , #messagearea{width: 100%;margin: 8px 0 !important;}
</style>

															
<script type="text/javascript">
    jQuery(document).ready(function () {
	    
	    jQuery("#to_selector").change(function(){
            var val = jQuery("#to_selector").val();
			if(val=="student"){
				jQuery("#searchbox").html("<?php echo $loader_html; ?>");
				jQuery.post( 'index.php?option=com_sms&task=message.studentbox',{val:val}, function(data){
					jQuery("#searchbox").html(data);
                }); 

            }else if(val=="parent"){
				jQuery("#searchbox").html("<?php echo $loader_html; ?>");
				jQuery.post( 'index.php?option=com_sms&task=message.parentbox',{val:val}, function(data){
				    jQuery("#searchbox").html(data);
                });          
			}else{
				jQuery("#searchbox").html("<?php echo $loader_html; ?>");
				jQuery.post( 'index.php?option=com_sms&task=message.teacherbox',{val:val}, function(data){
					jQuery("#searchbox").html(data);
				});
			}
								 
        });
					 
					 
	});//End doc
</script>
	
<div id="com_sms" >														
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-3" id="sms_leftbar">
			    <?php echo $this->smshelper->profile(); ?>
			    <?php echo $this->sidebar; ?>
			</div>
	 
	        <div class="col-xs-12 col-md-9">
	            <h1><?php echo JText::_('LABEL_MESSAGE_TO'); ?>
	                <select name="" style="margin-top: 10px;" id="to_selector">
	                    <option value="teacher">Teacher</option>
	                    <?php if($group_title=="Students" || $group_title=="Teachers"){ ?>
							<option value="student">Student</option>
						<?php } ?>
						<?php if($group_title=="Teachers"){ ?>
							<option value="parent">Parents</option>
						<?php } ?>
	               </select>
	            </h1>
							
	            <form action="<?php echo JRoute::_('index.php?option=com_sms&view=message');?>" method="post">
		            <span id="searchbox">
		                <input type="text" id="teacher" class="required" required="required" onkeyup="findteacher()" onblur="blure()" name="recever_name" placeholder="<?php echo JText::_('LABEL_TYPE_TEACHER_NAME'); ?> " />
		            </span>
		            <div id="message_suggestions" style=""></div>
		            <input type="text" id="subject" class="required" required="required"  name="subject" placeholder="<?php echo JText::_('LABEL_TYPE_SUBJECT'); ?>" />
		            <textarea name="message" class="required" required="required" id="messagearea"></textarea>
				    <input type="submit" value="<?php echo JText::_('BTN_SEND'); ?>" class="btn btn-small" />
		            <input type="hidden" name="sender_id" value="<?php echo $user->id; ?>"  />
					<input type="hidden" name="recever_id" value="" id="recever_id" />
		            <input type="hidden" name="controller" value="message" />
                    <input type="hidden" name="task" value="message_save" />
                    <?php echo JHtml::_('form.token'); ?>
		        </form>
	        </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	// Get Teacher
	function lookteacher(oname, ovalue) {
		jQuery("#recever_id").val(ovalue);
		jQuery("#teacher").val(oname);
	} 

	// Get Student
	function lookstudent(oname, ovalue) {
		jQuery("#recever_id").val(ovalue);
		jQuery("#student").val(oname);
	}

	// Get Parent
	function lookparent(oname, ovalue) {
		jQuery("#recever_id").val(ovalue);
		jQuery("#parent").val(oname);
	} 
			
    // Find Teacher			
	function findteacher(){
	    var val = jQuery("#teacher").val();
		if(val) {
			jQuery("#message_suggestions").html("Loading ...");
			jQuery.post( 'index.php?option=com_sms&task=message.findteacher',{val:val}, function(data){
				jQuery('#message_suggestions').fadeIn();
				jQuery("#message_suggestions").html(data);
            });        
	    }else{
		    jQuery('#message_suggestions').fadeOut();
		} 
	}
	
	// Find Student
	function findstudent(){
	    var val = jQuery("#student").val();
		if(val) {
            jQuery("#message_suggestions").html("Loading ...");
            jQuery.post( 'index.php?option=com_sms&task=message.findstudent',{val:val}, function(data){
				jQuery('#message_suggestions').fadeIn();
                jQuery("#message_suggestions").html(data);
            });        
	    }else{
			jQuery('#message_suggestions').fadeOut();
		} 
	}

	// Find Parent
	function findparent(){
	    var val = jQuery("#parent").val();
		if(val) {
			jQuery("#message_suggestions").html("Loading ...");
			jQuery.post( 'index.php?option=com_sms&task=message.findparent',{val:val}, function(data){
				jQuery('#message_suggestions').fadeIn();
				jQuery("#message_suggestions").html(data);
            });        
	    }else{
			jQuery('#message_suggestions').fadeOut();
		} 
	}
	
	function blure(){
	    jQuery('#message_suggestions').fadeOut();
	}
</script>
	
	



