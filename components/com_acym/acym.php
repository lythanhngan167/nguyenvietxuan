<?php
defined('_JEXEC') or die('Restricted access');
?><?php
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    echo '<p style="color:red">This version of AcyMailing requires at least PHP 5.4.0, it is time to update the PHP version of your server!</p>';
    exit;
}

$helperFile = rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acym'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
if (!include_once $helperFile) {
    echo 'Could not load AcyMailing helper file';

    return;
}
acym_loadLanguage();

if (acym_isDebug()) acym_displayErrors();

global $Itemid;
if (empty($Itemid)) {
    $urlItemid = acym_getVar('int', 'Itemid');
    if (!empty($urlItemid)) {
        $Itemid = $urlItemid;
    }
}

$ctrl = acym_getVar('cmd', 'ctrl', acym_getVar('cmd', 'view', ''));
if (!include_once ACYM_CONTROLLER_FRONT.$ctrl.'.php') {
    acym_redirect(acym_rootURI());

    return;
}
acym_setVar('ctrl', $ctrl);

$className = ucfirst($ctrl).'Controller';
$controller = new $className();

$task = acym_getVar('cmd', 'task', acym_getVar('cmd', 'layout', ''));
if (empty($task)) {
    $task = acym_getVar('cmd', 'defaulttask', $controller->defaulttask);
}
acym_setVar('task', $task);

acym_loadAssets('front', $ctrl, $task);

$controller->loadScripts($task);
$controller->checkTaskFront($task);

