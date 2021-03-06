<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die('Restricted access');

class CTablePollCategory extends JTable
{

	var $id = null;
	var $parent = null;
	var $name = null;
	var $description = null;

	/**
	 * Constructor
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__community_polls_category', 'id', $db);
	}

	public function delete($id = NULL)
	{
		$db	= JFactory::getDBO();

		// Check if any polls are assigned into this category
		// $strSQL	= 'SELECT COUNT(*) FROM ' . $db->quoteName('#__community_polls') . ' '
		// 			. 'WHERE ' . $db->quoteName('categoryid') . '=' . $db->Quote($id);
		// $db->setQuery( $strSQL );
		// $count = $db->loadResult();
		$count = 0;
		if ($count <= 0) {
			// Only delete if no groups are assigned to this category.
			parent::delete( $id );
			return true;
		}

		return false;
	}
}