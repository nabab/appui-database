<?php

/** @var bbn\Mvc\Model $model */
if ($model->hasData(['host_id', 'db', 'table'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  if (!is_array($model->data['table'])) {
    $model->data['table'] = [$model->data['table']];
  }

  if (is_array($model->data['table'])) {
    try {
      $model->data['res']['failed'] = [];
      $conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $model->data['db']);
      foreach ($model->data['table'] as $table) {
        if ($conn->check()
          && $conn->analyzeTable($table)
        ) {
          $model->data['res']['success'] = true;
        }
        else {
          $model->data['res']['failed'][] = $table;
        }
      }
    }
    catch (\Exception $e) {
      $model->data['res']['error'] = $e->getMessage();
    }

    if (!empty($model->data['res']['undeleted'])) {
      $model->data['res']['error'] = X::_("Impossible to analyze the following tables: %s", X::join($model->data['res']['undeleted'], ', '));
    }
  }
}

return $model->data['res'];
