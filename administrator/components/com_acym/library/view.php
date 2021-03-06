<?php
defined('_JEXEC') or die('Restricted access');
?><?php

class acymView extends acymObject
{
    var $name = '';
    var $steps = [];
    var $step = '';
    var $edition = false;

    public function __construct()
    {
        parent::__construct();

        $classname = get_class($this);
        $viewpos = strpos($classname, 'View');
        $this->name = strtolower(substr($classname, $viewpos + 4));
        $this->step = acym_getVar('string', 'nextstep', '');
        if (empty($this->step)) {
            $this->step = acym_getVar('string', 'step', '');
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLayout()
    {
        return acym_getVar('cmd', 'layout', acym_getVar('cmd', 'task', 'listing'));
    }

    public function setLayout($value)
    {
        acym_setVar('layout', $value);
    }

    public function display($data = [])
    {
        $name = $this->getName();
        $view = $this->getLayout();
        if (method_exists($this, $view)) $this->$view();

        $viewFolder = acym_isAdmin() ? ACYM_VIEW : ACYM_VIEW_FRONT;
        if (!file_exists($viewFolder.$name.DS.'tmpl'.DS.$view.'.php')) $view = 'listing';
        if (ACYM_CMS === 'wordpress') echo ob_get_clean();

        if (!empty($_SESSION['acynotif'])) {
            echo implode('', $_SESSION['acynotif']);
            $_SESSION['acynotif'] = [];
        }


        $outsideForm = (strpos($name, 'mails') !== false && $view == 'edit') || (strpos($name, 'campaigns') !== false && $view == 'edit_email');
        if ($outsideForm) echo '<form id="acym_form" action="'.acym_completeLink(acym_getVar('cmd', 'ctrl')).'" class="acym__form__mail__edit" method="post" name="acyForm" data-abide novalidate>';

        if (acym_getVar('cmd', 'task') != 'ajaxEncoding') echo '<div id="acym_wrapper" class="'.$name.'_'.$view.'">';

        if (acym_isLeftMenuNecessary()) echo acym_getLeftMenu($name).'<div id="acym_content">';

        if (!empty($data['header'])) echo $data['header'];

        if (acym_isAdmin()) acym_displayMessages();

        include acym_getView($name, $view);

        if (acym_isLeftMenuNecessary()) echo '</div>';
        if (acym_getVar('cmd', 'task') != 'ajaxEncoding') echo '</div>';

        if ($outsideForm) echo '</form>';

        $remind = json_decode($this->config->get('remindme', '[]'));
        if (ACYM_CMS == 'wordpress' && !in_array('reviews', $remind) && acym_isAdmin()) {
            echo '<div id="acym__reviews__footer" style="margin: 0 0 30px 30px;">';
            echo acym_translation_sprintf(
                'ACYM_REVIEW_FOOTER',
                '<a title="reviews" id="acym__reviews__footer__link" target="_blank" href="https://wordpress.org/support/plugin/acymailing/reviews/?rate=5#new-post"><i class="acymicon-star acym__color__light-blue"></i><i class="acymicon-star acym__color__light-blue"></i><i class="acymicon-star acym__color__light-blue"></i><i class="acymicon-star acym__color__light-blue"></i><i class="acymicon-star acym__color__light-blue"></i></a>'
            );
            echo '</div>';
        }
    }
}

