<?php
use bbn\X;

set_time_limit(0);
if ($model->hasData(['host_id', 'db', 'table', 'column'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $host = $model->data['host_id'];
  $db = $model->data['db'];
  $table = $model->data['table'];
  $column = $model->data['column'];
  if ($model->hasData('remove', true)) {
    if (is_string($column)) {
      $column = [$column];
    }

    if (is_array($column)) {
      $failed = [];
      if (!($tableId = $model->inc->dbc->tableId($table, $db, $host, $engine))) {
        return ['error' => X::_("The table %s does not exist", $table)];
      }

      foreach ($column as $col) {
        if ($columnId = $model->inc->dbc->columnId($col, $tableId)) {
          try {
            if (!$model->inc->options->removeFull($columnId)) {
              $failed[] = $col;
            }
          }
          catch (\Exception $e) {
            return ['error' => $e->getMessage()];
          }
        }
        else {
          $failed[] = $col;
        }
      }

      if (!empty($failed)) {
        return ['error' => X::_("Impossible to remove the following columns from the options: %s", X::join($failed, ', '))];
      }
      else {
        return ['success' => true];
      }
    }
  }
  else {
    try {
      $conn = $model->inc->dbc->connection($host, $engine, $db);
    }
    catch (\Exception $e) {
      return ['error' => $e->getMessage()];
    }

    if ($conn->check()) {
      $idDb = false;
      $idTable = false;
      if (!($idDb = $model->inc->dbc->dbId($db, $host))) {
        $idDb = $model->inc->dbc->importDb($db, $host);
      }

      if (!($idTable = $model->inc->dbc->tableId($table, $idDb))) {
        $idTable = $model->inc->dbc->importTab($table, $idDb, $host, false);
      }

      if (empty($idDb)) {
        return ['error' => X::_("No %s database found into options", $db)];
      }

      if (empty($idTable)) {
        return ['error' => X::_("No %s table found into options", $table)];
      }

      if (is_string($column)) {
        $column = [$column];
      }

      if (is_array($column)) {
        $failed = [];
        foreach ($column as $col) {
          if (!$model->inc->dbc->importColumn($col, $idTable, $host)) {
            $failed[] = $col;
          }
        }

        if (!empty($failed)) {
          return ['error' => X::_("Impossible to import the following columns: %s", X::join($failed, ', '))];
        }
        else {
          return ['success' => true];
        }
      }

    }
  }
}

return ['success' => false];
