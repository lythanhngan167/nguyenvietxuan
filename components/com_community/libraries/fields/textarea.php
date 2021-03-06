<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once COMMUNITY_COM_PATH . '/libraries/fields/profilefield.php';

class CFieldsTextarea extends CProfileField {

    public function getFieldHTML($field, $required) {
        $params = new CParameter($field->params);
        $readonly = $params->get('readonly') && !COwnerHelper::isCommunityAdmin() ? ' readonly=""' : '';

        $required = ($field->required == 1) ? ' data-required="true"' : '';
        $style = $this->getStyle() ? ' style="' .$this->getStyle() . '"' : '';

        $min_char = $params->get('min_char');
        $min_char = empty($min_char) ? FALSE : $min_char;

        $max_char = $params->get('max_char');
        $max_char = empty($max_char) ? FALSE : $max_char;

        if ( $min_char || $max_char ) {
            $html  = '<div class="joms-textarea__wrapper">';
            $html .= '<textarea id="field' . $field->id . '" name="field' . $field->id . '" class="joms-textarea joms-textarea--limit" ' . ($min_char ? 'data-min-char="' . $min_char . '" ' : '') . ($max_char ? 'data-max-char="' . $max_char . '" ' : '') . $readonly . $required . $style . ' >' . $field->value . '</textarea>';
            $html .= '<div class="joms-textarea__limit">';
            if ( $min_char && $max_char ) {
                $html .= JText::sprintf('COM_COMMUNITY_PROFILE_TEXTAREA_MIN_MAX_CHAR', $min_char, $max_char);
            } else if ( $min_char ) {
                $html .= JText::sprintf('COM_COMMUNITY_PROFILE_TEXTAREA_MIN_CHAR', $min_char, $max_char);
            } else if ( $max_char ) {
                $html .= JText::sprintf('COM_COMMUNITY_PROFILE_TEXTAREA_MAX_CHAR', $min_char, $max_char);
            }
            $html .= '<span>' . JText::_('COM_COMMUNITY_PROFILE_CHAR_TYPED') . ': <span>' . strlen($field->value) . '</span></span>';
            $html .= '</div>';
            $html .= '</div>';
		} else {
            $html  = '<textarea id="field' . $field->id . '" name="field' . $field->id . '" class="joms-textarea" ' . $readonly . $required . $style . ' >' . $field->value . '</textarea>';
        }

        return $html;
    }

    public function isValid($value, $required) {
        if ($required && empty($value)) {
            return false;
        }
        /* if not empty than we'll validate no matter what is it required or not */
        if (!empty($value)) {
            return $this->validLength($value);
        }
        return true;
    }

}
