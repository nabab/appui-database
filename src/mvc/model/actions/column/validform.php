<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\X;

$res = ['success' => false];

if ($model->hasData(['engine', 'db', 'host', 'table'])) {
	/*$database = new bbn\Appui\Database($model->db);
  $conn = $database->connection($model->data['host'], $model->data['engine'], $model->data['db']);
  $data = $conn->getColumnValues($model->data['table'], $model->data['name']);*/
  //X::ddump($conn, $data, $model->data['table'], $model->data['name'], $model->data['host'], $model->data['engine'], $model->data['db'], $model->data);
  $res = [
    'success' => true,
    'num' => 0
  ];
}


return $res;
