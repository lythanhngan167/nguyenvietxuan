<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;

class SmsHelper
{

	/**
    ** Select Single data
    **/
	public static function selectSingleData($select_field, $table, $where_field, $where_value)
	  {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($select_field);
		$query->from($db->quoteName('#__'.$table));
		$query->where($db->quoteName($where_field)." = ".$db->quote($where_value));

		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	  }

	
	public static function envato_verify_purchase(){
	    $params = JComponentHelper::getParams('com_sms');
        $envato_user_name = $params->get('envato_user_name');
        $envato_purchase_code = $params->get('envato_purchase_code');
	    $buyer				=	$envato_user_name;
		$purchase_code		=	$envato_purchase_code;
		$url = "http://marketplace.envato.com/api/v3/zwebtheme/31uinbno47v27nonholofj92fsuppbb7/verify-purchase:".$purchase_code.".json";
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
        $data = curl_exec($curl);
        $purchase_data = json_decode($data, true);
		if (isset($purchase_data['verify-purchase']['buyer']) && $purchase_data['verify-purchase']['buyer'] == $buyer){
		    return 1;
		}else{
		    return 0;
		}
	}
    
    /**
	** Get Extension Activation
	**/
    public static function getActivation(){
        $db = JFactory::getDBO();
        $query = "SELECT * FROM `#__sms_activation`  ";
        $db->setQuery($query);
        $rows = $db->loadObject();
        return $rows;
    }
    
    public static function getActivationData($purchase_code, $field){
        $db = JFactory::getDBO();
        $query = "SELECT ".$field." FROM `#__sms_activation` WHERE p_code = '".$purchase_code."' ";
        $db->setQuery($query);
        $rows = $db->loadResult();
		return $rows;
		
    }

