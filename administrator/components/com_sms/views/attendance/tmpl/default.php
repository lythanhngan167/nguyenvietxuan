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
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=attendance');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
	<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));	?>

	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" class="nowrap left"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th  class=" left"><?php echo JText::_('LABEL_ATTENDANCE_DATE'); ?></th>
				<th  class=" center"><?php echo JText::_('DEFAULT_CLASS'); ?></th>
				<th  class=" center"><?php echo JText::_('DEFAULT_DIVISION'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TEACHER'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TOTAL_STUDENTS'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TOTAL_PRESENT'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_TOTAL_ABSENT'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_DATE_TIME'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_ATTENDANCE_UPDATE'); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php foreach ($this->items as $i => $item) :
			$checked 	= JHTML::_('grid.id',   $i, $item->id );
			//$canChange	= $user->authorise('core.edit.state', 'com_sms.students.'.$item->id);
		    //$published = JHtml::_('jgrid.published', $item->published, $i, '', $canChange, 'cb', '', '');
		    $link 		= JRoute::_( 'index.php?option=com_sms&view=attendance&task=editattend&cid[]='. $item->id );
			$teacher_id = SmsHelper::getTeacherIDbyUserid($item->teacher);
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $checked; ?></td>
				<td class="left"><a href="<?php echo $link; ?>"><?php echo $item->attendance_date;?></a></td>
				<td class="center"><?php echo SmsHelper::getClassname($item->class);?></td>
				<td class="center"><?php echo SmsHelper::getSectionname($item->section);?></td>
				<td class="center"><?php if(!empty($teacher_id)){echo SmsHelper::getTeachername($teacher_id);}else{echo'Admin';}?></td>
				<td class="center"><?php echo $item->total_student;?></td>
				<td class="center"><?php echo count($model->totalPresent($item->id));?></td>
				<td class="center"><?php echo count($model->totalAbsent($item->id));?></td>
				<td class="center"><?php echo date( 'Y-m-d g:i A', strtotime($item->create_date)); ?></td>
				<td class="center"><?php echo date( 'Y-m-d g:i A', strtotime($item->update_date)); ?></td>
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
	
	<?php endif; ?>
	</div>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="attendance" />
	<?php echo JHtml::_('form.token'); ?>
</form>
