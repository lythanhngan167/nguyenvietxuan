<?php
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */
 
defined('_JEXEC') or die;

class SmsHelper
{

    /**
    ** Get ZIP download
    **/
    public static function getZip($path, $filename){
        $rootPath = realpath($path);
        $zipname = $filename;
        
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zipname, ZipArchive::CREATE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        
        header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=$zipname"); 
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile("$zipname");
        unlink($zipname);
        exit;
    }

    /**
    ** Select Single data
    **/
    public static function selectSingleData($select_field, $table, $where_field, $where_value){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($select_field);
        $query->from($db->quoteName('#__'.$table));
        $query->where($db->quoteName($where_field)." = ".$db->quote($where_value));
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    /**
    ** Get Student Data
    **/
    public static function getStudentData($field, $class_id, $roll){
        $db = JFactory::getDBO();
        $query = "SELECT ".$field." FROM `#__sms_students` WHERE class = '".$class_id."' AND roll='".$roll."'";
        $db->setQuery($query);
        $data = $db->loadResult();
        return $data;
    }
    
    /**
    ** Schools Note Pad Designs ( Get PDF Header) 
    **/
    public static function padHeader($pad_title, $class='', $print =''){
        //GET SCHOOLS DATA
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_sms');
        $schools_logo = $params->get('sms_logo');
        $logo_position = $params->get('logo_position');
        $schools_name = $params->get('schools_name');
        $schools_address = $params->get('schools_address');
        $schools_phone = $params->get('schools_phone');
        $schools_fax = $params->get('schools_fax');
        $schools_email = $params->get('schools_email');
        $schools_website = $params->get('schools_web');
         
        $show_school_name = $params->get('show_school_name');
        $show_school_address = $params->get('show_school_address');
        $show_school_email = $params->get('show_school_email');
        $show_school_phone = $params->get('show_school_phone');
        $show_school_fax = $params->get('show_school_fax');
        $show_school_website = $params->get('show_school_website');
        $show_custom_header_text = $params->get('show_custom_header_text');
        $header_bg_color = $params->get('header_bg_color');
        $header_text_color = $params->get('header_text_color');
        $show_bg_image = $params->get('show_bg_image');
        $header_bg_image = $params->get('header_bg_image');
         
        if(!empty($show_bg_image)):
           $header_bg ="background:".$header_bg_color.";";
        else:
           $header_bg ='background:'.$header_bg_color.';';
        endif;
         
        if($logo_position=='left'):
    	    $logo_div_class = 'span3 ';
            $info_div_class = 'span9  ';
            $style ='text-align: left;';
            $style_logo ='text-align: center;';
        elseif($logo_position=='right'):
            $logo_div_class = 'span3 ';
            $info_div_class = 'span9 ';
            $style ='text-align: right;';
            $style_logo ='text-align: center;';
        else:
            $logo_div_class = 'span12  ';
            $info_div_class = 'span12 ';
            $style ='text-align: center;';
            $style_logo ='text-align: center;';
        endif;
         
        $onclick_link ="'printableArea'";
        $header_con ='<p><input type="button" id="print" onclick="printDiv('.$onclick_link.')" class="btn btn-small"  style="border: none;margin-left: 10px;" value="Print" /> </p>';
	      
        $header ='';
        $header .= '<div class="pdf-header" style="'.$header_bg.' color:'.$header_text_color.';margin-bottom: 0px;padding: 0;">';
        if(!empty($header_bg_image)):
        $header .='<img alt="" src="../'.$header_bg_image.'" class="overlay-img" />';
        endif;
        $header .= '<div class="row-fluid " style="padding: 20px 0;">';
        
        // SMS LOGO
        $sms_logo ='<div class="'. $logo_div_class.'" style="'.$style_logo.'" > <img alt="" src="../'.$schools_logo.'" /></div>';
         
        // SMS INFO
        $sms_info = '<div class="'.$info_div_class.'" style="'.$style.'" >';
        if(!empty($show_school_name)){ $sms_info .= '<h3> '.$schools_name.'</h3>'; }
        if(!empty($show_school_address)){ $sms_info .= '<p> '.$schools_address.'</p>'; }
        if(!empty($show_school_email)){ $sms_info .= '<p> <b>'.JText::_('SMS_EMAIL').':</b> '.$schools_email.'</p>'; }
        if(!empty($show_school_phone)){ $sms_info .= '<p> <b>'.JText::_('SMS_PHONE').':</b> '.$schools_phone.'</p>'; }
        if(!empty($show_school_fax)){ $sms_info .= '<p> <b>'.JText::_('SMS_FAX').':</b>  '.$schools_fax.'</p>'; }
        if(!empty($show_school_website)){ $sms_info .= '<p> <b>'.JText::_('SMS_WEBSITE').':</b>  '.$schools_website.'</p>'; }
        if(!empty($show_custom_header_text)){ $sms_info .= '<p> '.$show_custom_header_text.'</p>'; }
        $sms_info .= '</div>';
         
        if($logo_position=='left'):
	        $header .= $sms_logo;
            $header .= $sms_info;
        elseif($logo_position=='right'):
            $header .= $sms_info;   
            $header .= $sms_logo;
        else:
            $header .= $sms_logo;
            $header .= $sms_info;
        endif;
         
         
        $header .= '</div>';
        $header .= '</div>'; 
         
        $header .= '<div class="row-fluid caption-area">'; 
        $header .= '<div class="span12 text-center">';
        $header .= '<p> <b>'.$pad_title.'</b></p>';
        if(!empty($class)){
        $header .= '<p> '.JText::_('LABEL_STUDENT_CLASS').' - <b>'.self::getClassname($class).'</b> </p>';
            }
        if(empty($print)){
        $header .= $header_con;
        }
        $header .= '</div>';
        $header .= '</div>';
        
        return $header;
	}
    
    /**
    ** Schools Note Pad Designs ( Get PDF Footer) 
    **/
    public static function padFooter(){
        //GET SCHOOLS DATA
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_sms');
        $show_logo_in_footer = $params->get('show_logo_in_footer');
        $sms_footer_logo = $params->get('sms_footer_logo');
        $footer_logo_position = $params->get('footer_logo_position');
        $schools_name = $params->get('schools_name');
        $schools_address = $params->get('schools_address');
        $schools_phone = $params->get('schools_phone');
        $schools_fax = $params->get('schools_fax');
        $schools_email = $params->get('schools_email');
        $schools_website = $params->get('schools_web');
         
        $show_school_name_in_footer = $params->get('show_school_name_in_footer');
        $show_school_address_in_footer = $params->get('show_school_address_in_footer');
        $show_school_email_in_footer = $params->get('show_school_email_in_footer');
        $show_school_phone_in_footer = $params->get('show_school_phone_in_footer');
        $show_school_fax_in_footer = $params->get('show_school_fax_in_footer');
        $show_school_website_in_footer = $params->get('show_school_website_in_footer');
        $show_custom_header_text_in_footer = $params->get('show_custom_header_text_in_footer');
        $bg_color_in_footer = $params->get('bg_color_in_footer');
        $text_color_in_footer = $params->get('text_color_in_footer');
        $show_bg_image_in_footer = $params->get('show_bg_image_in_footer');
        $bg_image_in_footer = $params->get('bg_image_in_footer');
        
        if(!empty($show_bg_image_in_footer)):
           $footer_bg ="background:".$bg_color_in_footer.";";
        else:
           $footer_bg ='background:'.$bg_color_in_footer.';';
        endif;
         
        if($footer_logo_position=='left' && !empty($show_logo_in_footer)):
    	    $logo_div_class = 'span3 ';
            $info_div_class = 'span9 ';
            $style ='text-align: left;';
            $style_logo ='text-align: center;';
        elseif($footer_logo_position=='right' && !empty($show_logo_in_footer)):
            $logo_div_class = 'span3 ';
            $info_div_class = 'span9 ';
            $style ='text-align: right;';
            $style_logo ='text-align: center;';
        else:
            $logo_div_class = 'span12 ';
            $info_div_class = 'span12 ';
            $style ='text-align: center;';
            $style_logo ='text-align: center;';
        endif;
         
        $onclick_link ="'printableArea'";
        $header_con ='<p><input type="button" id="print" onclick="printDiv('.$onclick_link.')" class="btn btn-small"  style="border: none;margin-left: 10px;" value="Print" /> </p>';
	      
        $footer ='';
        $footer .= '<div class="pdf-footer" style="'.$footer_bg.' color:'.$text_color_in_footer.';padding: 0px 0;margin-top: 0px;">';
        if(!empty($show_bg_image_in_footer)):
        $footer .='<img alt="" src="../'.$bg_image_in_footer.'" class="overlay-img" />';
        endif;
        $footer .= '<div class="row-fluid" style="padding: 20px 0;">';
        if(!empty($show_logo_in_footer)):
        // SMS LOGO
        $sms_logo = '<div class="'. $logo_div_class.'" style="'.$style_logo.'"> <img alt="" src="../'.$sms_footer_logo.'" /></div>';
        else:
         $sms_logo = '';
        endif;
        
        // SMS INFO
        $sms_info = '<div class="'.$info_div_class.'" style="'.$style.'" >';
        if(!empty($show_school_name_in_footer)){ $sms_info .= '<h3> '.$schools_name.'</h3>'; }
        if(!empty($show_school_address_in_footer)){ $sms_info .= '<p> '.$schools_address.'</p>'; }
        if(!empty($show_school_email_in_footer)){ $sms_info .= '<p> <b>'.JText::_('SMS_EMAIL').':</b> '.$schools_email.'</p>'; }
        if(!empty($show_school_phone_in_footer)){ $sms_info .= '<p> <b>'.JText::_('SMS_PHONE').':</b> '.$schools_phone.'</p>'; }
        if(!empty($show_school_fax_in_footer)){ $sms_info .= '<p> <b>'.JText::_('SMS_FAX').':</b>  '.$schools_fax.'</p>'; }
        if(!empty($show_school_website_in_footer)){ $sms_info .= '<p> <b>'.JText::_('SMS_WEBSITE').':</b>  '.$schools_website.'</p>'; }
        if(!empty($show_custom_header_text_in_footer)){ $sms_info .= '<p> '.$show_custom_header_text_in_footer.'</p>'; }
        $sms_info .= '</div>';
        
        
        if($footer_logo_position=='left' && !empty($show_logo_in_footer)):
	       $footer .= $sms_logo;
           $footer .= $sms_info;
        elseif($footer_logo_position=='right' && !empty($show_logo_in_footer)):
           $footer .= $sms_info;   
           $footer .= $sms_logo;
        else:
           $footer .= $sms_logo;
           $footer .= $sms_info;
        endif;
        
        $footer .= '</div>';
        $footer .= '</div>'; 
        
        return $footer;
	}
    
    
	/**
    ** Get Envato Verify Purchase
    **/
	public static function envato_verify_purchase($envato_user_name, $envato_purchase_code){
	    $buyer				=	$envato_user_name;
		$purchase_code		=	$envato_purchase_code;
		// Query using CURL:
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$purchase_code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => array(
                "Authorization: bearer wbmooMDbbMZh5Y9TZ4qzHrY0LMD5FJ9o",
                "User-Agent: Purchase code verification script"
            )
        ));

