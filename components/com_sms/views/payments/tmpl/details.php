<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
	$app = JFactory::getApplication();
	$params = JComponentHelper::getParams('com_sms');
	$year_range_start = $params->get('year_range_start');
	$year_range_end = $params->get('year_range_end');

	$model = $this->getModel('payments');

	if(!empty($this->student->name) && $this->group_title!="Teachers"){$name = $this->student->name;}else {$name="";}
	if($this->group_title=="Teachers"){
		$header_title = JText::_('LABEL_PAYMENT_MANAGEMENT');
	}else{
		$header_title = JText::_('LABEL_PAYMENT_DETAILS').' <b style="color: green;">'.$name.'</b>';
	}

	$link_back 		= JRoute::_( 'index.php?option=com_sms&view=payments' );
	$link_new 		= JRoute::_( 'index.php?option=com_sms&view=payments&task=newpayment' );
 	 
	//default param
	$cy = intVal(date('Y'));
	$cm = intVal(date('m'));

	if($this->group_title=="Parents"){
		$payment_list = $model->paymentList($this->parent_id,'',$cm,$cy,'','');	
		$items_id ='<input type="hidden" name="items_id" value="'.$this->parent_id.'" id="items_id"  /> ';
	}else{
		$payment_list = $model->paymentList($this->details,'',$cm,$cy,'','');	
		$items_id ='<input type="hidden" name="items_id" value="'.$this->details.'" id="items_id"  /> ';
	} 
		
	// Filter Filter
	$status_filter = '<select class="pull-right" id="status_filter">';
	$status_filter .= '<option > Select Status </option>';
	$status_filter .= '<option value="12"> '.JText::_('COM_SMS_LABEL_STATUS_PENDING').' </option>';
	$status_filter .= '<option value="1"> '.JText::_('COM_SMS_LABEL_STATUS_PAID').'</option>';
	$status_filter .= '<option value="2"> '.JText::_('COM_SMS_LABEL_STATUS_UN_PAID').' </option>';
	$status_filter .= '<option value="3"> '.JText::_('COM_SMS_LABEL_STATUS_CANCEL').' </option>';
	$status_filter .= '<option value="4"> '.JText::_('COM_SMS_LABEL_STATUS_UNDER_REVIEW').' </option>';
	$status_filter .= '</select>';

	if($this->group_title=="Teachers"){
		// Section Filder
		$section_ids = explode(",", $this->section_ids); 
		$section_filter = '<select id="section_filter">';
		$section_filter .= '<option value="" > '.JText::_('LABEL_PAYMENT_SELECT_SECTION').'</option>';
		foreach ($section_ids as $f=> $section_id) {
			$section_filter .= '<option value="'.$section_id.'"> '.SmsHelper::getSectionname($section_id).' </option>';
		}
	    $section_filter .= '</select>';

	    // Roll Filder
	    $roll_filder ='<input type="text" name="roll" value="" id="roll_filder" placeholder="Enter Roll" /> ';
	}else{
		$section_filter ='';
		$roll_filder = '';
	}
		 
	//Pay Month
	$months = array('', JText::_('COM_SMS_MONTH_JANUARY'), JText::_('COM_SMS_MONTH_FEBRUARY'), JText::_('COM_SMS_MONTH_MARCH'), JText::_('COM_SMS_MONTH_APRIL'),  JText::_('COM_SMS_MONTH_MAY'), JText::_('COM_SMS_MONTH_JUNE'), JText::_('COM_SMS_MONTH_JULY'), JText::_('COM_SMS_MONTH_AUGUST'), JText::_('COM_SMS_MONTH_SEPTEMBER'),JText::_('COM_SMS_MONTH_OCTOBER'), JText::_('COM_SMS_MONTH_NOVEMBER'),JText::_('COM_SMS_MONTH_DECEMBER'),);
    $month_filter ='<select id="month_filter" name="month" >';
	$month_filter .='<option value="" > '.JText::_('LABEL_PAYMENT_SELECT_MONTH').'</option>';
    for($m = 1; $m <= 12; $m++) {
        $isCurrentM = ($m == intVal(date("m"))) ? 'true': 'false';
        $monthName = $months[$i];
        if($isCurrentM=="true"){ $selected_month = 'selected="selected"'; }else{$selected_month = ''; }  
        $month_filter .='<option value="'.$m.'" '.$selected_month.'  >'.$monthName.'</option>';
    }
    $month_filter .='</select>';
		 
	//YEAR
    $year_filter ='<select id="year_filter" name="year" >';
    $year_filter .='<option value="" > '.JText::_('LABEL_PAYMENT_SELECT_YEAR').'</option>';
    for($i = $year_range_start; $i <= $year_range_end; $i++) {
        $isCurrentY = ($i == intVal(date("Y"))) ? 'true': 'false';
        if($isCurrentY=="true"){$selected_year = 'selected="selected"'; }else{$selected_year = ''; } 
        $year_filter .='<option value="'.$i.'" '.$selected_year.' >'.$i.'</option>';						 
    }
    $year_filter .='</select>';
		 
    $loader_html = '<div class=\"loader\"></div>';	
