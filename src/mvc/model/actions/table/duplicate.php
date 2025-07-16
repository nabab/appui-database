<?php
use bbn\Db\Languages\Sqlite;
use bbn\X;

if ($model->hasData(['host_id', 'db', 'table', 'name'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $db = $model->data['db'];
  $table = $model->data['table'];
  $newTable = $model->data['name'];
  try {
    $conn = $model->inc->dbc->connection($model->data['host_id'], $engine, $db);
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }

  if ($conn->check()) {
    if ($conn->duplicateTable($table, $newTable)) {
      if ($model->hasData('options', true)
        && ($tableId = $model->inc->dbc->tableId($table, $db, $model->data['host_id'], $engine))
      ) {
        $model->inc->dbc->duplicateTable($tableId, $newTable);
      }

      return ['success' => true];
    }
    else {
      return ['error' => X::_("Impossible to duplicate the table %s to %s", $db, $newTable)];
    }
  }
}

return ['success' => false];