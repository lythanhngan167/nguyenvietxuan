<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelParents extends JModelList
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
	** Get Parent by id
	**/
	function getParents($id){
	    if($id){
			$this->_id = $id;
		}
		if (empty($this->_data)) {
			$this->_data = $this->getTable ('parents');
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
		$query->select('*')->from($db->quoteName('#__sms_parents'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)){
			if (is_numeric($search)){
				//$query->where('mobile_no = ' . (int) $search.' OR roll LIKE ' . $search . ');
				$query->where('(mobile_no = ' . (int) $search . ' OR roll LIKE ' . $search . ')');
			}else{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(name LIKE ' . $search . ' )');
			}
		}
		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Get Popular
	**/
	protected function populateState($ordering = null, $direction = null){
        $app = JFactory::getApplication('administrator');
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState('id', 'asc');
    }
	
	
	/**
	** Get Save
	**/
	public function store(){
		$db =& JFactory::getDBO();
		$app             = JFactory::getApplication();
	    $params          = JComponentHelper::getParams('com_sms');
	    $parents_account = $params->get('parents_account');
		$data_student    = JRequest::get( 'post' );
		$chabima         = str_replace(' ', '', $data_student['chabima']);
		$alias           = str_replace(' ', '-', strtolower($data_student['name']));
		$churanita       = $data_student['churanita'];
	    if(!empty($parents_account)){                 
			//USER UPDATE ######################################################################
			if(!empty($data_student['user_id'])){
				$user = new JUser;
				// Bind the data.
		        if (!$user->bind($data_student)){
			        $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			        return false;
		        }
		                             
		        // Store the data.
				$uid            = $data_student['user_id'];
				$user->id       = $data_student['user_id'];
				$user->username = $chabima;
				$user->password = md5($churanita);
				$user->email    = $data_student['email'];
		        if (!$user->save()){
                    $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
                    return false;
                }
			}
																
			//USER CREATE ######################################################################
	        if(empty($data_student['user_id'])){
		        $user = new JUser;
		        $data = JRequest::get( 'post' );
		        $data['block'] = 0;
		                             
		        // Bind the data.
		        if (!$user->bind($data)){
			        $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			        return false;
		        }
		                             
		        // Store the data.
				$user->username = $chabima;
				$user->password = md5($churanita);
				$user->email = $data_student['email'];
		        if (!$user->save()){
                    $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
                    return false;
                }
		
		        //Set user group
		        $uid = $user->id;
		        $q_user_g= "SELECT id FROM #__usergroups WHERE title= 'Parents' ";
                $db->setQuery( $q_user_g);
	            $usergroup_id= $db->loadResult(); 
		        $queryg = "INSERT INTO `#__user_usergroup_map` (`user_id`, `group_id`) VALUES ( '$uid','$usergroup_id');";
                $db->setQuery( $queryg );
                $setquey = $db->query();
		    }//End user create section
	    }else{
			$uid = $data_student['user_id'];
		}
	
	    //GET NEXT ID
	    $config = JFactory::getConfig();
		$database_name = $config->get( 'db' );
		$dbprefix = $config->get( 'dbprefix' );
	    $query = "SELECT AUTO_INCREMENT AS id FROM information_schema.tables WHERE table_schema = '".$database_name."' AND table_name = '".$dbprefix."sms_teachers'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $nextid = $rows[0]->id;
	
		$table =& $this->getTable('parents');
		
		//upload section data
		$file = JRequest::getVar('photo', null, 'files', 'array');
		jimport('joomla.filesystem.file');
		
		if(empty($data_student['parent_id'])){
		    $nextid_final = $nextid;
		}else{
		    $nextid_final = $data_student['parent_id'];
		}
		
		$filename = JFile::makeSafe($nextid_final.'_'.$file['name']);
		$new_file = $_FILES['photo']['name'];
		
		// Bind the data.
		if (!$table->bind($data_student)){
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if($data_student['chabima']){$table->chabima = $chabima;}
		if($data_student['churanita']){$table->churanita = $churanita;}
		if($data_student['parent_id']){$table->id = $data_student['parent_id'];}
        
        //Save student_id
		if($data_student['student_id']){
            $student_ids = $data_student['student_id'];
	        $student_ids = array_filter($student_ids);
			$student_id_value = implode(",", $student_ids);
			$table->student_id = $student_id_value;
		}
		
		if($new_file){$table->photo = $filename;}
		if($uid){$table->user_id = $uid;}
		//store alias
		$table->alias = $alias;
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}

		$id = $table->id;
		
		          
		//Setting Custom Field Data
		$sid = SmsHelper::getFieldSectionID('parent');
		$fields = SmsHelper::getFieldList($sid);
		$total_field = count($fields);

		$f=0;
		foreach($fields as $field){
			$f++;
			$fid = $field->id;
			$sid = $sid;
			$field_input_name ='field_'.$fid;
			$field_data = $data_student[$field_input_name];
			$student_id = $id;
			$type = $field->type;

			$query_check_id = "SELECT id FROM `#__sms_fields_data` WHERE fid = '".$fid."' AND sid = '".$sid."' AND panel_id = '".$student_id."'";
			$db->setQuery($query_check_id);
			$old_id = $db->loadResult();
			SmsHelper::saveFields($fid, $type, $sid, $field_data, $student_id,$old_id);
		}
							      
		//PHOTO UPLOAD ######################################################################
		if($new_file){
			$old_file =$data_student['old_photo'];
			if($old_file!=""){
				$path ="../components/com_sms/photo/parents/";
				JFile::delete($path.$old_file);
		    }		
            $src = $file['tmp_name'];
            $dest = "../components/com_sms/photo/parents/".$filename;
            if ( strtolower(JFile::getExt($filename) ) == 'jpg' || 'png' || 'gif' ||'jpeg') {
                if ( JFile::upload($src, $dest) ) {
        
                }else{
                    $upload_error ="upload_error";
					return $upload_error;
                }
            }else{
                //Redirect and notify user file is not right extension
                $upload_error_extension ="upload_error_extension";
				return $upload_error_extension;
            }
		}
        return $id;
	}
	
	
	/**
	** Get Delete
	**/
	public function delete(){
		$db = JFactory::getDBO();
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable('parents');
		$app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_sms');
        $parents_account = $params->get('parents_account');
		
		if (count( $cids )) {
			foreach($cids as $cid) {
				$sid = $cid;
				//delete Student user and group
				$query_user_id = "SELECT user_id FROM `#__sms_parents` WHERE id = '".$sid."'";
				$db->setQuery($query_user_id);
				$user_id = $db->loadResult();
				if(!empty($parents_account)){ 
					if(!empty($user_id)){
						//Delete user group map
					    $db->setQuery( "DELETE FROM #__user_usergroup_map WHERE user_id=" . (int) $user_id." ");
						$delete_group_map = $db->query();
					    if($delete_group_map){
							$db->setQuery( "DELETE FROM #__users WHERE id=" . (int) $user_id." ");
							$db->query();
						}
					}
				}
						
				//Delete Student photo
				$query_user_photo = "SELECT photo FROM `#__sms_parents` WHERE id = '".$sid."'";
				$db->setQuery($query_user_photo);
				$user_photo = $db->loadResult();
				if($user_photo!=""){
					$path ="../components/com_sms/photo/parents/";
					JFile::delete($path.$user_photo);
				}
						
				if (!$table->delete( $cid )) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

}
