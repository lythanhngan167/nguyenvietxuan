<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

$app       = JFactory::getApplication();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=parents');?>" method="post" name="adminForm" id="adminForm">
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
				<th  class=" left"><?php echo JHtml::_('searchtools.sort', 'LABEL_PARENT_NAME', 'name', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_PARENT_STUDENT_NAME'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_PARENT_STUDENT_ROLL'); ?></th>
				
				<th  class=" center"><?php echo JText::_('LABEL_PARENT_EMAIL'); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php foreach ($this->items as $i => $item) :
			$checked 	= JHTML::_('grid.id',   $i, $item->id );
			
			//cover image
			if(!empty($item->photo)){
                $photo = $item->photo;
				$path = "../components/com_sms/photo/parents/";
			}else {
				$path = "../components/com_sms/photo/";
				$photo="photo.png";
			}

		    //$published = JHtml::_('jgrid.published', $item->published, $i, '', $canChange, 'cb', '', '');
		    $link 		= JRoute::_( 'index.php?option=com_sms&view=parents&task=editparent&cid[]='. $item->id );
			
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $checked; ?></td>
				<td class="left"><a href="<?php echo $link; ?>"><img src="<?php echo $path.$photo; ?>" class="avator-admin" alt="" width="50px" /> <?php echo $item->name;?></a></td>
				<td class="center">
                    <?php 
				    $student_ids = explode(",", $item->student_id);
				    $count_student = count($student_ids);
				    foreach ($student_ids as $s=> $student_id) {
                        $student_link 		= JRoute::_( 'index.php?option=com_sms&view=students&task=editstudents&cid[]='. $student_id );
                        ?>
                        <a href="<?php echo $student_link; ?>"> 
                        <?php echo SmsHelper::getStudentname($student_id);?>
                         </a>
                        <?php
				        if ($s < ($count_student - 1)) {
                         echo ', ';
                        }
                    }
				    ?>
                </td>
				<td class="center">
					<?php 
				    foreach ($student_ids as $s=> $student_id) {
                        echo SmsHelper::getStudentRoll($student_id);
				        if ($s < ($count_student - 1)) {
                         echo ', ';
                        }
                    }
                    ?>
                </td>
				<td class="center"><?php echo $item->email;?></td>
				<td class="center"><?php echo $item->id;?></td>
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
	<input type="hidden" name="controller" value="parents" />
	<?php echo JHtml::_('form.token'); ?>
</form>
