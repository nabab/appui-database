<?php

$res = ['success' => false];
if ($model->hasData(['engine', 'db', 'host', 'table', 'data'], true)) {
  $engine = $model->data['engine'];
  $db = $model->data['db'];
  $host = $model->data['host'];
  $table = $model->data['table'];
  $data = $model->data['data'];
  try {
    $conn = $model->inc->dbc->connection($host, $engine, $db);
    if ($conn->createColumn($table, $data['name'], $data)) {
      if ($model->hasData('options', true)) {
        $model->inc->dbc->importColumn(
          $data['name'],
          $model->inc->dbc->tableId($table, $db, $host, $engine),
          $host
        );
      }

      $res['success'] = true;
    }
  }
  catch (\Exception $e) {
    $res['error'] = $e->getMessage();
    return $res;
  }

  if (!empty($conn)) {
    $conn->close();
  }
}

return $res;