<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 

$user		= JFactory::getUser();

?>

<style type="text/css">
    #searchbox { position: relative;}
    #message_suggestions{ position: absolute; z-index: 999999999;margin:-10px 0 0 0px;padding: 0; width:20%; display:none;border: 1px solid #ccc;background: #f5f5f5; }
    .searchresult_ajax:hover {background: #ccc;}
</style>
														
<script type="text/javascript">
    jQuery(document).ready(function () {
	
	    jQuery("#to_selector").change(function(){
            var val = jQuery("#to_selector").val();
			if(val=="student"){
				jQuery("#searchbox").html("Loading ...");
					jQuery.post( 'index.php?option=com_sms&task=message.studentbox',{val:val}, function(data){
					    jQuery("#searchbox").html(data);
                    });        
			}else if(val=="parent"){
				jQuery("#searchbox").html("Loading ...");
					jQuery.post( 'index.php?option=com_sms&task=message.parentbox',{val:val}, function(data){
					    jQuery("#searchbox").html(data);
                    });        
			}else{
				jQuery("#searchbox").html("Loading ...");
				jQuery.post( 'index.php?option=com_sms&task=message.teacherbox',{val:val}, function(data){
					jQuery("#searchbox").html(data);
				});
			}					 
        });			 
	});
</script>
	
<form action="<?php echo JRoute::_('index.php?option=com_sms&view=message');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
	
	        <h1><?php echo JText::_('LABEL_MESSAGE_NEW_TO'); ?> 
	            <select name="" style="margin-top: 10px;" id="to_selector">
	                <option value="teacher">Teacher</option>
	                <option value="student">Student</option>
	                <option value="parent">Parent</option>
	            </select>
	        </h1>
								
		    <span id="searchbox">
			    <input type="text" style="width: 99%;" id="teacher" onkeyup="findteacher()" onblur="blure()" name="recever_name" placeholder="Type teacher name " />
			</span>
			<div id="message_suggestions" style=""></div>
			<input type="text" id="subject" style="width: 99%;"  name="subject" placeholder="Subject" />
			<textarea name="message" style="width: 99%;"></textarea>
			<input type="hidden" name="sender_id" value="<?php echo $user->id; ?>"  />
			<input type="hidden" name="recever_id" value="" id="recever_id" />
			<input type="hidden" name="controller" value="message" />
                   
	    </div>
	</div>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

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
	
	



