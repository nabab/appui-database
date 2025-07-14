<?php
use bbn\Db\Languages\Sqlite;
use bbn\X;

if ($model->hasData(['host_id', 'db', 'name'], true)
  && ($engineId = $model->inc->dbc->engineIdFromHost($model->data['host_id']))
  && ($engine = $model->inc->dbc->engineCode($engineId))
) {
  $isSqlite = $engine === 'sqlite';

  try {
    if (!$isSqlite) {
      $conn = $model->inc->dbc->connection($model->data['host_id'], $engine);
    }
  }
  catch (\Exception $e) {
    return ['error' => $e->getMessage()];
  }

  if ($isSqlite || $conn->check()) {
    if (($isSqlite
        && Sqlite::renameDatabaseOnHost($model->data['db'], $model->data['name'], $model->data['host_id']))
      || (!$isSqlite
        && $conn->renameDatabase($model->data['db'], $model->data['name']))
    ) {
      if ($model->hasData('options', true)
        && ($dbId = $model->inc->dbc->dbId($model->data['db'], $model->data['host_id'], $engine))
      ) {
        $model->inc->dbc->renameDatabase($dbId, $isSqlite ? Sqlite::normalizeFilename($model->data['name']) : $model->data['name']);
      }

      return ['success' => true];
    }
    else {
      return ['error' => X::_("Impossible to rename the database %s to %s", $model->data['db'], $model->data['name'])];
    }
  }
}

return ['success' => false];