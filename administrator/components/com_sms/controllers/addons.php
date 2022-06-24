<?php
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
class SmsControllerAddons extends JControllerLegacy
{
    
    function __construct()
    {
        parent::__construct();
    }

    /**
    ** Get Install
    **/
    function getInstall(){
        $model = $this->getModel('addons');
        
        // Get Admin Path
        $admin_path       = JPATH_COMPONENT_ADMINISTRATOR;
        $site_path       = JPATH_COMPONENT_SITE;


        $msg = '';
        if ($_FILES) {
            $fileName = $_FILES['install_file']['tmp_name'];
            $zip = new my_ZipArchive();

            if ($zip->open($fileName)) {

                // Get SQL file
                $sqlFile          =  $zip->getFromName('sql/addon.sql');

                // Get Addon XML
                $xmlFile          =  $zip->getFromName('addon.xml');
                $setting_data     = simplexml_load_string($xmlFile);
                
                $addon_name       = $setting_data->name;
                $addon_desc       = $setting_data->description;
                $addon_alias      = $setting_data->alias;
                $addon_product_id = $setting_data->product_id;
                $addon_version    = $setting_data->version;
                $addon_admin      = $setting_data->admin;
                $addon_front      = $setting_data->front;
                $addon_icon       = $setting_data->icon;

                if(!empty($addon_name) && !empty($addon_desc) && !empty($addon_alias) && !empty($addon_product_id) && !empty($addon_version)){
                    $exit_addon = SmsHelper::selectSingleData('id', 'sms_addons', 'alias', $addon_alias);
                    if(empty($exit_addon)){
                        $id = $model->saveaddon('', $addon_name, $addon_desc, $addon_alias, '1', $addon_product_id, $addon_version, $addon_admin, $addon_front, $addon_icon);
                        
                    }else{
                        $id = 0;
                        $msg .= 'Addon already installed ! <br>';
                    }
                    
                }else{
                   $id = 0;
                }
                

                if (!empty($id)) {

                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $filename = $zip->getNameIndex($i);
                        $fileinfo = pathinfo($filename);

                        
                        // Admin Files
                        if($fileinfo['dirname']=='admin'){
                            $zip->extractSubdirTo($admin_path, "admin/");
                        }

                        // Site Files
                        if($fileinfo['dirname']=='site'){
                           $zip->extractSubdirTo($site_path, "site/");
                        }

                        // SQL
                        if($fileinfo['dirname']=='sql'){
                            $db = JFactory::getDBO();
                            $config = JFactory::getConfig();
                            $dbprefix = $config->get( 'dbprefix' );
                            
                            $lines = str_replace('#__', $dbprefix, $sqlFile);
                            
                            $objects = explode(';', $lines);

                            foreach ($objects as $line) {
                                if(!empty($line)){
                                    $db->setQuery($line);
                                    $db->execute();
                                }
                            
                                
                            }
                            
                            
                        }
                    }
                    
                    $msg .= 'Addon successfully installed ! ';
                }else {
                    $msg .= 'Addon installed error !';
                }

            }
            $zip->close();

        }
        
        
        $link = 'index.php?option=com_sms&view=addons';
        $this->setRedirect($link, $msg);
    }

    /**
    ** Get Apply
    **/
    function apply(){
        $model = $this->getModel('addons');
        $id =$model->store();
        if ($id) {
            $msg = 'Addon active code save successfully !';
        } else {
            $msg = 'Addon active code saving error !';
        }
        $link = 'index.php?option=com_sms&view=addons&task=addactive&cid[]='. $id;
        $this->setRedirect($link, $msg);
    }
     
    
    /**
    ** Get Save
    **/
    function save(){
        $model = $this->getModel('addons');
        $id =$model->store();
        if ($id) {
            $msg = 'Addon active code save successfully !';
        } else {
            $msg = 'Addon active code saving error !';
        }
        $link = 'index.php?option=com_sms&view=addons';
        $this->setRedirect($link, $msg);
    }


    /**
    ** Get published
    **/
    function publish(){
        $model = $this->getModel('addons');
        if(!$model->toggle('addon','id','status','1')) {
            $msg = 'Addon publising error !';
        } else {
            $msg = 'Addon successfully published !';
        }
        $this->setRedirect( 'index.php?option=com_sms&view=addons', $msg );
    }
    
    
    /**
    ** Get Unpublished
    **/
    function unpublish(){
        $model = $this->getModel('addons');
        if(!$model->toggle('addon','id','status','0')) {
            $msg = 'Addon unpublising error !';
        } else {
            $msg = 'Addon successfully unpublished !';
        }
        $this->setRedirect( 'index.php?option=com_sms&view=addons', $msg );
    }
    
    
    /**
    ** Get Remove
    **/
    function remove(){
        $model = $this->getModel('addons');
        if(!$model->delete()) {
            $msg = 'Addon deleting error !';
        } else {
            $msg = 'Addon successfully delete !';
        }
        $this->setRedirect( 'index.php?option=com_sms&view=addons', $msg );
    }


}


class my_ZipArchive extends ZipArchive
  {
    public function extractSubdirTo($destination, $subdir)
    {
      $errors = array();

      // Prepare dirs
      $destination = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $destination);
      $subdir = str_replace(array("/", "\\"), "/", $subdir);

      if (substr($destination, mb_strlen(DIRECTORY_SEPARATOR, "UTF-8") * -1) != DIRECTORY_SEPARATOR)
        $destination .= DIRECTORY_SEPARATOR;

      if (substr($subdir, -1) != "/")
        $subdir .= "/";

      // Extract files
      for ($i = 0; $i < $this->numFiles; $i++)
      {
        $filename = $this->getNameIndex($i);

        if (substr($filename, 0, mb_strlen($subdir, "UTF-8")) == $subdir)
        {
          $relativePath = substr($filename, mb_strlen($subdir, "UTF-8"));
          $relativePath = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $relativePath);

          if (mb_strlen($relativePath, "UTF-8") > 0)
          {
            if (substr($filename, -1) == "/")  // Directory
            {
              // New dir
              if (!is_dir($destination . $relativePath))
                if (!@mkdir($destination . $relativePath, 0755, true))
                  $errors[$i] = $filename;
            }
            else
            {
              if (dirname($relativePath) != ".")
              {
                if (!is_dir($destination . dirname($relativePath)))
                {
                  // New dir (for file)
                  @mkdir($destination . dirname($relativePath), 0755, true);
                }
              }

              // New file
              if (@file_put_contents($destination . $relativePath, $this->getFromIndex($i)) === false)
                $errors[$i] = $filename;
            }
          }
        }
      }

      return $errors;
    }
  }

