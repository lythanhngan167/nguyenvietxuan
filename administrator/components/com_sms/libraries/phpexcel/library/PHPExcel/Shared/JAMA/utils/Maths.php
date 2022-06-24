<?php
/**
 * @package	Schools Management System !
 * @version	1.2.0
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2017 ZWEBTHEME. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
/**
 *	@package JAMA
 *
 *	Pythagorean Theorem:
 *
 *	a = 3
 *	b = 4
 *	r = sqrt(square(a) + square(b))
 *	r = 5
 *
 *	r = sqrt(a^2 + b^2) without under/overflow.
 */
 defined('_JEXEC') or die;
function hypo($a, $b) {
	if (abs($a) > abs($b)) {
		$r = $b / $a;
		$r = abs($a) * sqrt(1 + $r * $r);
	} elseif ($b != 0) {
		$r = $a / $b;
		$r = abs($b) * sqrt(1 + $r * $r);
	} else {
		$r = 0.0;
	}
	return $r;
}	//	function hypo()


/**
 *	Mike Bommarito's version.
 *	Compute n-dimensional hyotheneuse.
 *
function hypot() {
	$s = 0;
	foreach (func_get_args() as $d) {
		if (is_numeric($d)) {
			$s += pow($d, 2);
		} else {
			throw new PHPExcel_Calculation_Exception(JAMAError(ArgumentTypeException));
		}
	}
	return sqrt($s);
}
*/
