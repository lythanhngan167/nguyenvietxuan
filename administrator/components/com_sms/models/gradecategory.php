<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelGradeCategory extends JModelList
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
	** Get Grade by id
	**/
	function getGrade($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('gradecategory');
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
		$query->select('*')->from($db->quoteName('#__sms_grade_category'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Get Save
	**/
	public function store(){
		$table =& $this->getTable('gradecategory');
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
	** Get Delete
	**/
	public function delete(){
	    $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable('gradecategory');
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
