<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Recharge
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_recharge', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_recharge/js/form.js');

$user = Factory::getUser();
$canEdit = RechargeHelpersRecharge::canUserEdit($this->item, $user);
//print_r($this->item);
?>
<style>
    #jform_bank_name label.checkbox {
        float: left;
        width: 50%;
        padding-right: 15px;
        min-height: 55px
    }

    #jform_note {
        height: 100px;
        width: 70%;
    }

    #jform_bank_name {
        padding-left: 18px;
    }
    .moneytotal input{
      margin: 0px;
    }
    .billion{
      color: red;
    }
    <?php if($this->item->id <= 0){ ?>
    #image, #updated_time, #created_time, #status, #type, #code {
        display: none;
    }

    <?php } ?>
    @media screen and (max-width: 768px) {
        #jform_bank_name label.checkbox {
            width: 100%;
        }

        .form-horizontal .controls input, .form-horizontal .controls textarea {
            width: auto;
        }

        #jform_bank_name {
            padding-left: 10px;
            padding-right: 10px;
        }
    }

    #jform_bank_name-lbl {
        font-weight: bold;
    }
</style>
<script>
    jQuery(document).ready(function () {
        // alert("abc");
        // $("input").prop('disabled', true);
        // $("input").prop('disabled', false);
    });

