<?php
use bbn\X;

set_time_limit(0);
if ($model->hasData(['host_id', 'db', 'table'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  if ($model->hasData('remove', true)) {
    if ($idTable = $model->inc->dbc->tableId($model->data['table'], $model->data['db'], $model->data['host_id'], $engine)) {
      try {
        if ($model->inc->dbc->removeTable($idTable)) {
          return ['success' => true];
        }
        else {
          return ['error' => X::_("Impossible to remove the table %s", $model->data['table'])];
        }
      }
      catch (\Exception $e) {
        return ['error' => $e->getMessage()];
      }
    }
    else {
      return ['error' => X::_("The table %s does not exist", $model->data['table'])];
    }
  }
  else {
    try {
      $conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $model->data['db']);
    }
    catch (\Exception $e) {
      return ['error' => $e->getMessage()];
    }

    if ($conn->check()) {
      $idDb = false;
      if (!($idDb = $model->inc->dbc->dbId($model->data['db'], $model->data['host_id']))) {
        $idDb = $model->inc->dbc->importDb($model->data['db'], $model->data['host_id']);
      }

      if (empty($idDb)) {
        return ['error' => X::_("No %s database found into options", $model->data['db'])];
      }

      if (is_string($model->data['table'])) {
        $model->data['table'] = [$model->data['table']];
      }

      if (is_array($model->data['table'])) {
        $failed = [];
        foreach ($model->data['table'] as $table) {
          if (!$model->inc->dbc->importTable($table, $idDb, $model->data['host_id'])) {
            $failed[] = $table;
          }
        }

        if (!empty($failed)) {
          return ['error' => X::_("Impossible to import the following tables: %s", X::join($failed, ', '))];
        }
        else {
          return ['success' => true];
        }
      }

    }
  }
}

return ['success' => false];
