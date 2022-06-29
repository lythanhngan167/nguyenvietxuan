<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$attributes = array();

if ($item->anchor_title)
{
	$attributes['title'] = $item->anchor_title;
}

if ($item->anchor_css)
{
	$attributes['class'] = $item->anchor_css;
}

if ($item->anchor_rel)
{
	$attributes['rel'] = $item->anchor_rel;
}

$linktype = $item->title;

if ($item->menu_image)
{
	if ($item->menu_image_css)
	{
		$image_attributes['class'] = $item->menu_image_css;
		$linktype = JHtml::_('image', $item->menu_image, $item->title, $image_attributes);
	}
	else
	{
		$linktype = JHtml::_('image', $item->menu_image, $item->title);
	}

	if ($item->params->get('menu_text', 1))
	{
		$linktype .= '<span class="image-title">' . $item->title . '</span>';
	}
}

if ($item->browserNav == 1)
{
	$attributes['target'] = '_blank';
	$attributes['rel'] = 'noopener noreferrer';

	if ($item->anchor_rel == 'nofollow')
	{
		$attributes['rel'] .= ' nofollow';
	}
}
elseif ($item->browserNav == 2)
{
	$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');

	$attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
}

if($item->params['robots'] == 'index, nofollow'){
	$attributes['rel'] = 'nofollow';
}

echo JHtml::_('link', JFilterOutput::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
if($item->note != ''){
	$i_tag = '';
	if($item->id == 158 || $item->id == 159 || $item->id == 197 || $item->id == 198
	|| $item->id == 500 || $item->id == 501 || $item->id == 502 || $item->id == 503
	){
		$i_tag = '<i class="fa fa-graduation-cap" aria-hidden="true"></i>';
	}
	if($item->id == 199){
		$i_tag = '<i class="fa fa-star-half-o" aria-hidden="true"></i>';
	}
	if($item->id == 200){
		$i_tag = '<i class="fa fa-star" aria-hidden="true"></i>';
	}
	echo '<span class="number-product">'.$item->note.'</span>';
	echo '<div class="circle-outner outner-'.$item->id.'">
		<div class="circle-innner inner-'.$item->id.'">

		<div class="circle-innner2 inner2-'.$item->id.'">
		'.$i_tag.'
		</div>
		</div>
	</div>';
}
