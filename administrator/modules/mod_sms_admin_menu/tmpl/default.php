<?php
/**
 * @package	Schools Management System !
 * @author	zwebtheme.com
 * @copyright	(C) zwebtheme. All rights reserved.
 */

//no direct accees
defined ('_JEXEC') or die ('restricted access');

$user = JFactory::getUser();
$input 	= JFactory::getApplication()->input;
$view 	= $input->get('view', NULL, 'STRING');
$option = $input->get('option', NULL, 'STRING');
$layout = $input->get('layout', NULL, 'STRING');

if ($user->authorise('core.manage', 'com_sms')) { ?>

<ul id="sp-pagebuiler-menu" class="nav <?php echo ($layout == 'edit') ? 'disabled': ''; ?>">
	<li class="dropdown <?php echo ($option == 'com_sms' && $layout != 'edit') ? 'active': ''; ?> <?php echo ($layout == 'edit') ? 'disabled': ''; ?> ">

	<?php if($layout == 'edit') { ?>
		<a class="no-dropdown">
			<?php echo JText::_('MOD_MENU_COM_SMS');?>
		</a>
	<?php } else{ ?>
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<?php echo JText::_('MOD_MENU_COM_SMS');?> <span class="caret"></span>
		</a>
		<ul aria-labelledby="dropdownMenu" role="menu" class="dropdown-menu">
			<li <?php echo ($option == 'com_sms' && ($view == '' || $view == 'sms') ) ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms'); ?>">
					<span class="icon-home"></span> <?php echo JText::_('MOD_MENU_COM_SMS_HOME');?>
				</a>
			</li>
			<li <?php echo ($option == 'com_sms' && $view == 'academic') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=academic'); ?>">
					<span class="icon-screen"></span> <?php echo JText::_('MOD_MENU_COM_SMS_ACADEMIC');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'students') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=students'); ?>">
					<span class="icon-users"></span> <?php echo JText::_('MOD_MENU_COM_SMS_STUDENTS');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'teachers') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=teachers'); ?>">
					<span class="icon-users"></span> <?php echo JText::_('MOD_MENU_COM_SMS_TEACHERS');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'parents') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=parents'); ?>">
					<span class="icon-users"></span> <?php echo JText::_('MOD_MENU_COM_SMS_PARENTS');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'attendance') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=attendance'); ?>">
					<span class="icon-checkin"></span> <?php echo JText::_('MOD_MENU_COM_SMS_ATTENDANCE');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'exams') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=exams'); ?>">
					<span class="icon-pencil-2"></span> <?php echo JText::_('MOD_MENU_COM_SMS_EXAMS');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'marks') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=marks'); ?>">
					<span class="icon-star"></span> <?php echo JText::_('MOD_MENU_COM_SMS_MARKS');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'payments') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=payments'); ?>">
					<span class="icon-credit"></span> <?php echo JText::_('MOD_MENU_COM_SMS_PAYMENTS');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'message') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=message'); ?>">
					<span class="icon-mail-2"></span> <?php echo JText::_('MOD_MENU_COM_SMS_MESSAGE');?>
				</a>
			</li>

			<li <?php echo ($option == 'com_sms' && $view == 'accounting') ? 'class="active"': '';?>>
				<a href="<?php echo JRoute::_('index.php?option=com_sms&view=accounting'); ?>">
					<span class="icon-pie"></span> <?php echo JText::_('MOD_MENU_COM_SMS_ACCOUNTING');?>
				</a>
			</li>


		</ul>
	<?php } ?>
	</li>
</ul>

<?php
}
