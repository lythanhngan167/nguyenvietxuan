<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsControllerMessage extends JControllerLegacy
{

	/**
	** Find Teacher
	**/	
	function findteacher(){
	    $value = JRequest::getVar('val');
	    $model = $this->getModel('message');
	    $row   = $model->getTeacherID($value);
	    foreach($row as $teacher){
	        if(!empty($teacher->photo)){
                $path = "/components/com_sms/photo/teachers/";
				$photo = $teacher->photo;
				$img_src = $path.$photo;
			}else {
				$path = "/components/com_sms/photo/";
				$photo="photo.png";
				$img_src = $path.$photo;
			}
			$degignation = $teacher->designation;
	        $onclick = "onclick=\"lookteacher('".$teacher->name."','".$teacher->user_id."');\"";
	        echo'<div class="row-fluid searchresult_ajax" '.$onclick.' style="margin: 5px 0;cursor: pointer;">';
	        echo'<div class="span2"><img src="'.JURI::root().$img_src.'" alt="" width="30px"height: 30px; style="margin: 3px;" /></div>';
			echo'<div class="span10" >'.$teacher->name.'</div>';
	        echo'</div>';
	    }//end foreach
	    JFactory::getApplication()->close();
	}
	
	/**
	** Find Student
	**/
	function findstudent(){
	    $value = JRequest::getVar('val');
	    $model = $this->getModel('message');
	    $row = $model->getStudentID($value);
	    foreach($row as $student){
	        if(!empty($student->photo)){
                $path = "/components/com_sms/photo/students/";
				$photo = $student->photo;
				$img_src = $path.$photo;
			}else {
				$path = "/components/com_sms/photo/";
				$photo="photo.png";
				$img_src = $path.$photo;
			}
					 
	        $onclick = "onclick=\"lookstudent('".$student->name."','".$student->user_id."');\"";
	        echo'<div class="row-fluid searchresult_ajax" '.$onclick.' style="margin: 5px 0;cursor: pointer;">';
	        echo'<div class="span2"><img src="'.JURI::root().$img_src.'" alt="" width="30px"height: 30px; style="margin: 3px;" /></div>';
			echo'<div class="span10" >'.$student->name.'</div>';
	        echo'</div>';
	    }//end foreach
	    JFactory::getApplication()->close();
	}

	/**
	** Find Parent
	**/
	function findparent(){
		$value = JRequest::getVar('val');
		$model = $this->getModel('message');
		$row = $model->getParentID($value);
		foreach($row as $parent){
		    if(!empty($parent->photo)){
	            $path = "/components/com_sms/photo/parents/";
				$photo = $parent->photo;
				$img_src = $path.$photo;
			}else {
				$path = "/components/com_sms/photo/";
				$photo="photo.png";
				$img_src = $path.$photo;
			}

			$onclick = "onclick=\"lookparent('".$parent->name."','".$parent->user_id."');\"";
		    echo'<div class="row-fluid searchresult_ajax" '.$onclick.' style="margin: 5px 0;cursor: pointer;">';
		    echo'<div class="span2"><img src="'.JURI::root().$img_src.'" alt="" width="30px"height: 30px; style="margin: 3px;" /></div>';
			echo'<div class="span8" >'.$parent->name.'</div>';
		    echo'</div>';
		}//end foreach
		JFactory::getApplication()->close();
	}
	
	// Get Teacher Box
	function teacherbox(){
	    echo '<input type="text" id="teacher" class="required" onkeyup="findteacher()" onblur="blure()" name="recever_name" placeholder="Type teacher name " required="required" />';
	    JFactory::getApplication()->close();
	}
	
	// Get Student Box
	function studentbox(){
	    echo '<input type="text" id="student" class="required" onkeyup="findstudent()" onblur="blure()"  name="recever_name" placeholder="Type Student name or roll  " required="required" />';
	    JFactory::getApplication()->close();
	}

	/**
	** Get Parent Box
	**/
	function parentbox(){
		echo '<input type="text" id="parent" class="required" onkeyup="findparent()" onblur="blure()"  name="recever_name"  placeholder="Type Parent Name " required="required" />';
		JFactory::getApplication()->close();
	}
	
	/**
	** Get Message Save
	**/
	function message_save() {  
	    // Save  Category info
		$model = $this->getModel('message');
		$id =$model->savemessage();
		if($id) {
			$msg = JText::_(' Message successfully sent');
		}else {
			$msg = JText::_( 'Message not Sent' );
		}
		$link 		= JRoute::_( 'index.php?option=com_sms&view=message' );
	    $this->setRedirect($link,$msg);
	}
	
	/**
	** Get Message Reply
	**/
	function message_reply(){
	    $model = $this->getModel('message');
		$id =$model->messagereply();
		if($id) {
			$msg = JText::_(' Message successfully sent');
		}else {
			$msg = JText::_( 'Message not Sent' );
		}
		$link 		= JRoute::_( 'index.php?option=com_sms&view=message&task=messagedetails&mid='.$id.'' );
	    $this->setRedirect($link,$msg);
	}
	
	/**
	** Get Delete
	**/
	function delete(){
	    $model = $this->getModel('message');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: Message Could not be Delete' );
		} else {
			$msg = JText::_( 'Message successfully deleted' );
		}
        $link =JRoute::_('index.php?option=com_sms&view=message');
		$this->setRedirect( $link, $msg );
	}
	
	
}
