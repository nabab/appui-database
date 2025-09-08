<?php
use bbn\Mvc\Model;
use bbn\X;

/** @var Model $model */

if ($model->hasData(['host_id', 'db', 'table', 'column'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $host = $model->data['host_id'];
  $db = $model->data['db'];
  $table = $model->data['table'];
  $column = $model->data['column'];
  if (!is_array($column)) {
    $column = [$column];
  }

  try {
    $model->data['res']['undeleted'] = [];
    $conn = $model->inc->dbc->connection($host, $engine, $db);
    foreach ($column as $col) {
      if ($conn->check()
        && $conn->dropColumn($table, $col)
      ) {
        if ($model->hasData('options', true)
          && ($tableId = $model->inc->dbc->tableId($table, $db, $host, $engine))
          && ($columnId = $model->inc->dbc->columnId($col, $tableId))
        ) {
          $model->inc->options->removeFull($columnId);
        }

        $model->data['res']['success'] = true;
      }
      else {
        $model->data['res']['undeleted'][] = $col;
      }
    }

    if ($conn) {
      $conn->close();
    }
  }
  catch (\Exception $e) {
    $model->data['res']['error'] = $e->getMessage();
  }

  if (!empty($model->data['res']['undeleted'])) {
    $model->data['res']['error'] = X::_("Impossible to delete the following columns: %s", X::join($model->data['res']['undeleted'], ', '));
  }
}

return $model->data['res'];
