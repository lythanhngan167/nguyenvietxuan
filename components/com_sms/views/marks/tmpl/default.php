<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

$model  = $this->getModel();
$user	 = JFactory::getUser();
$userid = $user->get( 'id' ); 
        
$field_exam     = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_EXAM'),'select', 'exam',$this->exam , '','','required');
 
$class_data     = SmsHelper::selectSingleData('class', 'sms_teachers', 'user_id', $userid);
$class_select   = SmsHelper::getclassList($class_data,'','1');
$field_class    = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_CLASS'),'select', 'class',$class_select , '','','required');
 
$section_data   = SmsHelper::selectSingleData('section', 'sms_teachers', 'user_id', $userid);
$section_select = SmsHelper::getsectionList($section_data,'','1');
$field_section  = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_SECTION'),'select', 'section',$section_select , '','','required');
 
$subject_data   = SmsHelper::selectSingleData('subject', 'sms_teachers', 'user_id', $userid);
$subject_select = SmsHelper::getsubjectList($subject_data,'','1');
$field_subject  = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_SUBJECT'),'select', 'subject',$subject_select , '','','required');

$loader_html = '<div class=\"loader\"></div>';
?>

<script type="text/javascript">
	jQuery(document).ready(function () {

	    function desplyStudentList(){
		    var classid = jQuery("#class").val();
			var section = jQuery("#section").val();
			var exam = jQuery("#exam").val();
			var subject = jQuery("#subject").val();
			jQuery("#result").html("<?php echo $loader_html; ?>");
			jQuery.post( 'index.php?option=com_sms&task=marks.getstudentlist',{classid:classid,section:section,exam:exam,subject:subject}, function(data){
				if(data){ jQuery("#result").html(data); }
	        });
		}
	        
				
		jQuery( "#exam" ).change(function() { desplyStudentList() });
		jQuery( "#section" ).change(function() { desplyStudentList() });
	    jQuery( "#subject" ).change(function() { desplyStudentList() });
	    jQuery( "#class" ).change(function() { desplyStudentList() });
					
					
	});//End doc
</script>

<div id="com_sms" >
	<div class="container-fluid">
	    <div class="row">

	        <!-- Set Profile Sidebar -->
	        <div class="col-xs-12 col-md-3" id="sms_leftbar">
		        <?php echo $this->smshelper->profile(); ?>
		        <?php echo $this->sidebar; ?>
		    </div>
		 
		    <!-- Set main mark input body -->
		    <div class="col-xs-12 col-md-9">
		 
		        <!-- selection field -->
		        <div class="row">
				   <div class="col-xs-12 col-md-12">
					    <form action="" class="form-horizontal well">
					        <?php echo $field_exam; ?>
							<?php echo $field_class; ?>
							<?php echo $field_section; ?>
							<?php echo $field_subject; ?>
						</form>
					 </div>
				</div>
		    
				<!-- result box -->
				<div class="row">
				    <div class="col-xs-12 col-md-12">
					    <div id="printableArea" >
					        <div id="result">
							    <div class="alert alert-no-items"><?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?> <?php echo JText::_('LABEL_PLEASE_SELECT_EXAM'); ?></div>
						    </div>
						</div>
					</div>
				</div>
		
	        </div>
	    </div>
	</div>
</div>		
		
		
