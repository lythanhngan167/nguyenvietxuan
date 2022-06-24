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
$app       = JFactory::getApplication();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$user		= JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=grade');?>" method="post" name="adminForm" id="adminForm">
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
				<th width="1%" class=" left"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th class=" left"><?php echo JHtml::_('searchtools.sort', 'LABEL_EXAM_GRADE_NAME', 'name', $listDirn, $listOrder); ?></th>
				<th class=" left"><?php echo JHtml::_('searchtools.sort', 'LABEL_EXAM_GRADE_CATEGORY', 'category', $listDirn, $listOrder); ?></th>
				<th class="center"><?php echo JHtml::_('searchtools.sort', 'LABEL_EXAM_GRADE_POINT', 'grade_point', $listDirn, $listOrder); ?></th>
				<th class="center"><?php echo JHtml::_('searchtools.sort', 'LABEL_EXAM_GRADE_MARK_FORM', 'mark_from', $listDirn, $listOrder); ?></th>
				<th class="center"><?php echo JHtml::_('searchtools.sort', 'LABEL_EXAM_GRADE_MARK_UPTO', 'mark_upto', $listDirn, $listOrder); ?></th>
				<th class="center"><?php echo JText::_('LABEL_EXAM_GRADE_COMMENT'); ?></th>
				<th width="5%" class=" center"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php foreach ($this->items as $i => $item) :
			$checked 	= JHTML::_('grid.id',   $i, $item->id );
			$canChange	= $user->authorise('core.edit.state', 'com_sms.subjects.'.$item->id);
		    $link 		= JRoute::_( 'index.php?option=com_sms&view=grade&task=editgrade&cid[]='. $item->id );
			$catname    = $model->getGcategoryName($item->category);
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $checked; ?></td>
				<td class="left"><a href="<?php echo $link; ?>"><?php echo $item->name;?></a></td>
				<td class="left"><?php echo $catname;?></td>
				<td class="center"><?php echo $item->grade_point;?></td>
				<td class="center"><?php echo $item->mark_from;?></td>
				<td class="center"><?php echo $item->mark_upto;?></td>
				<td class="center"><?php echo $item->comment;?></td>
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
	<input type="hidden" name="controller" value="grade" />
	<?php echo JHtml::_('form.token'); ?>
</form>
