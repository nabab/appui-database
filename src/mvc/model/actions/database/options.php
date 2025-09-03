<?php
use bbn\X;

set_time_limit(0);
if ($model->hasData(['host_id', 'db'])
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $dbs = $model->data['db'];
  $hostId = $model->data['host_id'];
  if (is_string($dbs)) {
    $dbs = [$dbs];
  }

  if (is_array($dbs)) {
    $failed = [];
    if ($model->hasData('remove', true)) {
      foreach ($dbs as $db) {
        if ($idDb = $model->inc->dbc->dbId($db, $hostId, $engine)) {
          try {
            if (!$model->inc->dbc->removeDatabase($idDb)) {
              $failed[] = $db;
            }
          }
          catch (\Exception $e) {
            $failed[] = $db;
          }
        }
        else {
          $failed[] = $db;
        }
      }

      if (!empty($failed)) {
        return ['error' => X::_("Impossible to delete the following databases: %s", X::join($failed, ', '))];
      }
      else {
        return ['success' => true];
      }
    }
    else {
      foreach ($dbs as $db) {
        try {
          $conn = $model->inc->dbc->connection($hostId, $engine, $db);
        }
        catch (\Exception $e) {
          return ['error' => $e->getMessage()];
        }

        if (!$conn->check()
          || !$model->inc->dbc->importDb($db, $hostId, true)
        ) {
          $failed[] = $db;
        }
      }

      if (!empty($failed)) {
        return ['error' => X::_("Impossible to import the following databases: %s", X::join($failed, ', '))];
      }
      else {
        return ['success' => true];
      }
    }
  }

}

return ['success' => false];
