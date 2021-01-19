<?php
/**
 * User: BBN
 * Date: 21/02/2017
 * Time: 05:25
 */

/** @var \bbn\mvc\model $model */

$res = ['success' => false];

if ( isset($model->data['db'], $model->data['host'], $model->data['engine']) ){
  if ( $id_host = $model->inc->dbc->import_host($model->data['host'], $model->data['engine']) ){
	  $res['success'] = $model->inc->dbc->import_db($model->data['db'], $id_host, true);
  }
}
return $res;