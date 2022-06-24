<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsControllerResult extends SmsController
{
	
	function __construct()
	{
		parent::__construct();

	}
	
	/**
	** Get Result
	**/
	function getresult(){
		$group_title = JRequest::getVar('group_title');
		$class_id    = JRequest::getVar('classid');
		$roll        = JRequest::getVar('roll');
		$exam        = JRequest::getVar('exam');
		$model       = $this->getModel('result');

		if(!empty($exam)){
		    $return_value = $model->Result($exam,$class_id,$roll);
	        if(!empty($return_value)){
		        echo $return_value;
		    }else{

                if($group_title =='Parents'){
          	        if(empty($roll)){
          	        	echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select student !';
          	        }
                }

                if($group_title =='Teachers'){
          	        if(empty($class_id)){
          	        	echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select class !';
          	        }elseif(empty($roll)){
          	        	echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please enter roll !';
                    }else{
            	        echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').'';
                    }
                }
            }
	 
	    }else{
	        echo'<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam !</div>';
	    }
	 
	    JFactory::getApplication()->close();
	}

	/**
	** Save Comment
	**/
	function savecomment(){
		$cid     = JRequest::getVar('cid');
		$roll    = JRequest::getVar('roll');
		$eid     = JRequest::getVar('eid');
		$tid     = JRequest::getVar('tid');
		$comment = JRequest::getVar('comment');
		
		$model = $this->getModel('result');
		$id = $model->savecomment( $cid, $roll, $eid,  $tid, $comment);
		
		if (!empty($id)) {
			$comment = SmsHelper::selectSingleData('comments', 'sms_result_comments', 'id', $id);
			echo'<textarea cols="" rows="" id="comment" style="width: 98%;height: 100px;">'.$comment.'</textarea>';
		}else {
			echo '<p style="text-align: center;"><span id="meg" style=" background: red;color: #fff;padding: 3px 33px;">Error</span></p>';
		}
		
		JFactory::getApplication()->close();
	}
	
	
}
