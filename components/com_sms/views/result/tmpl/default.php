<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
    $model = $this->getModel();
    $user		= JFactory::getUser();
    $uid =$user->get('id');
    $group_title =  SmsHelper::checkGroup($uid);

    if(!empty($this->parent_id)){
        $student = $model->getStudentList($this->parent_id);
        $student_list = SmsHelper::buildField(JText::_('LABEL_PARENT_SELECT_STUDENT'),'select', 'student',$student , '','','required');
	}
		
	$field_roll = SmsHelper::buildField(JText::_('LABEL_STUDENT_ROLL'),'input', 'roll','' , '','','required');
	$field_exam = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_EXAM'),'select', 'exam',$this->exam , '','','required');

	$class_data = SmsHelper::selectSingleData('class', 'sms_teachers', 'user_id', $uid);
    $class_select = SmsHelper::getclassList($class_data,'','1');
    $field_class = SmsHelper::buildField(JText::_('LABEL_MARK_SELECT_CLASS'),'select', 'class',$class_select , '','','required');
	
	$loader_html = '<div class=\"loader\"></div>';	
?>

<script type="text/javascript">
    function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		document.getElementById("print").style.visibility = "hidden";
		window.print();
		document.body.innerHTML = originalContents;
		document.location.reload();
    }
</script>


<div id="com_sms" >
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-xs-12 col-md-3" id="sms_leftbar">
		        <?php echo $this->smshelper->profile(); ?>
		        <?php echo $this->sidebar; ?>
		    </div>
		 
		    <div class="col-xs-12 col-md-9">
		 
		        <div class="row">
				    <div class="col-xs-12 col-md-12">
					    <form action="" class="form-horizontal well">
							<?php 
							echo $field_exam; 

							if($group_title=="Teachers"){
							    echo $field_class;
							    echo $field_roll;
							} 
							
							if($group_title=="Parents" || $group_title=="Students" ){ 

	                            if(!empty($this->parent_id)){
	                                echo $student_list;
	                            }else{
	                          	    echo '<input type="hidden" name="roll" id="jform_roll"  value="'.$this->student->roll.'"  />';
	                          	    echo '<input type="hidden" name="class" id="class"  value="'.$this->student->class.'"  />';
	                            }

							} 
							?>
							<input type="hidden" id="group_title" name="g" value="<?php echo $group_title; ?>" />
					    </form>
					</div>
				</div>
		
				<div class="row">
				    <div class="col-xs-12 col-md-12">
					    <div id="printableArea" >
					        <div id="result">
							    <div class="alert alert-no-items"><?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?> <?php echo JText::_('LABEL_PLEASE_SELECT_EXAM'); ?> </div>
							</div>
						</div>
					</div>
				</div>
				
	        </div>
	    </div>
	</div>
</div>	
	
	
<script type="text/javascript">
    jQuery(document).ready(function () {

	    function desplyresult(){
	    	var group_title = jQuery("#group_title").val();
			var classid     = jQuery("#class").val();
			var roll        = jQuery("#jform_roll").val();
			var exam        = jQuery("#exam").val();
			jQuery("#result").html("<?php echo $loader_html; ?>");
			jQuery.post( 'index.php?option=com_sms&task=result.getresult',{group_title:group_title, classid:classid,roll:roll,exam:exam}, function(data){
				if(data){ jQuery("#result").html(data); }
	        });
		}
	    jQuery( "#exam" ).change(function() { desplyresult() });

	    <?php  if($group_title=="Parents"){ ?>
			jQuery( "#jform_roll" ).change(function() {desplyresult();});
		<?php } ?>

		<?php  if($group_title=="Teachers"){ ?>
			jQuery( "#class" ).change(function() { desplyresult() });
			jQuery( "#jform_roll" ).keyup(function() {desplyresult();});
		<?php } ?>
				
    });//End doc
</script>