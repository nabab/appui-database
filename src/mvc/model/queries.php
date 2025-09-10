<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
/** @var $model \bbn\Mvc\Model*/
if ($idQueries = $model->inc->options->fromCode('queries', $model->data['engine'], 'engines', 'database', 'appui')) {
  $prefs = $model->inc->pref->getAll($idQueries);
  return ['list' => $prefs];
}

