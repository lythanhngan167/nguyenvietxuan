<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsViewPayments extends JViewLegacy
{
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
	    $app = JFactory::getApplication();
	    $lang = JFactory::getLanguage();
	    $model = $this->getModel();
	    $user		= JFactory::getUser();
        $uid =$user->get('id');
		$task = JRequest::getWord('task');
	    switch ($task) 
	    {
	
	        case'process':
	            $array = JRequest::getVar('cid',  0, '', 'array');
                $id =(int)$array[0];
			    if(!empty($id)){
				    $payment = $model->getPayments($id);
					$this->assignRef('payment',	$payment);
			    }
		        $this->setLayout('process');
			    SmsHelper::addSubmenu('payments');
		    break;

		    case'invoice':
	            $array = JRequest::getVar('cid',  0, '', 'array');
                $id =(int)$array[0];
			    if(!empty($id)){
				    $payment		= $model->getPayments($id);
					$this->assignRef('payment',	$payment);
			
			        //student name
					$student_name = $model->getStudentname($payment->student_roll);
					$this->assignRef('student_name',		$student_name);
			    }
		  
			    $this->setLayout('invoice');
		    break;
		
	        case'invoicepdf':
	            $array = JRequest::getVar('cid',  0, '', 'array');
                $id =(int)$array[0];
			    if(!empty($id)){
					$payment = $model->getPayments($id);
					$this->assignRef('payment',	$payment);
					
					//student name
					$student_name = $model->getStudentname($payment->student_roll);
					$this->assignRef('student_name', $student_name);
				}
		 	
                $dir = $lang->get('rtl');
			    if($dir == 0) {
                    //do soemthing for ltl
		            $this->setLayout('invoice_pdf');
                }else {
			        //do something rtl
					$this->setLayout('invoice_pdf_rtl');
			    }
		    break;
	
	        case'newpayment':
	            $array = JRequest::getVar('cid',  0, '', 'array');
			    $pid =(int)$array[0];
                
                if(!empty($uid)){
					$group_title =  SmsHelper::checkGroup($uid);
					
					if($group_title=="Parents"){
						// Parent
						$student_id = $model->getParentStudentID($uid);
						$parent_id = SmsHelper::selectSingleData('id', 'sms_parents', 'user_id', $uid);
						$this->assignRef('parent_id', $parent_id);
					}else if($group_title=="Students"){
						// Student
					    $student_id = $model->getStudentID($uid);
					}else if($group_title=="Teachers"){
						// Teacher
					    $student_id ='';
					    $class_id = $model->getTeacherClass($uid);
                        $section_ids = $model->getSectionIDS($class_id);
						 
					    if(!empty($pid)){
						    $payment = $model->getPayments($pid);
                            $this->assignRef('payment',	$payment);
						    $pay_for_id = $payment->pay_for_id;
						}else{						
						    $pay_for_id='';
						}
													 
						$this->assignRef('classid',	$class_id);
						$this->assignRef('sectionid', $section_ids);
						$this->assignRef('group_title',	$group_title);
													 
				    }else{
				        // Others
				        $mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
                        $app->enqueueMessage($mge, 'warning');
                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
                        $app->redirect($redirect_link);
					}
													 
					if(!empty($student_id)){
			            $student		    = $model->getStudent($student_id);
						$student_class_id   = $student->class;
						$student_section_id = $student->section;
						$student_roll       = $student->roll;
						$pay_for_id         ='';
						$this->assignRef('student_id',	 $student_id);
                        $this->assignRef('student_roll', $student_roll);
                        $this->assignRef('classid',		 $student_class_id);
						$this->assignRef('sectionid',	 $student_section_id);
			            $this->assignRef('group_title',	 $group_title);
					}
					
			        $paytype_list = $model->getPayForList($pay_for_id);
			        $this->assignRef('paytype', $paytype_list);
		            SmsHelper::addSubmenu('payments');
			        $this->setLayout('form');
			    }else{
					$mge = JText::_('COM_SMS_MESSAGE_STUDENT_LOGIN_REQUIRED');
					$app->enqueueMessage($mge, 'warning');
                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students&task=slogin' );
                    $app->redirect($redirect_link);
			    }
		    break;

		    case'editpayment':
	            $array = JRequest::getVar('cid',  0, '', 'array');
			    $pid =(int)$array[0];
                
                if(!empty($uid)){
					$group_title =  SmsHelper::checkGroup($uid);
					if($group_title =="Teachers"){
						// Teacher
					    $student_id ='';
					    $class_id = $model->getTeacherClass($uid);
                        $section_ids = $model->getSectionIDS($class_id);
						 
					    if(!empty($pid)){
						    $payment = $model->getPayments($pid);
                            $this->assignRef('payment',	$payment);
						}else{						
						   
						}
													 
						$this->assignRef('classid',	$class_id);
						$this->assignRef('sectionid', $section_ids);
						$this->assignRef('group_title',	$group_title);
													 
				    }else{
				        // Others
				        $mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
                        $app->enqueueMessage($mge, 'warning');
                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
                        $app->redirect($redirect_link);
					}
													 
					
		            SmsHelper::addSubmenu('payments');
			        $this->setLayout('editform');
			    }else{
					$mge = JText::_('COM_SMS_MESSAGE_STUDENT_LOGIN_REQUIRED');
					$app->enqueueMessage($mge, 'warning');
                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students&task=slogin' );
                    $app->redirect($redirect_link);
			    }
		    break;
		
		
		    default:
		    
			    if(!empty($uid)){
					$group_title =  SmsHelper::checkGroup($uid);
					
					if($group_title=="Parents"){ 
					    // Get Parent
						$parent_id = SmsHelper::selectSingleData('id', 'sms_parents', 'user_id', $uid);
						$this->assignRef('parent_id', $parent_id);
						$this->assignRef('details',	  $parent_id);
							
					}else if($group_title=="Students"){ 
					    // Get Student
						$student_id = $model->getStudentID($uid);
					       
					}else if($group_title=="Teachers"){ 
					    // Get Teacher 
						$student_id='';
						$class_id = $model->getTeacherClass($uid);
						$section_ids = $model->getSectionIDS($class_id);
						    
					    if(!empty($class_id)){
			                $this->assignRef('details',		$class_id);
							$this->assignRef('section_ids',		$section_ids);
			            }
		            }else{
						$mge = JText::_('COM_SMS_MESSAGE_AREA_NOT_ALOW');
						$app->enqueueMessage($mge, 'warning');
                        $redirect_link = JRoute::_( 'index.php?option=com_sms&view=sms' );
                        $app->redirect($redirect_link);
				    }
													 
		            if(!empty($student_id)){
			            $student =$model->getStudent($student_id);
			            $this->assignRef('student',		$student);
			            $this->assignRef('details',		$student_id);
						$this->assignRef('group_title',	$group_title);
			        }
		          
		            $this->assignRef('group_title',		$group_title);
					SmsHelper::addSubmenu('payments');
				    $this->setLayout('details');
							
				}else{
					$mge = JText::_('COM_SMS_MESSAGE_STUDENT_LOGIN_REQUIRED');
					$app->enqueueMessage($mge, 'warning');
                    $redirect_link = JRoute::_( 'index.php?option=com_sms&view=students&task=slogin' );
                    $app->redirect($redirect_link);
			    }
	        break;
	    }
		
		$this->smshelper = new SmsHelper;
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
