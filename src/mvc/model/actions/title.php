<?php
/**
 * User: BBN
 * Date: 21/02/2017
 * Time: 05:25
 */

/** @var \bbn\mvc\model $model */

$res = ['success' => false];
if ( isset($model->data['host'], $model->data['text']) ){
  $m = $model->data;
  $t = new \appui\databases($model->db);
  $id = false;
  if ( isset($m['key']) ){
    $id = $t->key_id($m['key'], $m['table'], $m['database'], $m['host']);
  }
  else if ( isset($m['column']) ){
    $id = $t->column_id($m['column'], $m['table'], $m['database'], $m['host']);
  }
  else if ( isset($m['table']) ){
    $id = $t->table_id($m['table'], $m['database'], $m['host']);
  }
  else if ( isset($m['database']) ){
    $id = $t->db_id($m['database'], $m['host']);
  }
  else {
    $id = $t->host_id($m['host']);
  }
  if ( $id ){
    $res['args'] = [$id, $model->data['text']];
    $res['success'] = $model->inc->options->set_text($id, $model->data['text']);
  }
}
return $res;