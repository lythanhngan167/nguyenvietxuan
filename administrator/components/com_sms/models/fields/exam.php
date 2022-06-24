<?php
/**
 * @package	Schools Management System !
 * @author	zwebtheme.com
 * @copyright	(C) zwebtheme. All rights reserved.
 */
// No direct access.
defined('_JEXEC') or die();
 

class JFormFieldExam extends JFormField
{
    
    protected $type = 'exam';
    protected function getInput() {
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('c.id, c.	name');
        $query->from('`#__sms_exams` AS c');
        $query->order($db->escape('c.id ASC'));
        $db->setQuery($query);
        $clases = $db->loadObjectList();
        $options = array();
 
		$options[] = JHtml::_('select.option', '', '-- Select Exam --');
        foreach ($clases as $c){
            $options[] = JHtml::_('select.option', $c->id, $c->name);
        }
		
		return JHTML::_('select.genericlist',  $options,  $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );
	}
	
	
	
	
}
