<?php
use bbn\X;

set_time_limit(0);
if ($model->hasData(['host_id', 'db'])
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  if ($model->hasData('remove', true)) {
    if ($idDb = $model->inc->dbc->dbId($model->data['db'], $model->data['host_id'], $engine)) {
      try {
        if ($model->inc->dbc->removeDatabase($idDb)) {
          return ['success' => true];
        }
        else {
          return ['error' => X::_("Impossible to remove the database %s", $model->data['db'])];
        }
      }
      catch (\Exception $e) {
        return ['error' => $e->getMessage()];
      }
    }
    else {
      return ['error' => X::_("The database %s does not exist", $model->data['db'])];
    }
  }
  else {
    try {
      $conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $model->data['db']);
    }
    catch (\Exception $e) {
      return ['error' => $e->getMessage()];
    }

    if ($conn->check()
      && $model->inc->dbc->importDb($model->data['db'], $model->data['host_id'], true)
    ) {
      return ['success' => true];
    }
  }
}

return ['success' => false];
