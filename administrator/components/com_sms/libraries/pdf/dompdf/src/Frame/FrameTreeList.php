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
use Dompdf\Frame;

/**
 * Pre-order IteratorAggregate
 *
 * @access private
 * @package dompdf
 */
class FrameTreeList implements IteratorAggregate
{
    /**
     * @var \Dompdf\Frame
     */
    protected $_root;

    /**
     * @param \Dompdf\Frame $root
     */
    public function __construct(Frame $root)
    {
        $this->_root = $root;
    }

    /**
     * @return FrameTreeIterator
     */
    public function getIterator()
    {
        return new FrameTreeIterator($this->_root);
    }
}
