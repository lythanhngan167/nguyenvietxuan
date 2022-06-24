<?php
/**
 * @package	Schools Management System !
 * @author	zwebtheme.com
 * @copyright	(C) zwebtheme. All rights reserved.
 */
 
// No direct access.
defined('_JEXEC') or die();
 
JFormHelper::loadFieldClass('list');
 

class JFormFieldClass extends JFormFieldList
{
   
    protected $type = 'class';
    protected $loadExternally = 0;
 
    protected function getOptions(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('c.id, c.	class_name');
        $query->from('`#__sms_class` AS c');
        $query->order($db->escape('c.id ASC'));
        $db->setQuery($query);
        $clases = $db->loadObjectList();
        $options = array();
 
        foreach ($clases as $c){
            $options[] = JHtml::_('select.option', $c->id, $c->class_name);
        }
 
        if (!$this->loadExternally){
            $options = array_merge(parent::getOptions(), $options);
        }
 
        return $options;
    }
 
    public function getOptionsExternally(){
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}
