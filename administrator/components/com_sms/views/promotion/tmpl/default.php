<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
    
$class_id = JRequest::getVar('class_id');
$division_id = JRequest::getVar('division_id');
$model = $this->getModel();
$class_list = $model->getclassList($class_id,'class');
$division_list = $model->getdivisionList($division_id,'division');

if( !empty($class_id) && !empty($division_id)){
	$student_list = $model->getstudentList( $class_id,$division_id);
}else{
	$student_list = '';
}

$isCurrentY =  intVal(date("Y"));
$CurrentY_id = SmsHelper::getYear('id', 'year', $isCurrentY);
$year_list = SmsHelper::getyearList($CurrentY_id);

$document = JFactory::getDocument();
$document->addStyleSheet('../administrator/components/com_sms/css/sumoselect.css');
?>


<form action="<?php echo JRoute::_('index.php?option=com_sms&view=promotion');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
    <?php endif; ?>
		    <div class="row-fluid">
		        <div class="span12">
			        <table  class="admin-table" id="admin-table" style="width: 100%;margin-top: 20px;" align="center" >
					    <tr>
							<th>Select Year</th>
							<th>Select Class</th>
							<th>Select Division</th>
						</tr>
						<tr>
							<td><?php  echo $year_list;  ?></td>
							<td><?php echo $class_list; ?></td>
							<td><?php echo $division_list; ?></td>
						</tr>
					</table>
			    </div>
		    </div>
					
			<div id="dive3" style="text-align: center;position: fixed;width: 100%;top: 41px;z-index: 2147483647;"></div>
			<div class="row-fluid">
		        <div class="span12">
			        <div class="" id="student_list" >
						<?php echo $student_list; ?>
		            </div>
			    </div>
		    </div>
	    </div>
	</div>

    <input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="promotion" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">
    function desplyStudentList(){
        var class_id = jQuery("#class").val();
	    var division = jQuery("#division").val();
		var year = jQuery("#year").val();
        jQuery("#student_list").html("Loading ...");
	    jQuery.post( 'index.php?option=com_sms&task=promotion.getstudentlist',{year:year, class_id:class_id,division:division}, function(data){
	        if(data){ jQuery("#student_list").html(data); }
        });
    }

	jQuery( "#year" ).change(function() { desplyStudentList(); });
	jQuery( "#class" ).change(function() { desplyStudentList(); });
	jQuery( "#division" ).change(function() { desplyStudentList(); });	
</script>
	
	
	
