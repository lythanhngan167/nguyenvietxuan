<?php 
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
use Joomla\CMS\Component\Router\RouterBase;
class smsRouter extends RouterBase
{

public function build(&$query){

    $helper = new SmsrouterHelper();  
    $segments = array();
			
	if(isset($query['view'])){
        $segments[] = $query['view'];
        unset($query['view']);
    }
			 
	if(isset($query['task'])){
        $segments[] = $query['task'];
        unset($query['task']);
    }
       
    if(isset($query['tid'])){
        $segments[] =  $query['tid'] . ':' .$helper->getTeacherAlias($query['tid']);
        unset($query['tid']);
    }
			 
	if(isset($query['sid'])){
        $segments[] =  $query['sid'] . ':' .$helper->getStudentAlias($query['sid']);
        unset($query['sid']);
    }
			 
	if(isset($query['cid'])){
        $segments[] = $query['cid'];
        unset($query['cid']);
    }

	if(isset($query['mid'])){
        $segments[] = $query['mid'];
        unset($query['mid']);
    }
	
    return $segments;
}

public function parse(&$segments)
{
    $helper = new SmsrouterHelper();  
	$vars   = array();
	$app    = JFactory::getApplication();
    $menu   = $app->getMenu();
    $item   = $menu->getActive();
	$items  = $menu->getItems('component', 'com_sms');
	$count  = count($segments);

    switch($segments[0])
    {
		case 'students':
			if(!empty($segments[1])){
				switch($segments[1])
                {
					case 'profile':
                        $vars['view'] = 'students';
						$vars['task'] = 'profile';
                    break;
				}
			}
			$vars['view'] = 'students';
        break;

        case 'slogin':
	        $vars['view'] = 'slogin';
        break;

        case 'tlogin':
	        $vars['view'] = 'tlogin';
        break;

        case 'plogin':
	        $vars['view'] = 'plogin';
        break;
	
	    case 'teachers':
			if(!empty($segments[1])){
				switch($segments[1])
                {
					case 'profile':
                        $vars['view'] = 'teachers';
						$vars['task'] = 'profile';
                    break;
				}
			}
			$vars['view'] = 'teachers';
        break;
											 
		case 'parents':
			if(!empty($segments[1])){
				switch($segments[1])
                {
					case 'profile':
	                    $vars['view'] = 'parents';
				        $vars['task'] = 'profile';
	                break;

	                case 'studentprofile':
	                    $vars['view'] = 'parents';
				        $vars['task'] = 'studentprofile';
				        if(!empty($segments[2])){
				        $id = explode(':', $segments[2]);
	                    $vars['cid'] = (int) $id[0];
	                    }
	                break;
				}
		    }
			$vars['view'] = 'parents';
        break;
											 
		case 'message':
			if(!empty($segments[1])){
				switch($segments[1])
                {
				    case 'messagedetails':
			            $vars['view'] = 'message';
                        $vars['task'] = 'messagedetails';
                        $id = explode(':', $segments[2]);
                        $vars['mid'] = (int) $id[0];
                    break;
				    case 'newmessage':
                        $vars['view'] = 'message';
						$vars['task'] = 'newmessage';
                    break;
				}
			}
			$vars['view'] = 'message';
        break;

		case 'result':
			$vars['view'] = 'result';
        break;
	    case 'attendancereport':
			$vars['view'] = 'attendancereport';
        break;
	    case 'editparent':
			$vars['view'] = 'editparent';
        break;
											 
		case 'payments':
			if(!empty($segments[1])){
				switch($segments[1])
                {
					case 'editpayment':
						$vars['view'] = 'payments';
                        $vars['task'] = 'editpayment';
                        $id = explode(':', $segments[2]);
                        $vars['cid'] = (int) $id[0];
                    break;
					case 'paymentdetails':
						$vars['view'] = 'payments';
                        $vars['task'] = 'paymentdetails';
                        $id = explode(':', $segments[2]);
                        $vars['cid'] = (int) $id[0];
                    break;
					case 'newpayment':
                        $vars['view'] = 'payments';
						$vars['task'] = 'newpayment';
                    break;
					case 'process':
                        $vars['view'] = 'payments';
						$vars['task'] = 'process';
						$id = explode(':', $segments[2]);
                        $vars['cid'] = (int) $id[0];
                    break;
                    case 'invoice':
                        $vars['view'] = 'payments';
						$vars['task'] = 'invoice';
						$id = explode(':', $segments[2]);
                        $vars['cid'] = (int) $id[0];
                    break;
					case 'invoicepdf':
                        $vars['view'] = 'payments';
						$vars['task'] = 'invoicepdf';
						$id = explode(':', $segments[2]);
                        $vars['cid'] = (int) $id[0];
                    break;
				}
			}
			$vars['view'] = 'payments';
        break;
						
		case 'attendance':
			if(!empty($segments[1])){
				switch($segments[1])
                {
					case 'editattend':
						$vars['view'] = 'attendance';
                        $vars['task'] = 'editattend';
						if(!empty($segments[2])){
							$id = explode(':', $segments[2]);
                            $vars['cid'] = (int) $id[0];
						}
					break;
					case 'newattend':
                        $vars['view'] = 'attendance';
						$vars['task'] = 'newattend';
                    break;
				}
			}
			$vars['view'] = 'attendance';
        break;
						
		case 'marks':
			if(!empty($segments[1])){
				switch($segments[1])
                {
					case 'editattend':
					    $vars['view'] = 'attendance';
                        $vars['task'] = 'editattend';
                        $id = explode(':', $segments[2]);
                        $vars['cid'] = (int) $id[0];
                    break;
					case 'newattend':
                        $vars['view'] = 'attendance';
						$vars['task'] = 'newattend';
                    break;
				}
			}
			$vars['view'] = 'marks';
        break;
						
		case 'editstudents':
			$vars['view'] = 'editstudents';
        break;
						
		case 'editteachers':
			$vars['view'] = 'editteachers';
        break;
		case 'sms':
			$vars['view'] = 'sms';
        break;
               
    }
    return $vars;
}

}

class SmsrouterHelper {
  
	// Get Student Alias by Student id
	public function getStudentAlias($student_id){
	    $id = (int) $student_id;
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('alias')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
        $db->setQuery( $query);
	    $student_alias= $db->loadResult(); 
	    return $student_alias;
	}

    // Get student id by alias
	public function getStudentId($student_alias){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('alias') . ' = '. $db->quote($student_alias));
        $db->setQuery( $query);
        $id = $db->loadResult(); 
	    return $id;
	}
	 
	// Get Teacher Alias by teacher id
	public function getTeacherAlias($teacher_id){
	    $id = (int) $teacher_id;
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('alias')))
            ->from($db->quoteName('#__sms_teachers'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
        $db->setQuery( $query);
        $teacher_alias = $db->loadResult(); 
	    return $teacher_alias;
	}

	// Get Teacher id by teacher alias
	public function getTeacherId($teacher_alias){
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_teachers'))
            ->where($db->quoteName('alias') . ' = '. $db->quote($teacher_alias));
        $db->setQuery( $query);
        $id = $db->loadResult(); 
	    return $id;
	}
	
}