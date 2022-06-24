<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelExams extends JModelList
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
	** Get Exam by id
	**/
	function getExam($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('exams');
			$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	/**
	** Get popular
	**/
	protected function populateState($ordering = null, $direction = null){
        $app = JFactory::getApplication('administrator');
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('id', 'asc');
    }
	 
	/**
	** Get List
	**/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_exams'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 	
		
		// Filter by Status.
		$status = $this->getState('list.status');
		if (is_numeric($status))
		{
			$query->where('published = ' . $db->quote($status));
		}
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)){
			if (is_numeric($search)){
				$query->where('id = ' . (int) $search);
			}else{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(name LIKE ' . $search . ' OR examdate LIKE ' . $search . ')');
			}
		}
		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	
	
	/**
	** Get Save
	**/
	public function store(){
		$table =& $this->getTable('exams');
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
	** Get delete script 
	**/
	public function delete(){
	    $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable('exams');
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
