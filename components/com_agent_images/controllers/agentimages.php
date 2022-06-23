<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_images
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Agentimages list controller class.
 *
 * @since  1.6
 */
class Agent_imagesControllerAgentimages extends Agent_imagesController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Agentimages', $prefix = 'Agent_imagesModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
