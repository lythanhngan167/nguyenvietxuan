<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelClass extends JModelList
{
	
	/**
	** Get constructor
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
	** Grade Category List
	**/
	function getGcategoryList($id){
	    $db = JFactory::getDBO();
        $query = "SELECT id,name FROM `#__sms_grade_category`";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'name')))
            ->from($db->quoteName('#__sms_grade_category'))
            ->order('id ASC');
        $db->setQuery($query);
				
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $gcategory_list = array();
				
        foreach ($rows as $row) {
            $gcategory_list[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
        }
		$list =  JHTML::_('select.genericList', $gcategory_list, 'grade_system', ' class=" divisionBox inputbox  " ', 'value', 'text',$id);
        return $list;
	}
	
	/**
	** Get grade category name
	**/
	function getGcategoryName($id){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_grade_category'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	** Get Division List
	**/
	function getdivisionList($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'division_name')))
            ->from($db->quoteName('#__sms_division'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $divisions = array();

        if($id){
			$query_result = $db->getQuery(true);
			$query_result
                ->select($db->quoteName(array('division')))
                ->from($db->quoteName('#__sms_class'))
                ->where($db->quoteName('id') . ' = '. $db->quote($id));
			$db->setQuery($query_result);
			$select_data = $db->loadResult();
			$select_value = explode(",", $select_data);
		}else{
			$select_value = "";
		}
				
        foreach ($rows as $row) {
            $divisions[] = array('value' => $row->id, 'text' => JText::_(' '.$row->division_name));
        }
		$list =  JHTML::_('select.genericList', $divisions, 'division[]', 'multiple="multiple" class=" divisionBox inputbox  " ', 'value', 'text',$select_value);
       return $list;
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
        if($id){
			$query_result = $db->getQuery(true);
			$query_result
                ->select($db->quoteName(array('section')))
                ->from($db->quoteName('#__sms_class'))
                ->where($db->quoteName('id') . ' = '. $db->quote($id));
			$db->setQuery($query_result);
			$select_data = $db->loadResult();
			$select_value = explode(",", $select_data);
		}else{
			$select_value = "";
		}

        foreach ($rows as $row) {
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
        }
		$list =  JHTML::_('select.genericList', $sections, 'section[]', 'multiple="multiple" class=" sectionBox inputbox  " ', 'value', 'text', $select_value);
       return $list;
	}
	
	/**
	** Get Subject List
	**/
	function getsubjectList($id){
	    $db = JFactory::getDBO();
        $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'subject_name')))
            ->from($db->quoteName('#__sms_subjects'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('order_number ASC');	
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $subjects = array();
        if($id){
			$query_result = $db->getQuery(true);
			$query_result
                ->select($db->quoteName(array('subjects')))
                ->from($db->quoteName('#__sms_class'))
                ->where($db->quoteName('id') . ' = '. $db->quote($id));
			$db->setQuery($query_result);
			$select_data = $db->loadResult();
			$select_value = explode(",", $select_data);
		}else{
			$select_value = "";
		}

        foreach ($rows as $row) {
            $subjects[] = array('value' => $row->id, 'text' => JText::_(' '.$row->subject_name));
        }
		$list =  JHTML::_('select.genericList', $subjects, 'subjects[]', 'multiple="multiple" class=" subjectBox inputbox  " ', 'value', 'text', $select_value);
       return $list;
	}
	
	/**
	** Get Class by id
	**/
	function getClass($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('class');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
    /**
    ** Get List
    **/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_class'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	
	/**
	** Get Save
	**/
	public function store(){
		$table =& $this->getTable('class');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		
		//Save Division
		if($data['division']){
		$division_value = implode(",", $data['division']);
		$table->division = $division_value;
		}
		
		//Save Section
		if($data['section']){
		$section_value = implode(",", $data['section']);
		$table->section = $section_value;
		}
		
		//Save Subject
		if($data['subjects']){
		$subject_value = implode(",", $data['subjects']);
		$table->subjects = $subject_value;
		}
		
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	}
	
	/**
	** Get published unpublished toggle
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
	** Get Delete
	**/
	public function delete(){
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable('class');
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
