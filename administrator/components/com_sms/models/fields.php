<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelFields extends JModelList
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
	** Type List
	**/
	function getTypeList($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'type')))
            ->from($db->quoteName('#__sms_fields_type'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $types = array();
				$types[] = array('value' => '', 'text' => JText::_(' -- Select Type -- '));
        foreach ($rows as $row) {
            $types[] = array('value' => $row->id, 'text' => JText::_(' '.$row->type));
        }
		$type =  JHTML::_('select.genericList', $types, 'type', ' required="required" class=" required  inputbox  " ', 'value', 'text',$id);
        return $type;
	}
	
	/**
	** Get Type name
	**/
	function getTypeName($id){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('type')))
            ->from($db->quoteName('#__sms_fields_type'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Section List
	**/
	function getSectionList($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'name')))
            ->from($db->quoteName('#__sms_fields_section'))
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
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
        }
		$section =  JHTML::_('select.genericList', $sections, 'section', ' required="required" class=" required  inputbox  " ', 'value', 'text',$id);
        return $section;
	}
	
	/**
	** Get section name
	**/
	function getSectionName($id){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_fields_section'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	** Get Field
	**/
	function getField($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('fields');
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
		$query->select('*')->from($db->quoteName('#__sms_fields'));
		$orderCol	= $this->state->get('list.ordering', 'field_order');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 	
		
		// Filter by section
		$sectionId = $this->getState('list.section_id');
		if (is_numeric($sectionId)){
			$query->where('section = ' . $db->quote($sectionId));
		}
		
		// Filter by type
		$type_id = $this->getState('list.type_id');
		if (is_numeric($type_id)){
			$query->where('type = ' . $db->quote($type_id));
		}
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)){
			if (is_numeric($search)){
				$query->where('id = ' . (int) $search);
			}else{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(field_name LIKE ' . $search . ' OR id LIKE ' . $search . ')');
			}
		}
			
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null) {
	    // Initialise variables.
	    $app = JFactory::getApplication('administrator');
	    $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
	    $this->setState('filter.search', $search);
	    //Takes care of states: list. limit / start / ordering / direction
	    parent::populateState('field_order', 'asc');
    }
	
	
	/**
	** Get Save
	**/
	public function store(){
		$table =& $this->getTable('fields');
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
	** Publish & Unpublish toggle
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
		$table =& $this->getTable('fields');
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
