<?php
/** @var $ctrl \bbn\Mvc\Controller */
if ( !\defined('APPUI_DATABASES_ROOT') ){
  define('APPUI_DATABASES_ROOT', $ctrl->pluginUrl('appui-database').'/');
}
$ctrl->data['root'] = APPUI_DATABASES_ROOT;
$ctrl->addInc("dbc", new \bbn\Appui\Database($ctrl->db));