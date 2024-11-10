<?php

/** @var bbn\Mvc\Controller $ctrl */
if ( !empty($ctrl->arguments[0]) ){
  $ctrl->combo(_('Export'), true);
}
else{
  $url = APPUI_DATABASES_ROOT.'tabs/'.$ctrl->arguments[0].'/export';
  //$ctrl->setUrl($url);
  $ctrl->obj->url = $url;
}
