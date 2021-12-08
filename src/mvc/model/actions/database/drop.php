<?php

/** @var $model \bbn\Mvc\Model*/
if ($model->hasData(['host_id', 'db'], true)) {
  if (!is_array($model->data['db'])) {
    $model->data['db'] = [$model->data['db']];
  }

  try {
    $conn = $model->inc->dbc->connection($model->data['host_id']);
  }
  catch (\Exception $e) {
    $model->data['res']['error'] = $e->getMessage();
  }

  if ($conn->check()) {
    $model->data['res']['undeleted'] = [];
    $model->data['res']['is_same'] = $conn === $model->db;
    foreach ($model->data['db'] as $db) {
      if ($conn->dropDatabase($db)) {
        $model->data['res']['success'] = true;
      }
      else {
        $model->data['res']['undeleted'][] = $db;
      }
    }
    if (!empty($model->data['res']['undeleted'])) {
      $model->data['res']['error'] = _("Impossible to delete the following databases")
        .': '
        .x::join($model->data['res']['undeleted'], ', ');
    }
  }

}

return $model->data['res'];