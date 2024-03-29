<?php
defined('_JEXEC') or die('Restricted access');
?><input type="hidden" class="acym__wysid__hidden__save__content" id="editor_content" name="editor_content" value="" />
<input type="hidden" class="acym__wysid__hidden__save__stylesheet" id="editor_stylesheet" name="editor_stylesheet" value="<?php echo acym_escape($this->getWYSIDStylesheet()); ?>" />
<input type="hidden" class="acym__wysid__hidden__save__settings" id="editor_settings" name="editor_settings" value="<?php echo acym_escape($this->getWYSIDSettings()); ?>" />
<input type="hidden" id="acym__wysid__session--lifetime" name="acym_session_lifetime" value="<?php echo acym_escape(ini_get("session.gc_maxlifetime")); ?>" />
<input type="hidden" class="acym__wysid__hidden__mailId" id="editor_mailid" name="editor_autoSave" value="<?php echo intval($this->mailId); ?>" />
<input type="hidden" class="acym__wysid__hidden__save__auto" id="editor_autoSave" value="<?php echo acym_escape($this->autoSave); ?>">
<input type="hidden" id="acym__template__preview">
<input type="hidden" id="acym__wysid__block__html__content">

<div id="acym__wysid__edit" class="cell grid-x">
	<div class="cell grid-x padding-1 padding-bottom-0">
		<div class="cell medium-auto hide-for-small-only"></div>
		<button id="acym__wysid__edit__button" type="button" class="cell button xlarge-3 medium-4 margin-bottom-0">
			<i class="acymicon-edit" style="vertical-align: middle"></i>
            <?php
            $ctrl = acym_getVar('string', 'ctrl');
            echo acym_translation(in_array($ctrl, ['campaigns', 'frontcampaigns']) ? 'ACYM_EDIT_MAIL' : ($this->walkThrough ? 'ACYM_EDIT' : 'ACYM_EDIT_TEMPLATE'));
            ?>
		</button>
		<div class="cell medium-auto hide-for-small-only"></div>
	</div>
	<div class="cell grid-x" id="acym__wysid__edit__preview">
		<div class="cell medium-auto hide-for-small-only"></div>
        <?php
        if (!acym_isAdmin()) {
            $classes = 'medium-12 margin-top-1';
        } else {
            $classes = 'large-9 margin-top-1';
            if (!$this->walkThrough) $classes .= ' xxlarge-6';
        }
        ?>
		<div id="acym__wysid__email__preview" class="acym__email__preview grid-x cell <?php echo $classes ?>"></div>
		<div class="cell medium-auto hide-for-small-only"></div>
	</div>
</div>

<div class="grid-x grid-margin-x">
	<div id="acym__wysid" class="grid-x margin-0 grid-margin-x cell" style="display: none;">
		<!--Template & top toolbar-->
		<div id="acym__wysid__wrap" class="grid-y auto cell grid-padding-x grid-padding-y">
			<!--Top toolbar-->
            <?php
            include acym_getView('mails', 'editor_wysid_top_toolbar', true);
            include acym_getView('mails', 'editor_wysid_source', true);
            include acym_getView('mails', 'editor_wysid_template', true);
            ?>
		</div>

		<div class="grid-y large-4 small-3 cell" id="acym__wysid__right">
			<!--Send test-->
            <?php
            include acym_getView('mails', 'editor_wysid_test', true);
            ?>

			<!--Right toolbar-->
			<div id="acym__wysid__right-toolbar" class="grid-y cell">
				<div id="acym__wysid__right-toolbar__overlay"></div>
				<div class="acym__wysid__right-toolbar__content grid-y grid-padding-x small-12 cell" style="max-height: 829px;">

					<div class="cell grid-x text-center">
						<p data-attr-show="acym__wysid__right__toolbar__design" id="acym__wysid__right__toolbar__design__tab" class="large-4 small-4 cell acym__wysid__right__toolbar__selected acym__wysid__right__toolbar__tabs">
							<i class="acymicon-th"></i>
						</p>
						<p data-attr-show="acym__wysid__right__toolbar__current-block" id="acym__wysid__right__toolbar__block__tab" class="large-4 small-4 cell acym__wysid__right__toolbar__tabs">
							<i class="acymicon-edit"></i>
						</p>
						<p data-attr-show="acym__wysid__right__toolbar__settings" id="acym__wysid__right__toolbar__settings__tab" class="large-4 small-4 cell acym__wysid__right__toolbar__tabs">
							<i class="acymicon-cog"></i>
						</p>
					</div>

                    <?php
                    include acym_getView('mails', 'editor_wysid_design', true);
                    include acym_getView('mails', 'editor_wysid_settings', true);
                    include acym_getView('mails', 'editor_wysid_context', true);
                    ?>
				</div>
			</div>
		</div>

		<!--Modal-->
		<div id="acym__wysid__modal" class="acym__wysid__modal">
			<div class="acym__wysid__modal__bg acym__wysid__modal--close"></div>
			<div class="acym__wysid__modal__ui float-center cell">
				<div id="acym__wysid__modal__ui__fields"></div>
				<div id="acym__wysid__modal__ui__display"></div>
				<div id="acym__wysid__modal__ui__search"></div>
				<button class="close-button acym__wysid__modal--close" aria-label="Dismiss alert" type="button" data-close="">
					<span aria-hidden="true">×</span>
				</button>
			</div>
		</div>

        <?php if ('joomla' === ACYM_CMS) { ?>
			<div id="acym__wysid__modal__joomla-image">
				<div id="acym__wysid__modal__joomla-image__bg" class="acym__wysid__modal__joomla-image--close"></div>
				<div id="acym__wysid__modal__joomla-image__ui" class="float-center cell">
					<iframe id="acym__wysid__modal__joomla-image__ui__iframe" src="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;e_name=imageurl&amp;asset=com_content&amp;author=<?php echo (ACYM_J30 && !ACYM_J40) ? 'acymailing' : ''; ?>" frameborder="0"></iframe>
				</div>
			</div>
        <?php } ?>
	</div>
</div>
<div id="acym__wysid__fullscreen__modal" class="grid-x align-center">
	<div class="acym__imac cell medium-8 acym__wysid__fullscreen__modal__content__container" style="display: none">
		<div id="acym__wysid__fullscreen__modal__content__desktop" class=acym__imac__screen></div>
		<div class="acym__imac__stand"></div>
	</div>
	<div class="cell medium-4 acym__iphone acym__wysid__fullscreen__modal__content__container" style="display: none">
		<div id="acym__wysid__fullscreen__modal__content__smartphone" class="acym__iphone__screen"></div>
	</div>
	<div class="grid-x cell small-12"></div>
	<button id="acym__wysid__fullscreen__modal__close" class="close-button padding-1" aria-label="Dismiss alert" type="button" data-close="">
		<span aria-hidden="true">×</span>
	</button>
</div>
<div id="acym__wysid__modal__dynamic-text">
	<div id="acym__wysid__modal__dynamic-text__bg" class="acym__wysid__modal__dynamic-text--close"></div>
	<div id="acym__wysid__modal__dynamic-text__ui" class="float-center cell">
		<i class="acymicon-close acym__wysid__modal__dynamic-text--close" id="acym__wysid__modal__dynamic-text__close__icon"></i>
        <?php $dynamicCtrl = acym_isAdmin() ? 'dynamics' : 'frontdynamics'; ?>
		<iframe id="acym__wysid__modal__dynamic-text__ui__iframe" src="<?php echo acym_completeLink($dynamicCtrl.'&task=popup&automation='.$this->automation, true); ?>" frameborder="0"></iframe>
	</div>
</div>

