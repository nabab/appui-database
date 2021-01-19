<?php
/*
 * Describe what it does!
 *
 **/
use bbn\str;

/** @var $model \bbn\mvc\model*/
if ($model->has_data(['host_id', 'name'], true)) {
  if (!str::check_name($model->data['name'])) {
    $model->data['res']['error'] = _("This name is not authorized");
  }
  else {
    try {
      $conn = $model->inc->dbc->connection($model->data['host_id']);
    }
    catch (\Exception $e) {
      $model->data['res']['error'] = $e->getMessage();
    }

    if ($conn->check()) {
      $model->data['res']['return'] = $conn->create_database($model->data['name']);
      $model->data['res']['success'] = !!$model->data['res']['return'];
      $model->data['res']['is_same'] = $conn === $model->db;
    }

  }
}

return $model->data['res'];