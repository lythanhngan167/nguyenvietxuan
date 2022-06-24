<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

class SmsControllerSms extends JControllerLegacy
{

		
	
	
	
	/**---------------------------------------------------------------------------------------------------------------------
	*********************************************** CLIENTS ADD CANDIDATE ***************************************************
	**----------------------------------------------------------------------------------------------------------------------*/
	function newcandidate() {  
	  // Save  Category info
		$model = $this->getModel('candidates');
		$id =$model->addcandidate();
		
		
		if($id) {
			$msg = JText::_(' Candidate saved');
		}else {
			$msg = JText::_( 'Data not save' );
		}
		
		$link 		= JRoute::_( 'index.php?option=com_business&view=candidates' );
	  $this->setRedirect($link,$msg);
		
	}
	
	
	 function deletebusiness(){
	 $model = $this->getModel('clientbusiness');
		if(!$model->delete_business()) {
			$msg = JText::_( 'Error: One or More Greetings Could not be Deleted' );
		} else {
			$msg = JText::_( 'Data Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_business&view=clientbusiness', $msg );
		
	 }
	 
		public function login()
	{
		JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

		$app    = JFactory::getApplication();
		$input  = $app->input;
		$method = $input->getMethod();

		// Populate the data array:
		$data = array();

		$data['return']    = base64_decode($app->input->post->get('return', '', 'BASE64'));
		$data['username']  = $input->$method->get('username', '', 'USERNAME');
		$data['password']  = $input->$method->get('password', '', 'RAW');
		$data['secretkey'] = $input->$method->get('secretkey', '', 'RAW');

		// Set the return URL if empty.
		if (empty($data['return']))
		{
			$data['return'] = 'index.php?option=com_users&view=profile';
		}

		// Set the return URL in the user state to allow modification by plugins
		$app->setUserState('users.login.form.return', $data['return']);

		// Get the log in options.
		$options = array();
		$options['remember'] = $this->input->getBool('remember', false);
		$options['return']   = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];
		$credentials['secretkey'] = $data['secretkey'];

		// Perform the log in.
		if (true === $app->login($credentials, $options))
		{
			// Success
			if ($options['remember'] == true)
			{
				$app->setUserState('rememberLogin', true);
			}

			$app->setUserState('users.login.form.data', array());
			$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
		}
		else
		{
			// Login failed !
			$data['remember'] = (int) $options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
	}
	
	
	public function logout()
	{
		JSession::checkToken('request') or jexit(JText::_('JInvalid_Token'));

		$app = JFactory::getApplication();

		// Perform the log out.
		$error  = $app->logout();
		$input  = $app->input;
		$method = $input->getMethod();

		// Check if the log out succeeded.
		if (!($error instanceof Exception))
		{
			// Get the return url from the request and validate that it is internal.
			$return = $input->$method->get('return', '', 'BASE64');
			$return = base64_decode($return);

			

			// Redirect the user.
			$app->redirect(JRoute::_($return, false));
		}
		else
		{
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
	}
	
	
}
