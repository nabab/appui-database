<?php
use bbn\Mvc\Model;
use bbn\X;

/** @var Model $model */

if ($model->hasData(['host_id', 'db', 'table'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  if (!is_array($model->data['table'])) {
    $model->data['table'] = [$model->data['table']];
  }

  try {
    $model->data['res']['undeleted'] = [];
    $conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $model->data['db']);
    foreach ($model->data['table'] as $table) {
      if ($conn->check()
        && $conn->dropTable($table)
      ) {
        if ($model->hasData('options', true)
          && ($tableId = $model->inc->dbc->tableId($table, $model->data['db'], $model->data['host_id'], $engine))
        ) {
          $model->inc->dbc->removeTable($tableId);
        }

        $model->data['res']['success'] = true;
      }
      else {
        $model->data['res']['undeleted'][] = $table;
      }
    }
  }
  catch (\Exception $e) {
    $model->data['res']['error'] = $e->getMessage();
  }

  if (!empty($model->data['res']['undeleted'])) {
    $model->data['res']['error'] = X::_("Impossible to delete the following tables: %s", X::join($model->data['res']['undeleted'], ', '));
  }
}

return $model->data['res'];
