<?php
/**
 * What is my purpose?
 *
 **/

/** @var bbn\Mvc\Model $model */

use bbn\X;

$res = ['success' => false];

if ($model->hasData(['engine', 'db', 'host', 'table'])) {
  $database = new bbn\Appui\Database($model->db);
  $conn = $database->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  $data = [];
  if ($model->hasData('name') && $model->data['name'] !== '') {
    try {
	    $data = $conn->getColumnValues($model->data['table'], $model->data['name']);
    } catch (\Exception $e) {
      $res['error'] = $e->getMessage();
      return $res;
    }
  }
  $res = [
    'success' => true,
    'num' => count($data)
  ];
}


return $res;
