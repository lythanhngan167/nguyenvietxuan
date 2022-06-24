<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

$model = $this->getModel();

// Get Current Year
$current_year =date("Y");
//Total Income
$total_income = $model->getTotalIncome($current_year);

//Total Expense
$total_expense = $model->getTotalExpense($current_year);
	
//GET SCHOOLS DATA
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');
$schools_currency = $params->get('currency');

$user		= JFactory::getUser();

$link_income 		= JRoute::_( 'index.php?option=com_sms&view=income');
$link_expense 		= JRoute::_( 'index.php?option=com_sms&view=expenses');

//custom function for display income chart value
function incomeValue($month,$year,$model){
	$data = $model->getTotalIncomebyMonth($month,$year);
	if(empty($data)){$value = 0;}else{$value = $data;}
	return $value;
}
//custom function for display expense chart value
function expenseValue($month,$year,$model){
	$data = $model->getTotalExpensebyMonth($month,$year);
	if(empty($data)){$value = 0;}else{$value = $data;}
	return $value;
}

?>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
    //load the Google Visualization API and the chart
    google.load('visualization', '1', {'packages': ['corechart']});
    //set callback
    google.setOnLoadCallback (drawChart);
	function drawChart() {
	    var data = google.visualization.arrayToDataTable([
	        ['Month', '<?php echo JText::_('LABEL_ACCOUNTING_INCOME'); ?>', '<?php echo JText::_('LABEL_ACCOUNTING_EXPENSES'); ?>'],
	        ['January',  <?php echo incomeValue('1',$current_year,$model); ?>,       <?php echo expenseValue('1',$current_year,$model); ?>],
	        ['February', <?php echo incomeValue('2',$current_year,$model); ?>,       <?php echo expenseValue('2',$current_year,$model); ?>],
	        ['March',    <?php echo incomeValue('3',$current_year,$model); ?>,       <?php echo expenseValue('3',$current_year,$model); ?>],
			['April',    <?php echo incomeValue('4',$current_year,$model); ?>,       <?php echo expenseValue('4',$current_year,$model); ?>],
			['May',      <?php echo incomeValue('5',$current_year,$model); ?>,       <?php echo expenseValue('5',$current_year,$model); ?>],
			['June',     <?php echo incomeValue('6',$current_year,$model); ?>,       <?php echo expenseValue('6',$current_year,$model); ?>],
			['July',     <?php echo incomeValue('7',$current_year,$model); ?>,       <?php echo expenseValue('7',$current_year,$model); ?>],
			['August',   <?php echo incomeValue('8',$current_year,$model); ?>,       <?php echo expenseValue('8',$current_year,$model); ?>],
			['September',<?php echo incomeValue('9',$current_year,$model); ?>,       <?php echo expenseValue('9',$current_year,$model); ?>],
			['October',  <?php echo incomeValue('10',$current_year,$model); ?>,      <?php echo expenseValue('10',$current_year,$model); ?>],
			['November', <?php echo incomeValue('11',$current_year,$model); ?>,      <?php echo expenseValue('11',$current_year,$model); ?>],
	        ['December', <?php echo incomeValue('12',$current_year,$model); ?>,      <?php echo expenseValue('12',$current_year,$model); ?>]
	        ]);

	    var options = {
	        title: '<?php echo JText::_('LABEL_ACCOUNTING_PERFORMANCE'); ?>',
	        curveType: 'function',
	        legend: { position: 'bottom' }
	    };
	    var chart = new google.visualization.LineChart(document.getElementById('chart_income'));
	    chart.draw(data, options);
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=accounting');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
	
	<h1 style="text-align: center;font-size: 14px;color: #666;"><?php echo JText::_('LABEL_ACCOUNTING_DASHBOARD_TEXT'); ?> <?php echo $current_year; ?> </h1>
	
	<div id="chart_income" style="margin-top: 20px;margin-bottom: 20px;"></div>
	
	<div class="row-fluid">
	    <div class="span6">
		    <!-- START PANNEL -->
		    <div class="panel panel-success" style="border: 1px solid #ccc;background: #f5f5f5;">
		        <div class="panel-heading" >
		            <div class="row-fluid">
                        <div class="span3">
                            <i class="fa fa-bar-chart fa-5x"></i>
                        </div>
                        <div class="span8 text-right">
                            <div class="huge"><?php echo SmsHelper::getCurrency($total_income); ?></div>
                            <div><?php echo JText::_('LABEL_ACCOUNTING_TOTAL_INCOME'); ?></div>
                        </div>
                    </div>
				</div>
				<a href="<?php echo $link_income; ?>">
	                <div class="panel-footer">
	                    <span class="pull-left"><?php echo JText::_('DEFAULT_VIEW_DETAILS'); ?></span>
	                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                    <div class="clearfix"></div>
	                </div>
                </a>
			</div>
			<!-- END PANNEL -->
		</div>

		<div class="span6">
		    <!-- START PANNEL -->
		    <div class="panel panel-warning" style="border: 1px solid #ccc;background: #f5f5f5;">
		        <div class="panel-heading" >
		            <div class="row-fluid">
                        <div class="span10 text-left">
                            <div class="huge"><?php echo SmsHelper::getCurrency($total_expense); ?></div>
                            <div><?php echo JText::_('LABEL_ACCOUNTING_TOTAL_EXPENSE'); ?></div>
                        </div>
						<div class="span1">
                            <i class="fa fa-pie-chart fa-5x"></i>
                        </div>
                    </div>
				</div>
				<a href="<?php echo $link_expense; ?>">
                    <div class="panel-footer">
                        <span class="pull-left"><?php echo JText::_('DEFAULT_VIEW_DETAILS'); ?></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
			</div>
			<!-- END PANNEL -->
		</div>
	</div>
	
	</div>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="accounting" />
	<?php echo JHtml::_('form.token'); ?>
</form>
