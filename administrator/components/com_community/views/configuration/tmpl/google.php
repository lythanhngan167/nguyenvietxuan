<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="widget-box">

	<div class="widget-header widget-header-flat">
		<h5><?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_API' ); ?></h5>
		<!-- <div class="widget-toolbar no-border">
            <a href="http://tiny.cc/location-integration" target="_blank"><i class="js-icon-info-sign"></i> View Documentation</a>
        </div> -->
	</div>

	<div class="widget-body">
		<div class="widget-main">
			<table width="100%">
				<tr>
                    <td class="key">
                            <span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_API_DESCRIPTION'); ?>">
                                <?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_API' ); ?>
                            </span>
                    </td>
                    <td>
                        <select name="maps_api" id="maps_api_select">
                            <option value="googlemap"<?php echo $this->config->get('maps_api') == 'googlemap' ? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_GOOGLE');?></option>
                            <option value="openstreetmap"<?php echo $this->config->get('maps_api') == 'openstreetmap' ? ' selected="selected"' : ''; ?>><?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_OPEN_STREET');?></option>
                        </select>
                    </td>
            	</tr>
            	<tr><td colspan="2"><hr /></td></tr>

				<tr class="googlemap">
					<td class="key" width="200">
						<span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_GOOGLE_API_KEY_LABEL'); ?>">
							<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_GOOGLE_API_KEY_TIPS' ); ?>
						</span>
					</td>
					<td>
						<input type="text" name="googleapikey" value="<?php echo $this->config->get('googleapikey' , '' );?>" size="50" />
					</td>
				</tr>
				<tr class="googlemap"><td colspan="2"><hr /></td></tr>

                <tr>
                    <td class="key" width="200">
                        <span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_LOCATION_FIELD_CODE_TIPS'); ?>">
                            <?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_LOCATION_FIELD_CODE' ); ?>
                        </span>
                    </td>
                    <td>
                        <?php echo $this->getLocationFieldCodes( 'fieldcodelocation' , $this->config->get('fieldcodelocation') ); ?>
                    </td>
                </tr>
                <tr><td colspan="2"><hr /></td></tr>
                <tr><td colspan="2" class="key"><?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_NOT_FILL_IN' ); ?></td></tr>
				<tr>
					<td class="key" width="200">
						<span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_STREET_FIELD_CODE_TIPS'); ?>">
							<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_STREET_FIELD_CODE' ); ?>
						</span>
					</td>
					<td>
						<?php echo $this->getFieldCodes( 'fieldcodestreet' , $this->config->get('fieldcodestreet') ); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_CITY_FIELD_CODE_TIPS'); ?>">
							<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_CITY_FIELD_CODE' ); ?>
						</span>
					</td>
					<td>
						<?php echo $this->getFieldCodes( 'fieldcodecity' , $this->config->get('fieldcodecity') ); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_STATE_FIELD_CODE_TIPS'); ?>">
							<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_STATE_FIELD_CODE' ); ?>
						</span>
					</td>
					<td>
						<?php echo $this->getFieldCodes( 'fieldcodestate' ,  $this->config->get('fieldcodestate') ); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_COUNTRY_FIELD_CODE_TIPS'); ?>">
							<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_COUNTRY_FIELD_CODE' ); ?>
						</span>
					</td>
					<td>
						<?php echo $this->getFieldCodes( 'fieldcodecountry' , $this->config->get('fieldcodecountry') ); ?>
					</td>
				</tr>
                <tr>
                    <td class="key">
						<span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MAPS_POST_CODE_TIPS'); ?>">
							<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MAPS_POST_CODE' ); ?>
						</span>
                    </td>
                    <td>
                        <?php echo $this->getFieldCodes( 'fieldcodepostcode' , $this->config->get('fieldcodepostcode') ); ?>
                    </td>
                </tr>

			</table>
		</div>
	</div>
</div>

<script>
    jQuery( document ).ready(function($)
    {   
        $('#maps_api_select').on('input change', function() {
            if ($(this).val() == 'googlemap') $('.googlemap').show();
            else  $('.googlemap').hide();
        });

        if ($('#maps_api_select').val() == 'googlemap') $('.googlemap').show();
        else  $('.googlemap').hide();
    });
</script>