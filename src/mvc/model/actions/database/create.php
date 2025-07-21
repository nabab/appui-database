<?php
use bbn\Mvc\Model;
use bbn\Str;
use bbn\X;
use bbn\Db\Languages\Sqlite;

/** @var Model $model */
if ($model->hasData(['host_id', 'engine', 'name'], true)) {
  $isSqlite = $model->data['engine'] === 'sqlite';
  if ($isSqlite) {
    $ext = X::extension($model->data['name']);
    $filename = X::basename($model->data['name'], '.' . $ext);
    if (!Str::checkName($filename)) {
      $model->data['res']['error'] = _("This name is not authorized");
    }
    else if ($ext !== 'sqlite' && $ext !== 'db') {
      $model->data['name'] = $filename . '.sqlite';
    }
  }
  else if (!Str::checkName($model->data['name'])) {
    $model->data['res']['error'] = _("This name is not authorized");
  }

  if (empty($model->data['res']['error'])) {
    try {
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