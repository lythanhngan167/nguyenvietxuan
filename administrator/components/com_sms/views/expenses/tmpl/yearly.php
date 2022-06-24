<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
$model = $this->getModel();
$total =0;

//GET SCHOOLS DATA
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');
$schools_currency = $params->get('currency');

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

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=expenses');?>" method="post" name="adminForm" id="adminForm">
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
	<div id="printableArea">
	
	<style type="text/css">
		.information-div h3,
		.information-div p {text-align: center;}
    </style>
	<?php 
	//Header Information
	$onclick_link ="'printableArea'";
    $header_con ='<p style="text-align: center;"><input type="button" id="print" onclick="printDiv('.$onclick_link.')" class="btn btn-small"  style="border: none;margin-left: 10px;" value="Print" /> </p>';
	$header  = '<div class="information-div">';
	$header .= '<h3> '.$schools_name.'</h3>';
	$header .= '<p> '.JText::_('LABEL_EXPENSE_YEAR_LIST').'</p>';
	$header .= $header_con;
	$header .= '</div>';
	echo $header;
	?>
	
	<table class="table table-striped" align="center" id="admin-table" style="width: 95%;">
		<thead>
			<tr>
			  <th  class=" center">#</th>
				<th  class=" left"><?php echo JText::_('LABEL_EXPENSE_YEAR'); ?></th>
				<th style="text-align: right;padding-right: 100px;" ><?php echo JText::_('LABEL_EXPENSE_AMMOUNT'); ?></th>
			</tr>
		</thead>
		<tbody>
		    <?php 
			$i=0;
			foreach ($this->items as $i => $item) :
			$i++;
			$total += $item->Total;
			$year = $item->year;
		    ?>
			<tr class="row<?php echo $i % 2; ?>">
			  <td class=" center"><?php echo $i; ?></td>
				<td  style="color: green;text-align: center;"><?php echo $year;?></td>
				<td style="text-align: right;padding-right: 100px;" ><?php echo SmsHelper::getCurrency($item->Total); ?></td>
			</tr>
			<?php endforeach; ?>
			<tr class="row<?php echo $i % 2; ?>">
			  <td class="center"></td>
				<td class="center"></td>
				<td style="text-align: right;color: green;padding-right: 100px;"><?php echo JText::_('LABEL_EXPENSE_TOTAL'); ?>: <?php echo SmsHelper::getCurrency($total); ?></td>
			</tr>
		</tbody>
	</table>
	</div>
	
	<?php endif; ?>
	
	
	</div>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="expenses" />
	<?php echo JHtml::_('form.token'); ?>
</form>
