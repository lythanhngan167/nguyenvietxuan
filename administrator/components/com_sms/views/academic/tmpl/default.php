<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
$model = $this->getModel();

?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=academic');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
    </div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>
	
	
	
		<h4>Academic Quick icon </h4>
		<div class="row-fluid">
		    <div class="span12">
		        <div class="bs-glyphicons quick-icon">
		            <ul class="bs-glyphicons-list">
				        
				        <li>
				            <a href="index.php?option=com_sms&view=manageacademicyear"> 
				                <span class="fa fa-tags"></span> 
				                <span class="text_area"> <?php echo JText::_('MENU_ACADEMIC_YEAR'); ?></span>
				            </a>
				        </li>
											
		                <li>
		                    <a href="index.php?option=com_sms&view=subjects"> 
		                        <span class="icon-paragraph-center icon-white"></span> 
		                        <span class="text_area"><?php echo JText::_('MENU_SUBJECTS'); ?></span>
		                    </a>
		                </li>
						
						<li>
						    <a href="index.php?option=com_sms&view=sections"> 
						        <span class="icon-list icon-white"></span> 
						        <span class="text_area"><?php echo JText::_('MENU_SECTIONS'); ?></span>
						    </a>
						</li>
										 
						<li>
						    <a href="index.php?option=com_sms&view=division"> 
						        <span class="icon-grid icon-white"></span> 
						        <span class="text_area"><?php echo JText::_('MENU_DIVISION'); ?></span>
						    </a>
						</li>

						<li>
						    <a href="index.php?option=com_sms&view=class"> 
						        <span class="fa fa-sitemap"></span> 
						        <span class="text_area"><?php echo JText::_('MENU_CLASS'); ?></span>
						    </a>
						</li>

						<li>
				            <a href="index.php?option=com_sms&view=promotion"> 
				                <span class="fa fa-tags"></span> 
				                <span class="text_area"> <?php echo JText::_('MENU_PROMOTION'); ?></span>
				            </a>
				        </li>


					</ul>
		        </div>
		        
			</div>
		</div>
	
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="academic" />
	<?php echo JHtml::_('form.token'); ?>
</form>
