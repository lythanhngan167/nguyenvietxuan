<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
 
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

<style type="text/css">
#system-message-container {width: 100%;}
</style>

<script type="text/javascript">
    jQuery(document).ready(function () {
        // Get month event
		jQuery( "#select_month" ).change(function() {
		    var val = jQuery("#select_month").val();
			var sid = <?php echo $this->sid;?>;
		    jQuery("#details_show").html("Loading ...");
			jQuery.post( 'index.php?option=com_sms&task=payments.history',{val:val,sid:sid}, function(data){
			    if(data){ jQuery("#details_show").html(data); }
            });
        });		
	});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=payments&task=paymentdetails&cid[]='.$this->sid.'');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">

    <div class="control-group">
        <div class="control-label">
        <label id="jmark_upto-lbl" class="hasTip required" title="" for="jmark_upto"><?php echo JText::_('LABEL_PAYMENT_SELECT_YEAR'); ?>:<span class="star"> *</span></label>
        </div>
        <div class="controls">
		    <select id="select_month" name="year" required="required">
		        <?php 
		        for($i = 2015; $i <= 2050; $i++) {
			        if(!empty($year)){
					    $isCurrentY="false";
					}else{
					    $isCurrentY = ($i == intVal(date("Y"))) ? 'true': 'false';
					}
			    ?>
			    <option value="<?php echo $i; ?>" <?php if($isCurrentY=="true"){ echo $selected = 'selected="selected"'; }else{ echo $selected = ''; }  ?> ><?php echo $i; ?></option>
			    <?php } ?>
		    </select>
		</div>
    </div>

    <div id="printableArea">
        <div id="details_show" >
        <?php echo $this->display_history;?>
	    </div>
    </div>

	<input type="hidden" name="cid" value="<?php echo $this->sid;?>" />
	<input type="hidden" name="controller" value="payments" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

