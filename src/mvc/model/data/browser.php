<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
/** @var $model \bbn\Mvc\Model*/

if ($model->hasData('limit') {
  // Data for table
  $cfg = [];
  $grid = new bbn\Appui\Grid($model->db, $model->data, $cfg);
  return ['grid' => $grid];
} else {
  // Structure for table
	return ['id' => ''];
}