<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerPromotion extends SmsController
{
	
	/**
	** Get constructor
	**/
	function __construct(){
		parent::__construct();
	}
	

	/**
	** Mark Save
	**/
	function savepromo(){
		$sid = JRequest::getVar('sid');
	    $new_year = JRequest::getVar('newyear');
	    $new_class = JRequest::getVar('newclass');
		$new_division = JRequest::getVar('newdivision');
		$new_section = JRequest::getVar('newsection');
	    $new_roll = JRequest::getVar('newroll');
		$exit_id = JRequest::getVar('exit_id');
    
        // check data
	    if(!empty($new_roll) && !empty($new_class) && !empty($new_section) && !empty($new_division)){
        
		    $model = $this->getModel('promotion');
		    $id = $model->savepromo( $sid, $new_year, $new_class, $new_division, $new_section, $new_roll, $exit_id );
		    if (!empty($id)) {
	             $save_meg = '<b style="color: green;">Saved !</b>';
			}else {
		        $save_meg = '<p style="text-align: center;"><span id="meg" style=" background: red;color: #fff;padding: 3px 33px;">Error</span></p>';
			}
	    }else{
	        $id =0;
	        $save_meg = '<p style="text-align: left;"><span id="meg" style=" color: red;padding: 3px 33px;">Error: fill roll, class, division & section.</span></p>';
	    }   
	        
        $arr = array();
        $arr[0] = $save_meg;
        $arr[1] = $id;
        echo json_encode($arr);
		JFactory::getApplication()->close();
	}
	
    
    
    /**
    ** Get Fix Issue
    **/
    function fixissue(){
        $class_id = JRequest::getVar('class');
		$model = $this->getModel('promotion');
		$id =$model->fix($class_id);
		if ($id) {
			$msg = JText::_( 'Data Fixed!' );
		} else {
			$msg = JText::_( 'Error fixing Data' );
		}
		$link = 'index.php?option=com_sms&view=promotion';
		$this->setRedirect($link, $msg);
	 }
    
	
	/**
	** Student List
	**/
	function getstudentlist(){
		$year = JRequest::getVar('year');
		$class_id = JRequest::getVar('class_id');
		$division_id = JRequest::getVar('division');
		$model = $this->getModel('promotion');
	
	    if(!empty($year) && !empty($class_id) && !empty($division_id)){
	        $id = $model->getstudentList($year, $class_id, $division_id);
	        if(empty($id)){
				echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' </div>';
			}else{
				echo $id;
			}
	    }else{
	        if(empty($class_id)):
                echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select class ! </div>';
	        elseif(empty($year)): 
                echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select Year ! </div>';
		    elseif(empty($division_id)):
                echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select Division ! </div>';
		    else:
			    echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select class,Division ! </div>';
            endif;
	    }
	    JFactory::getApplication()->close();
	}
	 
	 
	/**
	** Mark List
	**/
	function getmarklist(){
		$exam_id = JRequest::getVar('exam');
		$class_id = JRequest::getVar('class_id');
		$division_id = JRequest::getVar('division');
		$section_id = JRequest::getVar('section');
		$subject_id = JRequest::getVar('subject');
	    $model = $this->getModel('marks');
	
	    // check data
	    if(!empty($exam_id) && !empty($class_id) && !empty($section_id) && !empty($subject_id)){
	        $id = $model->getMarkList($exam_id, $class_id,$section_id, $subject_id,$division_id);
	        if(empty($id)){
				echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' </div>';
			}else{
				echo $id;
			}
	    }else{
	        if(empty($exam_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam ! </div>';}
	        else if(empty($class_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select class ! </div>';}
	        else if(empty($section_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select section ! </div>';}
			else if(empty($subject_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select subject ! </div>';}
			else {echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam,class,section & subject ! </div>';}
	    }
	    JFactory::getApplication()->close();
	}
	 
	/**
	** Subject List
	**/
	function getsubjectlist(){
		$cid = JRequest::getVar('class_id');
		$division = JRequest::getVar('division_id');
		$model = $this->getModel('marks');
		echo $model->getsubjectList($cid,$division,'desplyStudentList();');
		JFactory::getApplication()->close();
	}
	 
	/**
	** Subject List for mark
	**/
	function getsubjectlistforMark(){
		$cid = JRequest::getVar('class_id');
		$division = JRequest::getVar('division_id');
		$model = $this->getModel('marks');
		echo $model->getsubjectList($cid,$division,'desplymarkList();');
		JFactory::getApplication()->close();
	}
	 	
}
