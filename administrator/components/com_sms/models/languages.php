<?php
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

if(!defined('DS')) define('DS', '/');
class SmsModelLanguages extends JModelList
{
	
	/**
    ** Get Constuctor
    **/
    function __construct(){
        parent::__construct();
        $mainframe = JFactory::getApplication();
        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }
	
	/**
    ** Get Language
    **/
	function getLanguages() {

		jimport('joomla.filesystem.folder');
		$path = JLanguage::getLanguagePath(JPATH_COMPONENT_ADMINISTRATOR);
		$dirs = JFolder::folders($path);
		foreach ($dirs as $dir) {
			if(strlen($dir) != 5){
				continue;
			}
			$iniFiles = JFolder::files( $path.'/'.$dir, '.ini', false, false);
			$iniFiles = reset($iniFiles);
			if(empty($iniFiles)){ 
				continue;
			}

			$fileName = JFile::getName($iniFiles);
			$oneLanguage = new stdClass();
			$oneLanguage->language = $dir;
			$oneLanguage->name = substr($fileName, 0, 5);
			$languages[] = $oneLanguage;
		}
		return $languages;
	}
	
	
	/**
     * @param $string
     * @return string
     * Function to check and format the content to UTF-8 Encoding
     */
    private function make_safe_for_utf8_use($string) {
        $encoding = mb_detect_encoding($string, "UTF-8,WINDOWS-1252");
        if ($encoding != 'UTF-8') {
            return iconv($encoding, 'UTF-8//TRANSLIT', $string);
        } else {
            return $string;
        }
    }

    /**
     * @param $array1
     * @param $array2
     * @return array
     */
    private function uniqueValues($array1,$array2) {
        $finalArray = array();
        if (!empty($array1)) {
            foreach ($array1 as $k => $value) {
                foreach ($array2 as $key => $values) {
                    $value2 = str_replace(PHP_EOL,null,$values);
                    if ($value == $value2) {
                        $finalArray[$key] = $value2;
                    }
                }
            }
        }
        $a = array_diff($array1,$finalArray);
        $b = array_diff($finalArray,$array1);
        $c = array_merge($a,$b);
        return array_unique($c);
    }
  
    /**
    ** Get Create Lang
    **/
	function createLanguage() {
	      
        set_time_limit(300);
		jimport('joomla.filesystem.folder');
        $code = JRequest::getString('code');
        $content = JRequest::getString('content');
        $content = $this->make_safe_for_utf8_use($content);
        // $content = htmlentities($content, ENT_NOQUOTES);

        if (!empty($code) && $code!='' && !empty($content) && $content!='') {
            $path = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'.com_sms.ini';
            $sysPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'.com_sms.sys.ini';
						
            JFolder::create(JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code, $mode=0755);

            JFile::write($path, $content);
            $this->fileAppend('', $sysPath);
            $msg = JText::_('LNG_LANGUAGE_SUCCESSFULLY_SAVED', true);
        }
        return $msg;
    }

    /**
    ** Get Save Lang
    **/
    function saveLanguage() {
        set_time_limit(300);
        $app = JFactory::getApplication();
        $code = JRequest::getString('code');
        $content = JRequest::getVar('content', '', 'post', 'string', JREQUEST_ALLOWRAW);
        //$content = htmlentities($content, ENT_NOQUOTES);
        $custom_content = JRequest::getVar('custom_content', '', 'post', 'string', JREQUEST_ALLOWRAW);
        //$custom_content = htmlentities($custom_content, ENT_NOQUOTES);

        if (empty($code)) {
            $app->enqueueMessage(JText::_('LNG_CODE_NOT_SPECIFIED', true));
            return;
        }

        $path = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'.com_sms.ini';
        $newPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code.DS.$code.'-custom.com_sms.ini';

        if(file_exists($path)) {
            if (!empty($content) && $content!='') {
            	$content = $this->make_safe_for_utf8_use($content);
                JFile::write($path, $content);
                $msg = JText::_('LNG_LANGUAGE_SUCCESSFULLY_SAVED', true);
            } else {
            	$empty = ' ';
            	JFile::write($path, $empty);
                $msg = JText::_('LNG_LANGUAGE_SUCCESSFULLY_SAVED', true);
            }
        } else {
            $app->enqueueMessage('File not found : '.$path);
            $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_ERROR_SAVING_LANGUAGE'), 'error');
        }

        $custom_content = $this->make_safe_for_utf8_use($custom_content);
        $contentToSave = preg_split('/([\r\n\t])/', $custom_content);
        $uniqueValues = array_unique($contentToSave);

        switch(file_exists($newPath)) {
            case false:
                if (!empty($custom_content) && $custom_content!='') {
                    foreach ($uniqueValues as $k => $value) {
                        $formatedContent = PHP_EOL.$value;
                        $this->fileAppend($formatedContent, $path);
                    }
                    JFile::write($newPath, $custom_content);
                    $msg = JText::_('LNG_LANGUAGE_SUCCESSFULLY_SAVED', true);
                }
                break;
            case true:
                if (!empty($custom_content) && !empty($uniqueValues)) {
                    $fileValues = file($newPath);
                    $array = $this->uniqueValues($uniqueValues, $fileValues);
                    foreach ($array as $k => $value) {
                        $formatedContentToAppend = PHP_EOL.$value;
                        $this->fileAppend($formatedContentToAppend, $path);
                    }
                    JFile::write($newPath, $custom_content);
                    $msg = JText::_('LNG_LANGUAGE_SUCCESSFULLY_SAVED', true);
                }
                if(empty($custom_content) && $custom_content=='') {
                    $delete = $this->deleteFile($newPath);
                    $msg = JText::_($delete, true);
                }
                break;
            default:
                $app->enqueueMessage('File not found : '.$newPath);
                $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_ERROR_SAVING_LANGUAGE'), 'error');
                break;
        }
        return $msg;
    }
		
    /**
    ** Get File Append
    **/
	function fileAppend($content, $path) {
        $filePath = fopen($path, 'a+') or die(JFactory::getApplication()->enqueueMessage(JText::_('LNG_COULD_NOT_OPEN_THE_FILE'), 'error'));
        fwrite($filePath, $content);
        fclose($filePath);
        return true;
    }

    /**
    ** Get Write File
    **/
    function writeFile($content, $path) {
        $filePath = fopen($path, 'w') or die(JFactory::getApplication()->enqueueMessage(JText::_('LNG_COULD_NOT_OPEN_THE_FILE'), 'error'));
        fwrite($filePath, $content);
        fclose($filePath);
        return true;
    }

    /**
    ** Get Delete File
    **/
    function deleteFile($path) {
        if(unlink($path)) {
            $msg = JText::_('LNG_CUSTOM_LANGUAGE_VALUES_DELETED', true);
        }
        else {
            $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_UNABLE_TO_DELETE_CUSTOM_LANGUAGE_VALUES'), 'error');
        }
        return $msg;
    }

    /**
    ** Get Delete Folder
    **/
    function deleteFolder($code) {
		jimport('joomla.filesystem.folder');
        $languagePath = JFolder::delete(JPATH_COMPONENT_ADMINISTRATOR.DS.'language'.DS.$code);
        if($languagePath) {
            $msg = JText::_('LNG_LANGUAGE_PACK_SUCCESSFULLY_DELETED', true);
        } else {
            $msg = JFactory::getApplication()->enqueueMessage(JText::_('LNG_LANGUAGE_PACK_NOT_DELETED'), 'error');
        }
        return $msg;
    }

	
}
