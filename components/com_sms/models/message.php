<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class SmsModelMessage extends JModelList
{
	
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
    }
	
	/**
	** Get Teacher ID
	**/
	function getTeacherID($value){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_teachers'))
            ->order('id ASC');
        $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($value), true) . '%'));
		$query->where('(name LIKE ' . $search . ')');
		$db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Get Student ID
	**/
	function getStudentID($value){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_students'))
            ->order('id ASC');
        $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($value), true) . '%'));
		$query->where('(name LIKE ' . $search . ')');
		$db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}

	/**
	** Get Parent ID
	**/
	function getParentID($value){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_parents'))
            ->order('id ASC');
        $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($value), true) . '%'));
		$query->where('(name LIKE ' . $search . ')');
		$db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Get Message
	**/
	function getMessage($id){
	    if($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('message');
			$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	
	/**
	** UPDATE STATUS
	**/
	function updateStatus($mid){
	    $db = JFactory::getDBO();
	    $db->setQuery( "UPDATE  #__sms_message ".
	        " SET status=1  ".
			" WHERE id=" . (int) $mid." ");
        $db->query();
		$db->setQuery( "UPDATE  #__sms_message_reply ".
	        " SET status=1  ".
			" WHERE message_id=" . (int) $mid." ");
        $db->query();
	}
	
	/**
	** Unread Message 
	**/
	function unreadMessageByid($id){
	    $db = JFactory::getDBO();
        $query_m = "SELECT * FROM #__sms_message m WHERE  m.status=0  and m.id='".$id."' ";
        $db->setQuery($query_m);
        $rows_m = $db->loadObjectList();
		$total_m = count($rows_m);
		$query_r = "SELECT * FROM #__sms_message_reply r WHERE  r.status=0  and r.message_id='".$id."' ";
        $db->setQuery($query_r);
        $rows_r = $db->loadObjectList();
		$total_r = count($rows_r);
		$total = round($total_m + $total_r);
        return $total;
	}
	
		
	
	/**
	** Sender Name
	**/
	function senderName($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Avator function
	**/
	function avator($id){
	    $db = JFactory::getDBO();
		//Get group id
		$query_group = $db->getQuery(true);
		$query_group
            ->select(array('group_id'))
            ->from($db->quoteName('#__user_usergroup_map'))
            ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
		$db->setQuery($query_group);
		$group = $db->loadResult();
				
		//Get group name
		$query_group_name = $db->getQuery(true);
		$query_group_name
            ->select(array('title'))
            ->from($db->quoteName('#__usergroups'))
            ->where($db->quoteName('id') . ' = '. $db->quote($group));
		$db->setQuery($query_group_name);
		$group_name = $db->loadResult();
		if($group_name=='Students'){
			$query_student = $db->getQuery(true);
			$query_student
                ->select(array('photo'))
                ->from($db->quoteName('#__sms_students'))
                ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
			$db->setQuery($query_student);
			$student_photo = $db->loadResult();
			$path = "components/com_sms/photo/students/".$student_photo."";
		}else if($group_name=='Teachers'){
			$query_teacher = $db->getQuery(true);
			$query_teacher
                ->select(array('photo'))
                ->from($db->quoteName('#__sms_teachers'))
                ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
			$db->setQuery($query_teacher);
			$teacher_photo = $db->loadResult();
			$path = "components/com_sms/photo/teachers/".$teacher_photo."";
		}else if($group_name=='Parents'){
			$query_parent = $db->getQuery(true);
			$query_parent
                ->select(array('photo'))
                ->from($db->quoteName('#__sms_parents'))
                ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
			$db->setQuery($query_parent);
			$parent_photo = $db->loadResult();
			$path = "components/com_sms/photo/parents/".$parent_photo."";
		}else{
			$path = "components/com_sms/photo/photo.png";
		}
		return $path;
	}
	
	/**
	** Teacher Name
	**/
	function getTeachername($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	
	/**
	** Reply Message List
	**/
	function getMessageList($message_id){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_message_reply'))
            ->where($db->quoteName('message_id') . ' = '. $db->quote($message_id))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Message List Query
	**/
	protected function getListQuery(){
	    $user		= JFactory::getUser();
	    $user_id = $user->id;
	
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_message'));
		$query->where('recever_id = ' . $db->quote($user_id).' OR sender_id = ' . $db->quote($user_id) );
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Message Save
	**/
	function savemessage(){
	    $table =& $this->getTable('message');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		if($data['message']){$table->message = nl2br($data['message']);}
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	}
	
	/**
	** Reply Message Save
	**/
	function messagereply(){
	    $table =& $this->getTable('messagereply');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		if($data['message']){$table->message = nl2br($data['message']);}
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		$mid = $data['message_id'];
		return $mid;
	}
	
	/**
	** Message Delete
	**/
	public function delete(){
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table = $this->getTable('message');
		$table_reply =& $this->getTable('messagereply');
        if (count( $cids )) {
			foreach($cids as $cid) {
				//Reply delete
				if($cid){
				    $db =& JFactory::getDBO();
	                $db->setQuery( "DELETE FROM #__sms_message_reply  WHERE message_id='".$cid."' ");
					$db->query();
				}
						
			    //Message delete
				if (!$table->delete( $cid )) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	
	
}
