<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
$bootstrapHelper        = $this->bootstrapHelper;
$rowFluidClass          = $bootstrapHelper->getClassMapping('row');
$span6Class             = $bootstrapHelper->getClassMapping('col-md-6 col-xs-12');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$controlGroupClass      = $bootstrapHelper->getClassMapping('form-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('col-md-3 col-xs-12');
$controlsClass          = $bootstrapHelper->getClassMapping('form-control');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
//$this->shipping_required = 1;
?>
<div class="<?php echo $rowFluidClass; ?>">
	<div class="col-md-12 col-xs-12 form-horizontal">
        <?php
        /*Tung Edit fields ============ */
        // field first name
        // $this->form->setFieldAttribute('firstname', 'hint', 'Placeholder text');

        // field first name
        if (EshopHelper::isFieldPublished('firstname'))
        {
            echo  $this->form->getField('firstname')->getControlGroup();
        }

        // field last name
        if (EshopHelper::isFieldPublished('lastname'))
        {
            echo  $this->form->getField('lastname')->getControlGroup();
        }

        // field email
        if (EshopHelper::isFieldPublished('email')) {
            echo $this->form->getField('email')->getControlGroup();
        }
        // field telephone
        if (EshopHelper::isFieldPublished('telephone')) {
            echo $this->form->getField('telephone')->getControlGroup();
        }

        // field country_id
        if (EshopHelper::isFieldPublished('country_id')) {
            echo $this->form->getField('country_id')->getControlGroup();
        }

        // field zone_id
        if (EshopHelper::isFieldPublished('zone_id')) {
            echo $this->form->getField('zone_id')->getControlGroup();
        }

        // field address 1
        if (EshopHelper::isFieldPublished('address_1')) {
            echo $this->form->getField('address_1')->getControlGroup();
        }
        // field address 2
        if (EshopHelper::isFieldPublished('address_2')) {
            echo $this->form->getField('address_2')->getControlGroup();
        }

        // field postcode
        if (EshopHelper::isFieldPublished('postcode')) {
            echo $this->form->getField('postcode')->getControlGroup();
        }
        // field company
        if (EshopHelper::isFieldPublished('company')) {
            echo $this->form->getField('company')->getControlGroup();
        }

        // field fax
        if (EshopHelper::isFieldPublished('fax')) {
            echo $this->form->getField('fax')->getControlGroup();
        }

        // field address 2
        if (EshopHelper::isFieldPublished('postcode')) {
            echo $this->form->getField('postcode')->getControlGroup();
        }

		if (isset($this->lists['customergroup_id']))
		{
			?>
			<div class="<?php echo $controlGroupClass; ?>">
				<label class="<?php echo $controlLabelClass; ?>" for="customergroup_id"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></label>
				<div class="<?php echo $controlsClass; ?> docs-input-sizes">
					<?php echo $this->lists['customergroup_id']; ?>
				</div>
			</div>
			<?php
		}
		elseif (isset($this->lists['default_customergroup_id']))
		{
			?>
			<input type="hidden" name="customergroup_id" value="<?php echo $this->lists['default_customergroup_id']; ?>" />
			<?php
		}
		foreach ($fields as $field)
		{
			if (!in_array($field->name, $personalFields))
			{
				echo $field->getControlGroup();
			}
		}
	?>
	</div>
</div>
<?php
if ($this->shipping_required && EshopHelper::getConfigValue('require_shipping_address', 1))
{
?>
	<div class="no_margin_right">
		<label class="checkbox"><input type="checkbox" value="1" name="shipping_address"><?php echo JText::_('ESHOP_SHIPPING_ADDRESS_SAME'); ?></label>
	</div>
<?php
}
?>
<button type="button" class="btn btn-outline-success" id="button-guest" ><?php echo JText::_('ESHOP_CONTINUE'); ?></button>
<script type="text/javascript">
	Eshop.jQuery(document).ready(function($){
	    $('#button-confirm').prop('disabled', true);
		<?php
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			?>
			$('#payment-address select[name=\'country_id\']').change(function(){
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&task=cart.getZones<?php echo EshopHelper::getAttachedLangLink(); ?>&country_id=' + this.value,
					dataType: 'json',
					beforeSend: function() {
						$('.wait').remove();
						$('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('.wait').remove();
					},
					success: function(json) {
						html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
						if (json['zones'] != '')
						{
							for (var i = 0; i < json['zones'].length; i++)
							{
			        			html += '<option value="' + json['zones'][i]['id'] + '"';
								//if (json['zones'][i]['id'] == '<?php echo $this->payment_zone_id; ?>')
								if (json['zones'][i]['id'] == 3780)
								{
				      				html += ' selected="selected"';
				    			}
				    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
							}
						}
						$('select[name=\'zone_id\']').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(xhr.responseText)
					}
				});
			});
			<?php
		}
		?>
		//Guest
		$('#button-guest').click(function(){
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=checkout.guest<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'hidden\'], #payment-address textarea, #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address select, #payment-address input[type=\'hidden\']'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-guest').attr('disabled', true);
					$('#button-guest').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('#button-guest').attr('disabled', false);
					$('.wait').remove();
				},
				success: function(json) {
					$('.warning, .error').remove();
					if (json['return']) {
						window.location.href = json['return'];
					} else if (json['error']) {
						if (json['error']['warning']) {
							$('#payment-address .box__content-body').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
							$('.warning').fadeIn('slow');
						}
						//Firstname validate
						var errors = json['error'];
						for (var field in errors)
						{
							errorMessage = errors[field];
							$('#payment-address #' + field).after('<span class="error">' + errorMessage + '</span>');
						}
					} else {
						<?php
						if ($this->shipping_required)
						{
							if (EshopHelper::getConfigValue('require_shipping_address' , 1))
							{
								?>
								var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');
								if (shipping_address) {
									var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
									$.ajax({
										url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
										dataType: 'html',
										success: function(html) {
											$('#shipping-method .box__content-body').html(html);
											$.ajax({
												url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=guest_shipping&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
												dataType: 'html',
												success: function(html) {
													$('#shipping-address .box__content-body').html(html);
                                                    $('#button-shipping-method').click();
                                                    $('#button-confirm').prop('disabled', false);
												},
												error: function(xhr, ajaxOptions, thrownError) {
													console.log(xhr.responseText);
												}
											});
										},
										error: function(xhr, ajaxOptions, thrownError) {
                                            console.log(xhr.responseText);
										}
									});
								} else {
									var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
									$.ajax({
										url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=guest_shipping&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
										dataType: 'html',
										success: function(html) {
											$('#shipping-address .box__content-body').html(html);
                                            $('#button-shipping-method').click();
                                            $('#button-confirm').prop('disabled', false);
										},
										error: function(xhr, ajaxOptions, thrownError) {
                                            console.log(xhr.responseText);
										}
									});
								}
								<?php
							}
							else
							{
								?>
								var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										$('#shipping-method .box__content-body').html(html);
                                        $('#button-shipping-method').click();
                                        $('#button-confirm').prop('disabled', false);
									},
									error: function(xhr, ajaxOptions, thrownError) {
                                        console.log(xhr.responseText);
									}
								});
								<?php
							}
						}
						else
						{
							?>
							if (json['total'] > 0)
							{
								var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										$('#payment-method .box__content-body').html(html);
                                        $('#button-payment-method').click();
                                        $('#button-confirm').prop('disabled', false);
									},
									error: function(xhr, ajaxOptions, thrownError) {
                                        console.log(xhr.responseText);
									}
								});
							}
							else
							{
								var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=confirm&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										$('#confirm .box__content-body').html(html);
									},
									error: function(xhr, ajaxOptions, thrownError) {
                                        console.log(xhr.responseText);
									}
								});
							}
							<?php
						}
						?>
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
				}
			});
		});
	})
</script>
