<?php
/**
 * User: BBN
 * Date: 21/02/2017
 * Time: 05:25
 */

/** @var bbn\Mvc\Model $model */

$res = ['success' => false];
if ( isset($model->data['host'], $model->data['text']) ){
  $m = $model->data;
  $t = new \Appui\Database($model->db);
  $id = false;
  if ( isset($m['key']) ){
    $id = $t->keyId($m['key'], $m['table'], $m['database'], $m['host']);
  }
  else if ( isset($m['column']) ){
    $id = $t->columnId($m['column'], $m['table'], $m['database'], $m['host']);
  }
  else if ( isset($m['table']) ){
    $id = $t->tableId($m['table'], $m['database'], $m['host']);
  }
  else if ( isset($m['database']) ){
    $id = $t->dbId($m['database'], $m['host']);
  }
  else {
    $id = $t->hostId($m['host']);
  }
  if ( $id ){
    $res['args'] = [$id, $model->data['text']];
    $res['success'] = $model->inc->options->setText($id, $model->data['text']);
  }
}
return $res;