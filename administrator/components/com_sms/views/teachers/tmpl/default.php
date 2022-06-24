<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$model = $this->getModel();
JHtml::_('formbehavior.chosen', 'select');
$app       = JFactory::getApplication();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=teachers');?>" method="post" name="adminForm" id="adminForm">
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
				<th  class=" left"><?php echo JHtml::_('searchtools.sort', 'LABEL_TEACHER_NAME', 'name', $listDirn, $listOrder); ?></th>
				<th  class=" center"></th>
				<th  class=" center"></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_TEACHER_CLASS', 'class', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_TEACHER_SECTION', 'section', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'LABEL_TEACHER_SUBJECT', 'subject', $listDirn, $listOrder); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_TEACHER_EMAIL'); ?></th>
				<th  class=" center"><?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php foreach ($this->items as $i => $item) :
			$checked 	= JHTML::_('grid.id',   $i, $item->id );
			
			//cover image
			if(!empty($item->photo)){
                $photo = $item->photo;
				$path = "../components/com_sms/photo/teachers/";
			}else {
				$path = "../components/com_sms/photo/";
				$photo="photo.png";
			}
			
		    $link 		      = JRoute::_( 'index.php?option=com_sms&view=teachers&task=editteacher&cid[]='. $item->id );
			$link_details 	  = JRoute::_( 'index.php?option=com_sms&view=teachers&task=details&cid[]='. $item->id );
			$pdf_link_details = JRoute::_( 'index.php?option=com_sms&view=teachers&task=detailspdf&cid[]='.$item->id.'' );
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $checked; ?></td>
				<td class="left"><a href="<?php echo $link; ?>"><img src="<?php echo $path.$photo; ?>" class="avator-admin" alt="" width="50px" /> <?php echo $item->name;?></a></td>
				<td class="center"><a href="<?php echo $link_details; ?>" class="btn btn-default"><?php echo JText::_('DEFAULT_VIEW'); ?></a></td>
				<td class="center"><a href="<?php echo $pdf_link_details; ?>" class="btn btn-default"><?php echo JText::_('DEFAULT_PDF'); ?></a></td>
				<td class="center">
                    <?php 
				    $class_ids = explode(",", $item->class);
				    $count_class = count($class_ids);
				    foreach ($class_ids as $c=> $class_id) {
                        echo SmsHelper::getClassname($class_id);
				        if ($c < ($count_class - 1)) {
                         echo ', ';
                        }
                    }
				    ?>
                </td>
				<td class="center">
                    <?php 
				    $section_ids = explode(",", $item->section);
				    $count_section = count($section_ids);
				    foreach ($section_ids as $s=> $section_id) {
                        echo SmsHelper::getSectionname($section_id);
				        if ($s < ($count_section - 1)) {
                         echo ', ';
                        }
                    }
				    ?>
                </td>
				<td class="center">
                    <?php 
				    $subject_ids = explode(",", $item->subject);
				    $count_subject = count($subject_ids);
				    foreach ($subject_ids as $sub=> $subject_id) {
                        echo SmsHelper::getSubjectname($subject_id);
				        if ($sub < ($count_subject - 1)) {
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
	<?php endif;?>
	
	</div>
	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="teachers" />
	<?php echo JHtml::_('form.token'); ?>
</form>
