<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
SmsHelper::valid();

class SmsViewPayments extends JViewLegacy
{
	
	public function display($tpl = null)
	{
		$task = JRequest::getWord('task');
	    switch ($task) {
			case'studentlist':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=studentpaymentlist';
				$app->redirect($link, '');
			break;
	
			case'managepaymenttype':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=paytype';
				$app->redirect($link, '');
			break;
	
			case'managepaymethod':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=paymethod';
				$app->redirect($link, '');
			break;
	
			case'paymentdetails':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
				$current_year = date("Y");	
		        $id =(int)$array[0];
				if(!empty($id)){
					$student		=$model->getStudent($id);
					$sid = $student->id;
					$details		=$model->getPaymentDetails($id,$current_year);
					$display_history		=$model->history($details,$student,$current_year);
					$this->assignRef('display_history',		$display_history);
					$this->assignRef('sid',		$sid);
				}
				JToolbarHelper::title(JText::_('LABEL_PAYMENT_HISTORY'), 'credit');
				JToolbarHelper::custom('getback', 'undo.png', 'new.png',JText::_('DEFAULT_BACK'), false);
				$this->setLayout('details');
			break;
		
			case'getback':
			    $app = JFactory::getApplication();
				$link = 'index.php?option=com_sms&view=payments';
				$app->redirect($link, '');
			break;
		
			case'invoice':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$payment		=$model->getPayments($id);
					$this->assignRef('payment',		$payment);
					
					//student name
					$student_name = $model->getStudentname($payment->student_roll);
					$this->assignRef('student_name',		$student_name);
					
				}
				  
				JToolbarHelper::title(JText::_('LABEL_PAYMENT_INVOICE'), 'credit');
			    JToolbarHelper::custom('getback', 'undo.png', 'new.png',JText::_('DEFAULT_BACK'), false);
				JToolbarHelper::custom('invoicepdf', 'attachment.png', 'new.png',JText::_('DEFAULT_PDF'), false);
				$this->setLayout('invoice');
			break;
		
			case'invoicepdf':
			    $model = $this->getModel();
				$array = JRequest::getVar('cid',  0, '', 'array');
		        $id =(int)$array[0];
				if(!empty($id)){
					$payment		= $model->getPayments($id);
					$this->assignRef('payment',		$payment);
					
					//student name
					$student_name = $model->getStudentname($payment->student_roll);
					$this->assignRef('student_name', $student_name);
					
				}
				$this->setLayout('invoice_pdf');	
			break;
	
			case'editpayment':
			    $model = $this->getModel();
				JRequest::setVar('hidemainmenu', 1);
				$array = JRequest::getVar('cid',  0, '', 'array');
				$id =(int)$array[0];
		        if(!empty($id)){
					$payment = $model->getPayments($id);
					$this->assignRef('payment',		$payment);
				}
					
				JToolbarHelper::title('Review Payment', 'star');
			    JToolbarHelper::apply('apply');
				JToolbarHelper::save('save');
				JToolbarHelper::cancel('cancel');
				$this->setLayout('form');
			break;
		
		    default:
		        $model = $this->getModel();
				$this->items		 = $this->get('Items');
		        $this->pagination	 = $this->get('Pagination');
				$this->filterForm    = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
                $class_list = $model->getclassList('');
			    $this->assignRef('class', $class_list);
	            SmsHelper::addSubmenu('payments');
			    JToolbarHelper::title(JText::_('LABEL_PAYMENT_LIST'), 'credit');
				//JToolbarHelper::custom('newpayment', 'new.png', 'new.png',JText::_('BTN_PAYMENT_NEW'), false);
				//JToolbarHelper::editList('editpayment');
				JToolbarHelper::custom('managepaymenttype', 'options.png', 'list.png',JText::_('Manage Payable Item'), false);
				JToolbarHelper::custom('managepaymethod', 'options.png', 'list.png',JText::_('BTN_PAYMENT_PAY_METHOD'), false);
				$this->setLayout('default');
	        break;
	    }
		
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}
