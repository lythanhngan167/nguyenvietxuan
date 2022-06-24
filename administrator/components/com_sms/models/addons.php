<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsModelAddons extends JModelList
{
	
	function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication(); 
    }
	
	/**
	** Get devision by id
	**/
	function getAddon($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('addon');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
    
    /**
    ** Get List
    **/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_addons'));
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Get Save
	**/
	public function saveaddon($addon_id, $name, $desc, $alias, $is_install, $product_id, $version, $admin, $front, $icon){


		$table = $this->getTable('addon');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($this->_db->getError());
			return false;
		}
		// Store the data.
		if(!empty($addon_id)){$table->id = $addon_id;}
		$table->name       = $name.'';
		$table->desc       = $desc.'';
		$table->alias      = $alias.'';
		$table->is_install = $is_install.'';
		$table->product_id = $product_id.'';
		$table->version    = $version.'';
		$table->admin      = $admin.'';
		$table->front      = $front.'';
		$table->icon       = $icon.'';
		if (!$table->store())
		{
			$this->setError($this->_db->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	}

	/**
	** Get Save
	**/
	public function store(){
		$table =& $this->getTable('addon');
		$data = JRequest::get( 'post' );
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		if (!$table->store())
		{
			$this->setError($user->getError());
			return false;
		}
		$id = $table->id;
		return $id;
	}
	

	/**
	** Get published/unpublished toggle
	**/
	public function toggle($table_name,$cid_name,$field,$value){
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable($table_name);
        if (count( $cids )) {
			foreach($cids as $cid) {
				if($cid){
				    $table->$cid_name = $cid;
					$table->$field = $value;
		            if (!$table->store()) {
			            $this->setError($this->_db->getErrorMsg());
			            return false;
		            }
				}
			}
		}
		return true;
	}
	
	
	
	/**
	** Get delete
	**/
	public function delete(){
		$cids       = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table      = $this->getTable('addon');
		$admin_path = JPATH_COMPONENT_ADMINISTRATOR;
        $site_path  = JPATH_COMPONENT_SITE;

        if (count( $cids )) {
			foreach($cids as $cid) {

				// Get delete file & folder
				$addon_alias = SmsHelper::selectSingleData('alias', 'sms_addons', 'id', $cid);

				// Admin model file delete
				$admin_model_path = $admin_path.'/models/'.$addon_alias.'.php';
				if(JFile::exists($admin_model_path)){
					JFile::delete($admin_model_path);
				}
				// Admin controller file delete
				$admin_controllers_path = $admin_path.'/controllers/'.$addon_alias.'.php';
				if(JFile::exists($admin_controllers_path)){
					JFile::delete($admin_controllers_path);
				}

				// Admin table file delete
				$admin_tables_path = $admin_path.'/tables/'.$addon_alias.'.php';
				if(JFile::exists($admin_tables_path)){
					JFile::delete($admin_tables_path);
				}

				// Admin view folder delete
				$admin_views_path = $admin_path.'/views/'.$addon_alias;
				if(JFolder::exists($admin_views_path)){
					JFolder::delete($admin_views_path);
				}


				// Site model file delete
				$site_model_path = $site_path.'/models/'.$addon_alias.'.php';
				if(JFile::exists($site_model_path)){
					JFile::delete($site_model_path);
				}
				// Site controller file delete
				$site_controllers_path = $site_path.'/controllers/'.$addon_alias.'.php';
				if(JFile::exists($site_controllers_path)){
					JFile::delete($site_controllers_path);
				}

				// Site view folder delete
				$site_views_path = $site_path.'/views/'.$addon_alias;
				if(JFolder::exists($site_views_path)){
					JFolder::delete($site_views_path);
				}
				
				// Get delete data from addon table
				if (!$table->delete( $cid )) {
					$this->setError( $this->_db->getErrorMsg() );
				    return false;
				}
		    }
		}
		return true;
	}
	
}
