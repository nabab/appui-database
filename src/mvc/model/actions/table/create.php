<?php
use bbn\X;
use bbn\Str;

$res = ['success' => false];
if ($model->hasData(['host', 'db', 'engine', 'name', 'fields'], true)) {
  $table = $model->data['name'];
  $db = $model->data['db'];
  if (($isSqlite = $model->data['engine'] === 'sqlite')
    && !str_ends_with($db, '.sqlite')
    && !str_ends_with($db, '.db')
  ) {
    $db .= '.sqlite';
  }

  try {
    $conn = $model->inc->dbc->connection($model->data['host'], $model->data['engine'], $db);
  }
  catch (Exception $e) {
    $res['error'] = Str::text2html($e->getMessage());
  }

  if ($conn->check()) {
    if ($conn->tableExists($table)) {
      $res['error'] = X::_("The table %s already exists", $table);
    }
    else {
      $structure = [
        'cols'=> $model->data['cols'] ?? [],
        'fields'=> $model->data['fields'],
        'keys'=> $model->data['keys'] ?? []
      ];
      if (!empty($model->data['charset'])) {
        $structure['charset'] = $model->data['charset'];
      }

      if (!empty($model->data['collation'])) {
        $structure['collation'] = $model->data['collation'];
      }

      $mode = $conn->getErrorMode();
      $conn->setErrorMode('continue');
      try {
        $conn->createTable($table, $structure, true, true);
      }
      catch (\Exception $e) {
        $res['error'] = Str::text2html($e->getMessage());
      }

      $conn->setErrorMode($mode);
      $res['success'] = empty($res['error']) && $conn->tableExists($table);
    }

  }
}

return $res;
