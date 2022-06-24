<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
class SmsControllerActivation extends SmsController
{
	
	/**
	** constructor
	**/
	function __construct(){
		parent::__construct();
	}
	
	/**
	** Get Find
	**/
    public function find(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get the caching duration
		$component = JComponentHelper::getComponent('com_installer');
		$params = $component->params;
		$cache_timeout = $params->get('cachetimeout', 6, 'int');
		$cache_timeout = 3600 * $cache_timeout;
		
		$module = JComponentHelper::getComponent('com_sms');
		
		// Find updates
		$model	= $this->getModel('activation');
		$model->findUpdates(array($module->id), $cache_timeout);
		$this->setRedirect(JRoute::_('index.php?option=com_sms&view=activation', false));
	}
    
    /**
    ** Get Update
    **/
    function update(){
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('activation');
		$uid   = JRequest::getVar('cid', array(), 'array');

		JArrayHelper::toInteger($uid, array());
		if ($model->update($uid)){
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();
		}
	
		$app = JFactory::getApplication();
		$redirect_url = $app->getUserState('com_sms.redirect_url');
		if (empty($redirect_url)){
			$redirect_url = JRoute::_('index.php?option=com_sms&view=activation', false);
		}else{
			// Wipe out the user state when we're going to redirect
			$app->setUserState('com_sms.redirect_url', '');
			$app->setUserState('com_sms.message', '');
			$app->setUserState('com_sms.extension_message', '');
		}
		$this->setRedirect($redirect_url);
	}
	
	/**
	** Get Apply
	**/
	function apply(){
	    $model = $this->getModel('activation');
        $data = JRequest::get( 'post' );
        $envato_user_name = $data['buyer'];
        $envato_purchase_code = $data['purchase_code']; 
        $website_host = $_SERVER['HTTP_HOST'];


        $runing_domain = SmsHelper::getDomain($website_host);
        $website_host = $runing_domain;

        $version = $model->getCurrentVersion();
         
        // Check Envato purchase code step 1
        $code = trim($envato_purchase_code);

	    // Make sure the code is valid before sending it to Envato:
	    if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $code)){
	        $msg = "Invalid purchase code";
	    }else{

	        $envato_verify_purchase = SmsHelper::envato_verify_purchase($envato_user_name, $envato_purchase_code);
	        if (!empty($envato_verify_purchase)){
	            if($website_host!='localhost'){
	            // step 2 : verify domain
	            $verify_website = SmsHelper::verify_website($envato_purchase_code);
	            
	            // get code
	            $verify_website_code = $verify_website['code'];
	            $verify_website_url = $verify_website['website'];
	           
                $exit_domain = SmsHelper::getDomain($verify_website['website']);

                if($exit_domain == $website_host){
	                $verify_website_code = 9;
                }

	            if($verify_website_code =='1' && $exit_domain != $website_host):
	                $msg = JText::_( 'System '.$verify_website['message'].' in '.$verify_website['website'] );

	            elseif($verify_website_code =='9'):
	            
	                // Get store data to system API
	                $store_website = SmsHelper::store_website($envato_purchase_code, $website_host, $envato_user_name, $version);
	                $store_website_code = $store_website['code'];
	                if($store_website_code =='8'):
	                  
	                   $msg = JText::_( 'System '.$store_website['message'] );
	            
	                elseif($store_website_code =='7'):
	                    // Get store data to system API
	                    $verify_data = SmsHelper::verify_website($envato_purchase_code);
	                    $a_code = $verify_data['active-code'];
	                    $website = $verify_data['website'];
	                    //set store
	                    $store =$model->store($a_code, $website);
	                    if (!empty($store)) {
				            $msg = JText::_( 'Registration Success !' );
			            } else {
				            $msg = JText::_( 'Error Saving Data' );
			            }
	                else:
	                    $msg = JText::_( 'System '.$verify_website['message'] );
	                endif;
	            
	                
	            else:
	                $msg = 'no condition';
	            endif;
	            
		        }else{
		            $a_code = 'localhost';
		            //set store
		            $store =$model->store($a_code, $website_host);
		            if (!empty($store)) {
			            $msg = JText::_( 'Registration Success !' );
		            } else {
			            $msg = JText::_( 'Error Saving Data' );
		            }
		        }
	            
	        }else{
	            $msg = JText::_( 'Invalid buyer name & purchase code !' );
	        }

        }
         
        
        $link = 'index.php?option=com_sms&view=activation';
		$this->setRedirect($link, $msg);
	 }
	 
	  
}
