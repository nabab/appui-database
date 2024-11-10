<?php
/** @var bbn\Mvc\Controller $ctrl */
if ( !\defined('APPUI_DATABASES_ROOT') ){
  define('APPUI_DATABASES_ROOT', $ctrl->pluginUrl('appui-database').'/');
}
$ctrl->addData(['root' => APPUI_DATABASES_ROOT]);
$ctrl->addInc("dbc", new \bbn\Appui\Database($ctrl->db));