<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

    $field_exam = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_EXAM'),'select', 'exam',$this->exam , '','','required');
	$class_select = SmsHelper::getclassList('');
	$field_class = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_CLASS'),'select', 'class',$class_select , '','','required');
	
	$section_select = SmsHelper::getsectionList('');
    $field_section = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_SECTION'),'select', 'section',$section_select , '','','required');
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=marks');?>" method="post" name="adminForm" class="form-horizontal" id="adminForm">
	
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
    <?php endif; ?>
	
			<?php echo $field_exam; ?>
			<?php echo $field_class; ?>
			<?php echo $field_section; ?>
			<div class="control-group">
				<div class="control-label">
	            <label id="class-lbl" class=" required" for="class"><?php echo JText::_('LABEL_MARK_SELECT_SUBJECT'); ?><span class="star"> *</span></label>
	            </div>
				<div class="controls">
					<div id="subject_list">
						<select name="subject" id="subjects" >
							<option value=""><?php echo JText::_('COM_SMS_SELECT_SUBJECT'); ?></option>
						</select>
					</div>
				</div>
			</div>
				
			<div id="status_save" style="text-align: center;position: fixed;width: 100%;top: 41px;z-index: 2147483647;"></div>
			<div class="row-fluid">
	            <div class="span12">
		            <div class="" id="student_list" ></div>
		        </div>
	        </div>
	    </div>
    </div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="marks" />
	<?php echo JHtml::_('form.token'); ?>
</form>
	
<script type="text/javascript">
	// Get Class Event
	jQuery( "#class" ).change(function() { 
	    var class_id = jQuery("#class").val();
		jQuery("#subject_list").html("Loading ...");
		jQuery.post( 'index.php?option=com_sms&task=marks.getsubjectlist',{class_id:class_id}, function(data){
		    if(data){ jQuery("#subject_list").html(data); }
        });
	});
	
	// Get Display Student List
	function desplyStudentList(){
	    var class_id = jQuery("#class").val();
		var section = jQuery("#section").val();
		var exam = jQuery("#exam").val();
		var subject = jQuery("#subjects").val();
	    jQuery("#student_list").html("Loading ...");
		jQuery.post( 'index.php?option=com_sms&task=marks.getMarkList',{class_id:class_id,section:section,exam:exam,subject:subject}, function(data){
		    if(data){ jQuery("#student_list").html(data); }
        });
	}
	
	jQuery( "#exam" ).change(function() { desplyStudentList(); });
	jQuery( "#class" ).change(function() { desplyStudentList(); });
	jQuery( "#section" ).change(function() { desplyStudentList(); });
	
</script>
	
	
