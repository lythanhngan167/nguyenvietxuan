<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.formvalidation');
  
//Collect Student Data
if(!empty($this->students->id)){$id = $this->students->id;}else {$id="";}
if(!empty($this->students->user_id)){$user_id = $this->students->user_id;}else {$user_id="";}
if(!empty($this->students->name)){$name = $this->students->name;}else {$name="";}
if(!empty($this->students->chabima)){$chabima = $this->students->chabima;}else {$chabima="";}
if(!empty($this->students->email)){$email = $this->students->email;}else {$email="";}
if(!empty($this->students->churanita)){$churanita = $this->students->churanita;}else {$churanita="";}

if(!empty($this->students->class)){$class = $this->students->class;}else {$class="";}
if(!empty($this->students->roll)){$roll = $this->students->roll;}else {$roll="";}
if(!empty($this->students->division)){$division = $this->students->division;}else {$division="";}
if(!empty($this->students->section)){$section = $this->students->section;}else {$section="";}
if(!empty($this->students->year)){$year = $this->students->year;}else {$year="";}
 
//Student Photo
if(!empty($this->students->photo)){
	$path = "../components/com_sms/photo/students/";
	$photo = $this->students->photo;
}else {
	$path = "../components/com_sms/photo/";
	$photo="photo.png";
}
 
 
JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'cancel' || document.formvalidator.isValid(document.getElementById('item-form')))
		{
			Joomla.submitform(task, document.getElementById('item-form'));
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

<style >
    .container-main {padding: 0;}
</style>


<form action="<?php echo JRoute::_('index.php?option=com_sms&view=students');?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal" enctype="multipart/form-data">
	<div id="printableArea" >
		<div  style="padding: 0px;">
        <?php  

        $pad_title = JText::_('LABEL_STUDENT_DETAILS');
		$student_info ='';	
    
        // Get Pad Header Info
        $student_info .= SmsHelper::padHeader($pad_title, $class);		
    
        // Get Pad Body
        $student_info .='<div class="padd-body" style="padding: 10px;">';
        $student_info .='<table cellpadding="0" cellspacing="0" width="100%" class="" id="layout-table" style="border: 0px;margin: 0px 0;" >';
			$student_info .='<tr>';
			//Student Academic details
			    $student_info .='<td style="text-align: left;border: 0px;" width="70%" >';
				$student_info .='<h4>'.JText::_('LABEL_STUDENT_ACADEMIC_INFO').'</h4>';
				$student_info .= '<table cellpadding="0" cellspacing="0"  width="100%" class="mark-table" id="admin-table"  style="border: 0px;margin: 0px 0;" >';
				$student_info .='<tr><td width="30%"> '.JText::_('LABEL_STUDENT_ROLL').':</td> <td> '.$roll.'</td></tr>';  
				$student_info .='<tr><td>'.JText::_('LABEL_STUDENT_CLASS').':</td> <td> '.SmsHelper::getClassname($class).'</td></tr>'; 
				$student_info .='<tr><td> '.JText::_('LABEL_STUDENT_SECTION').':</td> <td> '.SmsHelper::getSectionname($section).'</td></tr>'; 
				$student_info .='<tr><td> '.JText::_('LABEL_STUDENT_DIVISION').':</td> <td> '.SmsHelper::getDivisionname($division).'</td></tr>';  
				$student_info .='<tr><td> '.JText::_('LABEL_STUDENT_YEAR').':</td> <td> '.SmsHelper::getAcademicYear($year).'</td></tr>'; 
				$student_info .='</table>';
				$student_info .=' </td>';
				
			    //Student Photo
			    $student_info .='<td style="text-align: center;border: 0px;" width="30%" ><img src="'.$path.$photo.'" alt="" style="width: 150px;height: 165px;margin-top: 40px;" /> <p>'.$name.'</p></td>';
			$student_info .='</tr>';   
		$student_info .='</table>';
				
		//Student Profile Label
		$student_info .='<h4>'.JText::_('LABEL_STUDENT_INFORMATION').'</h4>';
				   
		//Student Details Table
		$student_info .= '<table cellpadding="0" cellspacing="0" width="100%" class="mark-table" id="admin-table" style="border: 0px;margin: 0px 0;" >';
		$student_info .='<tr><td width="30%">'.JText::_('LABEL_STUDENT_NAME').':</td> <td> '.$name.'</td></tr>';  
				
			//Field Builder
			$sid = SmsHelper::getFieldSectionID('student');
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
	    ?>
        </div>
    </div>

	<input type="hidden" name="cid" value="<?php echo $id;?>" />
	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
	<input type="hidden" name="controller" value="students" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
 
