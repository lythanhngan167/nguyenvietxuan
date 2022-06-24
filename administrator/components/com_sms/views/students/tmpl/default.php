<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

$model = $this->getModel();
JHtml::_('formbehavior.chosen', 'select');
$user		= JFactory::getUser();

$app       = JFactory::getApplication();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
//$canDo = CUHelper::getActions();

$loggeduser = JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=students');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
	<?php	echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));	?>
	
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" class="nowrap left"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th  class=" left"><?php echo JHtml::_('searchtools.sort', 'LABEL_STUDENT_NAME', 'name', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_STUDENT_ROLL', 'roll', $listDirn, $listOrder); ?></th>
                <th  class=" center"></th>
                <th  class=" center"></th>
				<th  class=" center"></th>
				<th  class=" center"></th>
				<th  class=" center"></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_STUDENT_CLASS', 'class', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_STUDENT_SECTION', 'section', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_STUDENT_DIVISION', 'division', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_STUDENT_YEAR', 'year', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		
		<tbody>
		    <?php foreach ($this->items as $i => $item) :
			$checked 	      = JHTML::_('grid.id',   $i, $item->id );
			$canChange	      = $user->authorise('core.edit.state', 'com_sms.students.'.$item->id);
		    $published        = JHtml::_('jgrid.published', $item->published, $i, '', $canChange, 'cb', '', '');
			$link_details 	  = JRoute::_( 'index.php?option=com_sms&view=students&task=details&cid[]='. $item->id );
			$pdf_link_details = JRoute::_( 'index.php?option=com_sms&view=students&task=detailspdf&cid[]='.$item->id.'' );
		    $link 		      = JRoute::_( 'index.php?option=com_sms&view=students&task=editstudents&cid[]='. $item->id );
			$link_result 	  = JRoute::_( 'index.php?option=com_sms&view=students&task=result&year='.$item->year.'&cid[]='. $item->id );
			$link_attendance  = JRoute::_( 'index.php?option=com_sms&view=students&task=attendance&cid[]='. $item->id );
            $link_idcard 	  = JRoute::_( 'index.php?option=com_sms&view=students&task=idcard&cid[]='. $item->id );
			
			//cover image
			if(!empty($item->photo)){
                $photo = $item->photo;
				$path = "../components/com_sms/photo/students/";
			}else {
				$path = "../components/com_sms/photo/";
				$photo="photo.png";
			}
			
			//Parent Check/ parent id
			$parent_id       = $model->getParentID($item->id);
			$link_parent 	 = JRoute::_( 'index.php?option=com_sms&view=parents&task=editparent&cid[]='. $parent_id );
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $checked; ?></td>
				<td class="left"><a href="<?php echo $link; ?>"><img src="<?php echo $path.$photo; ?>" class="avator-admin" alt="" width="50px" /> <?php echo $item->name;?></a></td>
                <td class="center"><?php echo $item->roll;?></td>
                <td class="center"><a href="<?php echo $link_details; ?>" class="btn btn-default"><?php echo JText::_('DEFAULT_VIEW'); ?></a></td>
				<td class="center"><a href="<?php echo $pdf_link_details; ?>" class="btn btn-default"><?php echo JText::_('DEFAULT_PDF'); ?></a></td>
				<td class="center"><a href="<?php echo $link_result; ?>" class="btn btn-default"><?php echo JText::_('BTN_STUDENT_RESULT'); ?></a></td>
				<td class="center"><a href="<?php echo $link_attendance; ?>" class="btn btn-default"><?php echo JText::_('BTN_STUDENT_ATTENDANCE'); ?></a></td>
				<td class="center"><?php if(!empty($parent_id)){echo '<a href="'.$link_parent.'" class="btn btn-default">'.JText::_('BTN_STUDENT_PARENT').'</a>';} ?></td>
				<td class="center"><?php echo SmsHelper::getClassname($item->class); ?></td>
				<td class="center"><?php echo SmsHelper::getSectionname($item->section);?></td>
				<td class="center"><?php echo SmsHelper::getDivisionname($item->division);?></td>
				<td class="center"><?php echo SmsHelper::getAcademicYear($item->year);?></td>
				<td class="center"><?php echo $item->id;?></td>
			</tr>
			<?php endforeach;  ?>
		</tbody>
	</table>
	
	<?php endif;?>
	</div>
	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="students" />
	<?php echo JHtml::_('form.token'); ?>
</form>
