<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsModelStudents extends JModelList
{
	/**
	** constructor
	**/
    public function __construct($config = array()){
        if (empty($config['filter_fields'])){
            $config['filter_fields'] = array('id','name','class','roll','published','division','year','section');
        }
        parent::__construct($config);
    }
	
	
	/**
	** Get Total Student
	**/
	function totalNewstudent(){
	    $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__sms_students` WHERE roll = '' ";
        $db->setQuery($query);
        $rows = count($db->loadObjectList());
        return $rows;
	}
	
	/**
	** Get Grade System
	**/
	function getGradeSystem($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('grade_system')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Subject Name by ID
	**/
	function getSubjectname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('subject_name')))
            ->from($db->quoteName('#__sms_subjects'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Subject Short Code by ID
	**/
	function getSubjectSNCode($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('subject_shot_name')))
            ->from($db->quoteName('#__sms_subjects'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Parent ID
	**/
	function getParentID($id){
	    $db = JFactory::getDBO(); 
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_parents'))
            ->where($db->quoteName('student_id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Mark
	**/
	function getMark($field, $exam_id, $class_id, $subject_id, $roll){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array($field)))
            ->from($db->quoteName('#__sms_exams_mark'))
            ->where($db->quoteName('exam_id') . ' = '. $db->quote($exam_id))
			->where($db->quoteName('class_id') . ' = '. $db->quote($class_id))
			->where($db->quoteName('subject_id') . ' = '. $db->quote($subject_id))
			->where($db->quoteName('roll') . ' = '. $db->quote($roll));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Exam List
	**/
	function getExamList($year){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_exams'))
            ->where(' YEAR(examdate) = '. $db->quote($year) .' AND '.$db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}

	/**
	** Exam ID
	**/
	function getExamID($year){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('id')
            ->from($db->quoteName('#__sms_exams'))
            ->where(' YEAR(examdate) = '. $db->quote($year) .' AND '.$db->quoteName('published') . ' = '. $db->quote('1'));
        $db->setQuery($query);
        $rows = $db->loadResult();
        return $rows;
	}
	
	/**
	** Grade List
	**/
	function getGradeList($cid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_exams_grade'))
            ->where($db->quoteName('category') . ' = '. $db->quote($cid))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
	}
	
	/**
	** Get Students by ID
	**/
	function getStudents($id){
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
    ** Get Student List
    **/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_students'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		
		// Filter by Gender.
		$gender = $this->getState('filter.gender');
		if ($gender){
			$query->where('gender = ' . $db->quote($gender));
		}
		
		// Filter by Class.
		$classId = $this->getState('filter.class_id');
		if (is_numeric($classId)){
			$query->where('class = ' . $db->quote($classId));
		}
		
		// Filter by Section.
		$sectionId = $this->getState('filter.section_id');
		if (is_numeric($sectionId)){
			$query->where('section = ' . $db->quote($sectionId));
		}
		
		// Filter by Division.
		$divisionId = $this->getState('filter.division_id');
		if (is_numeric($divisionId)){
			$query->where('division = ' . $db->quote($divisionId));
		}
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)){
			if (is_numeric($search)){
				$query->where('roll = ' . (int) $search);
			}else{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(name LIKE ' . $search . ' OR roll LIKE ' . $search . ')');
			}
		}
		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}

	/**
	** Populate State
	**/
	protected function populateState($ordering = null, $direction = null){
		$app = JFactory::getApplication('administrator');
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		parent::populateState('id', 'asc');
	}

    /** 
	** Publish Unpublish script 
	**/
	public function toggle($table_name,$cid_name,$field,$value){
	    $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable($table_name);
        if (count( $cids )) {
			foreach($cids as $cid){
				if($cid){
				    $table->$cid_name = $cid;
					$table->$field = $value;
		            if(!$table->store()){
			            $this->setError($this->_db->getErrorMsg());
			            return false;
		            }
				}
			}
		}
		return true;
	}
	
	/**
	** Get Save Student
	**/
	public function store(){
		$db    = JFactory::getDbo();
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_sms');
		$students_account = $params->get('students_account');
		$data_student = JRequest::get( 'post' );
		$table = $this->getTable('students');

		$chabima = str_replace(' ', '', $data_student['chabima']);
		$alias = str_replace(' ', '-', strtolower($data_student['name']));
		$churanita = $data_student['churanita'];
		$email = $data_student['email'];

		if(!empty($students_account)){                  

			//USER UPDATE ######################################################################
        	if(!empty($data_student['user_id'])){
				if(!empty($churanita)){
					$user = new JUser;
					if (!$user->bind($data_student)){
						$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
						return false;
					}
					$uid = $data_student['user_id'];
					$user->id = $data_student['user_id'];
					$user->username = $chabima;
					if($churanita){
					    $user->password = md5($churanita);
					}
					$user->email = $data_student['email'];
					if (!$user->save()){
						$user->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
						return false;
					}
				}
        	}

            //USER CREATE ######################################################################
		    if(empty($data_student['user_id'])){
				$user = new JUser;
				$data = JRequest::get( 'post' );
				$data['block'] = 0;

				if (!$user->bind($data)){
					$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
					return false;
				}

				// Store the data.
				$user->username = $chabima;
				$user->password = md5($churanita);
				$user->email = $email;
				if (!$user->save()){
					$this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
					return false;
				}

				//Set user group
				$uid = $user->id;
				$db =& JFactory::getDBO();
				$q_user_g= "SELECT id FROM #__usergroups WHERE title= 'Students' ";
				$db->setQuery( $q_user_g);
				$usergroup_id= $db->loadResult(); 

				//insert user group map
				$query_user_group = $db->getQuery(true);
				$columns_user_group = array('user_id', 'group_id');
				$values_user_group = array($uid, $usergroup_id);
				$query_user_group
				->insert($db->quoteName('#__user_usergroup_map'))
				->columns($db->quoteName($columns_user_group))
				->values(implode(',', $values_user_group));
				$db->setQuery($query_user_group);
				$db->execute();
			}//End user create section

		}else{
			$uid = $data_student['user_id'];
		}
	
	    //GET NEXT ID
	    $config = JFactory::getConfig();
		$database_name = $config->get( 'db' );
		$dbprefix = $config->get( 'dbprefix' );
	    $query = "SELECT AUTO_INCREMENT AS id FROM information_schema.tables WHERE table_schema = '".$database_name."' AND table_name = '".$dbprefix."sms_students'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $nextid = $rows[0]->id;
	
		//upload section data
		$file = JRequest::getVar('photo', null, 'files', 'array');
		jimport('joomla.filesystem.file');
		if(empty($data_student['student_id'])){
		    $nextid_final = $nextid;
		}else{
		    $nextid_final = $data_student['student_id'];
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
		if($data_student['student_id']){$table->id = $data_student['student_id'];}
		//Save year
		if($data_student['year']){
		    $table->year = $data_student['year'];
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
		$sid = SmsHelper::getFieldSectionID('student');
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
		        $path ="../components/com_sms/photo/students/";
		        JFile::delete($path.$old_file);
		    }
			$src = $file['tmp_name'];
            $dest = "../components/com_sms/photo/students/".$filename;
		    if ( strtolower(JFile::getExt($filename) ) == 'jpg' || 'png' || 'gif' ||'jpeg') {
                if ( JFile::upload($src, $dest) ) {
                }else{
                    $upload_error ="upload_error";
					return $upload_error;
                }
            } else {
	            $upload_error_extension ="upload_error_extension";
				return $upload_error_extension;
            }
		}
	
        // Year Management
        $exit_id = $this->getExitData('id', $data_student['year'], $id);
        if(empty($exit_id)){
            // Get insert
            $object_new = new stdClass();
            $object_new->sid  = $id;
            $object_new->class  = $data_student['class'];
            $object_new->roll = $data_student['roll'];
            $object_new->division = $data_student['division'];
            $object_new->section = $data_student['section'];
            $object_new->year = $data_student['year'];
            $result_insert = JFactory::getDbo()->insertObject('#__sms_student_year', $object_new);
        }else{
            // Get update
            $object = new stdClass();
            $object->id = $exit_id;
            $object->sid  = $id;
            $object->class  = $data_student['class'];
            $object->roll = $data_student['roll'];
            $object->division = $data_student['division'];
            $object->section = $data_student['section'];
            $object->year = $data_student['year'];
            $result_update = JFactory::getDbo()->updateObject('#__sms_student_year', $object, 'id');
        }
        
        return $id;	
	}
	
    /**
    ** Get Exit data for student's year
    **/
    function getExitData($select_field, $year, $sid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array($select_field)))
            ->from($db->quoteName('#__sms_student_year'))
			->where($db->quoteName('sid') . ' = '. $db->quote($sid))
            ->where($db->quoteName('year') . ' = '. $db->quote($year));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Delete Student
	**/
	public function delete(){
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table = $this->getTable('students');
		$app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_sms');
        $students_account = $params->get('students_account');	 
        if (count( $cids )) {
			foreach($cids as $cid) {
				$sid = $cid;
				$db  = JFactory::getDBO();

				// Delete custom field data
				$db->setQuery( "DELETE FROM #__sms_fields_data WHERE panel_id=" . (int) $sid." ");
				$db->query();


				//delete Student user and group
			    $query_user_id = "SELECT user_id FROM `#__sms_students` WHERE id = '".$sid."'";
				$db->setQuery($query_user_id);
				$user_id = $db->loadResult();
				if(!empty($students_account)){         
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
				$query_user_photo = "SELECT photo FROM `#__sms_students` WHERE id = '".$sid."'";
				$db->setQuery($query_user_photo);
				$user_photo = $db->loadResult();
				if($user_photo!=""){
					$path ="../components/com_sms/photo/students/";
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

    /**
	** Get Comment
	**/
	function getComment( $cid, $roll, $eid,  $tid){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('comments')))
            ->from($db->quoteName('#__sms_result_comments'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll))
			->where($db->quoteName('eid') . ' = '. $db->quote($eid))
			->where($db->quoteName('class') . ' = '. $db->quote($cid));
		$db->setQuery($query);
		$comment =  $db->loadResult();
        return $comment;
	}

	/**
	** Save Comment
	**/
	function savecomment( $cid, $roll, $eid,  $tid, $comment){
	    $db = JFactory::getDBO();
	    $query_check_comment = $db->getQuery(true);
		$query_check_comment
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_result_comments'))
            ->where($db->quoteName('roll') . ' = '. $db->quote($roll))
			->where($db->quoteName('eid') . ' = '. $db->quote($eid))
			->where($db->quoteName('class') . ' = '. $db->quote($cid));
		$db->setQuery($query_check_comment);
		$comment_id =  $db->loadResult();
	
	    $table = $this->getTable('comment');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if(!empty($comment_id)){$table->id = $comment_id;}
		$table->roll = $roll;
		$table->class = $cid;
		$table->eid = $eid;
		$table->comments = $comment;
		$table->tid = $tid;
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	}

	
}
