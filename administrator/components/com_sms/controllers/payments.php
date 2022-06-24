<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerPayments extends SmsController
{
	
	/**
	** constructor
	**/
	function __construct(){
		parent::__construct();
	}
	
	/**
	** Get Section
	**/
	function getsection(){
	    $class_id = JRequest::getVar('val');
	    $model = $this->getModel('payments');
	    if(!empty($class_id)){
	        $section_value = $model->sectionList($class_id);
	        $section_array = array();
			$section_array[] = array('value' => '', 'text' => JText::_(' -- Select Section -- '));
	        foreach ($section_value as $sections) {
	            $section_array[] = array('value' => $sections, 'text' => JText::_(' '.SmsHelper::getSectionname($sections)));
	        }
		    echo $section =  JHTML::_('select.genericList', $section_array, 'student_section', ' class="required  inputbox  "  required="required"   ', 'value', 'text', '');
		}else{
		echo 'Please choose Class ';
		}
	
		JFactory::getApplication()->close();
	}
	
	
	/**
	** Get Details of history
	**/
	function history(){
		$model           = $this->getModel('payments');
		$year            = JRequest::getVar('val');
		$sid             = JRequest::getVar('sid');
		$details		 = $model->getPaymentDetails($sid,$year);
		$student		 = $model->getStudent($sid);
		$display_sistory = $model->history($details,$student,$year);
		if(!empty($year)){
			echo $display_sistory;
		}else{
		    echo "<p style='color: red;'>Please select year. </p>";
		}
	    JFactory::getApplication()->close();
	}
	
	
	/**
	** Check Roll
	**/
	function checkroll(){
		$model           = $this->getModel('payments');
		$roll            = JRequest::getVar('val');
		$student_class   = JRequest::getVar('student_class');
		$student_section = JRequest::getVar('student_section');
		$uid             = $model->getuserID($roll,$student_class,$student_section);
	    if(!empty($uid)){
			echo'<p style="color: green;">OK ! Student Name: '.$model->getStudentname($roll).'</p>';
			echo'<input type="hidden" id="student_id" name="student_id" value="'.$uid.'" />';
		}else{
			echo '<p style="color: red;">The roll number not store our database ! please try again </p>';
		}
		JFactory::getApplication()->close();
	}
	
	/**
	** Check Bill
	**/
	function bill(){
		$model     = $this->getModel('payments');
		$bill_arry = JRequest::getVar('val');
		$bill      = $model->getBill($bill_arry);
		if(!empty($bill)){
			$bill_value = '<p style="color: green;font-size: 150%;" id="total_bill"> '.number_format($bill,2).'</p>';
			$bill_value .= '<input type="hidden" name="total_bill" value="'.$bill.'" />';
			echo $bill_value;
		}else{
			echo "<p style='color: red;'>You didn't select payment type ! please try again </p>";
		}
		JFactory::getApplication()->close();
	}
	
	
	/**
	** Duse Ammount
	**/
	function due(){
		$model     = $this->getModel('payments');
		$bill_arry = JRequest::getVar('val');
		$bill      = $model->getBill($bill_arry);
		if(!empty($bill)){
			$bill_value = '<p style="color: red;font-size: 150%;" id="due_bill"> '.number_format($bill,2).'</p>';
			$bill_value .= '<input type="hidden" name="due_ammount" value="'.$bill.'" />';
			echo $bill_value;
		}else{
			echo "<p style='color: red;'>You didn't select payment type ! please try again </p>";
		}
		JFactory::getApplication()->close();
	}
	
	/**
	** Get Mobile field
	**/
	function getmobilefield(){
		$model             = $this->getModel('payments');
		$mobile_banking_id = JRequest::getVar('val');
		$mobile_banking    = $model->getMobilebanking($mobile_banking_id);
		if($mobile_banking=="1"){
		    echo'<fieldset style="border: 1px dashed #ccc;padding: 10px;background: #f5f5f5;width: 70%;">';
			echo'<legend style="background: #f5f5f5;border: 1px dashed #ccc;  color: green;  font-size: 13px;  line-height: 15px;  margin: 0; padding: 4px 10px; width: auto;">If you select mobile banking section</legend>';
			echo'<input type="text" name="pay_mobile_no" placeholder="Mobile No" style="margin: 10px 0;" />';
			echo'<input type="text" name="pay_pin_code" placeholder="Confirmation Code" />';
			echo'</fieldset>';
		}else{
			echo"<p></p>";
		}
		JFactory::getApplication()->close();
	}

	
	/**
	** Get Apply
	**/
	function apply(){
	    $model = $this->getModel('payments');
		$id    = $model->store();
		if ($id) {
			$msg = JText::_( 'Successfully received payment !' );
		}else{
			$msg = JText::_( 'Error Saving Data' );
		}
        $link = 'index.php?option=com_sms&view=payments&task=editpayment&cid[]='. $id;
		$this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('payments');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'Successfully received payment !' );
		} else {
		    $msg = JText::_( 'Error Saving Data' );
		}
		
		$link = 'index.php?option=com_sms&view=payments';
		$this->setRedirect($link, $msg);
	}
	 
	
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('grade');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Data Deleted' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=grade', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_sms&view=payments', $msg );
	}

	
}
