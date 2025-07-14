<?php
use bbn\Mvc\Model;
use bbn\Str;
use bbn\Db\Languages\Sqlite;

/** @var Model $model */
if ($model->hasData(['host_id', 'engine', 'name'], true)) {
  if (!Str::checkName($model->data['name'])) {
    $model->data['res']['error'] = _("This name is not authorized");
  }
  else {
    try {
      if (($isSqlite = $model->data['engine'] === 'sqlite')
        && !str_ends_with($model->data['name'], '.sqlite')
        && !str_ends_with($model->data['name'], '.db')
      ) {
        $model->data['name'] .= '.sqlite';
      }

      if (!$isSqlite) {
        $conn = $model->inc->dbc->connection($model->data['host_id'], $model->data['engine']);
      }
    }
    catch (\Exception $e) {
      $model->data['res']['error'] = Str::text2html($e->getMessage());
    }

    if ($isSqlite || $conn->check()) {
      if (($isSqlite
          && Sqlite::createDatabaseOnHost($model->data['name'], $model->data['host_id']))
        || (!$isSqlite
          && $conn->createDatabase(
            $model->data['name'],
            $model->data['charset'] ?? null,
            $model->data['collation'] ?? null
          ))
      ) {
        $model->data['res']['success'] = true;
        if ($model->hasData('options', true)) {
          $model->inc->dbc->addDatabase(
            $model->data['name'],
            $model->data['host_id'],
            $model->data['engine']
          );
        }
      }
    }

  }
}

return $model->data['res'];