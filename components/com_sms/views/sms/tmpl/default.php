<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

//GET SCHOOLS DATA
$params = JComponentHelper::getParams('com_sms');
$schools_welcome = $params->get('schools_welcome');
$students_account = $params->get('students_account');
$teachers_account = $params->get('teachers_account');
$parents_account = $params->get('parents_account');

?>
<div id="com_sms" >
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-md-12 welcome-meg" ><?php echo $schools_welcome; ?></div>
		</div>
	</div>

	<div class="sms-container">
		<div class="bs-glyphicons quick-icon ">
			<ul class="bs-glyphicons-list sms-icon-menu">
				<?php if(!empty($students_account)): ?>
				<li>
					<a href="index.php?option=com_sms&view=students"> 
						<span class="fa fa-group "></span> 
						<span class="text_area"><?php echo JText::_('MENU_STUDENTS'); ?></span>
					</a>
				</li>
			    <?php endif; ?>

			    <?php if(!empty($teachers_account)): ?>
				<li>
					<a href="index.php?option=com_sms&view=teachers"> 
						<span class="fa fa-users"></span> 
						<span class="text_area"><?php echo JText::_('MENU_TEACHERS'); ?></span>
					</a>
				</li>
				<?php endif; ?>

				<?php if(!empty($parents_account)): ?>
				<li>
					<a href="index.php?option=com_sms&view=parents"> 
						<span class="fa fa-user "></span> 
						<span class="text_area"><?php echo JText::_('MENU_PARENTS'); ?></span>
					</a>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

</div>
