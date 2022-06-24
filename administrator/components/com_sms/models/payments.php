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
	
	/**
	** constructor
	**/
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
	** Get user ID
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
	    $query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll));
		$db->setQuery($query_result);
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
	
	/**
	** Total Pending
	**/
	function totalPending(){
	    $db = JFactory::getDBO();
        $query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_payments'))
            ->where($db->quoteName('status') . ' = '. $db->quote('0'));
		$db->setQuery($query);
        $rows = count($db->loadObjectList());
        return $rows;
	}
	
	/**
	** Paid By
	**/
	function getPaidBy($id){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_pay_method'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
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
	** Get Student 
	**/
	function getStudent($id){
		$student = $this->getTable('students');
        $student->load($id);
		return $student;
	}
	 
	/**
	** Get Details of history
	**/
	function history($details,$student,$year){
	    //Collect Student data
		if(!empty($student->id)){$id = $student->id;}else {$id="";}
        if(!empty($student->name)){$name = $student->name;}else {$name="";}
        if(!empty($student->class)){$class = SmsHelper::getClassname($student->class);}else {$class="";}
        if(!empty($student->section)){$section = SmsHelper::getSectionname($student->section);}else {$section="";}
        if(!empty($student->division)){$division = SmsHelper::getDivisionname($student->division);}else {$division="";}
        if(!empty($student->roll)){$roll = $student->roll;}else {$roll="";}
					 
		//GET SCHOOLS DATA
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_sms');
        $schools_name = $params->get('schools_name');
        $schools_address = $params->get('schools_address');
        $schools_phone = $params->get('schools_phone');
        $schools_email = $params->get('schools_email');
        $schools_website = $params->get('schools_web');
		$details_show = $schools_name;
					 
		//Display Header Information
		$onclick_link ="'printableArea'";
        $header_con ='<p style="text-align: center;"><input type="button" id="print" onclick="printDiv('.$onclick_link.')" class="btn btn-small"  style="border: none;margin-left: 10px;" value="Print" /> </p>';
	    $details_show  = '<h4 class="" style="text-align: center;margin-bottom: 3px;" >'.$schools_name.'</h4>';
		$details_show .='<p style="text-align: center;"><b>'.JText::_('LABEL_PAYMENT_HISTORY_TITLE').' - '.$year.'</b></p>';
		$details_show .= $header_con;
					 
		//Display Student info
		$details_show .='<table  align="center"   class="" id="admin-table" style="margin-bottom: 0px;margin-top: 10px;width: 95%;border: none;" >';
		$details_show .='<tr>';
		$details_show .='<td align="left" style="border: 0px;"><b> '.JText::_('LABEL_STUDENT_NAME').': </b>'.$name.'</td>';
		$details_show .='<td align="left" style="border: 0px;"><b> '.JText::_('LABEL_STUDENT_ROLL').':</b> '.$roll.'</td>';
		$details_show .='<td align="center" style="border: 0px;"><b> '.JText::_('LABEL_STUDENT_CLASS').': </b>'.$class.'</td>';
		$details_show .='<td align="right" style="border: 0px;"><b> '.JText::_('LABEL_STUDENT_SECTION').':</b> '.$section.'</td>';
		$details_show .='<td align="right" style="border: 0px;" ><b>'.JText::_('LABEL_STUDENT_DIVISION').':</b> '.$division.'</td>';
		$details_show .='</tr>'; 
		$details_show .='</table>';  

		$details_show .= '<table class="admin-table" align="center"  id="admin-table" style="width: 95%;margin-top: 10px;" align="center">';
		$details_show .= '<tr>';
		$details_show .='<th>#</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_HISTORY_MONTH').'</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_HISTORY_PAYMENT_TYPE').'</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_HISTORY_TOTAL_PAYMENT').'</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_HISTORY_PAID_AMMOUNT').'</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_HISTORY_DUE_AMMOUNT').'</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_HISTORY_PAID_BY').'</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_STATUS').'</th>';
		$details_show .='<th>'.JText::_('LABEL_PAYMENT_HISTORY_SUBMIT_DATE').'</th>';
		$details_show .='<th>#</th>';
		$details_show .='</tr>';
					
		$i=0;
		if(!empty($details)){
		       		 
		    foreach ($details as $item){
		        $i++;
				$monthName = date("F", mktime(null, null, null, $item->month));
				$year = $item->year;
				$payment_type = $this->getPayammount($item->pay_for_id,'name');
				$paidby = $item->payment_method;
				$link_invoice 		= JRoute::_( 'index.php?option=com_sms&view=payments&task=invoice&cid[]='. $item->id );
				$status = $item->status;
				if($status=="0"){$st = '<span style="color: orange;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_PENDING').'</span>';}
				if($status=="1"){$st = '<span style="color: green;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_PAID').'</span>';}
				if($status=="2"){$st = '<span style="color: red;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_UN_PAID').'</span>';}
				if($status=="3"){$st = '<span style="color: magenta;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_CANCEL').'</span>';}
				if($status=="4"){$st = '<span style="color: mediumblue;font-weight: bold;" >'.JText::_('COM_SMS_LABEL_STATUS_UNDER_REVIEW').'</span>';}
				
				$submit_date = date( 'Y-m-d g:i A', strtotime($item->create_date));
						
		        $details_show .='<tr>';
				$details_show .='<td align="center">'.$i.'</td>';
				$details_show .='<td style="text-align: left;" >'.$monthName.' - '.$year.'</td>';
				$details_show .='<td style="text-align: left;" >'.$payment_type.'</td>';
				$details_show .='<td align="right">'.SmsHelper::getCurrency($item->total_bill).'</td>';
				$details_show .='<td align="right">'.SmsHelper::getCurrency($item->paid_ammount).'</td>';
				$details_show .='<td align="right">'.SmsHelper::getCurrency($item->due_ammount).'</td>';
				$details_show .='<td align="center">'.$paidby.'</td>';
				$details_show .='<td align="center">'.$st.'</td>';
				$details_show .='<td align="center">'.$submit_date.'</td>';
				$details_show .='<td align="center"><a href="'.$link_invoice.'" class="btn">'.JText::_('BTN_PAYMENT_VIEW_INVOICE').'</a></td>';
				$details_show .='</tr>';
		    }
		}
		$details_show .='</table>';
		if(empty($details)){
		   $details_show .="<p style='color: red;text-align: center;padding: 10px;'>No record found. </p>";
		}
		return  $details_show;
	}
	
	/**
	** Payment Details
	**/
	function getPaymentDetails($id,$year){
	    $db = JFactory::getDBO();
        $query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_payments'))
            ->where($db->quoteName('student_id') . ' = '. $db->quote($id))
			->where(' YEAR(create_date) = '. $db->quote($year))
            ->order('month ASC');	
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		return  $rows;
	}
	
	
	/**
	** Get Payments
	**/
	function getPayments($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('payments');
			$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	/**
	** Payment List 
	**/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_payments'))
		->order('id desc');
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'desc'); 		
		
		// Filter by Status.
		$status = $this->getState('list.status');
		if (is_numeric($status)){
			$query->where('status = ' . $db->quote($status));
		}
		
		// Filter by Class.
		$classId = $this->getState('filter.class_id');
		if (is_numeric($classId)){
			$query->where('student_class = ' . $db->quote($classId));
		}
		
		// Filter by Section.
		$sectionId = $this->getState('filter.section_id');
		if (is_numeric($sectionId)){
			$query->where('student_section = ' . $db->quote($sectionId));
		}
		
		// Filter by Month.
		$monthId = $this->getState('filter.month_id');
		if (is_numeric($monthId)){
			$query->where('month = ' . $db->quote($monthId));
		}
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)){
			if (stripos($search, 'id:') === 0){
				$query->where('id = ' . (int) substr($search, 3));
			}else{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(month LIKE ' . $search . ' OR student_roll LIKE ' . $search . ')');
			}
		}
		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null){
       // Initialise variables.
       $app = JFactory::getApplication('administrator');
       $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
       $this->setState('filter.search', $search);
       //Takes care of states: list. limit / start / ordering / direction
       parent::populateState('id', 'asc');
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
		$class_array[] = array('value' => '', 'text' => JText::_(' -- Select Class -- '));
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
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('section')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($class_id));
		$db->setQuery($query_result);
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
        $sections[] = array('value' => '', 'text' => JText::_(' -- Select Section -- '));
        foreach ($rows as $row) {
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
        }
		$section =  JHTML::_('select.genericList', $sections, 'student_section', 'class=" required inputbox  " required="required" ', 'value', 'text', $id);
       return $section;
	}
	
	
	/**
	** Payment Method List
	**/
	function getPaymentMethodList($id){
	    $db = JFactory::getDBO();
        $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'name')))
            ->from($db->quoteName('#__sms_pay_method'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
		$db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $class_array = array();
        $onclick_manage_subject = "javascript: formget(this.form, 'index.php?option=com_sms&controller=marks&task=getsubjectlist&format=raw');";
		$class_array[] = array('value' => '', 'text' => JText::_(' -- Select Pay method -- '));
        foreach ($rows as $row) {
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
        }
		$class =  JHTML::_('select.genericList', $class_array, 'payment_method_id', ' class="required  inputbox  " required="required"   ', 'value', 'text', $id);
        return $class;
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
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name.' ('.$currency.':'.$row->fee.')'));
        }
	    $class =  JHTML::_('select.genericList', $class_array, 'pay_for_id[]', 'multiple="multiple" class="payment_type required  inputbox  " required="required"   ', 'value', 'text', $select_value);
        return $class;
	}
	
	
	/**
	** Get Save
	**/
	public function store(){
		$table = $this->getTable('payments');
		$data  = JRequest::get( 'post' );

		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}

		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		
		//Save Division
		if($data['pay_for_id']){
			$division_value = implode(",", $data['pay_for_id']);
			$table->pay_for_id = $division_value;
		}
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
    
		$id = $table->id;
		return $id;
	}
	
		
	/** 
	** Publish Unpublish script 
	**/
	public function toggle($table_name,$cid_name,$field,$value){
	    $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable($table_name);
        if (count( $cids )) {
			foreach($cids as $cid) {
				if($cid){
				    $table->$cid_name = $cid;
					$table->$field = $value;
		            if (!$table->store()) {
			            $this->setError($this->_db->getErrorMsg());
			            return false;
		            }
				}
			}
		}
		return true;
	}
	
	/** 
	** Get delete 
	**/
	public function delete(){
	    $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable('grade');
        if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$table->delete( $cid )) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	
}
