<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;
require_once JPATH_ADMINISTRATOR . '/components/com_installer/models/update.php';

class SmsModelActivation extends InstallerModelUpdate
{
	
	/**
	** constructor
	**/
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
    }
	
    /**
    ** Get Update
    **/
    public function update($uids, $minimum_stability = JUpdater::STABILITY_STABLE){
		$result = true;
		foreach ($uids as $uid){
			$update = new JUpdate;
			$instance = JTable::getInstance('update');
			$instance->load($uid);
			$update->loadFromXML($instance->detailsurl);
			$update->set('extra_query', $instance->extra_query);
	
			// Install sets state and enqueues messages
			$res = $this->install($update);
	
			if ($res){
				$instance->delete($uid);
			}
	
			$result = $res & $result;
		}
	
		// Set the final state
		$this->setState('result', $result);
	}
	
	/**
	** Get Install
	**/
	private function install($update){	
		
		try{
			$app = JFactory::getApplication();
			if (isset($update->get('downloadurl')->_data)){
				$url = $update->downloadurl->_data;
				$extra_query = $update->get('extra_query');
	
				if ($extra_query){
					if (strpos($url, '?') === false){
						$url .= '?';
					}else{
						$url .= '&amp;';
					}
	
					$url .= $extra_query;
				}
			}else{
				JError::raiseWarning('', JText::_('COM_INSTALLER_INVALID_EXTENSION_UPDATE'));
				return false;
			}
			
			$url .="&pcode=".SmsHelper::getActivation()->p_code."&buyer=".SmsHelper::getActivation()->buyer_name;
	
			$p_file = JInstallerHelper::downloadPackage($url);
			// Was the package downloaded?
			if (!$p_file){
				JError::raiseWarning('', JText::sprintf('COM_INSTALLER_PACKAGE_DOWNLOAD_FAILED', $url));
				return false;
			}
	
			$config		= JFactory::getConfig();
			$tmp_dest	= $config->get('tmp_path');
	
			// Unpack the downloaded package file
			$package	= JInstallerHelper::unpack($tmp_dest . '/' . $p_file);
	
			// Get an installer instance
			$installer	= JInstaller::getInstance();
			$update->set('type', $package['type']);
	
			if(empty($package['dir'])){
				throw new Exception("");
			}
			// Install the package
			if (!$installer->update($package['dir'])){
				// There was an error updating the package
				$msg = JText::sprintf('COM_INSTALLER_MSG_UPDATE_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
				$result = false;
			}else{
				// Package updated successfully
				$msg = JText::sprintf('COM_INSTALLER_MSG_UPDATE_SUCCESS', JText::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
				$result = true;
			}
			
			if(!$result){
				$msg = JText::_('You support & updates plan has expired. Please renew.'); 
			}
			
			// Quick change
			$this->type = $package['type'];
	
			// Set some model state values
			$app->enqueueMessage($msg);
	
			// TODO: Reconfigure this code when you have more battery life left
			$this->setState('name', $installer->get('name'));
			$this->setState('result', $result);
			$app->setUserState('com_installer.message', $installer->message);
			$app->setUserState('com_installer.extension_message', $installer->get('extension_message'));
	
			// Cleanup the install files
			if (!is_file($package['packagefile'])){
				$config = JFactory::getConfig();
				$package['packagefile'] = $config->get('tmp_path') . '/' . $package['packagefile'];
			}
	
			JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
		}
		catch(Exception $e){
			$msg = JText::_('Unable to retrieve update.');
			$app->enqueueMessage($msg);
		}

		return $result;
	}
    
    /**
    ** Get Save
    **/
	public function store($a_code, $website){
		$table = $this->getTable('activation');
		$data = JRequest::get( 'post' );
        
        $envato_user_name = $data['buyer'];
        $envato_purchase_code = $data['purchase_code'];
        
        $ag_pass = $envato_purchase_code . $a_code;
        $ag_code = password_hash($ag_pass, PASSWORD_DEFAULT);;
        
		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if($data['id']){$table->id = $data['id'];}
        $table->buyer_name = $envato_user_name;
        $table->p_code = $envato_purchase_code;
        $table->a_code = $a_code;
        $table->domain_name = $website;
        $table->ag_code = $ag_code;
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
        
	}
    
    /**
    ** Get Current version
    **/
    public function getCurrentVersion(){
		$module = JComponentHelper::getComponent('com_sms');
		$extension = JTable::getInstance('extension');
		$extension->load($module->id);
		$data = json_decode($extension->manifest_cache, true);
		return trim($data['version']);
	}
    
    /**
    ** Get Update version
    **/
    public function getUpdateVersion(){
        $update = new JUpdate;
		$table = JTable::getInstance('Updates');
		$module = JComponentHelper::getComponent('com_sms');
		$this->findUpdates(array($module->id), 0);
		$items = $this->getItems();
		foreach ($items as $i => $item)
			if($module->id==$item->extension_id){//if found return version
			return trim($item->version);
		}
		return 0;
    }
	
	
	
}