?>

<style type="text/css">
	#admin-table tbody td {font-size: 12px;}
	.filder select,.filder input {width: auto;}
	#diplay_payments {margin-top: 12px;}
</style>

<script type="text/javascript">
	jQuery(document).ready(function () {
	        
		function getpayment(){
			var items          = jQuery("#items_id").val();
			var status_filter  = jQuery("#status_filter").val();
			var month_filter   = jQuery("#month_filter").val();
			var year_filter    = jQuery("#year_filter").val();
			var section_filter = jQuery("#section_filter").val();
			var roll_filder    = jQuery("#roll_filder").val();
			jQuery("#diplay_payments").html("<?php echo $loader_html; ?>");
			jQuery.post( 'index.php?option=com_sms&task=payments.getpayments',{items:items,status_filter:status_filter,month_filter:month_filter,year_filter:year_filter,section_filter:section_filter,roll_filder:roll_filder}, function(data){
				if(data){ jQuery("#diplay_payments").html(data); }
	        });
		}
	 
	    // Filter Script
	    jQuery( "#status_filter" ).change(function() { getpayment();});
		jQuery( "#month_filter" ).change(function() { getpayment();});
		jQuery( "#year_filter" ).change(function() { getpayment();});

		<?php  if($this->group_title=="Teachers"){ ?>
		jQuery( "#roll_filder" ).keyup(function() {getpayment();});
		jQuery( "#section_filter" ).change(function() { getpayment();});
		<?php } ?>
					
	});//End doc
</script>


<div id="com_sms" >
	<div class="container-fluid">
		<div class="row">
		    <div class="col-xs-12 col-md-3" id="sms_leftbar">
			    <?php echo $this->smshelper->profile(); ?>
			    <?php echo $this->sidebar; ?>
			</div>
			 
			<div class="col-xs-12 col-md-9 payment-list">
			 
		        <h1 class="title"> <span class="fa fa-money"></span> <?php echo $header_title; ?> 
		            <a href="<?php echo $link_new; ?>" class="btn btn-small"><?php echo JText::_('BTN_MAKE_PAYMENT'); ?></a>
		        </h1>
		        
		        <?php if (empty($this->details)) : ?>
				<div class="alert alert-no-items">
					<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
				<?php else : ?>
				<div class="row filder">
					<div class="col-xs-12 col-md-12">
					    <?php echo $roll_filder; ?>
						<?php echo $month_filter; ?>
					    <?php echo $year_filter; ?>
						<?php echo $section_filter; ?>
						<?php echo $status_filter; ?>
						<?php echo $items_id; ?>
					</div>	 
		        </div>
						
				<div class="row">
		            <div class="col-xs-12 col-md-12">
						<div id="diplay_payments"><?php echo $payment_list; ?></div>
				    </div>
		        </div>
				<?php endif;?>
		    </div>
		</div>
	</div>
</div>

