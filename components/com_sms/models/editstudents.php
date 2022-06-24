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

class SmsModelEditstudents extends JModelList
{
	
    function __construct()
    {
        parent::__construct();
        $mainframe = JFactory::getApplication();
    }
	
	/**
	** Get Student ID
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
	** Get Students
	**/
	function getStudent($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('students');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	
    
	/**
	** Save Student
	**/
	public function store(){
	    $data_student = JRequest::get( 'post' );
		$chabima      = str_replace(' ', '', $data_student['chabima']);
		$alias        = str_replace(' ', '-', strtolower($data_student['name']));
		$churanita    = $data_student['churanita'];
		$email        = $data_student['email'];
		$uid          = $data_student['user_id'];
		$table        = $this->getTable('students');
	
	                              
		//USER UPDATE ######################################################################
		if(!empty($data_student['user_id'])){
		    $user = new JUser;
			// Bind the data.
		    if (!$user->bind($data_student)){
				$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			    return false;
		    }
		                             
		    // Store the data.
			$user->id       = $data_student['user_id'];
			$user->username = $chabima;
			$user->password = md5($churanita);
			$user->email    = $data_student['email'];
            if (!$user->save()){	
                $user->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
                return false;
            }
		}
	    
		//upload section data
		$file = JRequest::getVar('photo', null, 'files', 'array');
		jimport('joomla.filesystem.file');
		
		$filename = JFile::makeSafe($data_student['id'].'_'.$file['name']);
		$new_file = $_FILES['photo']['name'];
		
		// Bind the data.
		if (!$table->bind($data_student)){
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		$table->chabima   = $chabima;
		$table->churanita = $churanita;
		if($data_student['id']){$table->id = $data_student['id'];}
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
		$db          = JFactory::getDbo();
		$sid         = SmsHelper::getFieldSectionID('student');
		$fields      = SmsHelper::getFieldList($sid);
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
			$old_file = $data_student['old_photo'];
			if($old_file!=""){
			    $path ="components/com_sms/photo/students/";
				JFile::delete($path.$old_file);
			}
						
            $src = $file['tmp_name'];
            $dest = "components/com_sms/photo/students/".$filename;
            if( strtolower(JFile::getExt($filename) ) == 'jpg' || 'png' || 'gif' ||'jpeg') {
                if( JFile::upload($src, $dest) ) {
            
                }else{
                    $upload_error ="upload_error";
					return $upload_error;
                }
            }else{
                //Redirect and notify user file is not right extension
	            $upload_error_extension = "upload_error_extension";
				return $upload_error_extension;
            }
		}
	    return $id;			
	}
	
	
}
