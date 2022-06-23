   <?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

defined('_JEXEC') or die('Restricted access');

$user = CFactory::getUser($this->act->actor);

$date = JDate::getInstance($act->created);
if ( $config->get('activitydateformat') == "lapse" ) {
  $createdTime = CTimeHelper::timeLapse($date);
} else {
  $createdTime = $date->format($config->get('profileDateFormat'));
}

$truncateVal = 60;

?>

<div class="joms-stream__header">
    <div class= "joms-avatar--stream <?php echo CUserHelper::onlineIndicator($user); ?>">
        <a href="<?php echo ((int)$user->id !== 0) ? CUrlHelper::userLink($user->id) : 'javascript:void(0);'; ?>">
        <img data-author="<?php echo $user->_userid; ?>" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>"></a>
    </div>
    <div class="joms-stream__meta">
        <a href="<?php echo CUrlHelper::userLink($user->id); ?>" class="joms-stream__user"><?php echo $user->getDisplayName(false, true); ?></a>
        <span><?php echo JText::_('COM_COMMUNITY_PAGES_NEW_PAGE'); ?></span>
        <span class="joms-stream__time">
            <small><?php echo $createdTime; ?></small>
        </span>
    </div>
</div>

<div class="joms-stream__body">
    <div class="joms-media">
        <h4 class="joms-text--title"><a href="<?php echo $this->page->getLink();?>"><?php echo JHTML::_('string.truncate',$this->page->name , $truncateVal); ?></a></h4>
        <span><?php echo JHTML::_('string.truncate',strip_tags($this->page->description) , $config->getInt('streamcontentlength')); ?></span>
    </div>
</div>

<?php $this->load('stream/footer'); ?>


