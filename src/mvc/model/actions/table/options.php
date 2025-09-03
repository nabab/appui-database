<?php
use bbn\X;

set_time_limit(0);
if ($model->hasData(['host_id', 'db', 'table'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $db = $model->data['db'];
  $hostId = $model->data['host_id'];
  $tables = $model->data['table'];
  if (is_string($tables)) {
    $tables = [$tables];
  }

  if (is_array($tables)) {
    $failed = [];
    if ($model->hasData('remove', true)) {
      foreach ($tables as $table) {
        if ($idTable = $model->inc->dbc->tableId($table, $db, $hostId, $engine)) {
          try {
            if (!$model->inc->dbc->removeTable($idTable)) {
              $failed[] = $table;
            }
          }
          catch (\Exception $e) {
            $failed[] = $table;
          }
        }
        else {
          $failed[] = $table;
        }
      }

      if (!empty($failed)) {
        return ['error' => X::_("Impossible to delete the following tables: %s", X::join($failed, ', '))];
      }
      else {
        return ['success' => true];
      }
    }
    else {
      try {
        $conn = $model->inc->dbc->connection($hostId, $engine, $db);
      }
      catch (\Exception $e) {
        return ['error' => $e->getMessage()];
      }

      if ($conn->check()) {
        $idDb = false;
        if (!($idDb = $model->inc->dbc->dbId($db, $hostId))) {
          $idDb = $model->inc->dbc->importDb($db, $hostId);
        }

        if (empty($idDb)) {
          return ['error' => X::_("No %s database found into options", $db)];
        }

        foreach ($tables as $table) {
          if (!$model->inc->dbc->importTable($table, $idDb, $hostId)) {
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
