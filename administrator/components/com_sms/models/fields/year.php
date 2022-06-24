<?php
/**
 * @package	Schools Management System !
 * @author	zwebtheme.com
 * @copyright	(C) zwebtheme. All rights reserved.
 */
// No direct access.
defined('_JEXEC') or die();
 
JFormHelper::loadFieldClass('list');
 
class JFormFieldYear extends JFormFieldList
{
    
    protected $type = 'year';
    protected $loadExternally = 0;
    protected function getOptions(){
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_sms');
        $year_range_start = $params->get('year_range_start');
        $year_range_end = $params->get('year_range_end');
		$options = array();
        $year=0;
        for($i = $year_range_start; $i <= $year_range_end; $i++) {
			if(!empty($year)){
				$isCurrentY="false";
			}else{
				$isCurrentY = ($i == intVal(date("Y"))) ? 'true': 'false';
			}								 
            $options[] = JHtml::_('select.option', $i, $i);
        }
 
        if (!$this->loadExternally){
            // Merge any additional options in the XML definition.
            $options = array_merge(parent::getOptions(), $options);
        }
 
        return $options;
    }
 
    public function getOptionsExternally(){
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}
