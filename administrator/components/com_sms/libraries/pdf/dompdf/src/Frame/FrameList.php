<?php
/**
 * @package	Schools Management System !
 * @version	1.2.0
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2017 ZWEBTHEME. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
namespace Dompdf\Frame;

use IteratorAggregate;

/**
 * Linked-list IteratorAggregate
 *
 * @access private
 * @package dompdf
 */
class FrameList implements IteratorAggregate
{
    /**
     * @var
     */
    protected $_frame;

    /**
     * @param $frame
     */
    function __construct($frame)
    {
        $this->_frame = $frame;
    }

    /**
     * @return FrameListIterator
     */
    function getIterator()
    {
        return new FrameListIterator($this->_frame);
    }
}
