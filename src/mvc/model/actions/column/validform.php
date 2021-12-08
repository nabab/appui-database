<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\X;

$res = ['success' => false];

if ($model->hasData(['engine', 'db', 'host', 'table', 'name'])) {
	$database = new bbn\Appui\Database($model->db);
  $conn = $database->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  $data = $conn->getColumnValues($model->data['table'], $model->data['name']);
  $res = [
    'success' => true,
    'num' => count($data)
  ];
}


return $res;