        // Execute CURL with warnings suppressed:

        $response = @curl_exec($ch);
        $purchase_data = json_decode($response, true);
        
		if (isset($purchase_data['buyer']) && $purchase_data['buyer'] == $buyer) {
		    return 1;
        }else{
		    return 0;
		}
	}

    /**
    ** Get Domain name
    **/
    function getDomain($url){
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $url, $matches);
        return $matches[0];
    }
    
    /**
    ** Get Envato Verify Website
    **/
    public static function verify_website($envato_purchase_code){
		$purchase_code		=	$envato_purchase_code;
		$url = "http://api.codervex.com/api/?method=verify-website&api-format=json&purchase-code=".$purchase_code."";
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
        $data = curl_exec($curl);
        $purchase_data = json_decode($data, true);
        return $purchase_data;
	}
    
    /**
    ** Get API Store Website 
    **/
    public static function store_website($purchase_code, $website, $buyer, $version){
		$url = "http://api.codervex.com/api/?method=insert-website&api-format=json&purchase-code=".$purchase_code."&website=".$website."&buyer=".$buyer."&sms-version=".$version."";
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
        $data = curl_exec($curl);
        $purchase_data = json_decode($data, true);
        return $purchase_data;
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
    
    /**
    ** Get Activation Data
    **/
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
    ** Get Build PDF HTML
    **/
	public static function buildPDFHTML($html){
    	$app                  = JFactory::getApplication();
        $params               = JComponentHelper::getParams('com_sms');
    	$pdf_orientation      = $params->get('pdf_orientation');
    	$pdf_paper_size       = $params->get('pdf_paper_size');
    	$pdf_font             = $params->get('pdf_font');
    	$pdf_custom_font      = $params->get('custom_font');
    	$pdf_custom_font_name = $params->get('custom_font_name');
    	$pdf_custom_font_link = $params->get('custom_font_link');
    	$pdf_font_family      = $pdf_font;
		
        $chtml ='<html ><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        $chtml .='<style type="text/css">html { margin: 25% 0px 10% 0px; padding: .0in;} body {  padding: .0in;margin: 0in;} @font-face { font-family: "'.$pdf_font_family.'"; font-style: normal; font-weight: normal; ';
 
        $chtml .=" src: url(".JURI::root()."administrator/components/com_sms/libraries/pdf/dompdf/lib/fonts/".$pdf_font.".ttf) format('truetype'); }</style>";
        $chtml .='<style type="text/css">body{font-family: '.$pdf_font_family.', DejaVu Sans;} .information-div h3, .information-div p {text-align: center;}</style>';
        $chtml .='<link href="'.JURI::root().'administrator/components/com_sms/css/sms.css" rel="stylesheet">';
        $chtml .='</head><body id="pdf-body" >'.$html.'</body>';
        $chtml .='</html>';
	    return $chtml;
	}
	
    /**
    ** Get Build Field
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
			$input .= '<div class="control-label"><label id="'.$name.'-lbl" class=" '.$optinal_s.'"  for="'.$name.'">'. JText::_($label).': '.$star .'</label></div>';
			$input .= '<div class="controls">'.$value.'</div>';
        }
		$input .= '</div>';										
	    return $input;
    }
 
 
    /**
	** Get Custom Field Data for Biodata
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
		    $result ='<tr><td> '.$label.':</td> <td> '.$value.'</td></tr>';
		}else{
		   $result ='';
		}
		return $result;
    }
 
 
    /**
	** Get Custom Field for show on form
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
		    }// end loop
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
    ** Get Custom Field as save
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
	** Parent Modal function
	**/
	public static function getModalparent($field_id,$name, $student_id, $label){
        if(!empty($student_id)){
            $student_name = self::getStudentname($student_id);
        }else{
            $student_name = '';
        }
        
	    $modal_link = 'index.php?option=com_sms&view=students&task=modal&field='.$field_id.'&tmpl=component';
		$modal_field ='<div class="input-append">';
        $modal_field .='<input id="'.$field_id.'" class="field-user-input-name" value="'.$student_name.'" placeholder="'.JText::_($label).'" readonly="" type="text" aria-invalid="false">';
		$modal_field .='<input id="'.$field_id.'_id" type="hidden" name="'.$name.'" data-onchange="" value="'.$student_id.'" />';
        $modal_field .="<a class=\"btn btn-primary modal\" href=\"".$modal_link."\"  title=\"".JText::_($label)."\" rel=\"{size: {x: 600, y: 400}, handler:'iframe'}\">";
        $modal_field .='<span class="icon-user"></span>';
        $modal_field .='</a>';
		$modal_field .='</div>';
        return $modal_field;
	}
    
    /**
	** Class List
	**/
	public static function getclassList($id){
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
        $class_array[] = array('value' => '', 'text' => JText::_(' -- Select Class -- '));
        foreach ($rows as $row) {
            $class_array[] = array('value' => $row->id, 'text' => JText::_(' '.$row->class_name));
        }
		$class =  JHTML::_('select.genericList', $class_array, 'class', ' class="required  inputbox chzn-done " required="required"  ', 'value', 'text', $id);
        return $class;
	}
	
	/**
	** Section List
	**/
	public static function getsectionList($id){
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
        $sections = array();
        $sections[] = array('value' => '', 'text' => JText::_(' -- Select Section -- '));
        foreach ($rows as $row) {
            $sections[] = array('value' => $row->id, 'text' => JText::_(' '.$row->section_name));
        }
		$section =  JHTML::_('select.genericList', $sections, 'section', 'class=" required inputbox  chzn-done" required="required" ', 'value', 'text', $id);
        return $section;
	}
	
    
    /**
	** Year Value
	**/
    public static function getYear($select_field, $where_field, $where_value){
        $db = JFactory::getDBO();
        $query = "SELECT ".$select_field." FROM `#__sms_academic_year` WHERE ".$where_field." = '".$where_value."' ";
        $db->setQuery($query);
        $rows = $db->loadResult();
		return $rows;
    }
    
    
	/**
	** Year List
	**/
	public static function getyearList($id, $optional =''){
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
        $years[] = array('value' => '', 'text' => JText::_(' -- Select Year -- '));
        foreach ($rows as $row) {
            $years[] = array('value' => $row->id, 'text' => JText::_(' '.$row->year));
        }
		$year =  JHTML::_('select.genericList', $years, 'year'.$optional, 'class=" required inputbox chzn-done " required="required" ', 'value', 'text', $id);
        return $year;
	}
	
    /**
	** Division List
	**/
	public static function getdivisionList($id){
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
        $divisions[] = array('value' => '', 'text' => JText::_(' -- Select Division -- '));
        foreach ($rows as $row) {
            $divisions[] = array('value' => $row->id, 'text' => JText::_(' '.$row->division_name));
        }
		$division =  JHTML::_('select.genericList', $divisions, 'division', 'class="required  inputbox chzn-done " required="required" ', 'value', 'text',$id);
        return $division;
	}
	
	/**
	** Subject List
	**/
	public static function getsubjectList($id){
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
        $subjects = array();
        $subjects[] = array('value' => '', 'text' => JText::_(' -- Select Subject -- '));
        foreach ($rows as $row) {
            $subjects[] = array('value' => $row->id, 'text' => JText::_(' '.$row->subject_name));
        }
		$subject_list =  JHTML::_('select.genericList', $subjects, 'subject', 'class="required  inputbox chzn-done " required="required" ', 'value', 'text',$id);
        return $subject_list;
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
	** Student Roll
	**/
	public static function getStudentRoll($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('roll')))
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
	
	/**
	** Attendance display
	**/
	public static function DisplayAttendance($month_title, $year, $month, $student_id, $class, $section, $monthend){
		$monthstart = 1;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select('*')
            ->from($db->quoteName('#__sms_attendance'))
            ->where('YEAR(attendance_date) = '. $db->quote($year))
			->where('MONTH(attendance_date) = '. $db->quote($month))
			->where($db->quoteName('class') . ' = '. $db->quote($class))
			->where($db->quoteName('section') . ' = '. $db->quote($section))
            ->order('id ASC');
		$db->setQuery($query);
		$attendance_row = $db->loadObjectList();
		$total_class = count($attendance_row);
		$startdate = $year.'-'.$month.'-'.$monthstart;
		$enddate = $year.'-'.$month.'-'.$monthend;
						
		// Get Present Query
		$query_present = $db->getQuery(true);
		$query_present
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where("create_date BETWEEN '".$startdate." 00:00:01' AND '".$enddate." 23:59:59'")
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
			->where($db->quoteName('attend') . ' = '. $db->quote('1'))
            ->order('id ASC');
        $db->setQuery($query_present);
		$present_row = $db->loadObjectList();
		$total_present = count($present_row);
						
		// Get Absent Query
		$query_absent = $db->getQuery(true);
		$query_absent
            ->select('*')
            ->from($db->quoteName('#__sms_attendance_info'))
            ->where("create_date BETWEEN '".$startdate." 00:00:01' AND '".$enddate." 23:59:59'")
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
			->where($db->quoteName('attend') . ' = '. $db->quote('0'))
            ->order('id ASC');
        $db->setQuery($query_absent);
		$absent_row = $db->loadObjectList();
		$total_absent = count($absent_row);
						
	    $show ='<table cellpadding="0" cellspacing="0" class="admin-table" id="admin-table" style="width: 100%;border: 1px solid #eee;background: #f5f5f5;" align="center" >';
		$show .='<tr>';
		$show .= '<td style="text-align: left;border: none;"><b>'.$month_title.'</b></td>';
		$show .= '<td style="border: none;"><i>'.JText::_('LABEL_STUDENT_ATTENDANCE_TOTAL_CLASS').': '.$total_class.' '.JText::_('DEFAULT_DAYS').'</i></td>';
		$show .= '<td style="border: none;"><i>'.JText::_('LABEL_STUDENT_ATTENDANCE_ATTENT').': '.$total_present.' '.JText::_('DEFAULT_DAYS').'</i></td>';
		$show .= '<td style="border: none;"><i>'.JText::_('LABEL_STUDENT_ATTENDANCE_ABSENT').': '.$total_absent.' '.JText::_('DEFAULT_DAYS').'</i></td>';
		$show .='</tr>';
		$show .='</table>';
		$show .='<table cellpadding="0" cellspacing="0" class="admin-table" id="admin-table" style="width: 100%;margin-top: 0px;margin-bottom: 20px;" align="center" >';
		$show .='<tr>';

		for ($h = $monthstart; $h <= $monthend; $h++) {
			$getattendanceD =  self::getAttendanceDay('DAY(attendance_date)',$year, $month , $h ,$class, $section);
			if($getattendanceD==$h){$show .='<th style="background: #ffff99;" >'.$h.'</th>';}else{$show .= '<th >'.$h.'</th>';}
		} 
		$show .='</tr>';
		$show .='<tr>';
		for ($x = $monthstart; $x <= $monthend; $x++) {
			$getattendanceDd = self::getAttendanceDay('DAY(attendance_date)',$year, $month , $x ,$class, $section);
			$attendane_id = self::getAttendanceDay('id',$year, $month , $x ,$class, $section);
			$student_attent = self::getStudentAttent($student_id, $attendane_id);
			if($getattendanceDd==$x){
				if($student_attent==1){$show .='<td style="background: #ccffcc ;" ><span style="color: green;font-weight: Bold;">P</span></td>';}
				else{$show .='<td style="background: #ffcccc ;" ><span style="color: red;font-weight: Bold;">A</span></td>';}
				}else{$show .= '<td ></td>';}
			} 
		$show .='</tr>';
		$show .='</table>';
	    return $show;
	}
	
    /**
    ** Get Attendance Day
    **/
	static function getAttendanceDay($select, $year, $month , $day ,$class, $section){
	    $db = JFactory::getDBO();
		$query_add_day = $db->getQuery(true);
		$query_add_day
            ->select($select)
            ->from($db->quoteName('#__sms_attendance'))
            ->where('YEAR(attendance_date) = '. $db->quote($year))
			->where('MONTH(attendance_date) = '. $db->quote($month))
			->where('DAY(attendance_date) = '. $db->quote($day))
			->where($db->quoteName('class') . ' = '. $db->quote($class))
			->where($db->quoteName('section') . ' = '. $db->quote($section));
		$db->setQuery($query_add_day);
		$data = $db->loadResult();
	    return $data;
	}

    /**
    ** Get Student Attent
    **/
	static function getStudentAttent($student_id, $attendane_id){
	    $db = JFactory::getDBO();
		$query_add_day = $db->getQuery(true);
		$query_add_day
            ->select('attend')
            ->from($db->quoteName('#__sms_attendance_info'))
			->where($db->quoteName('student_id') . ' = '. $db->quote($student_id))
			->where($db->quoteName('attendance_id') . ' = '. $db->quote($attendane_id));
		$db->setQuery($query_add_day);
		$data = $db->loadResult();
	    return $data;
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
    ** Get Component Menu
    **/
	public static function addSubmenu($vName){
    	jimport('joomla.application.component.model');
        JModelLegacy::addIncludePath(JPATH_SITE.'/administrator/components/com_sms/models');
	
		JHtmlSidebar::addEntry(
			'<span class="icon-home"></span> '.JText::_('MENU_HOME'),
			'index.php?option=com_sms&view=sms',
			$vName == 'sms'
		);

        JHtmlSidebar::addEntry(
            '<span class="icon-screen"></span> '.JText::_('MENU_ACADEMIC'),
            'index.php?option=com_sms&view=academic',
            $vName == 'academic'
        );
		
		// Get Student Model
		$students_model = JModelLegacy::getInstance( 'students', 'SmsModel' );
		$total_new = $students_model->totalNewstudent();
		if(!empty($total_new)){
    		$newstudent_message = ' <span class="red-notification">'.$total_new.' New</span>';
		}else{
    		$newstudent_message ="";
		}
		
		JHtmlSidebar::addEntry(
			'<span class="icon-users"></span> '.JText::_('MENU_STUDENTS'),
			'index.php?option=com_sms&view=students',
			$vName == 'students'
		);
        
		JHtmlSidebar::addEntry(
			'<span class="icon-users"></span> '.JText::_('MENU_TEACHERS'),
			'index.php?option=com_sms&view=teachers',
			$vName == 'teachers'
		);
		
		JHtmlSidebar::addEntry(
			'<span class="icon-users"></span> '.JText::_('MENU_PARENTS'),
			'index.php?option=com_sms&view=parents',
			$vName == 'parents'
		);
		
		
		JHtmlSidebar::addEntry(
			'<span class="icon-checkin"></span> '.JText::_('MENU_MANAGE_ATTENDANCE'),
			'index.php?option=com_sms&view=attendance',
			$vName == 'attendance'
		);
		
		
		JHtmlSidebar::addEntry(
			'<span class="icon-pencil-2"></span> '.JText::_('MENU_EXAMS'),
			'index.php?option=com_sms&view=exams',
			$vName == 'exams' || $vName == 'grade' || $vName == 'gradecategory'
		);
		
		
		
		JHtmlSidebar::addEntry(
			'<span class="icon-star"></span> '.JText::_('MENU_MANAGE_MARKS'),
			'index.php?option=com_sms&view=marks',
			$vName == 'marks'
		);
		
		
		// Get Payment Model
		$payemnt_model = JModelLegacy::getInstance( 'payments', 'SmsModel' );
		$total_pending = $payemnt_model->totalPending();
		if(!empty($total_pending)){
    		$pending_message = ' <span class="red-notification">'.$total_pending.' Pending</span>';
		}else{
    		$pending_message ="";
		}
		
		JHtmlSidebar::addEntry(
			'<span class="icon-credit"></span> '.JText::_('MENU_PAYMENTS').$pending_message ,
			'index.php?option=com_sms&view=payments',
			$vName == 'payments'
		);
		
		
		JHtmlSidebar::addEntry(
			'<span class="icon-mail-2"></span> '.JText::_('MENU_MESSAGES'),
			'index.php?option=com_sms&view=message',
			$vName == 'message'
		);
		
		JHtmlSidebar::addEntry(
			'<span class="icon-pie"></span> '.JText::_('MENU_ACCOUNTING'),
			'index.php?option=com_sms&view=accounting',
			$vName == 'accounting'
		);

		// Get Addon Menu
		$db = JFactory::getDBO();
		$addon_query = $db->getQuery(true);
		$addon_query
            ->select('*')
            ->from($db->quoteName('#__sms_addons'))
            ->where($db->quoteName('status') . ' = '. $db->quote('1'))
            ->where($db->quoteName('admin') . ' = '. $db->quote('1'))
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
		
		
		JHtmlSidebar::addEntry(
			'<span class="icon-options"></span> '.JText::_('MENU_LANGUAGES'),
			'index.php?option=com_sms&view=languages',
			$vName == 'languages'
		);
		
		JHtmlSidebar::addEntry(
			'<span class="icon-options"></span> '.JText::_('MENU_FIELD_BUILDER'),
			'index.php?option=com_sms&view=fields',
			$vName == 'fields'
		);
        
        
        JHtmlSidebar::addEntry(
			'<span class="icon-options"></span> '.JText::_('MENU_CONFIGURATION'),
			'index.php?option=com_config&view=component&component=com_sms',
			$vName == 'configuration'
		);
        
        JHtmlSidebar::addEntry(
			'<span class="icon-options"></span> '.JText::_('Activation & Update'),
			'index.php?option=com_sms&view=activation',
			$vName == 'activation'
		);

        JHtmlSidebar::addEntry(
            '<span class="icon-options"></span> '.JText::_('Addons'),
            'index.php?option=com_sms&view=addons',
            $vName == 'addons'
        );

        JHtmlSidebar::addEntry(
            '<span class="icon-options"></span> '.JText::_('Backup & Restore'),
            'index.php?option=com_sms&view=backup',
            $vName == 'backup'
        );
		
	}

	

}