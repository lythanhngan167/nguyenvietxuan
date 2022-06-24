<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelPayments extends JModelList
{
	
	
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
				
		// Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    /**
	** Parent student List
	**/
	function getStudentList($pid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'roll', 'name')))
            ->from($db->quoteName('#__sms_students'))
            ->order('id ASC');
				
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        // get 
        $student_data = SmsHelper::selectSingleData('student_id', 'sms_parents', 'id', $pid);
        $student_ids = explode(",", $student_data);


        $student_array = array();
        $student_array[] = array('value' => '', 'text' => JText::_(' -- Select Student -- '));
        foreach ($rows as $key=>$row) {
            if(in_array($row->id, $student_ids))
            {
            $student_array[] = array('value' => $row->roll, 'text' => JText::_(' '.$row->name));
            }else{
               	unset( $rows[ $key ] ); 
            }
        }
	    $student_list =  JHTML::_('select.genericList', $student_array, 'student_roll', ' class="required  inputbox  "   ', 'value', 'text', '');
        return $student_list;
	}
	
	/**
	** Get Student Id from user id
	**/
	function getStudentID($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Division Name
	**/
	function getDivisionname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('division_name')))
            ->from($db->quoteName('#__sms_division'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Class Name
	**/
	function getClassname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('class_name')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Section Id
	**/
	function getSectionIDS($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('section')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Student Id
	**/
	function getuserID($roll,$student_class,$student_section){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_students'))
			->where($db->quoteName('class') . ' = '. $db->quote($student_class))
			->where($db->quoteName('section') . ' = '. $db->quote($student_section))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Student Name
	**/
	function getStudentname($roll){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Section Name
	**/
	function getSectionname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('section_name')))
            ->from($db->quoteName('#__sms_sections'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Pay Ammount
	**/
	function getPayammount($id,$field){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array($field)))
            ->from($db->quoteName('#__sms_pay_type'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	// Paid By
	function getPaidBy($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_pay_method'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	**  TEACHERE ID
	**/
	function getTeacherClass($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('class')))
            ->from($db->quoteName('#__sms_teachers'))
            ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	**  get Student
	**/
	function getStudent($id){
		$student = $this->getTable('students');
        $student->load($id);
		return $student;
	}
	 
	/**
	** Parent Student ID
	**/
	function getParentStudentID($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('student_id')))
            ->from($db->quoteName('#__sms_parents'))
            ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Display Payment List 
	**/
	function paymentList($item_id,$status_filter,$month_filter,$year_filter,$section_filter,$roll_filder){
        // Get user info
		$user		= JFactory::getUser();
        $uid =$user->get('id');
		$group_title =  SmsHelper::checkGroup($uid);
					 
		// Condition of teacher , student & Parent
		if($group_title=="Teachers"){
			$items = $this->getPaymentDetailsByClass($item_id,$status_filter,$month_filter,$year_filter,$section_filter,$roll_filder);
		}elseif ($group_title=="Parents") {
			$items = $this->getPaymentDetails($item_id,$status_filter,$month_filter,$year_filter);	
		}else{
			$items = $this->getPaymentDetails($item_id,$status_filter,$month_filter,$year_filter);
		}

		// Empty message show
		if(empty($items)){
			$payment_data ='<div class="alert alert-no-items">';
			$payment_data .= JText::_('JGLOBAL_NO_MATCHING_RESULTS');
			$payment_data .='</div>';
		}else{ 
			$payment_data ='<table class="admin-table" id="admin-table" style="width: 100%;margin-top: 20px;" align="center">';
			$payment_data .='<tr>';
			$payment_data .='<th>#</th>';
			if($group_title=="Teachers"){
			    $payment_data .='<th>'.JText::_('LABEL_STUDENT_NAME').'</th>';
				$payment_data .='<th>'.JText::_('LABEL_STUDENT_ROLL').'</th>';
			}
			$payment_data .='<th>'.JText::_('LABEL_PAYMENT_PAY_MONTH_YEAR').'</th>';
			$payment_data .='<th>'.JText::_('LABEL_PAYMENT_PAY_AMMOUNT').'</th>';
			$payment_data .='<th>'.JText::_('LABEL_PAYMENT_PAY_BY').'</th>';
			$payment_data .='<th>'.JText::_('LABEL_PAYMENT_STATUS').'</th>';
			$payment_data .='<th>Date</th>';
			$payment_data .='<th></th>';
			$payment_data .='<th></th>';
			$payment_data .='</tr>';

			$months = array('', JText::_('COM_SMS_MONTH_JANUARY'), JText::_('COM_SMS_MONTH_FEBRUARY'), JText::_('COM_SMS_MONTH_MARCH'), JText::_('COM_SMS_MONTH_APRIL'),  JText::_('COM_SMS_MONTH_MAY'), JText::_('COM_SMS_MONTH_JUNE'), JText::_('COM_SMS_MONTH_JULY'), JText::_('COM_SMS_MONTH_AUGUST'), JText::_('COM_SMS_MONTH_SEPTEMBER'),JText::_('COM_SMS_MONTH_OCTOBER'), JText::_('COM_SMS_MONTH_NOVEMBER'),JText::_('COM_SMS_MONTH_DECEMBER'),);
					 
			$i=0;
		    foreach ($items as $item){
		        $i++;
		        $monthName = $months[$item->month]; 
			    //$monthName = date("F", mktime(null, null, null, $item->month));
				$year = $item->year;
				$ammount = $item->paid_ammount;
				$uid = $item->uid;
				$student_id         = $item->student_id;
			    $student_name = SmsHelper::getStudentname($student_id);
				$paidby = $item->payment_method;
				$status = $item->status;

				$link_payment = JRoute::_( 'index.php?option=com_sms&view=payments&task=process&cid='. $item->id );
                $edit_payment = JRoute::_( 'index.php?option=com_sms&view=payments&task=editpayment&cid='. $item->id );

                if($paidby == 'offline'){
                    $paid_button = '';

                	if($group_title=="Teachers"){
                        $edit_button = '<br><a href="'.$edit_payment.'" title="Review" class="btn btn-primary" ><i class="fa fa-pencil"></i></a>';
                	}else{
                		$edit_button = '';
                	}
                	
                }else{
                	$paid_button = '<br><a href="'.$link_payment.'" class="btn btn-primary" >Pay Now</a>';
                	$edit_button = '';
                }

				if($status=="0"){
					$st = '
					<span style="color: orange;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_PENDING').'</span> 
					'.$edit_button.'
					'.$paid_button.'
					';
				}

				if($status=="1"){
					$st = '
					<span style="color: green;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_PAID').'</span>
					'.$edit_button.'
					';
				}

				if($status=="2"){
					$st = '
					<span style="color: red;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_UN_PAID').'</span>
					'.$edit_button.'
					'.$paid_button.'
					';
				}
						
			    if($status=="3"){
			    	$st = '
			    	<span style="color: magenta;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_CANCEL').'</span>
					'.$edit_button.'
					'.$paid_button.'
					';
				}
						
				if($status=="4"){
					$st = '
					<span style="color: mediumblue;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_UNDER_REVIEW').'</span>
					'.$edit_button.'
					'.$paid_button.'
					';
				}
						
				$submit_date = date( 'Y-m-d', strtotime($item->create_date));
				$link_invoice 		= JRoute::_( 'index.php?option=com_sms&view=payments&task=invoice&cid='. $item->id );
				$link_PDF 	= JRoute::_( 'index.php?option=com_sms&view=payments&task=invoicepdf&cid='. $item->id.'' );

		        $payment_data .='<tr>';
				$payment_data .='<td>'.$i.'</td>';
				if($group_title=="Teachers"){
					$payment_data .='<td style="text-align: left;" >'.$student_name.'</td>';
					$payment_data .='<td  >'.$item->student_roll.'</td>';
				}
				$payment_data .='<td style="text-align: left;" >'.$monthName.' - '.$year.'</td>';
				$payment_data .='<td>'.SmsHelper::getCurrency($ammount).'</td>';
				$payment_data .='<td>'.$paidby.'</td>';
				$payment_data .='<td>'.$st.'</td>';
				$payment_data .='<td>'.$submit_date.'</td>';
				$payment_data .='<td>
								<a href="'.$link_invoice.'" class="btn" title="Invoice">
								<i class="fa fa-file-text-o"></i>
								</a>
								</td>';
				$payment_data .='<td>
								<a href="'.$link_PDF.'" title="PDF" class="btn"><i class="fa fa-file-pdf-o"></i></a>
								</td>';
				$payment_data .='</tr>';
		    } // end loop
		$payment_data .='</table>';
		}
					 
	    return $payment_data;
	}
	
	
	/**
	**  Students & Parent payment list (Query By Student ID)
	***/
	function getPaymentDetails($id,$status_filter,$month_filter,$year_filter){

		$user		= JFactory::getUser();
        $uid =$user->get('id');
	    $group_title =  SmsHelper::checkGroup($uid);

	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_payments'))
            ->order('month ASC');
		
		if ($group_title=="Parents") {
			$student_ids = SmsHelper::selectSingleData('student_id', 'sms_parents', 'id', $id);
			$query->where('student_id IN(' . $student_ids.')');
			
		}else{
            $query->where($db->quoteName('student_id') . ' = '. $db->quote($id));
		}		
		
		// Filter by Status.
		if($status_filter){
		    if($status_filter=='12'){$status_filter=0;}
		    if (is_numeric($status_filter)){
			    $query->where('status = ' . $db->quote($status_filter));
		    }
		}
				
		// Filter by Month.
		if(!empty($month_filter)){
			$query->where('month = ' . $db->quote($month_filter));
		}
				
		// Filter by Year.
		if(!empty($year_filter)){
			$query->where('year = ' . $db->quote($year_filter));
		}
			
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		return  $rows;
	}
	
	
	/**
	** Teachers Payment list (Query by teacher class ID)
	**/
	function getPaymentDetailsByClass($id,$status_filter,$month_filter,$year_filter,$section_filter,$roll_filder)
	{
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_payments'))
            ->where($db->quoteName('student_class') . ' = '. $db->quote($id))
            ->order('month ASC');
		// Filter by Status.
		if($status_filter){
			if($status_filter=='12'){$status_filter=0;}
			if (is_numeric($status_filter)){
				$query->where('status = ' . $db->quote($status_filter));
			}
		}
				
		// Filter by Month.
		if(!empty($month_filter)){
			$query->where('month = ' . $db->quote($month_filter));
		}
				
		// Filter by Year.
		if(!empty($year_filter)){
			$query->where('year = ' . $db->quote($year_filter));
		}
				
		// Filter by Section.
		if(!empty($section_filter)){
			$query->where('student_section = ' . $db->quote($section_filter));
		}
				
		// Filter by Roll.
		if(!empty($roll_filder)){
			$query->where('student_roll = ' . $db->quote($roll_filder));
		}
				
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'desc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
				
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		return  $rows;
	}
	
	/**
	** Get Payment
	**/
	function getPayments($id){
	    if($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('payments');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	
	/**
	** Class List
	**/
	function getclassList($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'class_name')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $class_array = array();
        $onclick_manage_subject = "javascript: formget(this.form, 'index.php?option=com_sms&controller=payments&task=getsectionlist&format=raw');";
		$class_array[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_CLASS'));
        foreach ($rows as $row) {
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
        }
	    $class =  JHTML::_('select.genericList', $class_array, 'student_class', ' class="required  inputbox  "  required="required"   ', 'value', 'text', $id);
       return $class;
	}
	
	/**
	** Section List
	**/
	function sectionList($class_id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('section')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($class_id));
		$db->setQuery($query);
		$data = $db->loadResult();
		$section_value = explode(",", $data);
		return $section_value;
	}
	
	/**
	** Get Section List
	**/
	function getsectionList($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'section_name')))
            ->from($db->quoteName('#__sms_sections'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $sections = array();
        $sections[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_SECTION'));
        foreach ($rows as $row) {
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
        }
	    $section =  JHTML::_('select.genericList', $sections, 'student_section', 'class=" required inputbox  " required="required" ', 'value', 'text', $id);
        return $section;
	}
	
	/**
	** Pay For  List
	**/
	function getPayForList($id){
	    $db = JFactory::getDBO();
		$app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_sms');
        $currency = $params->get('currency');
        $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'name','fee')))
            ->from($db->quoteName('#__sms_pay_type'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $class_array = array();
				
		if($id){
			$query_result = $db->getQuery(true);
			$query_result
                ->select($db->quoteName(array('pay_for_id')))
                ->from($db->quoteName('#__sms_payments'))
                ->where($db->quoteName('id') . ' = '. $db->quote($id));
			$db->setQuery($query_result);
			$select_data = $db->loadResult();
			$select_value = explode(",", $select_data);
		}else{
			$select_value = "";
		}
       
        foreach ($rows as $row) {
        	$ammount = SmsHelper::getCurrency($row->fee);
            $class_array[] = array('value' => $row->id, 'text' => $row->name.' ('.$ammount.')');
        }
		$class =  JHTML::_('select.genericList', $class_array, 'pay_for_id[]', 'multiple="multiple" class="payment_type required  inputbox  " required="required"   ', 'value', 'text', $select_value);
        return $class;
	}
	
	
	/**
	** Get Bill 
	**/
	function getBill($bill_arry){
	    $db = JFactory::getDBO();
		$total_fee =0;
		if($bill_arry){
			foreach ($bill_arry as $bills){
				$bill_id = $bills;
				$query_result = $db->getQuery(true);
				$query_result
                    ->select($db->quoteName(array('fee')))
                    ->from($db->quoteName('#__sms_pay_type'))
                    ->where($db->quoteName('id') . ' = '. $db->quote($bill_id));
				$db->setQuery($query_result);
				$fee = $db->loadResult();
				$total_fee += $fee;
			}
		}
				
		return $total_fee;
	}
	
	
	/**
	** Save Payment
	**/
	public function store(){
		$table =& $this->getTable('payments');
		$data = JRequest::get( 'post' );

		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}

		// Store the data.
		if($data['id']){$table->id = $data['id'];}

        if($data['student_roll']){
        	$table->student_roll = $data['student_roll'];
        	$student_id = SmsHelper::selectSingleData('id', 'sms_students', 'roll', $data['student_roll']);
        	$student_section = SmsHelper::selectSingleData('section', 'sms_students', 'roll', $data['student_roll']);
        	$student_class = SmsHelper::selectSingleData('class', 'sms_students', 'roll', $data['student_roll']);
        	$table->student_id = $student_id;
        	$table->student_section = $student_section;
        	$table->student_class = $student_class;
        }

		if($data['pay_for_id']){
			$pay_item = implode(",", $data['pay_for_id']);
			$table->pay_for_id = $pay_item;
		}
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
    
		$id = $table->id;
		return $id;
	}
	
	
	
}
