<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

 
//Data Collect
if(!empty($this->teacher->id)){$id = $this->teacher->id;}else {$id="";}
if(!empty($this->teacher->user_id)){$user_id = $this->teacher->user_id;}else {$user_id="";}
if(!empty($this->teacher->name)){$name = $this->teacher->name;}else {$name="";}

if(!empty($this->teacher->chabima)){$chabima = $this->teacher->chabima;}else {$chabima="";}
if(!empty($this->teacher->email)){$email = $this->teacher->email;}else {$email="";}
if(!empty($this->teacher->churanita)){$churanita = $this->teacher->churanita;}else {$churanita="";}

if(!empty($this->teacher->class)){$class = $this->teacher->class;}else {$class="";}
if(!empty($this->teacher->division)){$division = $this->teacher->division;}else {$division="";}
if(!empty($this->teacher->section)){$section = $this->teacher->section;}else {$section="";}
if(!empty($this->teacher->subject)){$subject = $this->teacher->subject;}else {$subject="";}

if(!empty($this->teacher->facebook)){$facebook = $this->teacher->facebook;}else {$facebook="";}
if(!empty($this->teacher->twitter)){$twitter = $this->teacher->twitter;}else {$twitter="";}
if(!empty($this->teacher->linkedin)){$linkedin = $this->teacher->linkedin;}else {$linkedin="";}
if(!empty($this->teacher->googleplus)){$googleplus = $this->teacher->googleplus;}else {$googleplus="";}

if(!empty($this->teacher->photo)){
	$photo = $this->teacher->photo;
	$path = "../components/com_sms/photo/teachers/";
}else {
	$path = "../components/com_sms/photo/";
	$photo="photo.png";
}
if(!empty($this->teacher->designation)){$designation = $this->teacher->designation;}else {$designation="";}


//cancel button script
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('user-form')))
		{
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	};
");
	
		
?>

<script type="text/javascript">
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	document.getElementById("print").style.visibility = "hidden";
	window.print();
	document.body.innerHTML = originalContents;
	document.location.reload();
}
</script>

<style type="text/css">
    #system-message-container {width: 100%;}
    .container-main {padding: 0;}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_sms&view=teachers');?>" method="post" name="user-form" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data">

	<div id="printableArea" >
	<style type="text/css">
		.information-div h3,
		.information-div p {text-align: center;}
	</style>
    <?php 

        $pad_title = JText::_('LABEL_TEACHER_DETAILS');
		$student_info ='';	
    
        // Get Pad Header Info
        $student_info .= SmsHelper::padHeader($pad_title);		
    
        // Get Pad Body
        $student_info .='<div class="padd-body" style="padding: 10px;">';

        $student_info .= '<table  width="100%" class="" id="layout-table" style="border: 0px;margin: 0px 0;" >';
			$student_info .='<tr>';
				//Bio Data
			    $student_info .='<td style="text-align: left;border: 0px;" width="70%" >';
				$student_info .='<h4>'.JText::_('TAB_TEACHER_ACADEMIC_INFO').'</h4>';
				$student_info .= '<table  width="100%" class="mark-table" id="admin-table" style="border: 0px;margin: 0px 0;" >';
				$student_info .='<tr><td> '.JText::_('LABEL_TEACHER_DESIGNATION').':</td> <td> '.$designation.'</td></tr>'; 
					 
                // Set Teacher Subjects
                $student_info .='<tr><td> '.JText::_('LABEL_TEACHER_SUBJECT').':</td> <td> ';
                                $subject_ids = explode(",", $subject);
			                    $count_subject = count($subject_ids);
                                foreach ($subject_ids as $s=> $subject_id) {
                                $student_info .= SmsHelper::getSubjectname($subject_id);
                    
			                    if ($s < ($count_subject - 1)) {
                                $student_info .= ', ';
                                }
                                }
                $student_info .='</td></tr>';  
 
                // Set Teacher Class
				$student_info .='<tr><td> '.JText::_('LABEL_TEACHER_CLASS').':</td> <td> ';
                                $class_ids = explode(",", $class);
			                    $count_class = count($class_ids);
                                foreach ($class_ids as $c=> $class_id) {
                                $student_info .= SmsHelper::getClassname($class_id);
                    
			                    if ($c < ($count_class - 1)) {
                                $student_info .= ', ';
                                }
                                }
                $student_info .='</td></tr>'; 
 
                // Set Teacher Section
				$student_info .='<tr><td> '.JText::_('LABEL_TEACHER_SECTION').':</td> <td> ';
                                $section_ids = explode(",", $section);
			                    $count_section = count($section_ids);
                                foreach ($section_ids as $sc=> $section_id) {
                                $student_info .= SmsHelper::getSectionname($section_id);
                    
			                    if ($sc < ($count_section - 1)) {
                                $student_info .= ', ';
                                }
                                }
                $student_info .= '</td></tr>'; 
				$student_info .='</table>';
					 
					
				$student_info .=' </td>';
				
				//Student Photo
				$student_info .='<td style="text-align: center;border: 0px;" width="30%" ><img src="'.$path.$photo.'" alt="" style="width: 150px;height: 165px;margin-top: 40px;" /><p>'.$name.'</p>';
				
				
				$student_info .='</td>';
				
				$student_info .='</tr>';   
				$student_info .='</table>';
				
				
				
				
				$student_info .='<h4>'.JText::_('LABEL_TEACHER_INFORMATION').'</h4>';
				$student_info .= '<table  width="100%" class="mark-table" id="admin-table" style="border: 0px;margin: 0px 0;" >';
				$student_info .='<tr><td> '.JText::_('LABEL_TEACHER_NAME').':</td> <td> '.$name.'</td></tr>';  
					
					
					//Field Builder
				 $sid = SmsHelper::getFieldSectionID('teacher');
				 $fields = SmsHelper::getFieldList($sid);
				 $total_field = count($fields);
				 
				 $f=0;
				 foreach($fields as $field){
				 $f++;
				 $fid = $field->id;
				 $sid = $sid;
				 $panel_id = $id;
				 
				 $student_info .= SmsHelper::fieldBiodata($fid, $sid, $panel_id, $field->field_name,$field->type,$field->biodata);
				 }
					
					 $student_info .='</table>';
        $student_info .='</div>';
        // End Pad Body
    
        // Get Pad Footer Info
	    $student_info .= SmsHelper::padFooter();
     
        // Dispaly All
        echo $student_info;
 ?>
</div>
<input type="hidden" name="cid" value="<?php echo $id;?>" />
<input type="hidden" name="id" value="<?php echo $id;?>" />
<input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
<input type="hidden" name="controller" value="teachers" />
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

