<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelGrade extends JModelList
{
	
	/**
	** Constructor
	**/
    function __construct(){
        if (empty($config['filter_fields'])){
            $config['filter_fields'] = array(
                'id',
				'name',
				'grade_point',
				'mark_from',
				'mark_upto'
            );
        }
        parent::__construct($config);
    }
	
	/**
	** Grade Category List
	**/
	function getGcategoryList($id){
	    $db = JFactory::getDBO();
        $query = "SELECT id,name FROM `#__sms_grade_category`";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $grade_category = array();
				
        foreach ($rows as $row) {
            $grade_category[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
        }
		$list =  JHTML::_('select.genericList', $grade_category, 'category', ' class=" divisionBox inputbox  " ', 'value', 'text',$id);
        return $list;
	}
	
	/**
	** Get category name by id
	**/
	function getGcategoryName($id){
	    $db = JFactory::getDBO();
	    $query_result = "SELECT name FROM `#__sms_grade_category` WHERE id = '".$id."'";
	    $db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Grade
	**/
	function getGrade($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('grade');
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
		$query->select('*')->from($db->quoteName('#__sms_exams_grade'));
		
		// Filter by Category.
		$status = $this->getState('list.status');
		if (is_numeric($status)){
			$query->where('category = ' . $db->quote($status));
		}
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)){
			if (is_numeric($search)){
				$query->where('id = ' . (int) $search);
			}else{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(name LIKE ' . $search . ')');
			}
		}
		
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Get Save
	**/
	public function store(){
		$table =& $this->getTable('grade');
		$data = JRequest::get( 'post' );

		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}

		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
    
		$id = $table->id;
		return $id;
	}
	
	/**
	** Get published/unpublished toggle
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
