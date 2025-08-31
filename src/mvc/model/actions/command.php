<?php

/** @var bbn\Mvc\Model $model */

$res = ['success' => false];
if ( isset($model->data['request']) ){
  $parsed = $model->db->parseQuery($model->data['request']);
  if ( $model->db->is_write_sequence($parsed) ){
    $res['type'] = 'write';
    $res['num'] = $model->db->query($model->data['request']);
  }
  else{
    $res['type'] = 'read';
    $res['data'] = $model->db->getRows($model->data['request']);
    $res['num'] = count($res['data']);
    if ( $res['num'] > 1000 ){
      $res['data'] = array_splice($res['data'], 0, 1000);
    }
  }
  if ( $model->db->error ){
    $res['error'] = $model->db->getLastError();
  }
  $res['request'] = $model->data['request'];
  $res['url'] = 'result/'.time();
}
return $res;