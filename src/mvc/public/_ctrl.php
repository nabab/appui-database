<?php
/** @var $ctrl \bbn\mvc\controller */
if ( !\defined('APPUI_DATABASES_ROOT') ){
  define('APPUI_DATABASES_ROOT', $ctrl->plugin_url('appui-databases').'/');
}
$ctrl->data['root'] = APPUI_DATABASES_ROOT;
$ctrl->add_inc("dbc", new \bbn\appui\databases($ctrl->db));