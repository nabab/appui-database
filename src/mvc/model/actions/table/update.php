<?php
/**
 * User: BBN
 * Date: 21/02/2017
 * Time: 05:25
 */

/** @var \bbn\mvc\model $model */

$res = ['success' => false];
if ( isset($model->data['table'], $model->data['mapper']) ){
  $res['success'] = $model->data['mapper']->import($model->data['table']);
}
else if ( !empty($model->data['host']) &&
  !empty($model->data['db']) &&
  !empty($model->data['table'])
){
  if (!($id_db = $model->inc->dbc->db_id($model->data['db']))) {
    $id_db = $model->inc->dbc->import_db($model->data['db'], $model->data['host']);
  }
  if ($id_db) {
    $res['success'] = $model->inc->dbc->import_table($model->data['table'], $id_db);
  }
}
return $res;