    /**
    ** Get Extension Activation valid
    **/
    public static function valid(){
        // set the array for testing the local environment
        $whitelist = array( '127.0.0.1', '::1' );

        // check if the server is not in the array
        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $app = JFactory::getApplication();
            $activation = self::getActivation();
            if(!empty($activation)){
                $activation_count = count($activation);
            }else{
                $activation_count = 0;
            }
            
            
            $view = JRequest :: getCmd('view');

            if (!empty($activation_count)){
                $pppccc = $activation->p_code;$acc = $activation->a_code;$aag_code = $activation->ag_code;$verify_sms_pass =$pppccc . $acc;
                if(password_verify($verify_sms_pass, $aag_code)) {
                 //return true;
                    
                }else{
                    if($view!='activation'){
                        $msg ='buyer name & purchase code valid but activation not ok. please contact with support@zwebtheme.com';
                        $link = 'index.php?option=com_sms&view=activation';
                    }
                }  
            }else{
                if($view!='activation'){
                $msg ='Please setting your application. enter valid buyer name & purchase code.  ';
                $link = 'index.php?option=com_sms&view=activation';
                $app->redirect($link, $msg);
                }
            }
        }
       
    }

	/**
	** Get PDF HTML BUILDER
	**/
	public static function buildPDFHTML($html){
		$app = JFactory::getApplication();
	    $params = JComponentHelper::getParams('com_sms');
		$pdf_orientation = $params->get('pdf_orientation');
		$pdf_paper_size = $params->get('pdf_paper_size');
		$pdf_font = $params->get('pdf_font');
		$pdf_custom_font = $params->get('custom_font');
		$pdf_custom_font_name = $params->get('custom_font_name');
		$pdf_custom_font_link = $params->get('custom_font_link');
		
		$pdf_font_family = $pdf_font;
		
		$lang = JFactory::getLanguage();
	    $dir = $lang->get('rtl');
	 
		if($dir == 0) {	
		    $chtml ='<html dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
		}else{
		    $chtml ='<html dir="rtl"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
		}
		$chtml .='<style type="text/css"> @font-face { font-family: "'.$pdf_font_family.'"; font-style: normal; font-weight: normal; ';
		 
		$chtml .=" src: url(".JURI::root()."administrator/components/com_sms/libraries/pdf/dompdf/lib/fonts/".$pdf_font.".ttf) format('truetype'); }</style>";
		 
		$chtml .='<style type="text/css">body{font-family: '.$pdf_font_family.', DejaVu Sans;} .information-div h3, .information-div p {text-align: center;}</style>';
		 
		$chtml .='<link href="'.JURI::root().'components/com_sms/asset/css/sms_style.css" rel="stylesheet">';
		$chtml .='<link href="'.JURI::root().'components/com_sms/asset/css/bootstrap3.css" rel="stylesheet">';
		$chtml .='</head><body>'.$html.'</body>';
		$chtml .='</html>';
		
		return $chtml;
	}

	/**
	** BUILD FIRLD
	**/
	public static function buildField($label, $type, $name, $value, $note,  $placeholder ="", $optinal ="", $enable =""){
	    if($optinal =="required"){
			 $optinal_s ="required"; 
			 $star = '<span class="star"> *</span>'; 
			 $r = 'required="required"';
		}else{
			 $optinal_s ="";
			 $star = '';
			 $r = '';
		}
													 
        $input = '<div class="control-group">';
        if($type=="input"){
		    $input .= '<div class="control-label"><label id="jform_'.$name.'-lbl" class=" '.$optinal_s.'"  for="jform_'.$name.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls"><input type="text" name="'.$name.'" '.$enable.' value="'.$value.'" id="jform_'.$name.'" class="'.$optinal_s.' " '.$r.' /></div>';
        }
			
		if($type=="password"){
			$input .= '<div class="control-label"><label id="jform_'.$name.'-lbl" class=" '.$optinal_s.'"  for="jform_'.$name.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls"><input type="password" name="'.$name.'" '.$enable.' value="'.$value.'" id="jform_'.$name.'" class="'.$optinal_s.' " '.$r.' /></div>';
        }
			
		if($type=="select"){
			$input .= '<div class="control-label"><label id="'.$name.'-lbl" class=" '.$optinal_s.'"  for="'.$name.'">'. JText::_($label).' '.$star .'</label></div>';
			$input .= '<div class="controls">'.$value.'</div>';
        }
			
		$input .= '</div>';
													
	return $input;
    }
 
 
    /**
	** Field builder fieldshow
	**/
    public static function fieldBiodata($fid, $sid, $panel_id, $label, $type, $biodata){
        $db = JFactory::getDBO();
		//value manage
        if(!empty($panel_id)){
		    $query_value = "SELECT data FROM `#__sms_fields_data` WHERE fid = '".$fid."' AND sid = '".$sid."' AND panel_id = '".$panel_id."'";
			$db->setQuery($query_value);
			$value = $db->loadResult();
		}else{
		    $value ='';
		}
		
		if(!empty($biodata)){
		    $result ='<tr><td class="first"> '.$label.':</td> <td class="secound"> '.$value.'</td></tr>';
		}else{
		    $result ='';
		}
	return $result;
    }
 
 
 
    /**
	** Field builder fieldshow
	**/
    public static function fieldshow($fid, $sid, $panel_id, $label, $type, $required){
        $db = JFactory::getDBO();
		 
		//value manage
        if(!empty($panel_id)){
		    $query_value = "SELECT data FROM `#__sms_fields_data` WHERE fid = '".$fid."' AND sid = '".$sid."' AND panel_id = '".$panel_id."'";
			$db->setQuery($query_value);
			$value = $db->loadResult();
		}else{
		    $value ='';
		}
		
		$input = '<div class="control-group">';
		
	    // Field Name make
	    $field_input_name ='field_'.$fid;
		
		// Required manage
	    if($required==1){
			$required_class ="required"; 
			$star = '<span class="star"> *</span>'; 
			$required_code = 'required="required"';
		}else{
			$required_class ="";
			$star = '';
			$required_code = '';
		}
		
		//Input Box
		if($type==1){
			$input .= '<div class="control-label"><label id="jform_'.$fid.'-lbl" class=" '.$required_class.'"  for="jform_'.$fid.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls"><input type="text" name="'.$field_input_name.'"  value="'.$value.'" id="jform_'.$fid.'" class="'.$required_class.' " '.$required_code.' /></div>';
        }
			
		//TextArea Box
		if($type==2){
			$input .= '<div class="control-label"><label id="jform_'.$fid.'-lbl" class=" '.$required_class.'"  for="jform_'.$fid.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls"><textarea cols="" rows="" name="'.$field_input_name.'"  id="jform_'.$fid.'" class="'.$required_class.' " '.$required_code.' style="min-height: 40px;">'.$value.'</textarea></div>';
      
		}
			
		//Check Box
		if($type==3){
			//get option value
			$check_box_option = self::getFieldOption($fid, $sid, $type);
			$check_box_option_values = explode(",",$check_box_option);
			$input .= '<div class="control-label"><label id="jform_'.$fid.'-lbl" class=" '.$required_class.'"  for="jform_'.$fid.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls"><span class="radio-box gender"  >';
				
		    if($required==1){
				$input .= '<input type="hidden" name="'.$field_input_name.'_check"  value="'.$value.'" id="jform_'.$fid.'" class="'.$required_class.' " '.$required_code.' />';
			}
				
			$key =0;
			foreach($check_box_option_values as $option){
				$key++;
				$options = explode("=",$option);
				$option_value = $options[0];
				$option_name = $options[1];
				if (in_array($option_value, explode(",",$value))) {
                    $checked_code ='checked="checked"';
                }else{
				    $checked_code ='';
				}
				 
				if($required==1){
				    $onclick_checkbox =' onclick="changeCheckbox(this.value,\'jform_'.$fid.'\',this.name)"';
				}else{
				    $onclick_checkbox ='';
				}
				 
				$input .= '<label><input type="checkbox" '.$onclick_checkbox.'  class=" "  '.$checked_code.' name="'.$field_input_name.'[]" id="'.$fid.'_'.$key.'"  value="'.$option_value.'"> '.$option_name.' </label>';
		    }
			$input .= '</span></div>';
		}
			
		//Radio Box
		if($type==4){
			//get option value
			$radio_box_option = self::getFieldOption($fid, $sid, $type);
			$radio_option_values = explode(",",$radio_box_option);
			$input .= '<div class="control-label"><label id="jform_'.$fid.'-lbl" class=" '.$required_class.'"  for="jform_'.$fid.'" >'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls"><span class="radio-box gender"  >';
				
		    if($required==1){
				$input .= '<input type="hidden" name="'.$field_input_name.'_check"  value="'.$value.'" id="jform_'.$fid.'" class="'.$required_class.' " '.$required_code.' />';
				$onclick_radio =' onclick="changeRadio(this.value,\'jform_'.$fid.'\')"';
			}else{
				$onclick_radio ='';
			}
				
			foreach($radio_option_values as $radio_option){
				$radio_options = explode("=",$radio_option);
				$roption_value = $radio_options[0];
				$roption_name = $radio_options[1];
				if (in_array($roption_value, explode(",",$value))) {
                    $checked_code ='checked="checked"';
                }else{
				    $checked_code ='';
				}
				 
				$input .= '<label  > <input type="radio" '.$onclick_radio.'   class=" "  '.$checked_code.' name="'.$field_input_name.'"  value="'.$roption_value.'">   '.$roption_name.' </label>';
		    }
		$input .= '</span></div>';
		}
			
		//Select Box
		if($type==5){
			//get option value
			$select_box_option = self::getFieldOption($fid, $sid, $type);
			$select_option_values = explode(",",$select_box_option);
			$input .= '<div class="control-label"><label id="field_'.$fid.'-lbl" class=" '.$required_class.'"  for="field_'.$fid.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls">';
			$select_box_array = array();
            $select_box_array[] = array('value' => '', 'text' => JText::_(' -- Select '.$label.' -- '));
            foreach ($select_option_values as $row) {
				$select_options = explode("=",$row);
				$soption_value = $select_options[0];
				$soption_name = $select_options[1];
                $select_box_array[] = array('value' =>$soption_value, 'text' => JText::_($soption_name));
            }
			$input .=  JHTML::_('select.genericList', $select_box_array, $field_input_name, ' class="'.$required_class.'  inputbox chzn-done " '.$required_code.'  ', 'value', 'text', $value);
            $input .= '</div>';
		}
			
		//Datepicker Box
		if($type==6){
			if($required==1){
				$datereq ="required";
				$valid_class = "validate[\'required\']";
			}else{
				$datereq ='';
				$valid_class='';
			}
				
			$datefield = JHTML::calendar($value,$field_input_name, 'jform_'.$fid, '%Y-%m-%d',array('size'=>'8','maxlength'=>'10',$datereq=>$datereq,'class'=>' date-formp '.$valid_class.' ',));
		
		    $input .= '<div class="control-label"><label id="jform_'.$fid.'-lbl" class=" '.$required_class.'"  for="jform_'.$fid.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls">'.$datefield.'</div>';
        }
			
			
		$input .= '</div>';
		$input .= '<script type="text/javascript">function changeRadio(value,id) { document.getElementById(id).value = value;    } function changeCheckbox(value,id,sid) { var le = document.querySelectorAll(\'input[name="\'+sid+\'"]:checked\').length;  if(le!=0){document.getElementById(id).value = le;}else{document.getElementById(id).value = null;}   }</script>';
													
	return $input;
    }
 
 
    /**
    ** Get Field Option
    **/
    public static function getFieldOption($fid, $sid, $type){
        $db = JFactory::getDBO();
		$query_option_value = "SELECT option_param FROM `#__sms_fields` WHERE type = '".$type."' AND section = '".$sid."' AND id = '".$fid."'";
	    $db->setQuery($query_option_value);
	    $value = $db->loadResult();
	return $value;
    }
 
    /**
    ** Save Field value
    **/
    public static function saveFields($fid, $type, $sid, $field_data, $student_id, $old_id){
        $db = JFactory::getDBO();
		if(is_array($field_data)){
		    $field_data = implode(",", $field_data);
		}

		if(empty($old_id)){
		    //insert code
			$query_field = $db->getQuery(true);
		    $columns = array('fid', 'sid', 'data', 'panel_id');
		    $values = array($db->quote($fid), $db->quote($sid), $db->quote($field_data), $db->quote($student_id));
			$query_field
	            ->insert($db->quoteName('#__sms_fields_data'))
	            ->columns($db->quoteName($columns))
	            ->values(implode(',', $values));
            $db->setQuery($query_field);
		    $result = $db->execute();
		}else{
		    //update code
			$query_field = $db->getQuery(true);
			$fields = array( $db->quoteName('data') . ' = ' . $db->quote($field_data));
			$conditions = array(  $db->quoteName('fid') . ' = ' . $db->quote($fid), $db->quoteName('sid') . ' = ' . $db->quote($sid) , $db->quoteName('panel_id') . ' = ' . $db->quote($student_id));
														
			$query_field->update($db->quoteName('#__sms_fields_data'))->set($fields)->where($conditions);
		    $db->setQuery($query_field);
		    $result = $db->execute();
		}
    }
 
    /**
	** Field builder section id
	**/
	public static function getFieldSectionID($name){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_fields_section'))
            ->where($db->quoteName('name') . ' = '. $db->quote($name));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Get Type name
	**/
	public static function getTypeName($id){
	    $db = JFactory::getDBO();
		$query_result = $db->getQuery(true);
		$query_result
            ->select($db->quoteName(array('type')))
            ->from($db->quoteName('#__sms_fields_type'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query_result);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Field builder field list
	**/
	public static function getFieldList($sid){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'field_name', 'type', 'required' , 'option_param' , 'profile' , 'list' , 'biodata')))
            ->from($db->quoteName('#__sms_fields'))
						->where($db->quoteName('section') . ' = '. $db->quote($sid))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('field_order ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
	return $rows;
	}
 
 
 
    /**
    ** Common LoadResult query
    **/
    public static function getLoadResult($select, $table,$where,$where_value){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array($select)))
            ->from($db->quoteName('#__'.$table))
            ->where($db->quoteName($where) . ' = '. $db->quote($where_value));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
 
    /**
	** Class List
	**/
	public static function getclassList($id, $enable ="", $teacher =""){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'class_name')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        $class_array = array();
        
        if(!empty($teacher)){
            $values = explode(",", $id);
        }
        
        $class_array[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_CLASS'));
        foreach ($rows as $row) {
            if(!empty($teacher)){
                if (in_array($row->id, $values)) {
                    $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
                }
            }else{
                $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
            }
            
            
        }
		$class =  JHTML::_('select.genericList', $class_array, 'class', ' class="required  inputbox chzn-done " required="required" '.$enable.'  ', 'value', 'text', $id);
    return $class;
	}
	
	/**
	** Section List
	**/
	public static function getsectionList($id, $enable ="" , $teacher =""){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'section_name')))
            ->from($db->quoteName('#__sms_sections'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        
        
        if(!empty($teacher)){
            $values = explode(",", $id);
        }
        
        $sections = array();
        $sections[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_SECTION'));
        foreach ($rows as $row) {
            if(!empty($teacher)){
            if (in_array($row->id, $values)) {
                $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
            }
            }else{
                $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
            }
            
        }
	    $section =  JHTML::_('select.genericList', $sections, 'section', 'class=" required inputbox  chzn-done" required="required" '.$enable.' ', 'value', 'text', $id);
    return $section;
	}
	
	/**
	** Year List
	**/
	public static function getyearList($id, $enable =""){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'year')))
            ->from($db->quoteName('#__sms_academic_year'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $years = array();
        $years[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_YEAR'));
        foreach ($rows as $row) {
            $years[] = array('value' => $row->id, 'text' => JText::_(' '.$row->year));
        }
		$year =  JHTML::_('select.genericList', $years, 'year', 'class=" required inputbox chzn-done " required="required" '.$enable.' ', 'value', 'text', $id);
    return $year;
	}
	
    /**
	** Division List
	**/
	public static function getdivisionList($id, $enable =""){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'division_name')))
            ->from($db->quoteName('#__sms_division'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $divisions = array();
        $divisions[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_DIVISION'));
        foreach ($rows as $row) {
            $divisions[] = array('value' => $row->id, 'text' => JText::_(' '.$row->division_name));
        }
			$division =  JHTML::_('select.genericList', $divisions, 'division', 'class="required  inputbox chzn-done " required="required" '.$enable.' ', 'value', 'text',$id);
    return $division;
	}
	
	/**
	** Subject List
	**/
	public static function getsubjectList($id, $enable ="", $teacher =""){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id', 'subject_name')))
            ->from($db->quoteName('#__sms_subjects'))
            ->where($db->quoteName('published') . ' = '. $db->quote('1'))
            ->order('id ASC');	
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        
        if(!empty($teacher)){
            $values = explode(",", $id);
        }
        
        $subjects = array();
        $subjects[] = array('value' => '', 'text' => JText::_('COM_SMS_SELECT_SUBJECT'));
        foreach ($rows as $row) {
            if(!empty($teacher)){
            if (in_array($row->id, $values)) {
                $subjects[] = array('value' => $row->id, 'text' => JText::_(' '.$row->subject_name));
            }
            }else{
                $subjects[] = array('value' => $row->id, 'text' => JText::_(' '.$row->subject_name));
            }
            
        }
	    $subject_list =  JHTML::_('select.genericList', $subjects, 'subject', 'class="required  inputbox chzn-done " required="required" '.$enable.' ', 'value', 'text',$id);
    return $subject_list;
	}
	
	/**
	** USER GROUP
	**/
	public static function checkGroup($id){
	    $db =JFactory::getDBO();
		$check_group = $db->getQuery(true);
		$check_group
            ->select($db->quoteName(array('group_id')))
            ->from($db->quoteName('#__user_usergroup_map'))
            ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
        $db->setQuery( $check_group);
	    $user_group_id= $db->loadResult(); 
																	
        $check_group_title = $db->getQuery(true);
		$check_group_title
            ->select($db->quoteName(array('title')))
            ->from($db->quoteName('#__usergroups'))
            ->where($db->quoteName('id') . ' = '. $db->quote($user_group_id));
		$db->setQuery( $check_group_title);
	    $title= $db->loadResult(); 
	return  $title;
	}
	
	/**
	** Student Name
	**/
	public static function getStudentname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_students'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Teacher Name
	**/
	public static function getTeachername($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_teachers'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Teacher ID
	**/
	public static function getTeacherIDbyUserid($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('id')))
            ->from($db->quoteName('#__sms_teachers'))
            ->where($db->quoteName('user_id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Class Name
	**/
	public static function getClassname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('class_name')))
            ->from($db->quoteName('#__sms_class'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Student Year
	**/
	public static function getAcademicYear($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('year')))
            ->from($db->quoteName('#__sms_academic_year'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Division Name
	**/
	public static function getDivisionname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('division_name')))
            ->from($db->quoteName('#__sms_division'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
	/**
	** Section Name
	**/
	public static function getSectionname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('section_name')))
            ->from($db->quoteName('#__sms_sections'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}

	
	/**
	** Subject Name
	**/
	public static function getSubjectname($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('subject_name')))
            ->from($db->quoteName('#__sms_subjects'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
	return $data;
	}
	
    // Get Profile photo src
    public static function getProfilePhoto($photo_data, $section){
        if(!empty($photo_data)){
            $path = "components/com_sms/photo/".$section."/";
		    $img = $path.$photo_data;
		}else {
			$path = "components/com_sms/photo/";
			$photo="photo.png";
			$img = $path.$photo;
		}
    return $img;
    }
    

    public static function getStudentClass($id){
	    $db = JFactory::getDBO();
	    $query_result = "SELECT class FROM `#__sms_students` WHERE id = '".$id."'";
		$db->setQuery($query_result);
		$data = $db->loadResult();
	return $data;
	}

    // Get multi-value genarator
    public static function getMultivalue($input, $function){
        $output ='';
        $values = explode(",", $input);
		$total_value = count($values);
		foreach ($values as $v=> $value) {
            $output .= self::$function($value);
			if ($v < ($total_value - 1)) {
                $output .=', ';
            }
        }
        return $output;
    }

    // Get multi-value genarator
    public static function getStudents($input, $function, $list =''){
        $output ='';
        $values = explode(",", $input);
		$total_value = count($values);
		if(!empty($list)){
			$output .= '<ul class="profile-menu">';
           foreach ($values as $v=> $value) {
			$link 		= JRoute::_( 'index.php?option=com_sms&view=parents&task=studentprofile&cid='.$value.'' );
            $output .= '<li><a href="'.$link.'">'.self::$function($value).'</a></li>';
			
          }
          $output .= '</ul>';
		}else{
		  foreach ($values as $v=> $value) {
			$link 		= JRoute::_( 'index.php?option=com_sms&view=parents&task=studentprofile&cid='.$value.'' );
            $output .= '<a href="'.$link.'">'.self::$function($value).'</a>';
			if ($v < ($total_value - 1)) {
                $output .=', ';
            }
          }
        }
        return $output;
    }

    /**
    ** Get currency format
    **/
    public static function getCurrency($value){
        $params    = JComponentHelper::getParams('com_sms');
        $currency = $params->get('currency_sign');
        $currency_decimal = $params->get('currency_decimal');
        $currency_position = $params->get('currency_position');
        if ($currency_position == 'after') {
            $currency_value = number_format($value, $currency_decimal).''.$currency;
        }else{
            $currency_value = $currency.''.number_format($value, $currency_decimal);
        }
        return $currency_value;
    }
	

    /**
    ** Get Profile
    **/
	function profile(){
	    $user = JFactory::getUser();
        $uid =$user->get( 'id' );
	                  
		jimport('joomla.application.component.model');
        JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_sms/models');
						
	    //LOAD COMMON MODEL
	    $common_model = JModelLegacy::getInstance( 'common', 'SmsModel' );
	    $group_title = $common_model->checkGroup($uid);
	          
        //TEACHER DATA
        if($group_title=="Teachers"){
            $model = JModelLegacy::getInstance( 'teachers', 'SmsModel' );
            $teacher_id = $model->getTeacherID($uid);
            $teacher = $model->getTeacher($teacher_id);
            $img_src = self::getProfilePhoto($teacher->photo, 'teachers');
            $name = $teacher->name;
		    $degignation = $teacher->designation;
            $teacher_class = self::getMultivalue($teacher->class, 'getClassname');
            $teacher_subjects = self::getMultivalue($teacher->subject, 'getSubjectname');
        }
				
										
										
		//STUDENT DATA
		if($group_title=="Students"){
			$model = JModelLegacy::getInstance( 'students', 'SmsModel' );
			$student_id = self::selectSingleData('id', 'sms_students', 'user_id', $uid);
			$student = $model->getStudent($student_id);
	        if(!empty($student->photo)){
                $path = "components/com_sms/photo/students/";
				$photo = $student->photo;
				$img_src = $path.$photo;
			}else {
				$path = "components/com_sms/photo/";
				$photo="photo.png";
				$img_src = $path.$photo;
			}

			$name = $student->name;
			$roll = $student->roll;
			$class_id = $student->class;
			$class_name = self::selectSingleData('class_name', 'sms_class', 'id', $class_id);
			$section_id = $student->section;
			$section_name = self::selectSingleData('section_name', 'sms_sections', 'id', $section_id);
			$division_id = $student->division;
			$division_name = self::selectSingleData('division_name', 'sms_division', 'id', $division_id);
		}
										
										
		//PARENT DATA
		if($group_title=="Parents"){
			$model = JModelLegacy::getInstance( 'parents', 'SmsModel' );
			$parent_id = $model->getParentID($uid);
			$parent = $model->getParent($parent_id);
	        if(!empty($parent->photo)){
                $path = "components/com_sms/photo/parents/";
				$photo = $parent->photo;
				$img_src = $path.$photo;
			}else {
				$path = "components/com_sms/photo/";
				$photo="photo.png";
				$img_src = $path.$photo;
			}
			$name = $parent->name;
			$student_id = $parent->student_id;
            $student_name = self::getStudents($parent->student_id, 'getStudentName','1');
		}
										
		$userToken = JSession::getFormToken();
	    $logout_redirectUrl = JRoute::_('index.php?option=com_sms');
		$logout_return_link = base64_encode($logout_redirectUrl);
		
		$log_out_form ='<form action="" method="post">';
		$log_out_form .='<input type="submit" value="'.JText::_('DEFAULT_LOGOUT').'" class="btn " />';
		$log_out_form .='<input type="hidden" name="option" value="com_sms" />';
		$log_out_form .='<input type="hidden" name="task" value="sms.logout" />';
		$log_out_form .='<input type="hidden" name="controller" value="sms" />';
		$log_out_form .='<input type="hidden" name="return" value="'.$logout_return_link.'" />';
		$log_out_form .='<input type="hidden" name="'.$userToken.'" value="1" />';
		$log_out_form .='</form>';
										
	    $show = '<div class="avator">'.$log_out_form.'</div>';
		$show .= '<div class="avator"><img src="'.$img_src.'" alt="'.$name.'" width="150px" height="150px" /></div>';
	    $show .='<h1 class="dash-title">'.$name.'</h1>';
		if(!empty($degignation)){$show .='<p style="text-align: center;"> <b>'.$degignation.'</b></p>';}
        
		// Class 
        if(!empty($teacher_class)){
            $show .='<p ><label>'.JText::_('LABEL_TEACHER_CLASS').':</label> <b>'.$teacher_class.'</b></p>';
        }
    
		if(!empty($teacher_subjects)){$show .='<p ><label>'.JText::_('LABEL_TEACHER_SUBJECT').':</label> <b>'.$teacher_subjects.'</b></p>';}
		if(!empty($student_name)){$show .='<p style="text-align:center;margin-top: 25px;" ><label>'.JText::_('LABEL_PARENT_STUDENT_NAME').'</label> <b>'.$student_name.'</b></p>';}
		//if(!empty($student_class_name)){$show .='<p ><label>'.JText::_('LABEL_STUDENT_CLASS').':</label> <b>'.$student_class_name.'</b></p>';}
		if(!empty($roll)){$show .='<p ><label>'.JText::_('LABEL_STUDENT_ROLL').': </label><b>'.$roll.'</b></p>';}
        if(!empty($class_name)){$show .='<p ><label>'.JText::_('LABEL_STUDENT_CLASS').': </label><b>'.$class_name.'</b></p>';}
		//if(!empty($section_name)){$show .='<p ><label>'.JText::_('LABEL_STUDENT_SECTION').': </label><b>'.$section_name.'</b></p>';}
		if(!empty($division_name) && $division_name!="None"){$show .='<p ><label>'.JText::_('LABEL_STUDENT_DIVISION').':</label><b>'.$division_name.'</b></p>';}
	return $show;
	}
	
	/**
	** SMS Menu
	**/
	public static function addSubmenu($vName)
	{
		$user = JFactory::getUser();
        $uid = $user->get( 'id' );
	                  
		jimport('joomla.application.component.model');
        JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_sms/models');
						
	    //LOAD COMMON MODEL
	    $common_model = JModelLegacy::getInstance( 'common', 'SmsModel' );
										
		//GET TOTAL MESSAGE
		$message_id = $common_model->unreadMessage($uid);
		if(!empty($message_id)){
			$total_un_read_message = $common_model->unreadMessage($uid);
			$unread ='<b style="color: green;">('.$total_un_read_message.' unread)</b> ';
		}else{
			$total_un_read_message ="";
			$unread ='';
		}
	
	    $group_title = $common_model->checkGroup($uid);

	    // Get Teacher Menu
		if($group_title=="Teachers"){

		    if($vName=="teachers" || $vName=="message" || $vName=="attendance" || $vName=="marks" || $vName=="payments" || $vName=="editteachers" || $vName == "result" || $vName == "profile"){
			    JHtmlSidebar::addEntry( 
			    	'<span class="fa fa-tachometer"></span> '.JText::_('MENU_DASHBOARD'),
			    	'index.php?option=com_sms&view=teachers',
			    	$vName == 'teachers'
			    );
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-user"></span> '.JText::_('MENU_MY_PROFILE'),
					'index.php?option=com_sms&view=teachers&task=profile',
					$vName == 'profile'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-envelope-open"></span> '.JText::_('MENU_MESSAGE').$unread,
					'index.php?option=com_sms&view=message',
					$vName == 'message'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-check-square"></span> '.JText::_('MENU_MANAGE_ATTENDANCE'),
					'index.php?option=com_sms&view=attendance',
					$vName == 'attendance'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-star"></span> '.JText::_('MENU_MANAGE_MARKSHEET'),
					'index.php?option=com_sms&view=marks',
					$vName == 'marks'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-file-text-o"></span> '.JText::_('MENU_RESULTS'),
					'index.php?option=com_sms&view=result',
					$vName == 'result'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-money"></span> '.JText::_('MENU_MANAGE_PAYMENTS'),
					'index.php?option=com_sms&view=payments',
					$vName == 'payments'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-pencil-square"></span> '.JText::_('MENU_PROFILE_SETTING'),
					'index.php?option=com_sms&view=editteachers',
					$vName == 'editteachers'
				);
		    }
		}
		
		// Get Student Menu
		if($group_title=="Students"){
		    if($vName=="students" || $vName=="message" || $vName=="editstudents" || $vName=="marks" || $vName=="payments" || $vName == "result" || $vName == "attendancereport" || $vName == "profile"){
			    JHtmlSidebar::addEntry( 
			    	'<span class="fa fa-tachometer"></span> '.JText::_('MENU_DASHBOARD'),
			    	'index.php?option=com_sms&view=students',
			    	$vName == 'students'
			    );
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-user"></span> '.JText::_('MENU_MY_PROFILE'),
					'index.php?option=com_sms&view=students&task=profile',
					$vName == 'profile'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-envelope-open"></span> '.JText::_('MENU_MESSAGE').$unread,
					'index.php?option=com_sms&view=message',
					$vName == 'message'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-check-square"></span> '.JText::_('MENU_ATTENDANCE_REPORT'),
					'index.php?option=com_sms&view=attendancereport',
					$vName == 'attendancereport'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-file-text-o"></span> '.JText::_('MENU_RESULTS'),
					'index.php?option=com_sms&view=result',
					$vName == 'result'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-money"></span> '.JText::_('MENU_PAYMENTS'),
					'index.php?option=com_sms&view=payments',
					$vName == 'payments'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-pencil-square"></span> '.JText::_('MENU_PROFILE_SETTING'),
					'index.php?option=com_sms&view=editstudents',
					$vName == 'editstudents'
				);
		    }
		}
		
		// Get Parent Menu								
		if($group_title=="Parents"){
		    if($vName=="parents" || $vName=="message" || $vName=="editparent" || $vName=="marks" || $vName=="payments" || $vName == "result" || $vName == "attendancereport" || $vName == "profile" || $vName == "studentprofile"){
			    JHtmlSidebar::addEntry( 
			    	'<span class="fa fa-tachometer"></span> '.JText::_('MENU_DASHBOARD'),
			    	'index.php?option=com_sms&view=parents',
			    	$vName == 'parents'
			    );

				JHtmlSidebar::addEntry( 
					'<span class="fa fa-user"></span> '.JText::_('MENU_MY_PROFILE'),
					'index.php?option=com_sms&view=parents&task=profile',
					$vName == 'profile'
				);

				JHtmlSidebar::addEntry( 
					'<span class="fa fa-envelope-open"></span> '.JText::_('MENU_MESSAGE').$unread,
					'index.php?option=com_sms&&view=message',
					$vName == 'message'
				);

				JHtmlSidebar::addEntry( 
					'<span class="fa fa-check-square"></span> '.JText::_('MENU_ATTENDANCE_REPORT'),
					'index.php?option=com_sms&view=attendancereport',
					$vName == 'attendancereport'
				);

				JHtmlSidebar::addEntry( 
					'<span class="fa fa-file-text-o"></span> '.JText::_('MENU_RESULTS'),
					'index.php?option=com_sms&view=result',
					$vName == 'result'
				);

				JHtmlSidebar::addEntry( 
					'<span class="fa fa-money"></span> '.JText::_('MENU_PAYMENTS'),
					'index.php?option=com_sms&view=payments',
					$vName == 'payments'
				);
				JHtmlSidebar::addEntry( 
					'<span class="fa fa-pencil-square"></span> '.JText::_('MENU_PROFILE_SETTING'),
					'index.php?option=com_sms&view=editparent',
					$vName == 'editparent'
				);
		    }
		}


		// Get Addon Menu
		$db = JFactory::getDBO();
		$addon_query = $db->getQuery(true);
		$addon_query
            ->select('*')
            ->from($db->quoteName('#__sms_addons'))
            ->where($db->quoteName('status') . ' = '. $db->quote('1'))
            ->where($db->quoteName('front') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($addon_query);
        $addon_list = $db->loadObjectList();

        foreach ($addon_list as $key => $addon) {
        	$addon_icon = $addon->icon;
        	$addon_title = $addon->name;
        	$addon_view = $addon->alias;

        	JHtmlSidebar::addEntry(
				'<span class="'.$addon_icon.'"></span> '.$addon_title,
				'index.php?option=com_sms&view='.$addon_view.'',
				$vName == $addon_view
			);
        }
		
	}

	
	
}