<?php
/**
 * User: BBN
 * Date: 21/02/2017
 * Time: 05:25
 */

/** @var \bbn\Mvc\Model $model */

$res = ['success' => false];

if ( isset($model->data['db'], $model->data['host'], $model->data['engine']) ){
  if ( $id_host = $model->inc->dbc->importHost($model->data['host'], $model->data['engine']) ){
	  $res['success'] = $model->inc->dbc->importDb($model->data['db'], $id_host, true);
  }
}
return $res;