<?php
defined('_JEXEC') or die('Restricted access');
?><div class="acym__content acym_area padding-vertical-1 padding-horizontal-2 margin-bottom-2">
	<div class="acym_area_title"><?php echo acym_translation('ACYM_CONFIGURATION_QUEUE'); ?></div>
	<div class="grid-x grid-margin-x">
		<div class="cell medium-3"><?php echo acym_translation('ACYM_CONFIGURATION_QUEUE_PROCESSING'); ?></div>
		<div class="cell medium-9">
            <?php
            $queueModes = [
                'auto' => acym_translation('ACYM_CONFIGURATION_QUEUE_AUTOMATIC'),
                'automan' => acym_translation('ACYM_CONFIGURATION_QUEUE_AUTOMAN'),
                'manual' => acym_translation('ACYM_CONFIGURATION_QUEUE_MANUAL'),
            ];
            echo acym_radio($queueModes, 'config[queue_type]', $this->config->get('queue_type', 'automan'));
            ?>
		</div>
		<div class="cell medium-3 margin-top-1"><?php echo acym_translation('ACYM_AUTO_SEND_PROCESS'); ?></div>
		<div class="cell medium-9 margin-top-1">
            <?php
            $delayTypeAuto = acym_get('type.delay');
            echo acym_translation_sprintf(
                'ACYM_SEND_X_EVERY_Y',
                '<input class="intext_input" type="text" name="config[queue_nbmail_auto]" value="'.intval($this->config->get('queue_nbmail_auto')).'" />',
                $delayTypeAuto->display('config[cron_frequency]', $this->config->get('cron_frequency'), 2)
            ); ?>
		</div>
		<div class="cell medium-3 margin-top-1"><?php echo acym_translation('ACYM_MANUAL_SEND_PROCESS'); ?></div>
		<div class="cell medium-9 margin-top-1">
            <?php
            $delayTypeAuto = acym_get('type.delay');
            echo acym_translation_sprintf(
                'ACYM_SEND_X_WAIT_Y',
                '<input class="intext_input" type="text" name="config[queue_nbmail]" value="'.intval($this->config->get('queue_nbmail')).'" />',
                $delayTypeAuto->display('config[queue_pause]', $this->config->get('queue_pause'), 0)
            ); ?>
		</div>
		<div class="cell medium-3 margin-top-1"><?php echo '<span>'.acym_translation('ACYM_MAX_NB_TRY').'</span>'.acym_info(acym_translation('ACYM_MAX_NB_TRY_DESC')); ?></div>
		<div class="cell medium-9 margin-top-1">
            <?php echo acym_translation_sprintf('ACYM_CONFIG_TRY', '<input class="intext_input" type="text" name="config[queue_try]" value="'.intval($this->config->get('queue_try')).'">');

            $failaction = acym_get('type.failaction');
            echo ' '.acym_translation_sprintf('ACYM_CONFIG_TRY_ACTION', $failaction->display('maxtry', $this->config->get('bounce_action_maxtry'))); ?>
		</div>
		<div class="cell medium-3 margin-top-1"><?php echo acym_translation('ACYM_MAX_EXECUTION_TIME'); ?></div>
		<div class="cell medium-9 margin-top-1">
            <?php
            echo acym_translation_sprintf('ACYM_TIMEOUT_SERVER', ini_get('max_execution_time')).'<br />';
            $maxexecutiontime = intval($this->config->get('max_execution_time'));
            if (intval($this->config->get('last_maxexec_check')) > (time() - 20)) {
                echo acym_translation_sprintf('ACYM_TIMEOUT_CURRENT', $maxexecutiontime);
            } else {
                if (!empty($maxexecutiontime)) {
                    echo acym_translation_sprintf('ACYM_MAX_RUN', $maxexecutiontime).'<br />';
                }
                echo '<span id="timeoutcheck"><a id="timeoutcheck_action" class="acym__color__blue">'.acym_translation('ACYM_TIMEOUT_AGAIN').'</a></span>';
            }
            ?>
		</div>
		<div class="cell medium-3 margin-top-1"><?php echo acym_translation('ACYM_ORDER_SEND_QUEUE'); ?></div>
		<div class="cell medium-9 margin-top-1">
            <?php
            $ordering = [];
            $ordering[] = acym_selectOption("user_id, ASC", 'user_id ASC');
            $ordering[] = acym_selectOption("user_id, DESC", 'user_id DESC');
            $ordering[] = acym_selectOption('rand', 'ACYM_RANDOM');
            echo acym_select(
                $ordering,
                'config[sendorder]',
                $this->config->get('sendorder', 'user_id, ASC'),
                'class="intext_select"',
                'value',
                'text',
                'sendorderid'
            );

            echo '</div>';

            ?>
		</div>
	</div>
    <?php
if (!acym_level(1)) {
    $data['version'] = 'essential';
    echo '<div class="acym_area">
            <div class="acym_area_title">'.acym_translation('ACYM_CRON').'</div>';
    include(ACYM_VIEW.'dashboard'.DS.'tmpl'.DS.'upgrade.php');
    echo '</div>';
}

