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
$link_new = JRoute::_( 'index.php?option=com_sms&view=attendance&task=newattend' );

?>
<div id="com_sms" >
	<div class="container-fluid">
	    <div class="row">
	        
	        <!-- Set Profile Sidebar -->
	        <div class="col-xs-12 col-md-3" id="sms_leftbar">
		    <?php echo $this->smshelper->profile(); ?>
		    <?php echo $this->sidebar; ?>
		    </div>
		 

		    <!-- Set Attendance main body -->
		    <div class="col-xs-12 col-md-9">
		        <form method="post" action="">
		 
		            <!-- Toolsbar -->
		            <table class="message  table-striped"  style="margin-top: 0px;width: 100%;">
			            <thead>
				            <tr>
				                <th  class=" left">
				                    <!-- New Attendance button -->
					                <a href="<?php echo $link_new; ?>" class="btn btn-small"><?php echo JText::_('LABEL_ATTENDANCE_NEW'); ?></a>
					            </th>
					        </tr>
			            </thead>
		            </table>

	                <!-- Attendance list  -->
		            <table class="table table-striped" id="admin-table" style="margin-top: 0px;">
			            <thead>
				            <tr>
								<th  class=" left"><?php echo JText::_('LABEL_ATTENDANCE_TABLE_DETAIL'); ?></th>
								<th  class=" center"><?php echo JText::_('DEFAULT_CLASS'); ?></th>
								<th  class=" center"><?php echo JText::_('DEFAULT_SECTION'); ?></th>
								<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TEACHER'); ?></th>
								<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TOTAL_STUDENTS'); ?></th>
								<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TOTAL_PRESENT'); ?></th>
								<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TOTAL_ABSENT'); ?></th>
								<th  class=" center">Edit</th>
							</tr>
						</thead>

				        <tbody>
				        <?php foreach ($this->items as $i => $item) :
					    $link 		= JRoute::_( 'index.php?option=com_sms&view=attendance&task=editattend&cid='. $item->id );
				        ?>
					    <tr class="row<?php echo $i % 2; ?>">
					        <td class="left"><a href="<?php echo $link; ?>"><?php echo $item->attendance_date;?></a></td>
							<td class="center"><?php echo $model->getClassname($item->class);?></td>
							<td class="center"><?php echo $model->getSectionname($item->section);?></td>
							<td class="center"><?php echo $model->getTeachername($item->teacher);?></td>
							<td class="center"><?php echo $item->total_student;?></td>
							<td class="center"><?php echo count($model->totalPresent($item->id));?></td>
							<td class="center"><?php echo count($model->totalAbsent($item->id));?></td>
							<td class="center"> <a href="<?php echo $link; ?>" class="btn btn-small">Edit</a></td>
						</tr>
					    <?php endforeach;  ?>
				        </tbody>
		            </table>
		
		            <!-- Pagination -->
		            <div class="row-fluid " style="">
		                <?php echo $this->pagination->getListFooter(); ?>
		            </div>
		
		        <input type="hidden" name="view" value="attendance" />
		        <?php echo JHtml::_('form.token'); ?>
	            </form>
	        </div>
	    </div>
	</div>

</div>