<?php
/**
 * What is my purpose?
 *
 **/

/** @var bbn\Mvc\Model $model */
die(var_dump($model->data));
if ($model->inc->conn && $model->inc->conn->check() && $model->hasData('table')) {
  $primary = $model->inc->conn->getUniquePrimary($model->data['table']);
  if ($prinary && $model->hasData($primary, true)) {
    if ($model->inc->conn->delete($model->data['table'], [$primary => $model->data[$primary]])) {
      $model->data['res']['success'] = true;
    }
  }
}

return $model->data['res'];
