<?php
/*
 * Describe what it does!
 *
 **/

/** @var $ctrl \bbn\mvc\controller */
if ( !empty($ctrl->arguments[0]) ){
  $ctrl->combo(_('Export'), true);
}
else{
  $url = APPUI_DATABASES_ROOT.'tabs/'.$ctrl->arguments[0].'/export';
  //$ctrl->set_url($url);
  $ctrl->obj->url = $url;
}
