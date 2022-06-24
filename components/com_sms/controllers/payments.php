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
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

	}
	
	/**
	** Get Payments Filter
	**/
	function getpayments(){
		$items = JRequest::getVar('items');
		$status_filter = JRequest::getVar('status_filter');
		$month_filter = JRequest::getVar('month_filter');
		$year_filter = JRequest::getVar('year_filter');
		$section_filter = JRequest::getVar('section_filter');
		$roll_filder = JRequest::getVar('roll_filder');
		$model = $this->getModel('payments');
		if(!empty($items)){
			$return_value = $model->paymentList($items,$status_filter,$month_filter,$year_filter,$section_filter,$roll_filder);
			echo $return_value;
		}
	 
	    JFactory::getApplication()->close();
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
	            $section_array[] = array('value' => $sections, 'text' => JText::_(' '.$model->getSectionname($sections)));
	        }
			echo $section =  JHTML::_('select.genericList', $section_array, 'student_class', ' class="required  inputbox  "  required="required"   ', 'value', 'text', '');
	       
		}else{
		echo 'Please choose Class ';
		}
		//return $test;
		JFactory::getApplication()->close();
	}
	
	
	/**
	** Check Roll
	**/
	function checkroll(){
		$model = $this->getModel('payments');
		$roll = JRequest::getVar('val');
		$student_class = JRequest::getVar('student_class');
		$student_section = JRequest::getVar('student_section');
		
		$uid = $model->getuserID($roll,$student_class,$student_section);
		if(!empty($uid)){

			// Get Student avatar
			$student_photo = SmsHelper::selectSingleData('photo', 'sms_students', 'id', $uid);
			if(!empty($student_photo)){
	            $path = "components/com_sms/photo/students/";
				$photo = $student_photo;
				$img_src = JUri::base().$path.$photo;
			}else {
				$path = "components/com_sms/photo/";
				$photo="photo.png";
				$img_src = JUri::base().$path.$photo;
			}

			$student = '
			<p style="color: green;">Roll number is match ! <br> </p>
			<div>
			    <img src="'.$img_src.'" class="avator-admin" alt="" width="50px" style="float: left;padding-right: 10px;">
			    <p style="margin: 0;font-size: 12px;">Student Name: '.$model->getStudentname($roll).'</p>
			    <p style="margin: 0;font-size: 12px;">Roll: '.$roll.'</p>
			</div>
			';
		
		    echo $student;
		    echo'<input type="hidden" id="student_id" name="student_id" value="'.$uid.'" />';
		}else{
		echo '<p style="color: red;">The roll number not store our database ! please try again </p>';
		}
		
		JFactory::getApplication()->close();
	}
	
	/**
	** Get Save
	**/ 
	function save(){
		$model = $this->getModel('payments');

		$id = $model->store();
		if ($id) {
			$msg = JText::_( 'Payment successfully store' );
		} else {
			$msg = JText::_( 'Error Saving Data' );
		}
		
		$link = JRoute::_('index.php?option=com_sms&view=payments&task=process&cid='.$id.'');
		$this->setRedirect($link);
	}
	 
	/**
	** Check Bill
	**/
	function bill(){
		$model = $this->getModel('payments');
		$bill_arry = JRequest::getVar('val');
		$bill= $model->getBill($bill_arry);
		if(!empty($bill)){
			$bill_value = '<p style="color: green;font-size: 150%;" id="total_bill"> '.number_format($bill,2).'</p>';
			$bill_value .= '<input type="hidden" id="total_bill_value" name="total_bill" value="'.$bill.'" />';
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
		$model = $this->getModel('payments');
		$bill_arry = JRequest::getVar('val');
		$bill= $model->getBill($bill_arry);
		if(!empty($bill)){
			$bill_value = '<p style="color: red;font-size: 150%;" id="due_bill"> '.number_format($bill,2).'</p>';
			$bill_value .= '<input type="hidden" name="due_ammount" value="'.$bill.'" />';
			echo $bill_value;
		}else{
			echo "<p style='color: red;'>You didn't select payment type ! please try again </p>";
		}
		
		JFactory::getApplication()->close();
	}
	
	
	
}
