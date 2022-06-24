<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

// No direct access
defined('_JEXEC') or die;


class SmsModelExpenses extends JModelList
{
	
	/**
	** constructor
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
	** Expense Category Row Data
	**/
	function getExpenseCat($id){
	    if ($id) {
			$this->_id = $id;
		}
		if (empty($this->_data)) {
		$this->_data = $this->getTable ('expense');
		$this->_data->load ($this->_id);
		}
		return $this->_data;
	}
	
	/**
	** Get Yearly Expense List
	**/
	function yearlyList(){
	    $db    = JFactory::getDbo();
	    $sql =  "SELECT year(p.expense_date) as year,  p.id, sum(p.ammount) as Total ".
			    "FROM #__sms_expenses as p ".
			    "GROUP BY year(p.expense_date) ".
                "ORDER BY year(p.expense_date) ASC";
        $db->setQuery($sql);
        $result = $db->loadObjectList();
		return $result;
	}
	
	/**
	** Get Expense Category List
	**/
	protected function getListQuery(){
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__sms_expenses'));
		
		// Filter by id.
		$pId = $this->getState('filter.search');
		if (is_numeric($pId)){
			$query->where('id = ' . $db->quote($pId));
		}
		
		// Filter by Month.
		$monthId = $this->getState('filter.month_id');
		if (is_numeric($monthId)){
			$query->where('MONTH(expense_date) = ' . $db->quote($monthId));
		}
		
		// Filter by category.
		$category = $this->getState('filter.categoty');
		if (is_numeric($category)){
			$query->where('cat = ' . $db->quote($category));
		}
		
		// Filter by Year.
		$yearId = $this->getState('filter.year_id');
		if (is_numeric($yearId)){
			$query->where('YEAR(expense_date) = ' . $db->quote($yearId));
		}
		
		$orderCol	= $this->state->get('list.ordering', 'id');		
		$orderDirn 	= $this->state->get('list.direction', 'asc'); 		
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}
	
	/**
	** Income populate state
	**/
	protected function populateState($ordering = null, $direction = null){
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		//Takes care of states: list. limit / start / ordering / direction
		parent::populateState('id', 'asc');
    }
	
	/**
	** Get Cat Name
	**/
	function getCatName($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__sms_expense_category'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Entry Name
	**/
	function getEntryName($id){
	    $db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query
            ->select($db->quoteName(array('name')))
            ->from($db->quoteName('#__users'))
            ->where($db->quoteName('id') . ' = '. $db->quote($id));
		$db->setQuery($query);
		$data = $db->loadResult();
		return $data;
	}
	
	/**
	** Get Save
	**/
	public function store(){
		$table =& $this->getTable('expense');
		$data = JRequest::get( 'post' );

		// Bind the data.
		if (!$table->bind($data)){
			$this->setError($user->getError());
			return false;
		}

		// Store the data.
		if($data['id']){$table->id = $data['id'];}
		if (!$table->store()){
			$this->setError($user->getError());
			return false;
		}
    
		$id = $table->id;
		return $id;
	}
	
	
	/**
	** Get Delete
	**/
	public function delete(){
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$table =& $this->getTable('expense');
        if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$table->delete( $cid )) {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}
	
	/**
	** Get Expense Category List
	**/
	function getcatList($id){
	    $db = JFactory::getDBO();
        $query = "SELECT id,name FROM `#__sms_expense_category`";
        $query.=" ORDER BY id asc ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $expense_cat = array();
		$expense_cat[] = array('value' => '', 'text' => JText::_(' -- Select Category -- '));
        foreach ($rows as $row) {
            $expense_cat[] = array('value' => $row->id, 'text' => JText::_(' '.$row->name));
        }
		$category =  JHTML::_('select.genericList', $expense_cat, 'cat', 'class="required  inputbox  " required="required" ', 'value', 'text',$id);
        return $category;
	}

	
}