</script>
<div class="recharge-edit front-end-edit">
    <?php if (!$canEdit) : ?>
        <h3>
            <?php throw new Exception(Text::_('COM_RECHARGE_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
        </h3>
    <?php else : ?>
        <?php if (!empty($this->item->id)): ?>
            <h3><?php echo Text::sprintf('COM_RECHARGE_EDIT_ITEM_TITLE', $this->item->id); ?></h3>
        <?php else: ?>
            <h3><?php echo Text::_('COM_RECHARGE_ADD_ITEM_TITLE'); ?> </h3>
        <?php endif; ?>

        <form id="form-recharge"
              action="<?php echo Route::_('index.php?option=com_recharge&task=recharge.save'); ?>"
              method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

            <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>

            <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>

            <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>"/>

            <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>"/>

            <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>"/>

            <?php echo $this->form->getInput('created_by'); ?>
            <?php echo $this->form->getInput('modified_by'); ?>

            <br>
            <div class="control-group">
            			<div class="control-label"><label id="jform_moneytotal-lbl"  class="required">
            	Ch???n g??i<span class="star">&nbsp;*</span></label>
            </div>
            		<div class="controls moneytotal">
                  <input type="radio" onclick="changeMoneyTotal(this.value)" checked="checked" id="1billion" name="moneytotal" value="1000000">
                  <label for="other">1.000.000 <?php echo BIZ_XU; ?> - <span class="billion">1 tri???u <?php echo BIZ_XU; ?></span></label><br>
                  <input type="radio" onclick="changeMoneyTotal(this.value)" id="2billion" name="moneytotal" value="2000000">
                  <label for="other">2.000.000 <?php echo BIZ_XU; ?> - <span class="billion">2 tri???u <?php echo BIZ_XU; ?></span></label><br>
                  <input type="radio" onclick="changeMoneyTotal(this.value)" id="3billion" name="moneytotal" value="3000000">
                  <label for="other">3.000.000 <?php echo BIZ_XU; ?> - <span class="billion">3 tri???u <?php echo BIZ_XU; ?></span></label><br>
                  <input type="radio" onclick="changeMoneyTotal(this.value)" id="4billion" name="moneytotal" value="4000000">
                  <label for="other">4.000.000 <?php echo BIZ_XU; ?> - <span class="billion">4 tri???u <?php echo BIZ_XU; ?></span></label><br>
                  <input type="radio" onclick="changeMoneyTotal(this.value)" id="5billion" name="moneytotal" value="5000000">
                  <label for="other">5.000.000 <?php echo BIZ_XU; ?> - <span class="billion">5 tri???u <?php echo BIZ_XU; ?></span></label><br>
                  <input type="radio" onclick="changeMoneyTotal(this.value)" id="10billion" name="moneytotal" value="10000000">
                  <label for="other">10.000.000 <?php echo BIZ_XU; ?> - <span class="billion">10 tri???u <?php echo BIZ_XU; ?></span></label>
                </div>
            </div>


            <?php echo $this->form->renderField('amount'); ?>

            <?php echo $this->form->renderField('note'); ?>

            <?php //echo $this->form->renderField('bank_name'); ?>
            <?php echo $this->form->renderField('type'); ?>
            <div id="status">
                <?php echo $this->form->renderField('status'); ?>
            </div>
            <div id="image">
                <?php echo $this->form->renderField('image'); ?>

                <?php if (!empty($this->item->image)) : ?>
                    <?php $imageFiles = array(); ?>
                    <?php foreach ((array)$this->item->image as $fileSingle) : ?>
                        <?php if (!is_array($fileSingle)) : ?>
                            <a href="<?php echo JRoute::_(JUri::root() . 'images/banking' . DIRECTORY_SEPARATOR . $fileSingle, false); ?>"><?php echo $fileSingle; ?></a> |
                            <?php $imageFiles[] = $fileSingle; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <input type="hidden" name="jform[image_hidden]" id="jform_image_hidden"
                       value="<?php echo implode(',', $imageFiles); ?>"/>
            </div>
            <div id="created_time">
                <?php echo $this->form->renderField('created_time'); ?>
            </div>
            <div id="code">
                <?php echo $this->form->renderField('code'); ?>
            </div>

            <div id="type">
                <?php echo $this->form->renderField('type'); ?>
            </div>
            <div id="updated_time">
                <?php echo $this->form->renderField('updated_time'); ?>
            </div>
            <div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin', 'recharge')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
            <?php if (!JFactory::getUser()->authorise('core.admin', 'recharge')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function () {
                        var option_selected = jQuery(this).find(':selected');
                        var input = document.createElement("input");
                        input.setAttribute("type", "hidden");
                        input.setAttribute("name", jQuery(this).attr('name'));
                        input.setAttribute("value", option_selected.val());
                        document.getElementById("form-recharge").appendChild(input);
                    });
                </script>
            <?php endif; ?>
            <div class="control-group recharge">
                <div class="controls">

                    <?php if ($this->canSave): ?>
                        <button type="button" id="btn-recharge" onclick="formRecharge();" class="validate btn btn-primary">
                            <?php echo Text::_('JSUBMIT'); ?>
                        </button>
                    <?php endif; ?>
                    <!-- <a class="btn"
                       href="<?php echo Route::_('index.php?option=com_recharge&task=rechargeform.cancel'); ?>"
                       title="<?php echo Text::_('JCANCEL'); ?>">
                        <?php echo Text::_('JCANCEL'); ?>
                    </a> -->
                </div>
            </div>

            <input type="hidden" name="option" value="com_recharge"/>
            <input type="hidden" name="task"
                   value="rechargeform.save"/>
            <?php echo HTMLHelper::_('form.token'); ?>
        </form>
    <?php endif; ?>
</div>
<script>
jQuery(document).ready(function(){
  //$after_money1 = '<b>1 tri???u ?????ng</b>';
	jQuery('#jform_amount').attr('readonly','readonly');
  //jQuery('#jform_amount').after($after_money1);
});

function formRecharge(){
  jQuery('#form-recharge').submit();
  jQuery('#btn-recharge').html('??ang x??? l??...');
  jQuery('#btn-recharge').prop("disabled",true);
}

function changeMoneyTotal($money){
  // $after_money = '';
  // if($money == '1000000'){
  //   $after_money = '<b>1 tri???u ?????ng</b>';
  // }
  // if($money == '2000000'){
  //   $after_money = '<b>2 tri???u ?????ng</b>';
  // }
  // if($money == '3000000'){
  //   $after_money = '<b>3 tri???u ?????ng</b>';
  // }
  // if($money == '4000000'){
  //   $after_money = '<b>4 tri???u ?????ng</b>';
  // }
  // if($money == '5000000'){
  //   $after_money = '<b>5 tri???u ?????ng</b>';
  // }
  // if($money == '10000000'){
  //   $after_money = '<b>10 tri???u ?????ng</b>';
  // }
  // jQuery('#jform_amount').after($after_money);

  jQuery('#jform_amount').val($money);
}
</script>
