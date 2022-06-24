<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen', 'select');

$model            = $this->getModel();
$app              = JFactory::getApplication();
$params           = JComponentHelper::getParams('com_sms');
$schools_name     = $params->get('schools_name');
$schools_currency = $params->get('currency_sign');
$user		      = JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=payments');?>" method="post" name="adminForm" id="adminForm">
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
				<th  class=" left"><?php echo JText::_('LABEL_STUDENT_NAME'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_STUDENT_ROLL'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_STUDENT_CLASS'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_STUDENT_SECTION'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_PAYMENT_PAY_AMMOUNT'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_PAYMENT_PAY_BY'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_PAYMENT_PAY_MONTH_YEAR'); ?></th>
				<th  class=" center"><?php echo JText::_('LABEL_PAYMENT_STATUS'); ?></th>
				<th  class=" left">&nbsp;</th>
				<th  class=" left">&nbsp;</th>
				<th  class=" left">&nbsp;</th>
				<th  class=" center"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php foreach ($this->items as $i => $item) :
			$checked        = JHTML::_('grid.id',   $i, $item->id );
		    $link_details 	= JRoute::_( 'index.php?option=com_sms&view=payments&task=paymentdetails&cid[]='. $item->student_id );
			$link_invoice 	= JRoute::_( 'index.php?option=com_sms&view=payments&task=invoice&cid[]='. $item->id );
			$link_new 		= JRoute::_( 'index.php?option=com_sms&view=payments&task=newpayment&cid[]='. $item->id );
			
			$monthName      = date("F", mktime(null, null, null, $item->month));
			$year           = $item->year;
			$status         = $item->status;
			$student_id         = $item->student_id;
			$student_name = SmsHelper::getStudentname($student_id);

			$ammount = SmsHelper::getCurrency($item->total_bill);

			if($item->payment_method == 'offline'){
                $link_edit 	= JRoute::_( 'index.php?option=com_sms&view=payments&task=editpayment&cid[]='. $item->id );
                $edit_button = '<a href="'.$link_edit.'" title="Review" class="btn" ><i class="fa fa-pencil"></i></a>';
            }else{
            	$edit_button = '';
            }
			
			if($status=="0"){$st = '<span style="color: orange;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_PENDING').'</span>';}
			if($status=="1"){$st = '<span style="color: green;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_PAID').'</span>';}
			if($status=="2"){$st = '<span style="color: red;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_UN_PAID').'</span>';}
			if($status=="3"){$st = '<span style="color: magenta;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_CANCEL').'</span>';}
			if($status=="4"){$st = '<span style="color: mediumblue;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_UNDER_REVIEW').'</span>';}
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="left"><?php echo $student_name; ?></td>
				
				<td class="center"><?php echo $item->student_roll;?></td>
				<td class="center"><?php echo SmsHelper::getClassname($item->student_class);?></td>
				<td class="center"><?php echo SmsHelper::getSectionname($item->student_section);?></td>
				
				<td class="center"><?php echo $ammount; ?></td>
				<td class="center"><?php echo $item->payment_method; ?></td>
				<td class="center"><?php echo $monthName.' - '.$year;?></td>
				<td class="center"><?php echo $st;?></td>
                <td class="center"><?php echo $edit_button;?></td>
				<td class="left"><a href="<?php echo $link_details; ?>" class="btn" title="History" ><i class="fa fa-history"></i></a></td>
				<td class="left"><a href="<?php echo $link_invoice; ?>" class="btn" title="Invoice" ><i class="fa fa-file-text-o"></i></a></td>
				
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
	<input type="hidden" name="controller" value="payments" />
	<?php echo JHtml::_('form.token'); ?>
</form>
