<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

use bbn\X;

$tableName = $model->data['name'];
$structure = ['cols'=> $model->data['cols'], 'fields'=> $model->data['fields'], 'keys'=> $model->data['keys']];

$statement = $model->db->getCreate($tableName, $structure);
$mode = $model->db->getErrorMode();
$model->db->setErrorMode('continue');
try {
  $res = $model->db->query($statement);
}
catch (\Exception $e) {
  $error = $model->db->getLastError();
}
$model->db->setErrorMode($mode);

return [
  'query' => $statement,
  'query2' => $model->db->getCreate('bbn_options'),
  'success' => $res,
  'error' => $error ?? null
];