<?php
/**
 * What is my purpose?
 *
 **/

use bbn\X;
use bbn\Str;
/** @var bbn\Mvc\Model $model */

if ($model->hasData('limit')) {
  // Data for table
  $cfg = [];
  $grid = new bbn\Appui\Grid($model->db, $model->data, $cfg);
  return ['grid' => $grid];
} else {
  // Structure for table
  //$model->addData($model->getModel('./table', $model->data));
  $table = $model->getModel('./table', $model->data);
  $res = [];
  foreach($table['data']['structure']['fields'] as $k => $f) {
    $res[] = [
      'field' => $k,
      'type' => 'string'
    ];
  }
	return [
    'success' => true,
    'data' => [
      'columns' => $res,
      'table' => $model->data['table']
    ]
  ];
}