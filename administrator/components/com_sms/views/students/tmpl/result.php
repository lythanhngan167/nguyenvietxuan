<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidation');

//GET SCHOOLS DATA
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');

$user		= JFactory::getUser();
$uid =$user->get( 'id' );

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('user-form')))
		{
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	};
");

$model = $this->getModel();
?>

<script type="text/javascript">
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	document.getElementById("print").style.visibility = "hidden";
	document.getElementById("save").style.visibility = "hidden";
	//document.getElementById("comment").style.visibility = "hidden";
	window.print();
	document.body.innerHTML = originalContents;
	document.location.reload();
}
</script>

<div id="printableArea" >

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=students');?>" method="post" name="user-form" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data" style="margin: 0;">

    <style type="text/css">
        #isisJsData {margin-bottom: 0;}
        .container-main {padding: 50px 100px;background: #666;}
        #printableArea {background: #fff;}

		#system-message-container {width: 100%;}
		.odd-1{background: #eaf9f9;border: 1px solid #7cd5d5 !important;}
		.odd-2{background: #ccffcc;border: 1px solid #5ead5e !important;}

		.fail {color: red;}
		.abb-value {
		    display: block;
		    border: medium none !important;
		    line-height: 32px !important;
		    margin: 0;
		    position: absolute;
		    min-width: 40px;
		 }

		.bn-1 {border-bottom: 1px solid #eaf9f9 !important;}
		.bn-2 {border-bottom: 1px solid #ccffcc  !important;}

		.oddhead-1{background: #bbe8e8 !important;border: 1px solid #7cd5d5 !important;}
		.oddhead-2{background: #baefba !important;border: 1px solid #5ead5e !important;}

		.rf {width: 45px;}
		.rfd {width: 45px;}
		.mark-table {font-size: 15px;}
		.mark-table th {line-height: 14px;}
		.mark-table td {line-height: 14px;font-size: 15px; padding: 0;}
		.information-div h3,
		.information-div p {text-align: center;}
		.student_info_table {font-size: 16px;font-style: italic;line-height: 30px;}
		#comment {box-shadow: none; border-radius: 0px;font-style: italic;}
		#footer { position: fixed; bottom: 0px; }
    </style>

    <?php 
    //COMMON VARIABLE
    $db = JFactory::getDBO();
    $total_grade_point	=	0;
    $total_marks		=	0;
    $total_subjects		=	0;
    
    $class = $this->class;
    $roll = $this->roll;
    $exam_id = $this->exam_id;
    $exam_name = SmsHelper::selectSingleData('name', 'sms_exams', 'id', $exam_id);

    $student_name = SmsHelper::getStudentData('name', $class, $roll);

    $comment = $model->getComment( $class, $roll, $exam_id,  $uid);
        
	//Get grade system from class
	$grade_system_id = $model->getGradeSystem($class);
														
	//get grade list 
	$grade_items = $model->getGradeList($grade_system_id);
	$grade_rows = $grade_items;
				
    //GET SUBJECT LIST
	$query_subject_list = "SELECT subjects FROM `#__sms_class` WHERE id = '".$class."' ";
	$db->setQuery($query_subject_list);
	$subject_list_data = $db->loadResult();
	$subject_list = explode(",", $subject_list_data);
	$total_subject = count($subject_list);
				
	// Get Pad Header Info
	$pad_title = JText::_('LABEL_STUDENT_RESULT_CAPTION');
    $padheader = SmsHelper::padHeader($pad_title, $class);				
	
    				
	//Student Information
    $student_info = '<p style="text-align:center;"><b> '.JText::_('DEFAULT_EXAM').': '.$exam_name.'</b></p>'; 
	$student_info .= '<table  width="100%" class="student_info_table" id="admin-table" style="border: 0px;margin: 0px 0;" >';
	$student_info .='<tr>';
    $student_info .='<td style="text-align: left;border: 0px;padding-left:0;" width="20%" ><b> '.JText::_('LABEL_STUDENT_NAME').': </b>'.$student_name.' </td>';
	$student_info .='<td style="text-align: right;border: 0px;" width="20%" ><b> '.JText::_('LABEL_STUDENT_ROLL').':</b> '.$this->roll.'</td>';
	$student_info .='</tr>';   
	$student_info .='</table>';

    // Get Pad Body
    
    $result_display = $padheader;  
	//Result Display 
	$result_display .='<div class="padd-body" style="padding: 10px;">';
	$result_display .= $student_info;
	$result_display .= '<table  width="100%" class="mark-table " id="admin-table" style="border: 0px;" >';
	$result_display .= '<tr>'; 
	$result_display .= '<td style="border: 0px;padding-bottom: 2px; padding:0;">'; 
	
	$result_display .= '<table  width="100%" class="mark-table" id="admin-table"  >';
	//Head
	$result_display .= '<tr>'; 
	$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_SUBJECT').'</th>';
	$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_OBTAIN_MARK').'</th>';
	$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_GRADE').'</th>';
	$result_display .= '<th>'.JText::_('LABEL_STUDENT_RESULT_GRADE_COMMENT').'</th>';
	$result_display .= '</tr>'; 
	$tearm_total_mark=0;
	$tearm_total_gp =0;

			foreach ($subject_list as $j=>$subject) {
			    $subject_name = $model->getSubjectname($subject);
			    $marks = $model->getMark('marks', $exam_id, $class, $subject, $roll);
			    if(!empty($marks)){
			        $tearm_total_mark += $marks;
			    }
				
				//grade system
				$gp =0;
				$gpa =0;
				$gpcomment =0;
				foreach ($grade_rows as $grade_row) {
					if ($marks >= $grade_row->mark_from && $marks <= $grade_row->mark_upto){
                        $gp = $grade_row->grade_point;
						$gpa = $grade_row->name;
						$gpcomment = $grade_row->comment;
                    }
				}

				//total tearm gp
				$tearm_total_gp += $gp;

				//ignore empty comment
				if(!empty($gpcomment)){$gp_comment = $gpcomment;}else{$gp_comment ='';}

				//ignore empty GPA
				if(!empty($gpa)){$gpa_ok = $gpa;}else{$gpa_ok ='';}
														
				$result_display .= '<tr>'; 
				$result_display .= '<td class="text-left">'.$subject_name.'</td>';
				$result_display .= '<td class="text-center">'.$marks.'</td>';
				$result_display .= '<td class="text-center">'.$gpa_ok.'</td>';
				$result_display .= '<td class="text-center">'.$gp_comment.'</td>';
				$result_display .= '</tr>'; 
			}
			$result_display .= '</table>';  
					
	//Resultsheet Footer
	$result_display .= '<table  width="100%" class=" none-border-table"  style="border: 0px;margin: 0px 0;" >';
	$result_display .= '<tr>'; 
	$result_display .= '<td class="text-left" style="padding-left:0;padding-top: 15px;"><b> '.JText::_('LABEL_STUDENT_RESULT_TOTAL_MARK').': '.$tearm_total_mark.'</b></td>';
	
	//calculate Tearm GPA
	$total_subject = count($subject_list);
	$tearm_gp = round($tearm_total_gp / $total_subject);
	
	$result_display .= '<td class="text-center" style="padding-top: 15px;text-align:right;"><b> '.JText::_('LABEL_STUDENT_RESULT_GPA').' : '.$tearm_gp.'</b></td>';
	$result_display .= '</tr>'; 
	$result_display .= '</table>';  
	
	$result_display .= '</td>';
	$result_display .= '</tr>'; 
			
	$result_display .= '</table>';  
	$result_display .= '</div>';  
				
	echo $result_display;
	
    ?>

    <input type="hidden" name="class"  value="<?php echo $class; ?>" />
    <input type="hidden" name="roll"  value="<?php echo $roll; ?>" />
    <input type="hidden" name="exam"  value="<?php echo $exam_id; ?>" />
    <input type="hidden" name="controller" value="students" />
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    </form>


    <div  style="padding: 10px;margin-bottom: 20px;">
		<p style="margin-top: 10px;"><b><?php echo JText::_('LABEL_STUDENT_RESULT_TEACHER_COMMENT'); ?>:</b></p>
		<!-- Comment Post Form -->
		<div id="comment_result">
		    <textarea cols="" rows="" id="comment" style="width: 99%;height: 100px;"><?php echo $comment; ?></textarea>
		</div>
		<input type="hidden" name="class_id" id="class" value="<?php echo $class; ?>" />
		<input type="hidden" name="roll_number" id="roll" value="<?php echo $roll; ?>" />
		<input type="hidden" name="exam_id" id="eid" value="<?php echo $exam_id; ?>" />
		<input type="hidden" name="teacher_id" id="tid" value="<?php echo $uid; ?>" />
		<button id="save">Save</button>
    </div>

    <?php 
    // Get Pad Footer Info
	echo $padfooter = SmsHelper::padFooter();	
    ?>

</div>


<script type="text/javascript">

    function savecomment(){
		var cid = jQuery("#class").val();
		var roll = jQuery("#roll").val();
		var eid = jQuery("#eid").val();
		var tid = jQuery("#tid").val();
		var comment = jQuery("#comment").val();
		jQuery("#comment_result").html("Loading ...");
			jQuery.post( 'index.php?option=com_sms&task=students.savecomment',{cid:cid,roll:roll,eid:eid,tid:tid, comment:comment}, function(data){
			if(data){ jQuery("#comment_result").html(data); }
        });
	}
				
	jQuery( "#save" ).click(function() { savecomment(); });
	
				
</script>


