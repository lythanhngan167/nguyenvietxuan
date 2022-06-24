<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelPaytype extends JModelList
{
	
	
function __construct()
  {
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
	** Get Pay Type
	**/
	function getPaytype($id)
	{
	 if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('paytype');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	/**
	** Pay Type List
	**/
	 protected function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_pay_type'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	
	
	/**
	** Pay Type Save
	**/
	public function store()
	{
		$table =& $this->getTable('paytype');
		$data = JRequest::get( 'post' );

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}

		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
    
		$id = $table->id;
		return $id;
	}
	
	
	
	
	
	 /** 
	*   delete script 
	*/
	public function delete(){
	      
				 $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
				
				 $table =& $this->getTable('paytype');
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
