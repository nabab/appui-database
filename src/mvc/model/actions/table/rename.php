<?php
use bbn\X;

/** @var bbn\Mvc\Model $model */
if ($model->hasData(['host_id', 'db', 'table', 'name'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $db = $model->data['db'];
  $table = $model->data['table'];
  $newName = $model->data['name'];
  try {
    $conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $db);
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }

  if ($conn->check()) {
    if ($conn->renameTable($table, $newName)) {
      if ($model->hasData('options', true)
        && ($tableId = $model->inc->dbc->tableId($table, $db, $model->data['host_id'], $engine))
      ) {
        $model->inc->dbc->renameTable($tableId, $newName);
      }

      return ['success' => true];
    }
    else {
      return ['error' => X::_("Impossible to rename the table %s to %s", $table, $newName)];
    }
  }
}

return ['success' => false];
