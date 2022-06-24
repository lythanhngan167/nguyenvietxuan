<?php
/**
 * @package		sms offline plugin
 * @author 		zwebtheme http://www.zwebtheme.com
 * @copyright	Copyright (c) zwebtheme. All rights reserved.
 * @license 	GNU General Public License version 3 or later; see LICENSE.txt
 * @since 		1.0.0
 */

defined('_JEXEC') or die;

class plgSms_paymentOffline extends JPlugin
{

	/*
	* method buildLayoutPath
	* @layout = ask for tmpl file name, default is default, but can be used others name
	* return propur file to take htmls
	*/
	function buildLayoutPath($layout)
	{
		if(empty($layout)) $layout = "default";
		$app = JFactory::getApplication();

		// core path
		$core_file 	= dirname(__FILE__) . '/' . $this->_name . '/tmpl/' . $layout . '.php';

		// override path from site active template
		$override	= JPATH_BASE .'/templates/' . $app->getTemplate() . '/html/plugins/' . $this->_type . '/' . $this->_name . '/' . $layout . '.php';

		if(JFile::exists($override)){
			$file = $override;
		}else{
  		$file =  $core_file;
		}
		return $file;
	}

	/*
	* method buildLayout
	* @vars = object with product, order, user info
	* @layout = tmpl name
	* Builds the layout to be shown, along with hidden fields.
	* @return html
	*/
	function buildLayout($vars, $layout = 'default' )
	{
		// Load the layout & push variables
		ob_start();
		$layout = $this->buildLayoutPath($layout);
		include($layout);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function onSMS_PaymentGetHTML($vars, $pg_plugin){
		if($pg_plugin != $this->_name) {
			return;
		}

		$html = $this->buildLayout($vars);
		return $html;
	}

	

	
}
