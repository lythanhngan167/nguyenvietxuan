<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

$model = $this->getModel();
$user = JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=class');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" class=" left"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th  class=" left"><?php echo JText::_('LABEL_CLASS_NAME'); ?></th>
				<th  class=" center"><?php echo JText::_('DEFAULT_DIVISION'); ?></th>
				<th  class=" center"><?php echo JText::_('DEFAULT_SECTION'); ?></th>
				<th  class=" center"><?php echo JText::_('DEFAULT_SUBJECT'); ?></th>
				<th  class=" center"><?php echo JText::_('DEFAULT_GRADE_SYSTEM'); ?></th>
				<th width="5%" class=" center"><?php echo JText::_('DEFAULT_PUBLISHED'); ?></th>
				<th width="5%" class=" center">ID</th>
			</tr>
		</thead>
		<tbody>
		  <?php foreach ($this->items as $i => $item) :
			$checked 	= JHTML::_('grid.id',   $i, $item->id );
			$canChange	= $user->authorise('core.edit.state', 'com_sms.subjects.'.$item->id);
		    $published = JHtml::_('jgrid.published', $item->published, $i, '', $canChange, 'cb', '', '');
		    $link 		= JRoute::_( 'index.php?option=com_sms&view=class&task=editclass&cid[]='. $item->id );
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $checked; ?></td>
				<td class="left"><a href="<?php echo $link; ?>"><?php echo $item->class_name;?></a></td>
				<td class="center">
					<?php 
					$division_ids = explode(",", $item->division);
					$count_division = count($division_ids);
					foreach ($division_ids as $d=> $division_id) {
					    echo SmsHelper::getDivisionname($division_id);
					    if ($d < ($count_division - 1)) { echo ', ';}
	                }
					?>
				</td>
				<td class="center">
					<?php 
					$section_ids = explode(",", $item->section);
					$count_section = count($section_ids);
					foreach ($section_ids as $f=> $section_id) {
	                    echo SmsHelper::getSectionname($section_id);
					    if ($f < ($count_section - 1)) { echo ', '; }
	                }
					?>
				</td>
				<td class="center">
					<?php 
					$subject_ids = explode(",", $item->subjects);
					$count_subject = count($subject_ids);
					foreach ($subject_ids as $g=> $subject_id) {
	                    echo SmsHelper::getSubjectname($subject_id);
					    if ($g < ($count_subject - 1)) { echo ', ';}
	                }
					?>
				</td>
				<td class="center">
					<?php 
			        $catname = $model->getGcategoryName($item->grade_system);
				    echo  $catname;
				    ?>
				</td>
				<td class="center"><?php echo $published;?></td>
				<td class="center"><?php echo $item->id;?></td>
			</tr>
			<?php endforeach; ?>
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
	<input type="hidden" name="controller" value="class" />
	<?php echo JHtml::_('form.token'); ?>
</form>
