<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Whitelist
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Whitelist controller class.
 *
 * @since  1.6
 */
class WhitelistControllerWhitelist extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'whitelists';
		parent::__construct();
	}
}
