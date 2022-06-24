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

class SmsModelTeachers extends JModelList
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
	
	function senderName($id){
	      $db = JFactory::getDBO();
			  $query_result = "SELECT name FROM `#__users` WHERE id = '".$id."'";
				$db->setQuery($query_result);
				$data = $db->loadResult();
				return $data;
	}
	
	function getLatestMessage($sid)
	{
	      $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__sms_message` WHERE recever_id = '".$sid."' OR sender_id = '".$sid."'";
        $query.=" ORDER BY id desc LIMIT 0 ,5 ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
				return  $rows;
	}
	
	function checkGroup($id){
	                                $db =JFactory::getDBO();
																	$check_group= "SELECT group_id FROM #__user_usergroup_map WHERE user_id= '$id' ";
                                  $db->setQuery( $check_group);
	                                $user_group_id= $db->loadResult(); 
																	
																	$check_group_title= "SELECT title FROM #__usergroups WHERE id= '$user_group_id' ";
                                  $db->setQuery( $check_group_title);
	                                $title= $db->loadResult(); 
																	return  $title;
	}
	
	
	
	function getTeacherID($id){
	      $db = JFactory::getDBO();
			  $query_result = "SELECT id FROM `#__sms_teachers` WHERE user_id = '".$id."'";
				$db->setQuery($query_result);
				$data = $db->loadResult();
				return $data;
	}
	
	function getTeacher($id)
	{
	 if ($id) {
			 $this->_id = $id;
		  }
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('teachers');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	function getClassname($id){
	      $db = JFactory::getDBO();
			  $query_result = "SELECT class_name FROM `#__sms_class` WHERE id = '".$id."'";
				$db->setQuery($query_result);
				$data = $db->loadResult();
				return $data;
	}
	
	function getSubjectname($id){
	      $db = JFactory::getDBO();
			  $query_result = "SELECT subject_name FROM `#__sms_subjects` WHERE id = '".$id."'";
				$db->setQuery($query_result);
				$data = $db->loadResult();
				return $data;
	}
	
	function getSectionname($id){
	      $db = JFactory::getDBO();
			  $query_result = "SELECT section_name FROM `#__sms_sections` WHERE id = '".$id."'";
				$db->setQuery($query_result);
				$data = $db->loadResult();
				return $data;
	}
	/**
	** Subject List
	**/
	function getsubjectList($id){
	     $db = JFactory::getDBO();
        $query = "SELECT id,subject_name FROM `#__sms_subjects` WHERE published = 1";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $subjects = array();
        
				
				
				$subjects[] = array('value' => '', 'text' => JText::_(' -- Select Division -- '));
        foreach ($rows as $row) {
            $subjects[] = array('value' => $row->id, 'text' => JText::_(' '.$row->subject_name));
        }
			 $subject_list =  JHTML::_('select.genericList', $subjects, '', 'class="required  inputbox  " disabled="disabled" required="required" ', 'value', 'text',$id);
       return $subject_list;
	}
	
	/**
	** Class List
	**/
	function getclassList($id){
	      $db = JFactory::getDBO();
        $query = "SELECT id,class_name FROM `#__sms_class` WHERE published = 1";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $class_array = array();
        
				$class_array[] = array('value' => '', 'text' => JText::_(' -- Select Class -- '));
        foreach ($rows as $row) {
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
        }
			 $class =  JHTML::_('select.genericList', $class_array, '', ' class="required  inputbox  " disabled="disabled"  required="required"  ', 'value', 'text', $id);
       return $class;
	}
	
	/**
	** Section List
	**/
	function getsectionList($id){
	     $db = JFactory::getDBO();
        $query = "SELECT id,section_name FROM `#__sms_sections` WHERE published = 1";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $sections = array();
        
				
				$sections[] = array('value' => '', 'text' => JText::_(' -- Select Section -- '));
        foreach ($rows as $row) {
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
        }
			 $section =  JHTML::_('select.genericList', $sections, '', 'class=" required inputbox  " disabled="disabled"  required="required" ', 'value', 'text', $id);
       return $section;
	}
	
	
	/**------------------------------------------------------------------------------------------------------------------
	** ------------------------------- TEACHER SINGLE DATA ------------------------------------------------------------
	**-----------------------------------------------------------------------------------------------------------------*/
	function getStudents($id)
	{
	 if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('teachers');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
   /**------------------------------------------------------------------------------------------------------------------
	 ** ------------------------------- TEACHER LIST --------------------------------------------------------------------
	 **-----------------------------------------------------------------------------------------------------------------*/
	 protected function getListQuery()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_teachers'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	
	
	/**------------------------------------------------------------------------------------------------------------------
	** ------------------------------- SAVE TEACHER -------------------------------------------------------------------
	**-----------------------------------------------------------------------------------------------------------------*/
	public function store()
	{
	$data_student = JRequest::get( 'post' );
	
	$chabima = str_replace(' ', '', $data_student['chabima']);
	$churanita = $data_student['churanita'];
	
	                              
																//USER UPDATE ######################################################################
																if(!empty($data_student['user_id'])){
																  $user = new JUser;
																	// Bind the data.
		                              if (!$user->bind($data_student))
		                              {
			                             $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			                             return false;
		                              }
		                             
		                              // Store the data.
																	$user->id = $data_student['user_id'];
																	$user->chabima = $chabima;
																	$user->churanita = md5($churanita);
																	$user->email = $data_student['email'];
		                              if (!$user->save())
		                              {
			                             $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
			                             return false;
		                              }
																}
																
																//USER CREATE ######################################################################
	                              if(empty($data_student['id'])){
	                                $params = JComponentHelper::getParams('com_users');
		                              $user = new JUser;
													        // Prepare the data for the user object.
		                              $data = JRequest::get( 'post' );
		                              $useractivation = $params->get('useractivation');
		                              $sendchuranita = $params->get('sendchuranita', 0);
		                              // Check if the user needs to activate their account.
		                              if (($useractivation == 0) || ($useractivation == 2))
		                              {
			                             $data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
			                             $data['block'] = 0;
		                              }
		                              // Bind the data.
		                              if (!$user->bind($data))
		                              {
			                             $this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			                             return false;
		                              }
		                             
		                              // Store the data.
																	$user->chabima = $chabima;
		                              if (!$user->save())
		                              {
			                             $this->setError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
			                             return false;
		                              }
		
		                              //Set user group
		                              $uid = $user->id;
		                              $db =& JFactory::getDBO();
                                  $q_user_g= "SELECT id FROM #__usergroups WHERE title= 'Teachers' ";
                                  $db->setQuery( $q_user_g);
	                                $usergroup_id= $db->loadResult(); 
		
	                                $queryg = "INSERT INTO `#__user_usergroup_map` (`user_id`, `group_id`) VALUES ( '$uid','$usergroup_id');";
                                  $db->setQuery( $queryg );
                                  $setquey = $db->query();
		                            }//End user create section
	
	
	
	    //GET NEXT ID
	    $config = JFactory::getConfig();
			$database_name = $config->get( 'db' );
			$dbprefix = $config->get( 'dbprefix' );
			
			$db    = JFactory::getDbo();
	    $query = "SELECT AUTO_INCREMENT AS id FROM information_schema.tables WHERE table_schema = '".$database_name."' AND table_name = '".$dbprefix."sms_teachers'";
      $db->setQuery($query);
      $rows = $db->loadObjectList();
      $nextid = $rows[0]->id;
	
		$table =& $this->getTable('teachers');
		
		//upload section data
		$file = JRequest::getVar('photo', null, 'files', 'array');
		jimport('joomla.filesystem.file');
		
		 if(empty($data_student['id'])){
		 $nextid_final = $nextid;
		 }else{
		 $nextid_final = $data_student['id'];
		 }
		
		$filename = JFile::makeSafe($nextid_final.'_'.$file['name']);
		$new_file = $_FILES['photo']['name'];
		
		$description = JRequest::getVar('description_others', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		// Bind the data.
		if (!$table->bind($data_student))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		$table->chabima = $chabima;
		$table->churanita = $churanita;
		if($data_student['id']){$table->id = $data_student['id'];}
		
		if($new_file){$table->photo = $filename;}
		if($uid){$table->user_id = $uid;}
		$table->description_others = $description;
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		
		                
		              //PHOTO UPLOAD ######################################################################
									
									if($new_file){
										$old_file =$data_student['old_photo'];
										if($old_file!=""){
									          $path ="components/com_sms/photo/teachers/";
											       JFile::delete($path.$old_file);
									  }
										
		                $src = $file['tmp_name'];
                    $dest = "components/com_sms/photo/teachers/".$filename;
		                if ( strtolower(JFile::getExt($filename) ) == 'jpg' || 'png' || 'gif' ||'jpeg') {
                       if ( JFile::upload($src, $dest) ) {
                            
                           } else {
                            $upload_error ="upload_error";
														return $upload_error;
                           }
                    } else {
                    //Redirect and notify user file is not right extension
	                  $upload_error_extension ="upload_error_extension";
										return $upload_error_extension;
                    }
									}
	
				return $id;					  
		
		
	}
	
	
	function buildField($label, $type, $name, $value, $note,  $placeholder ="", $optinal ="", $enable =""){
	                         
													 if($optinal ==""){
													    $optinal_s ="required"; 
															$star = '<span class="star"> *</span>'; 
															$r = 'required="required"';
															}else{
															$optinal_s ="";
															$star = '';
															$r = '';
													 }
													 
                          
													 $input = '<p>';
                           
													 if($type=="input"){
													 $input .= '<label id="jform_'.$name.'-lbl" class="labelp '.$optinal_s.'"  for="jform_'.$name.'">'. JText::_($label).': '.$star .'</label>';
					                 $input .= '<input type="text" name="'.$name.'" value="'.$value.'" id="jform_'.$name.'" class="'.$optinal_s.' cinp" '.$r.' />';
                           }
													 if($type=="select"){
													  $input .= '<label id="'.$name.'-lbl" class="labelp '.$optinal_s.'"  for="'.$name.'">'. JText::_($label).': '.$star .'</label>';
					                  $input .= $value;
                           }
													 
													 $input .= '</p>';
													
													 return $input;
 }
	
	
	
	
  
 
            
						
						

	
	
}
