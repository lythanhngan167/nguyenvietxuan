<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


?>


	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
        

        <h3 class="text-center">Student's Backup & Restore</h3>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_students_csv');?>" class="btn btn-primary">Download Student's CSV</a>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_students_avatar');?>" class="btn btn-primary">Download Student's Avatar</a>

        <hr>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
        		<div class="row-fluid">
        			<div class="span3 text-right"><b>Upload CSV:</b></div>
        			<div class="span3"><input type="file" id="student_data" name="student_data"></div>
        			<div class="span3 text-center"><input type="button" id="upload_student_csv" class="btn btn-primary" value="Restore" ></div>
        			<div class="span3 text-left"><div id="student_csv_result"></div></div>
        		</div>
        </form>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    		<div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Avatar:</b></div>
    			<div class="span3"><input type="file" id="student_avatars" name="student_avatars"></div>
    			<div class="span3 text-center"><input type="button" id="upload_student_avatar" class="btn btn-primary" value="Restore" name=""></div>
    			<div class="span3 text-left"><div id="student_avatar_result"></div></div>
    		</div>
        </form>

        <hr>

        <h3 class="text-center">Teacher's Backup & Restore</h3>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_teachers_csv');?>" class="btn btn-primary">Download Teacher's CSV</a>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_teachers_avatar');?>" class="btn btn-primary">Download Teacher's Avatar</a>

        <hr>
        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    		<div class="row-fluid">
    			<div class="span3 text-right"><b>Upload CSV:</b></div>
    			<div class="span3"><input type="file" id="teachers_data" name="teachers_data"></div>
    			<div class="span3 text-center"><input type="button" id="upload_teachers_csv" class="btn btn-primary" value="Restore" ></div>
    			<div class="span3"><div id="teachers_csv_result"></div></div>
    		</div>
        </form>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    		<div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Avatar:</b></div>
    			<div class="span3"><input type="file" id="teacher_avatars" name="teacher_avatars"></div>
    			<div class="span3 text-center"><input type="button" id="upload_teachers_avatar" class="btn btn-primary" value="Restore" name=""></div>
    			<div class="span3 text-left"><div id="teachers_avatar_result"></div></div>
    		</div>
        </form>

        <hr>
        <h3 class="text-center">Parent's Backup & Restore</h3>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_parents_csv');?>" class="btn btn-primary">Download Parent's CSV</a>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_parents_avatar');?>" class="btn btn-primary">Download Parent's Avatar</a>

        <hr>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
        		<div class="row-fluid">
        			<div class="span3 text-right"><b>Upload CSV:</b></div>
        			<div class="span3"><input type="file" id="parent_data" name="parent_data"></div>
        			<div class="span3 text-center"><input type="button" id="upload_parent_csv" class="btn btn-primary" value="Restore" ></div>
        			<div class="span3 text-left"><div id="parent_csv_result"></div></div>
        		</div>
        </form>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    		<div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Avatar:</b></div>
    			<div class="span3"><input type="file" id="parent_avatars" name="parent_avatars"></div>
    			<div class="span3 text-center"><input type="button" id="upload_parent_avatar" class="btn btn-primary" value="Restore" name=""></div>
    			<div class="span3 text-left"><div id="parent_avatar_result"></div></div>
    		</div>
        </form>


        <hr>
        <h3 class="text-center">Academic Backup & Restore</h3>
        <p class="text-center">Class, Subject, Section, Division & Academic year</p>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_class_csv');?>" class="btn btn-primary">Class CSV</a>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_subject_csv');?>" class="btn btn-primary">Subject CSV</a>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_section_csv');?>" class="btn btn-primary">Section CSV</a>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_division_csv');?>" class="btn btn-primary">Division CSV</a>
        <a href="<?php echo JRoute::_('index.php?option=com_sms&controller=backup&task=download_year_csv');?>" class="btn btn-primary">Year CSV</a>
        

        <hr>
        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
        	<div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Class CSV:</b></div>
    			<div class="span3"><input type="file" id="upload_class" ></div>
    			<div class="span3 text-center"><input type="button" id="btn_upload_class" class="btn btn-primary" value="Restore" ></div>
    			<div class="span3 text-left"><div id="result_class"></div></div>
        	</div>
        </form>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    	    <div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Subjects CSV:</b></div>
    			<div class="span3"><input type="file" id="upload_subjects" ></div>
    			<div class="span3 text-center"><input type="button" id="btn_upload_subjects" class="btn btn-primary" value="Restore" ></div>
    			<div class="span3 text-left"><div id="result_subjects"></div></div>
    		</div>
        </form>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    	    <div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Section CSV:</b></div>
    			<div class="span3"><input type="file" id="upload_section" ></div>
    			<div class="span3 text-center"><input type="button" id="btn_upload_section" class="btn btn-primary" value="Restore" ></div>
    			<div class="span3 text-left"><div id="result_section"></div></div>
    		</div>
        </form>

        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    	    <div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Division CSV:</b></div>
    			<div class="span3"><input type="file" id="upload_division" ></div>
    			<div class="span3 text-center"><input type="button" id="btn_upload_division" class="btn btn-primary" value="Restore" ></div>
    			<div class="span3 text-left"><div id="result_division"></div></div>
    		</div>
        </form>
        <form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
    	    <div class="row-fluid">
    			<div class="span3 text-right"><b>Upload Year CSV:</b></div>
    			<div class="span3"><input type="file" id="upload_year" ></div>
    			<div class="span3 text-center"><input type="button" id="btn_upload_year" class="btn btn-primary" value="Restore" ></div>
    			<div class="span3 text-left"><div id="result_year"></div></div>
    		</div>
        </form>

	</div>


	<script type="text/javascript">
		// ##### Student's CSV upload
	    jQuery( "#upload_student_csv" ).click(function() { 
	    	var studentcsvData    = new FormData();
	        studentcsvData.append('csv', jQuery('#student_data')[0].files[0]);
	        jQuery('#student_csv_result').html('Loading....');
	        if (studentcsvData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_students_csv',
			        type : 'POST',
			        data : studentcsvData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#student_csv_result').html(obj.html);
			            jQuery('#student_data').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Student's avatar upload
	    jQuery( "#upload_student_avatar" ).click(function() { 
	    	var studentformData = new FormData();
	        studentformData.append('file', jQuery('#student_avatars')[0].files[0]);
	        jQuery('#student_avatar_result').html('Loading....');
	        if (studentformData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_students_avatar',
			        type : 'POST',
			        data : studentformData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#student_avatar_result').html(obj.html);
			            jQuery('#student_avatars').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Teacher's CSV upload
	    jQuery( "#upload_teachers_csv" ).click(function() { 
	    	var teachercsvData = new FormData();
	        teachercsvData.append('tcsv', jQuery('#teachers_data')[0].files[0]);
	        jQuery('#teachers_csv_result').html('Loading....');
	        if (teachercsvData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_teachers_csv',
			        type : 'POST',
			        data : teachercsvData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#teachers_csv_result').html(obj.html);
			            jQuery('#teachers_data').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Teacher's avatar upload
	    jQuery( "#upload_teachers_avatar" ).click(function() { 
	    	var teacherformData = new FormData();
	        teacherformData.append('file', jQuery('#teacher_avatars')[0].files[0]);
	        jQuery('#teachers_avatar_result').html('Loading....');
	        if (teacherformData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_teachers_avatar',
			        type : 'POST',
			        data : teacherformData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#teachers_avatar_result').html(obj.html);
			            jQuery('#teacher_avatars').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Parent's CSV upload
	    jQuery( "#upload_parent_csv" ).click(function() { 
	    	var parentcsvData = new FormData();
	        parentcsvData.append('parentcsv', jQuery('#parent_data')[0].files[0]);
	        jQuery('#parent_csv_result').html('Loading....');
	        if (parentcsvData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_parent_csv',
			        type : 'POST',
			        data : parentcsvData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#parent_csv_result').html(obj.html);
			            jQuery('#parent_data').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Parent's avatar upload
	    jQuery( "#upload_parent_avatar" ).click(function() { 
	    	var parentformData = new FormData();
	        parentformData.append('file', jQuery('#parent_avatars')[0].files[0]);
	        jQuery('#parent_avatar_result').html('Loading....');
	        if (parentformData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_parents_avatar',
			        type : 'POST',
			        data : parentformData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#parent_avatar_result').html(obj.html);
			            jQuery('#parent_avatars').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Class CSV upload
	    jQuery( "#btn_upload_class" ).click(function() { 
	    	var classData = new FormData();
	        classData.append('class_csv', jQuery('#upload_class')[0].files[0]);
	        jQuery('#result_class').html('Loading....');
	        if (classData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_class_csv',
			        type : 'POST',
			        data : classData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#result_class').html(obj.html);
			            jQuery('#upload_class').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Subject CSV upload
	    jQuery( "#btn_upload_subjects" ).click(function() { 
	    	var subjectData = new FormData();
	        subjectData.append('subject_csv', jQuery('#upload_subjects')[0].files[0]);
	        jQuery('#result_subjects').html('Loading....');
	        if (subjectData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_subject_csv',
			        type : 'POST',
			        data : subjectData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#result_subjects').html(obj.html);
			            jQuery('#upload_subjects').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Section CSV upload
	    jQuery( "#btn_upload_section" ).click(function() { 
	    	var sectionData = new FormData();
	        sectionData.append('section_csv', jQuery('#upload_section')[0].files[0]);
	        jQuery('#result_section').html('Loading....');
	        if (sectionData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_section_csv',
			        type : 'POST',
			        data : sectionData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#result_section').html(obj.html);
			            jQuery('#upload_section').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Division CSV upload
	    jQuery( "#btn_upload_division" ).click(function() { 
	    	var divisionData = new FormData();
	        divisionData.append('division_csv', jQuery('#upload_division')[0].files[0]);
	        jQuery('#result_division').html('Loading....');
	        if (divisionData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_division_csv',
			        type : 'POST',
			        data : divisionData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#result_division').html(obj.html);
			            jQuery('#upload_division').val(''); 
			       }
		        });
	         }
	    });

	    // ##### Year CSV upload
	    jQuery( "#btn_upload_year" ).click(function() { 
	    	var yearData = new FormData();
	        yearData.append('year_csv', jQuery('#upload_year')[0].files[0]);
	        jQuery('#result_year').html('Loading....');
	        if (yearData) {
		        jQuery.ajax({
			        url : 'index.php?option=com_sms&task=backup.upload_year_csv',
			        type : 'POST',
			        data : yearData,
			        processData: false,  // tell jQuery not to process the data
			        contentType: false,  // tell jQuery not to set contentType
			        success : function(data) {
	                    var obj = jQuery.parseJSON(data); 
			            jQuery('#result_year').html(obj.html);
			            jQuery('#upload_year').val(''); 
			       }
		        });
	         }
	    });
	</script>
   
	
	
