<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

 
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$model = $this->getModel();
$total_students = $model->getTotalStudents();
$total_teachers = $model->getTotalTeachers();
$total_parents = $model->getTotalParents();

 $today_date = date("Y-m-d");
 //get total present
 $total_present = count($model->totalPresent($today_date));
   
?>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=sms');?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
	
	
	    <div class="row-fluid" style="margin-top: 20px;">

		    <div class="span4 quick-info">
		        <a href="index.php?option=com_sms&view=students" class="tile-red">
		            <span class=" tile-stats-number"><?php echo $total_students; ?></span> 
					<span class="text_area  tile-stats-text"> <i class="fa fa-group"></i> <?php echo JText::_('LABEL_TOTAL_STUDENT'); ?> </span>
				</a>
		    </div>
		 
		    <div class="span4 quick-info">
		        <a href="index.php?option=com_sms&view=teachers" class="tile-green"> 
				    <span class=" tile-stats-number"><?php echo $total_teachers; ?></span> 
					<span class="text_area  tile-stats-text"> <i class="icon-users icon-white tile-stats-icond"></i> <?php echo JText::_('LABEL_TOTAL_TEACHER'); ?></span>
				</a>
		    </div>
		 
		    <div class="span4 quick-info">
		        <a href="index.php?option=com_sms&view=parents" class="tile-aqua"> 
				    <span class=" tile-stats-number"><?php echo $total_parents; ?></span> 
					<span class="text_area  tile-stats-text"> <i class="fa fa-user"></i> <?php echo JText::_('LABEL_TOTAL_PARENT'); ?></span>
				</a>
		    </div>
		 
		    
	    </div>
	
	    <h4>Schools Management System Quick icon</h4>
	    <div class="row-fluid">
	        <div class="span12">
	            <div class="bs-glyphicons quick-icon">
	                <ul class="bs-glyphicons-list">

			            <li>
			            	<a href="index.php?option=com_sms&view=students"> 
			            		<span class="fa fa-group "></span> 
			            		<span class="text_area"><?php echo JText::_('MENU_STUDENTS'); ?></span>
			            	</a>
			            </li>

						<li>
							<a href="index.php?option=com_sms&view=promotion"> 
								<span class="fa fa-tags"></span> 
								<span class="text_area"> <?php echo JText::_('MENU_PROMOTION'); ?></span>
							</a>
						</li>
										
                        <li>
                        	<a href="index.php?option=com_sms&view=teachers"> 
                        		<span class="icon-users icon-white"></span> 
                        		<span class="text_area"><?php echo JText::_('MENU_TEACHERS'); ?></span>
                        	</a>
                        </li>

						<li>
							<a href="index.php?option=com_sms&view=parents"> 
								<span class="fa fa-user "></span>
								<span class="text_area"><?php echo JText::_('MENU_PARENTS'); ?></span>
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
							<a href="index.php?option=com_sms&view=attendance"> 
								<i class="fa fa-check-square-o"></i> 
								<span class="text_area"><?php echo JText::_('MENU_ATTENDANCE'); ?></span>
							</a>
						</li>

						<li>
							<a href="index.php?option=com_sms&view=exams"> 
								<i class="fa fa-graduation-cap"></i> 
								<span class="text_area"><?php echo JText::_('MENU_EXAMS'); ?></span>
							</a>
						</li>
						 
						<li>
							<a href="index.php?option=com_sms&view=marks"> 
								<span class="icon-star icon-white"></span> 
								<span class="text_area"><?php echo JText::_('MENU_EXAM_MARKS'); ?></span>
							</a>
						</li>

						<li>
							<a href="index.php?option=com_sms&view=payments"> 
								<span class="icon-credit icon-white"></span> 
								<span class="text_area"><?php echo JText::_('MENU_PAYMENTS'); ?></span>
							</a>
						</li>

						<li>
							<a href="index.php?option=com_sms&view=message"> 
								<span class="icon-mail-2 icon-white"></span> 
								<span class="text_area"><?php echo JText::_('MENU_MESSAGES'); ?></span>
							</a>
						</li>

						<li>
							<a href="index.php?option=com_sms&view=accounting"> 
								<span class="icon-pie icon-white"></span> 
								<span class="text_area"><?php echo JText::_('MENU_ACCOUNTING'); ?></span>
							</a>
						</li>

                         
                        <li>
                        	<a href="index.php?option=com_sms&view=languages"> 
                        		<span class="fa fa-language"></span> 
                        		<span class="text_area"> <?php echo JText::_('MENU_LANGUAGES'); ?></span>
                        	</a>
                        </li>

                        <li>
                        	<a href="index.php?option=com_sms&view=fields"> 
                        		<span class="fa fa-building-o"></span> 
                        		<span class="text_area"> <?php echo JText::_('MENU_FIELD_BUILDER'); ?></span>
                        	</a>
                        </li>

                        <li>
                        	<a href="index.php?option=com_config&view=component&component=com_sms"> 
                        		<span class="fa fa-cog"></span> 
                        		<span class="text_area"> <?php echo JText::_('MENU_CONFIGURATION'); ?></span>
                        	</a>
                        </li>

                        <li>
                        	<a href="index.php?option=com_sms&view=activation"> 
                        		<span class="fa fa-toggle-on"></span> 
                        		<span class="text_area"> <?php echo JText::_('Activation & Update'); ?></span>
                        	</a>
                        </li>
                    </ul>
	            </div>
		    </div>
	    </div>
	
	</div>
	</div>
	
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="sms" />
	<?php echo JHtml::_('form.token'); ?>
</form>
