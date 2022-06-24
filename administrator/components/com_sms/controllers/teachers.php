<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerTeachers extends SmsController
{
	
	/**
	** constructor
	**/
	function __construct(){
		parent::__construct();
	}
	
	
	/**
	** Send Email
	**/
	public function sendemail(){
	 
		// Get the Mailer
		$mailer = JFactory::getMailer();
		
		//Set Sender
		$config = JFactory::getConfig();
	    $sender = array( 
		    $config->get( 'mailfrom' ),
		    $config->get( 'fromname' ) 
	    );
		$mailer->setSender($sender);
		
		//Set receiver
		$recipient = explode(',',JRequest::getVar('email_address'));
		
		//$recipient ='mdsuzonmia01@yahoo.com';
        $mailer->addRecipient($recipient);
		
		$body   = nl2br(JRequest::getVar('message'));
	    $mailer->setSubject(JRequest::getVar('subject'));
	    $mailer->isHTML(true);
	    $mailer->Encoding = 'base64';
	    $mailer->setBody($body);
		
		$send = $mailer->Send();
	    if ( $send !== true ) {
		    echo $message = JText::_( 'LABEL_TEACHER_EMAIL_SENT_ERROR' ).': ' . $send->__toString();
	    } else {
		    $message = JText::_( 'LABEL_TEACHER_EMAIL_SENT' );
	    }
		
		$this->setMessage($message);
		$this->setRedirect('index.php?option=com_sms&view=teachers');
	}
	
	//Send SMS
	public function sendsms(){
		$appSettings   = JBusinessUtil::getInstance()->getApplicationSettings();
		$profile_id    = $appSettings->sms_username;
		$password      = $appSettings->sms_password;
		$sender        = $appSettings->sms_sender;
		
		$mobile_no     = JRequest::getVar('mobile_no');
		$message_body  = JRequest::getVar('message');
		$message_final = rawurlencode($message_body);
		$url ="http://mshastra.com/sendurlcomma.aspx?user=".$profile_id."&pwd=".$password."&senderid=".$sender."&mobileno=".$mobile_no."&msgtext=".$message_final."&CountryCode=ALL";
		
		$ch = curl_init($url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
                             curl_close($ch);
		
		$message = $curl_scraped_page;
		$this->setMessage($message);
		$this->setRedirect('index.php?option=com_jbusinessdirectory&view=companies');
	}
	
	
	/**
	** Get Apply
	**/
	function apply(){
	    $model = $this->getModel('teachers');
		$id =$model->store();
		if ($id) {
			$msg = JText::_( 'LABEL_TEACHER_DATA_SAVE' );
		} else {
			$msg = JText::_( 'LABEL_TEACHER_DATA_SAVE_ERROR' );
		}
	    $link = 'index.php?option=com_sms&view=teachers&task=editteacher&cid[]='. $id;
	    $this->setRedirect($link, $msg);
	}
	 
	/**
	** Get Save
	**/
	function save(){
		$model = $this->getModel('teachers');
		$id =$model->store();
		if (!empty($id)) {
			$msg = JText::_( 'LABEL_TEACHER_DATA_SAVE' );
		}else {
			$msg = JText::_( 'LABEL_TEACHER_DATA_SAVE_ERROR' );
		}
		$link = 'index.php?option=com_sms&view=teachers';
		$this->setRedirect($link, $msg);
	}
	 
	 
	/**
	** Get Remove
	**/
	function remove(){
		$model = $this->getModel('teachers');
		if(!$model->delete()) {
			$msg = JText::_( 'LABEL_TEACHER_DATA_DELETED_ERROR' );
		} else {
			$msg = JText::_( 'LABEL_TEACHER_DATA_DELETED' );
		}
		$this->setRedirect( 'index.php?option=com_sms&view=teachers', $msg );
	}
	
	/**
	** Get Cancel
	**/
	function cancel(){
		$msg = JText::_( 'DEFAULT_CANCELL' );
		$this->setRedirect( 'index.php?option=com_sms&view=teachers', $msg );
	}

	
}
