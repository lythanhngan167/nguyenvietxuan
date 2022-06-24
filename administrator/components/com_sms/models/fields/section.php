<?php
/**
 * @package	Schools Management System !
 * @author	zwebtheme.com
 * @copyright	(C) zwebtheme. All rights reserved.
 */
 
// No direct access.
defined('_JEXEC') or die();
 
JFormHelper::loadFieldClass('list');
 

class JFormFieldSection extends JFormFieldList
{
    
    protected $type = 'section';
    protected $loadExternally = 0;
 
    protected function getOptions() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('c.id, c.	section_name');
        $query->from('`#__sms_sections` AS c');
        $query->order($db->escape('c.id ASC'));
        $db->setQuery($query);
        $sections = $db->loadObjectList();
        $options = array();
 
        foreach ($sections as $c){
            $options[] = JHtml::_('select.option', $c->id, $c->section_name);
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
