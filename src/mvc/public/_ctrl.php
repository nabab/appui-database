<?php
/** @var $ctrl \bbn\mvc\controller */
if ( !\defined('APPUI_DATABASES_ROOT') ){
  define('APPUI_DATABASES_ROOT', $ctrl->plugin_url('appui-database').'/');
}
$ctrl->data['root'] = APPUI_DATABASES_ROOT;
$ctrl->add_inc("dbc", new \bbn\appui\database($ctrl->db));